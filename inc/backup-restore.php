<?php
/**
 * KWL Resume — Backup & Restore
 *
 * Exports all resume content options and Customizer theme_mods to a
 * signed JSON file, and restores them on import. Works across domains
 * and WordPress installations.
 *
 * @package kwl-resume
 * @since   1.0.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Option keys that store resume content ── */
define( 'KWL_RESUME_OPTION_KEYS', [
    'kwl_resume_profile',
    'kwl_resume_contact',
    'kwl_resume_experience',
    'kwl_resume_skills',
    'kwl_resume_education',
    'kwl_resume_certifications',
    'kwl_resume_projects',
    'kwl_resume_custom_sections',
    'kwl_resume_sections',
] );

/* ── Customizer theme_mod keys ── */
define( 'KWL_RESUME_MOD_KEYS', [
    'kwl_color_scheme',
    'kwl_color_dark',
    'kwl_color_dark2',
    'kwl_color_accent',
    'kwl_color_accent2',
    'kwl_color_gold',
    'kwl_color_page_bg',
    'kwl_font_pair',
    'kwl_sidebar_position',
    'kwl_sidebar_width',
    'kwl_animations',
    'kwl_print_button',
    'kwl_show_open_to_work',
] );

/* ────────────────────────────────────────────────────────────────
   EXPORT — triggered by GET param
──────────────────────────────────────────────────────────────── */

add_action( 'admin_init', 'kwl_resume_handle_export' );

function kwl_resume_handle_export() {
    if ( ! isset( $_GET['kwl_action'] ) || $_GET['kwl_action'] !== 'export_backup' ) return;
    if ( ! check_admin_referer( 'kwl_resume_export' ) ) wp_die( esc_html__( 'Security check failed.', 'kwl-resume' ) );
    if ( ! current_user_can( 'edit_theme_options' ) )   wp_die( esc_html__( 'Insufficient permissions.', 'kwl-resume' ) );

    $data = [
        'kwl_backup_version' => KWL_RESUME_VERSION,
        'kwl_backup_created' => gmdate( 'Y-m-d H:i:s' ) . ' UTC',
        'kwl_backup_site'    => get_site_url(),
        'options'            => [],
        'theme_mods'         => [],
    ];

    foreach ( KWL_RESUME_OPTION_KEYS as $key ) {
        $value = get_option( $key, null );
        if ( $value !== null ) {
            $data['options'][ $key ] = $value;
        }
    }

    $saved_mods = get_theme_mods();
    foreach ( KWL_RESUME_MOD_KEYS as $key ) {
        if ( isset( $saved_mods[ $key ] ) ) {
            $data['theme_mods'][ $key ] = $saved_mods[ $key ];
        }
    }

    // Simple integrity hash (not a security measure — just detects corruption / manual edits).
    $data['kwl_backup_hash'] = hash( 'sha256', wp_json_encode( $data['options'] ) . wp_json_encode( $data['theme_mods'] ) );

    $json     = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    $filename = 'kwl-resume-backup-' . gmdate( 'Y-m-d-His' ) . '.json';

    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . strlen( $json ) );
    header( 'Cache-Control: no-store, no-cache, must-revalidate' );
    header( 'Pragma: no-cache' );
    echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    exit;
}

/* ────────────────────────────────────────────────────────────────
   IMPORT — triggered by POST
──────────────────────────────────────────────────────────────── */

add_action( 'admin_init', 'kwl_resume_handle_import' );

function kwl_resume_handle_import() {
    if ( ! isset( $_POST['kwl_resume_import'] ) ) return;
    if ( ! check_admin_referer( 'kwl_resume_admin', 'kwl_resume_nonce' ) ) return;
    if ( ! current_user_can( 'edit_theme_options' ) ) return;

    $redirect_base = add_query_arg( [ 'page' => 'kwl-resume-settings', 'tab' => 'backup' ], admin_url( 'themes.php' ) );

    /* ── Validate upload ── */
    if ( empty( $_FILES['kwl_backup_file']['tmp_name'] ) ) {
        wp_redirect( add_query_arg( 'kwl_import_error', 'no_file', $redirect_base ) );
        exit;
    }

    $file = $_FILES['kwl_backup_file'];

    if ( $file['error'] !== UPLOAD_ERR_OK ) {
        wp_redirect( add_query_arg( 'kwl_import_error', 'upload_error', $redirect_base ) );
        exit;
    }

    // 5 MB guard
    if ( $file['size'] > 5 * 1024 * 1024 ) {
        wp_redirect( add_query_arg( 'kwl_import_error', 'too_large', $redirect_base ) );
        exit;
    }

    $raw = file_get_contents( $file['tmp_name'] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
    if ( $raw === false ) {
        wp_redirect( add_query_arg( 'kwl_import_error', 'read_error', $redirect_base ) );
        exit;
    }

    $data = json_decode( $raw, true );
    if ( ! is_array( $data ) || ! isset( $data['kwl_backup_version'], $data['options'], $data['theme_mods'] ) ) {
        wp_redirect( add_query_arg( 'kwl_import_error', 'invalid_file', $redirect_base ) );
        exit;
    }

    /* ── Integrity check ── */
    if ( ! empty( $data['kwl_backup_hash'] ) ) {
        $expected = hash( 'sha256', wp_json_encode( $data['options'] ) . wp_json_encode( $data['theme_mods'] ) );
        if ( ! hash_equals( $expected, $data['kwl_backup_hash'] ) ) {
            wp_redirect( add_query_arg( 'kwl_import_error', 'hash_mismatch', $redirect_base ) );
            exit;
        }
    }

    /* ── Restore options ── */
    foreach ( KWL_RESUME_OPTION_KEYS as $key ) {
        if ( array_key_exists( $key, $data['options'] ) ) {
            update_option( $key, $data['options'][ $key ] );
        }
    }

    /* ── Restore theme_mods ── */
    foreach ( KWL_RESUME_MOD_KEYS as $key ) {
        if ( array_key_exists( $key, $data['theme_mods'] ) ) {
            set_theme_mod( $key, $data['theme_mods'][ $key ] );
        }
    }

    wp_redirect( add_query_arg( 'kwl_import_ok', '1', $redirect_base ) );
    exit;
}

/* ────────────────────────────────────────────────────────────────
   TAB RENDER
──────────────────────────────────────────────────────────────── */

function kwl_resume_tab_backup() {
    $export_url = wp_nonce_url(
        add_query_arg( [
            'page'       => 'kwl-resume-settings',
            'kwl_action' => 'export_backup',
        ], admin_url( 'themes.php' ) ),
        'kwl_resume_export'
    );

    /* Collect a quick summary of what will be exported */
    $option_count = 0;
    $mod_count    = 0;
    foreach ( KWL_RESUME_OPTION_KEYS as $key ) {
        if ( get_option( $key, null ) !== null ) $option_count++;
    }
    $saved_mods = get_theme_mods();
    foreach ( KWL_RESUME_MOD_KEYS as $key ) {
        if ( isset( $saved_mods[ $key ] ) ) $mod_count++;
    }

    $import_error = isset( $_GET['kwl_import_error'] ) ? sanitize_key( $_GET['kwl_import_error'] ) : '';
    $import_ok    = ! empty( $_GET['kwl_import_ok'] );
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Export Backup', 'kwl-resume' ); ?></h2>
        <p><?php esc_html_e( 'Download a JSON backup file containing all your resume content and appearance settings. Use this file to migrate to a new domain, restore after a reinstall, or keep an off-site copy.', 'kwl-resume' ); ?></p>

        <?php if ( $option_count === 0 && $mod_count === 0 ) : ?>
        <div class="notice notice-warning inline" style="margin:0 0 16px">
            <p><?php esc_html_e( 'No saved data found yet. Fill in your resume content first, then export.', 'kwl-resume' ); ?></p>
        </div>
        <?php else : ?>
        <p class="description" style="margin-bottom:20px">
            <?php
            printf(
                /* translators: 1: number of content option groups, 2: number of customizer settings */
                esc_html__( 'Your backup will include %1$s content section(s) and %2$s appearance setting(s).', 'kwl-resume' ),
                '<strong>' . esc_html( $option_count ) . '</strong>',
                '<strong>' . esc_html( $mod_count ) . '</strong>'
            );
            ?>
        </p>
        <?php endif; ?>

        <a href="<?php echo esc_url( $export_url ); ?>" class="button button-primary button-large">
            ⬇ <?php esc_html_e( 'Download Backup (.json)', 'kwl-resume' ); ?>
        </a>
    </div>

    <div class="kwl-section-card" style="margin-top:24px">
        <h2><?php esc_html_e( 'Restore from Backup', 'kwl-resume' ); ?></h2>
        <p><?php esc_html_e( 'Upload a backup file exported from this theme. All current resume content and appearance settings will be overwritten with the backup data.', 'kwl-resume' ); ?></p>

        <?php if ( $import_ok ) : ?>
        <div class="notice notice-success inline" style="margin:0 0 20px">
            <p><?php esc_html_e( 'Backup restored successfully! Your resume content and settings have been updated.', 'kwl-resume' ); ?></p>
        </div>
        <?php endif; ?>

        <?php if ( $import_error ) : ?>
        <div class="notice notice-error inline" style="margin:0 0 20px">
            <p>
                <?php
                $messages = [
                    'no_file'      => __( 'No file was uploaded. Please choose a backup file and try again.', 'kwl-resume' ),
                    'upload_error' => __( 'The file could not be uploaded. Check your server upload settings.', 'kwl-resume' ),
                    'too_large'    => __( 'The file is too large (max 5 MB). Are you sure this is a KWL Resume backup?', 'kwl-resume' ),
                    'read_error'   => __( 'Could not read the uploaded file. Please try again.', 'kwl-resume' ),
                    'invalid_file' => __( 'Invalid backup file. Make sure you are uploading a .json file exported from KWL Resume.', 'kwl-resume' ),
                    'hash_mismatch'=> __( 'The backup file appears to be corrupted or was manually edited. Restore aborted.', 'kwl-resume' ),
                ];
                $msg = isset( $messages[ $import_error ] ) ? $messages[ $import_error ] : __( 'An unknown error occurred.', 'kwl-resume' );
                echo esc_html( $msg );
                ?>
            </p>
        </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" class="kwl-form">
            <?php wp_nonce_field( 'kwl_resume_admin', 'kwl_resume_nonce' ); ?>
            <input type="hidden" name="kwl_tab" value="backup">

            <div class="kwl-field-row" style="max-width:480px">
                <label><?php esc_html_e( 'Backup File (.json)', 'kwl-resume' ); ?></label>
                <input type="file" name="kwl_backup_file" accept=".json,application/json" class="kwl-file-input" required>
                <p class="description"><?php esc_html_e( 'Only .json files exported from KWL Resume are accepted.', 'kwl-resume' ); ?></p>
            </div>

            <div class="kwl-restore-warning" style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:6px;padding:14px 18px;margin:18px 0;max-width:600px">
                <p style="margin:0;font-size:0.83rem;color:#92400E">
                    <strong>⚠ <?php esc_html_e( 'Warning:', 'kwl-resume' ); ?></strong>
                    <?php esc_html_e( 'Restoring a backup will replace all current resume content and appearance settings. This action cannot be undone. Export your current data first if you want to keep it.', 'kwl-resume' ); ?>
                </p>
            </div>

            <button type="submit" name="kwl_resume_import" class="button button-secondary button-large"
                    onclick="return confirm('<?php echo esc_js( __( 'This will overwrite your current resume data. Are you sure you want to continue?', 'kwl-resume' ) ); ?>')">
                ⬆ <?php esc_html_e( 'Restore Backup', 'kwl-resume' ); ?>
            </button>
        </form>
    </div>

    <div class="kwl-section-card" style="margin-top:24px;background:#F0FDF4;border-color:#86EFAC">
        <h3 style="margin-top:0;color:#166534"><?php esc_html_e( 'Migration Guide', 'kwl-resume' ); ?></h3>
        <ol style="margin:0 0 0 18px;color:#166534;font-size:0.87rem;line-height:1.9">
            <li><?php esc_html_e( 'On your current site: go to this tab and click "Download Backup (.json)".', 'kwl-resume' ); ?></li>
            <li><?php esc_html_e( 'On your new WordPress install: install and activate the KWL Resume theme.', 'kwl-resume' ); ?></li>
            <li><?php esc_html_e( 'On the new site: go to Appearance → Resume Content → 💾 Backup & Restore.', 'kwl-resume' ); ?></li>
            <li><?php esc_html_e( 'Upload the .json file from Step 1 and click "Restore Backup".', 'kwl-resume' ); ?></li>
            <li><?php esc_html_e( 'Done — all your resume content and color/font settings will be restored instantly.', 'kwl-resume' ); ?></li>
        </ol>
    </div>
    <?php
}
