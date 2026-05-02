<?php
/**
 * KWL Resume — Admin Settings Page
 *
 * @package kwl-resume
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Register admin menu ── */
add_action( 'admin_menu', function() {
    add_theme_page(
        __( 'KWL Resume Settings', 'kwl-resume' ),
        __( 'Resume Content', 'kwl-resume' ),
        'edit_theme_options',
        'kwl-resume-settings',
        'kwl_resume_admin_page'
    );
});

/* ── Save handler ── */
add_action( 'admin_init', 'kwl_resume_save_settings' );

function kwl_resume_save_settings() {
    if ( ! isset( $_POST['kwl_resume_save'] ) ) return;
    if ( ! check_admin_referer( 'kwl_resume_admin', 'kwl_resume_nonce' ) ) return;
    if ( ! current_user_can( 'edit_theme_options' ) ) return;

    $tab = isset( $_POST['kwl_tab'] ) ? sanitize_key( $_POST['kwl_tab'] ) : 'profile';

    switch ( $tab ) {

        case 'profile':
            $profile = [
                'name'         => sanitize_text_field( $_POST['kwl_name'] ?? '' ),
                'title'        => sanitize_text_field( $_POST['kwl_title'] ?? '' ),
                'summary'      => wp_kses_post( $_POST['kwl_summary'] ?? '' ),
                'photo_url'    => esc_url_raw( $_POST['kwl_photo_url'] ?? '' ),
                'initials'     => sanitize_text_field( $_POST['kwl_initials'] ?? '' ),
                'open_to_work' => isset( $_POST['kwl_open_to_work'] ) ? '1' : '0',
                'last_updated' => sanitize_text_field( $_POST['kwl_last_updated'] ?? '' ),
            ];
            update_option( 'kwl_resume_profile', $profile );
            break;

        case 'contact':
            $custom_links = [];
            if ( ! empty( $_POST['kwl_custom_link_label'] ) ) {
                foreach ( $_POST['kwl_custom_link_label'] as $i => $label ) {
                    $label = sanitize_text_field( $label );
                    $url   = esc_url_raw( $_POST['kwl_custom_link_url'][ $i ] ?? '' );
                    $icon  = sanitize_text_field( $_POST['kwl_custom_link_icon'][ $i ] ?? '' );
                    if ( $label || $url ) {
                        $custom_links[] = compact( 'label', 'url', 'icon' );
                    }
                }
            }
            $contact = [
                'location'      => sanitize_text_field( $_POST['kwl_location'] ?? '' ),
                'remote_label'  => sanitize_text_field( $_POST['kwl_remote_label'] ?? '' ),
                'email'         => sanitize_email( $_POST['kwl_email'] ?? '' ),
                'linkedin'      => esc_url_raw( $_POST['kwl_linkedin'] ?? '' ),
                'linkedin_label'=> sanitize_text_field( $_POST['kwl_linkedin_label'] ?? '' ),
                'github'        => esc_url_raw( $_POST['kwl_github'] ?? '' ),
                'github_label'  => sanitize_text_field( $_POST['kwl_github_label'] ?? '' ),
                'website'       => esc_url_raw( $_POST['kwl_website'] ?? '' ),
                'website_label' => sanitize_text_field( $_POST['kwl_website_label'] ?? '' ),
                'custom_links'  => $custom_links,
            ];
            update_option( 'kwl_resume_contact', $contact );
            break;

        case 'experience':
            $exp = [];
            if ( ! empty( $_POST['kwl_exp_role'] ) ) {
                foreach ( $_POST['kwl_exp_role'] as $i => $role ) {
                    $bullets_raw = $_POST['kwl_exp_bullets'][ $i ] ?? '';
                    $bullets     = array_filter( array_map( 'sanitize_text_field', preg_split( '/\r?\n/', $bullets_raw ) ) );
                    $exp[] = [
                        'role'    => sanitize_text_field( $role ),
                        'company' => sanitize_text_field( $_POST['kwl_exp_company'][ $i ] ?? '' ),
                        'date'    => sanitize_text_field( $_POST['kwl_exp_date'][ $i ] ?? '' ),
                        'bullets' => array_values( $bullets ),
                    ];
                }
            }
            update_option( 'kwl_resume_experience', $exp );
            break;

        case 'skills':
            $raw    = sanitize_text_field( $_POST['kwl_skills_raw'] ?? '' );
            $skills = array_filter( array_map( 'trim', explode( ',', $raw ) ) );
            update_option( 'kwl_resume_skills', array_values( $skills ) );
            break;

        case 'education':
            $edu = [];
            if ( ! empty( $_POST['kwl_edu_institution'] ) ) {
                foreach ( $_POST['kwl_edu_institution'] as $i => $inst ) {
                    $edu[] = [
                        'institution' => sanitize_text_field( $inst ),
                        'degree'      => sanitize_text_field( $_POST['kwl_edu_degree'][ $i ] ?? '' ),
                        'date'        => sanitize_text_field( $_POST['kwl_edu_date'][ $i ] ?? '' ),
                    ];
                }
            }
            update_option( 'kwl_resume_education', $edu );
            break;

        case 'certifications':
            $certs = [];
            if ( ! empty( $_POST['kwl_cert_name'] ) ) {
                foreach ( $_POST['kwl_cert_name'] as $i => $name ) {
                    $certs[] = [
                        'name'   => sanitize_text_field( $name ),
                        'issuer' => sanitize_text_field( $_POST['kwl_cert_issuer'][ $i ] ?? '' ),
                        'date'   => sanitize_text_field( $_POST['kwl_cert_date'][ $i ] ?? '' ),
                    ];
                }
            }
            update_option( 'kwl_resume_certifications', $certs );
            break;

        case 'projects':
            $projects = [];
            if ( ! empty( $_POST['kwl_proj_name'] ) ) {
                foreach ( $_POST['kwl_proj_name'] as $i => $name ) {
                    $projects[] = [
                        'name'        => sanitize_text_field( $name ),
                        'type'        => sanitize_text_field( $_POST['kwl_proj_type'][ $i ] ?? '' ),
                        'description' => wp_kses_post( $_POST['kwl_proj_desc'][ $i ] ?? '' ),
                        'url'         => esc_url_raw( $_POST['kwl_proj_url'][ $i ] ?? '' ),
                        'url_label'   => sanitize_text_field( $_POST['kwl_proj_url_label'][ $i ] ?? '' ),
                    ];
                }
            }
            update_option( 'kwl_resume_projects', $projects );
            break;

        case 'custom':
            $custom = [];
            if ( ! empty( $_POST['kwl_cs_title'] ) ) {
                foreach ( $_POST['kwl_cs_title'] as $si => $title ) {
                    $entries = [];
                    $ent_headings = $_POST['kwl_cs_entry_heading'][ $si ] ?? [];
                    foreach ( $ent_headings as $ei => $heading ) {
                        $bullets_raw = $_POST['kwl_cs_entry_bullets'][ $si ][ $ei ] ?? '';
                        $bullets     = array_filter( array_map( 'sanitize_text_field', preg_split( '/\r?\n/', $bullets_raw ) ) );
                        $entries[] = [
                            'heading'    => sanitize_text_field( $heading ),
                            'subheading' => sanitize_text_field( $_POST['kwl_cs_entry_sub'][ $si ][ $ei ] ?? '' ),
                            'date'       => sanitize_text_field( $_POST['kwl_cs_entry_date'][ $si ][ $ei ] ?? '' ),
                            'description'=> wp_kses_post( $_POST['kwl_cs_entry_desc'][ $si ][ $ei ] ?? '' ),
                            'bullets'    => array_values( $bullets ),
                        ];
                    }
                    $custom[] = [
                        'title'   => sanitize_text_field( $title ),
                        'enabled' => sanitize_text_field( $_POST['kwl_cs_enabled'][ $si ] ?? '0' ),
                        'entries' => $entries,
                    ];
                }
            }
            update_option( 'kwl_resume_custom_sections', $custom );
            break;

        case 'sections':
            $sections_cfg = kwl_resume_get_sections_config();
            foreach ( array_keys( $sections_cfg ) as $key ) {
                $sections_cfg[ $key ]['enabled'] = isset( $_POST[ 'kwl_section_' . $key ] ) ? '1' : '0';
                if ( isset( $_POST[ 'kwl_section_label_' . $key ] ) ) {
                    $sections_cfg[ $key ]['label'] = sanitize_text_field( $_POST[ 'kwl_section_label_' . $key ] );
                }
            }
            update_option( 'kwl_resume_sections', $sections_cfg );
            break;
    }

    wp_redirect( add_query_arg( [ 'page' => 'kwl-resume-settings', 'tab' => $tab, 'saved' => '1' ], admin_url( 'themes.php' ) ) );
    exit;
}

/* ── Admin Page Render ── */
function kwl_resume_admin_page() {
    $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'profile';
    $tabs = [
        'profile'        => __( '👤 Profile',         'kwl-resume' ),
        'contact'        => __( '📬 Contact',          'kwl-resume' ),
        'experience'     => __( '💼 Experience',       'kwl-resume' ),
        'skills'         => __( '🛠 Skills',           'kwl-resume' ),
        'education'      => __( '🎓 Education',        'kwl-resume' ),
        'certifications' => __( '📜 Certifications',   'kwl-resume' ),
        'projects'       => __( '🗂 Projects',         'kwl-resume' ),
        'custom'         => __( '➕ Custom Sections',  'kwl-resume' ),
        'sections'       => __( '⚙️ Section Settings', 'kwl-resume' ),
    ];
    ?>
    <div class="wrap kwl-resume-admin">
        <h1 class="kwl-admin-heading">
            <span class="kwl-logo">KWL Resume</span>
            <?php esc_html_e( 'Resume Content Settings', 'kwl-resume' ); ?>
        </h1>

        <?php if ( ! empty( $_GET['saved'] ) ) : ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved successfully!', 'kwl-resume' ); ?></p></div>
        <?php endif; ?>

        <nav class="kwl-tabs">
            <?php foreach ( $tabs as $key => $label ) : ?>
            <a href="<?php echo esc_url( add_query_arg( [ 'page' => 'kwl-resume-settings', 'tab' => $key ], admin_url( 'themes.php' ) ) ); ?>"
               class="kwl-tab <?php echo $tab === $key ? 'kwl-tab--active' : ''; ?>">
                <?php echo esc_html( $label ); ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <form method="post" action="" class="kwl-form">
            <?php wp_nonce_field( 'kwl_resume_admin', 'kwl_resume_nonce' ); ?>
            <input type="hidden" name="kwl_tab" value="<?php echo esc_attr( $tab ); ?>">

            <div class="kwl-tab-content">
                <?php
                switch ( $tab ) {
                    case 'profile':        kwl_resume_tab_profile();        break;
                    case 'contact':        kwl_resume_tab_contact();        break;
                    case 'experience':     kwl_resume_tab_experience();     break;
                    case 'skills':         kwl_resume_tab_skills();         break;
                    case 'education':      kwl_resume_tab_education();      break;
                    case 'certifications': kwl_resume_tab_certifications(); break;
                    case 'projects':       kwl_resume_tab_projects();       break;
                    case 'custom':         kwl_resume_tab_custom();         break;
                    case 'sections':       kwl_resume_tab_sections();       break;
                }
                ?>
            </div>

            <div class="kwl-form-footer">
                <button type="submit" name="kwl_resume_save" class="button button-primary button-large">
                    <?php esc_html_e( 'Save Changes', 'kwl-resume' ); ?>
                </button>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-secondary">
                    <?php esc_html_e( 'Preview Resume →', 'kwl-resume' ); ?>
                </a>
            </div>
        </form>
    </div>
    <?php
}

/* ── TAB: Profile ── */
function kwl_resume_tab_profile() {
    $p = kwl_resume_get_profile();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Profile Information', 'kwl-resume' ); ?></h2>

        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Full Name', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_name" value="<?php echo esc_attr( $p['name'] ); ?>" class="regular-text">
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Professional Title / Subtitle', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_title" value="<?php echo esc_attr( $p['title'] ); ?>" class="large-text">
            <p class="description"><?php esc_html_e( 'e.g. "Affiliate Marketing Manager · WordPress & SEO Specialist"', 'kwl-resume' ); ?></p>
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Avatar Initials', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_initials" value="<?php echo esc_attr( $p['initials'] ); ?>" class="small-text" maxlength="3">
            <p class="description"><?php esc_html_e( 'Shown when no photo is uploaded (e.g. KW).', 'kwl-resume' ); ?></p>
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Profile Photo URL', 'kwl-resume' ); ?></label>
            <input type="url" name="kwl_photo_url" value="<?php echo esc_attr( $p['photo_url'] ); ?>" class="large-text">
            <p class="description"><?php esc_html_e( 'Paste the URL of your photo from the Media Library (or any image URL). Leave blank to show initials.', 'kwl-resume' ); ?></p>
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Professional Summary', 'kwl-resume' ); ?></label>
            <textarea name="kwl_summary" rows="6" class="large-text"><?php echo esc_textarea( $p['summary'] ); ?></textarea>
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Last Updated Text', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_last_updated" value="<?php echo esc_attr( $p['last_updated'] ); ?>" class="regular-text">
        </div>
        <div class="kwl-field-row">
            <label>
                <input type="checkbox" name="kwl_open_to_work" value="1" <?php checked( $p['open_to_work'], '1' ); ?>>
                <?php esc_html_e( 'Show "Open to new opportunities" in footer', 'kwl-resume' ); ?>
            </label>
        </div>
    </div>
    <?php
}

/* ── TAB: Contact ── */
function kwl_resume_tab_contact() {
    $c = kwl_resume_get_contact();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Contact & Social Links', 'kwl-resume' ); ?></h2>

        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Location', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_location" value="<?php echo esc_attr( $c['location'] ); ?>" class="regular-text">
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Remote Label', 'kwl-resume' ); ?></label>
            <input type="text" name="kwl_remote_label" value="<?php echo esc_attr( $c['remote_label'] ); ?>" class="small-text">
            <p class="description"><?php esc_html_e( 'Shown below location. e.g. "Remote" or "Available Worldwide". Leave blank to hide.', 'kwl-resume' ); ?></p>
        </div>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Email Address', 'kwl-resume' ); ?></label>
            <input type="email" name="kwl_email" value="<?php echo esc_attr( $c['email'] ); ?>" class="regular-text">
        </div>
        <div class="kwl-field-row kwl-field-row--inline">
            <div>
                <label><?php esc_html_e( 'LinkedIn URL', 'kwl-resume' ); ?></label>
                <input type="url" name="kwl_linkedin" value="<?php echo esc_attr( $c['linkedin'] ); ?>" class="regular-text">
            </div>
            <div>
                <label><?php esc_html_e( 'LinkedIn Display Label', 'kwl-resume' ); ?></label>
                <input type="text" name="kwl_linkedin_label" value="<?php echo esc_attr( $c['linkedin_label'] ); ?>" class="regular-text">
            </div>
        </div>
        <div class="kwl-field-row kwl-field-row--inline">
            <div>
                <label><?php esc_html_e( 'GitHub URL', 'kwl-resume' ); ?></label>
                <input type="url" name="kwl_github" value="<?php echo esc_attr( $c['github'] ); ?>" class="regular-text">
            </div>
            <div>
                <label><?php esc_html_e( 'GitHub Display Label', 'kwl-resume' ); ?></label>
                <input type="text" name="kwl_github_label" value="<?php echo esc_attr( $c['github_label'] ); ?>" class="regular-text">
            </div>
        </div>
        <div class="kwl-field-row kwl-field-row--inline">
            <div>
                <label><?php esc_html_e( 'Website URL', 'kwl-resume' ); ?></label>
                <input type="url" name="kwl_website" value="<?php echo esc_attr( $c['website'] ); ?>" class="regular-text">
            </div>
            <div>
                <label><?php esc_html_e( 'Website Display Label', 'kwl-resume' ); ?></label>
                <input type="text" name="kwl_website_label" value="<?php echo esc_attr( $c['website_label'] ); ?>" class="regular-text">
            </div>
        </div>
    </div>

    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Additional / Custom Links', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:16px"><?php esc_html_e( 'Add any extra links: Twitter/X, Upwork profile, portfolio, etc.', 'kwl-resume' ); ?></p>
        <div id="kwl-custom-links-list" class="kwl-repeater-list">
            <?php foreach ( $c['custom_links'] as $link ) : ?>
            <div class="kwl-repeater-item">
                <div class="kwl-repeater-fields">
                    <input type="text" name="kwl_custom_link_icon[]" value="<?php echo esc_attr( $link['icon'] ); ?>" placeholder="<?php esc_attr_e( 'Icon/Emoji (e.g. 🐦)', 'kwl-resume' ); ?>" class="small-text">
                    <input type="text" name="kwl_custom_link_label[]" value="<?php echo esc_attr( $link['label'] ); ?>" placeholder="<?php esc_attr_e( 'Label', 'kwl-resume' ); ?>" class="regular-text">
                    <input type="url" name="kwl_custom_link_url[]" value="<?php echo esc_attr( $link['url'] ); ?>" placeholder="https://" class="regular-text">
                </div>
                <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( 'Remove', 'kwl-resume' ); ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="kwl-add-custom-link" class="button" data-target="kwl-custom-links-list" data-template="kwl-tpl-custom-link">
            + <?php esc_html_e( 'Add Link', 'kwl-resume' ); ?>
        </button>
        <script type="text/html" id="kwl-tpl-custom-link">
        <div class="kwl-repeater-item">
            <div class="kwl-repeater-fields">
                <input type="text" name="kwl_custom_link_icon[]" placeholder="<?php esc_attr_e( 'Icon/Emoji', 'kwl-resume' ); ?>" class="small-text">
                <input type="text" name="kwl_custom_link_label[]" placeholder="<?php esc_attr_e( 'Label', 'kwl-resume' ); ?>" class="regular-text">
                <input type="url" name="kwl_custom_link_url[]" placeholder="https://" class="regular-text">
            </div>
            <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( 'Remove', 'kwl-resume' ); ?></button>
        </div>
        </script>
    </div>
    <?php
}

/* ── TAB: Experience ── */
function kwl_resume_tab_experience() {
    $items = kwl_resume_get_experience();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Work Experience', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:16px"><?php esc_html_e( 'Drag to reorder. Each bullet point goes on its own line.', 'kwl-resume' ); ?></p>
        <div id="kwl-exp-list" class="kwl-repeater-list kwl-sortable">
            <?php foreach ( $items as $i => $item ) : ?>
            <div class="kwl-repeater-item kwl-exp-item">
                <div class="kwl-repeater-handle">☰</div>
                <div class="kwl-repeater-body">
                    <div class="kwl-field-row kwl-field-row--inline">
                        <div>
                            <label><?php esc_html_e( 'Job Title / Role', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_exp_role[]" value="<?php echo esc_attr( $item['role'] ); ?>" class="large-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Date Range', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_exp_date[]" value="<?php echo esc_attr( $item['date'] ); ?>" class="regular-text" placeholder="Jan 2020 – Dec 2022">
                        </div>
                    </div>
                    <div class="kwl-field-row">
                        <label><?php esc_html_e( 'Company / Client', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_exp_company[]" value="<?php echo esc_attr( $item['company'] ); ?>" class="large-text">
                    </div>
                    <div class="kwl-field-row">
                        <label><?php esc_html_e( 'Bullet Points (one per line)', 'kwl-resume' ); ?></label>
                        <textarea name="kwl_exp_bullets[]" rows="5" class="large-text"><?php echo esc_textarea( implode( "\n", $item['bullets'] ) ); ?></textarea>
                    </div>
                </div>
                <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove Entry', 'kwl-resume' ); ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button kwl-add-repeater" data-target="kwl-exp-list" data-template="kwl-tpl-exp">
            + <?php esc_html_e( 'Add Experience Entry', 'kwl-resume' ); ?>
        </button>

        <script type="text/html" id="kwl-tpl-exp">
        <div class="kwl-repeater-item kwl-exp-item">
            <div class="kwl-repeater-handle">☰</div>
            <div class="kwl-repeater-body">
                <div class="kwl-field-row kwl-field-row--inline">
                    <div>
                        <label><?php esc_html_e( 'Job Title / Role', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_exp_role[]" class="large-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Date Range', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_exp_date[]" class="regular-text" placeholder="Jan 2020 – Dec 2022">
                    </div>
                </div>
                <div class="kwl-field-row">
                    <label><?php esc_html_e( 'Company / Client', 'kwl-resume' ); ?></label>
                    <input type="text" name="kwl_exp_company[]" class="large-text">
                </div>
                <div class="kwl-field-row">
                    <label><?php esc_html_e( 'Bullet Points (one per line)', 'kwl-resume' ); ?></label>
                    <textarea name="kwl_exp_bullets[]" rows="5" class="large-text"></textarea>
                </div>
            </div>
            <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove Entry', 'kwl-resume' ); ?></button>
        </div>
        </script>
    </div>
    <?php
}

/* ── TAB: Skills ── */
function kwl_resume_tab_skills() {
    $skills = kwl_resume_get_skills();
    $skills_csv = implode( ', ', $skills );
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Skills', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:16px"><?php esc_html_e( 'Enter your skills as a comma-separated list. Each skill becomes a tag on the resume.', 'kwl-resume' ); ?></p>
        <div class="kwl-field-row">
            <label><?php esc_html_e( 'Skills (comma-separated)', 'kwl-resume' ); ?></label>
            <textarea name="kwl_skills_raw" rows="6" class="large-text"><?php echo esc_textarea( $skills_csv ); ?></textarea>
        </div>
        <div class="kwl-skills-preview" id="kwl-skills-preview">
            <?php foreach ( $skills as $skill ) : ?>
            <span class="kwl-skill-preview-tag"><?php echo esc_html( $skill ); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/* ── TAB: Education ── */
function kwl_resume_tab_education() {
    $items = kwl_resume_get_education();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Education', 'kwl-resume' ); ?></h2>
        <div id="kwl-edu-list" class="kwl-repeater-list kwl-sortable">
            <?php foreach ( $items as $item ) : ?>
            <div class="kwl-repeater-item">
                <div class="kwl-repeater-handle">☰</div>
                <div class="kwl-repeater-body">
                    <div class="kwl-field-row">
                        <label><?php esc_html_e( 'Institution', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_edu_institution[]" value="<?php echo esc_attr( html_entity_decode( $item['institution'] ) ); ?>" class="large-text">
                    </div>
                    <div class="kwl-field-row kwl-field-row--inline">
                        <div>
                            <label><?php esc_html_e( 'Degree / Diploma', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_edu_degree[]" value="<?php echo esc_attr( html_entity_decode( $item['degree'] ) ); ?>" class="large-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Date Range', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_edu_date[]" value="<?php echo esc_attr( $item['date'] ); ?>" class="regular-text">
                        </div>
                    </div>
                </div>
                <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove', 'kwl-resume' ); ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button kwl-add-repeater" data-target="kwl-edu-list" data-template="kwl-tpl-edu">
            + <?php esc_html_e( 'Add Education Entry', 'kwl-resume' ); ?>
        </button>
        <script type="text/html" id="kwl-tpl-edu">
        <div class="kwl-repeater-item">
            <div class="kwl-repeater-handle">☰</div>
            <div class="kwl-repeater-body">
                <div class="kwl-field-row">
                    <label><?php esc_html_e( 'Institution', 'kwl-resume' ); ?></label>
                    <input type="text" name="kwl_edu_institution[]" class="large-text">
                </div>
                <div class="kwl-field-row kwl-field-row--inline">
                    <div>
                        <label><?php esc_html_e( 'Degree / Diploma', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_edu_degree[]" class="large-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Date Range', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_edu_date[]" class="regular-text">
                    </div>
                </div>
            </div>
            <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove', 'kwl-resume' ); ?></button>
        </div>
        </script>
    </div>
    <?php
}

/* ── TAB: Certifications ── */
function kwl_resume_tab_certifications() {
    $items = kwl_resume_get_certifications();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Certifications', 'kwl-resume' ); ?></h2>
        <div id="kwl-cert-list" class="kwl-repeater-list kwl-sortable">
            <?php foreach ( $items as $item ) : ?>
            <div class="kwl-repeater-item">
                <div class="kwl-repeater-handle">☰</div>
                <div class="kwl-repeater-body">
                    <div class="kwl-field-row kwl-field-row--3col">
                        <div>
                            <label><?php esc_html_e( 'Certification Name', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_cert_name[]" value="<?php echo esc_attr( $item['name'] ); ?>" class="large-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Issuer / Platform', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_cert_issuer[]" value="<?php echo esc_attr( $item['issuer'] ); ?>" class="regular-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Date', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_cert_date[]" value="<?php echo esc_attr( $item['date'] ); ?>" class="regular-text">
                        </div>
                    </div>
                </div>
                <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove', 'kwl-resume' ); ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button kwl-add-repeater" data-target="kwl-cert-list" data-template="kwl-tpl-cert">
            + <?php esc_html_e( 'Add Certification', 'kwl-resume' ); ?>
        </button>
        <script type="text/html" id="kwl-tpl-cert">
        <div class="kwl-repeater-item">
            <div class="kwl-repeater-handle">☰</div>
            <div class="kwl-repeater-body">
                <div class="kwl-field-row kwl-field-row--3col">
                    <div>
                        <label><?php esc_html_e( 'Certification Name', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_cert_name[]" class="large-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Issuer / Platform', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_cert_issuer[]" class="regular-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Date', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_cert_date[]" class="regular-text">
                    </div>
                </div>
            </div>
            <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove', 'kwl-resume' ); ?></button>
        </div>
        </script>
    </div>
    <?php
}

/* ── TAB: Projects ── */
function kwl_resume_tab_projects() {
    $items = kwl_resume_get_projects();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Projects', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:16px"><?php esc_html_e( 'Can be open source, client work, personal projects, portfolio pieces, research — any type.', 'kwl-resume' ); ?></p>
        <div id="kwl-proj-list" class="kwl-repeater-list kwl-sortable">
            <?php foreach ( $items as $item ) : ?>
            <div class="kwl-repeater-item">
                <div class="kwl-repeater-handle">☰</div>
                <div class="kwl-repeater-body">
                    <div class="kwl-field-row kwl-field-row--inline">
                        <div>
                            <label><?php esc_html_e( 'Project Name', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_proj_name[]" value="<?php echo esc_attr( $item['name'] ); ?>" class="large-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Type', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_proj_type[]" value="<?php echo esc_attr( $item['type'] ); ?>" class="regular-text" placeholder="Open Source, Client Work, Personal...">
                        </div>
                    </div>
                    <div class="kwl-field-row">
                        <label><?php esc_html_e( 'Description', 'kwl-resume' ); ?></label>
                        <textarea name="kwl_proj_desc[]" rows="3" class="large-text"><?php echo esc_textarea( $item['description'] ); ?></textarea>
                    </div>
                    <div class="kwl-field-row kwl-field-row--inline">
                        <div>
                            <label><?php esc_html_e( 'URL', 'kwl-resume' ); ?></label>
                            <input type="url" name="kwl_proj_url[]" value="<?php echo esc_attr( $item['url'] ); ?>" class="large-text">
                        </div>
                        <div>
                            <label><?php esc_html_e( 'Link Label', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_proj_url_label[]" value="<?php echo esc_attr( $item['url_label'] ); ?>" class="regular-text">
                        </div>
                    </div>
                </div>
                <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove Project', 'kwl-resume' ); ?></button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button kwl-add-repeater" data-target="kwl-proj-list" data-template="kwl-tpl-proj">
            + <?php esc_html_e( 'Add Project', 'kwl-resume' ); ?>
        </button>
        <script type="text/html" id="kwl-tpl-proj">
        <div class="kwl-repeater-item">
            <div class="kwl-repeater-handle">☰</div>
            <div class="kwl-repeater-body">
                <div class="kwl-field-row kwl-field-row--inline">
                    <div>
                        <label><?php esc_html_e( 'Project Name', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_proj_name[]" class="large-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Type', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_proj_type[]" class="regular-text" placeholder="Open Source, Client Work, Personal...">
                    </div>
                </div>
                <div class="kwl-field-row">
                    <label><?php esc_html_e( 'Description', 'kwl-resume' ); ?></label>
                    <textarea name="kwl_proj_desc[]" rows="3" class="large-text"></textarea>
                </div>
                <div class="kwl-field-row kwl-field-row--inline">
                    <div>
                        <label><?php esc_html_e( 'URL', 'kwl-resume' ); ?></label>
                        <input type="url" name="kwl_proj_url[]" class="large-text">
                    </div>
                    <div>
                        <label><?php esc_html_e( 'Link Label', 'kwl-resume' ); ?></label>
                        <input type="text" name="kwl_proj_url_label[]" class="regular-text">
                    </div>
                </div>
            </div>
            <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove Project', 'kwl-resume' ); ?></button>
        </div>
        </script>
    </div>
    <?php
}

/* ── TAB: Custom Sections ── */
function kwl_resume_tab_custom() {
    $sections = kwl_resume_get_custom_sections();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Custom Sections', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:20px">
            <?php esc_html_e( 'Add any section that doesn\'t fit the standard template — Awards, Volunteer Work, Languages, Publications, Speaking Engagements, and more.', 'kwl-resume' ); ?>
        </p>

        <div id="kwl-custom-sections-list" class="kwl-custom-sections-wrap">
            <?php foreach ( $sections as $si => $section ) : ?>
            <div class="kwl-custom-section" data-section-index="<?php echo esc_attr( $si ); ?>">
                <div class="kwl-custom-section-header">
                    <div class="kwl-field-row kwl-field-row--inline" style="flex:1;margin:0">
                        <div style="flex:1">
                            <label><?php esc_html_e( 'Section Title', 'kwl-resume' ); ?></label>
                            <input type="text" name="kwl_cs_title[]" value="<?php echo esc_attr( $section['title'] ); ?>" class="large-text" placeholder="e.g. Awards, Volunteer Work...">
                        </div>
                        <div style="align-self:flex-end;padding-bottom:4px">
                            <label>
                                <input type="hidden" name="kwl_cs_enabled[]" value="<?php echo esc_attr( $section['enabled'] ); ?>">
                                <input type="checkbox" <?php checked( $section['enabled'], '1' ); ?>
                                       onchange="this.previousElementSibling.value = this.checked ? '1' : '0';">
                                <?php esc_html_e( 'Enabled', 'kwl-resume' ); ?>
                            </label>
                        </div>
                    </div>
                    <button type="button" class="kwl-remove-section button-link-delete"><?php esc_html_e( '✕ Remove Section', 'kwl-resume' ); ?></button>
                </div>
                <div class="kwl-custom-entries" id="kwl-cs-entries-<?php echo esc_attr( $si ); ?>">
                    <?php foreach ( $section['entries'] as $ei => $entry ) : ?>
                    <div class="kwl-repeater-item kwl-cs-entry">
                        <div class="kwl-repeater-body">
                            <div class="kwl-field-row kwl-field-row--inline">
                                <div>
                                    <label><?php esc_html_e( 'Heading', 'kwl-resume' ); ?></label>
                                    <input type="text" name="kwl_cs_entry_heading[<?php echo esc_attr( $si ); ?>][]" value="<?php echo esc_attr( $entry['heading'] ); ?>" class="large-text">
                                </div>
                                <div>
                                    <label><?php esc_html_e( 'Subheading', 'kwl-resume' ); ?></label>
                                    <input type="text" name="kwl_cs_entry_sub[<?php echo esc_attr( $si ); ?>][]" value="<?php echo esc_attr( $entry['subheading'] ); ?>" class="regular-text">
                                </div>
                                <div>
                                    <label><?php esc_html_e( 'Date', 'kwl-resume' ); ?></label>
                                    <input type="text" name="kwl_cs_entry_date[<?php echo esc_attr( $si ); ?>][]" value="<?php echo esc_attr( $entry['date'] ); ?>" class="regular-text">
                                </div>
                            </div>
                            <div class="kwl-field-row">
                                <label><?php esc_html_e( 'Description', 'kwl-resume' ); ?></label>
                                <textarea name="kwl_cs_entry_desc[<?php echo esc_attr( $si ); ?>][]" rows="2" class="large-text"><?php echo esc_textarea( $entry['description'] ); ?></textarea>
                            </div>
                            <div class="kwl-field-row">
                                <label><?php esc_html_e( 'Bullet Points (one per line)', 'kwl-resume' ); ?></label>
                                <textarea name="kwl_cs_entry_bullets[<?php echo esc_attr( $si ); ?>][]" rows="3" class="large-text"><?php echo esc_textarea( implode( "\n", $entry['bullets'] ) ); ?></textarea>
                            </div>
                        </div>
                        <button type="button" class="kwl-remove-item button-link-delete"><?php esc_html_e( '✕ Remove Entry', 'kwl-resume' ); ?></button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button kwl-add-cs-entry" data-section="<?php echo esc_attr( $si ); ?>">
                    + <?php esc_html_e( 'Add Entry', 'kwl-resume' ); ?>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="kwl-add-custom-section" class="button button-primary" style="margin-top:16px">
            + <?php esc_html_e( 'Add Custom Section', 'kwl-resume' ); ?>
        </button>
    </div>
    <?php
}

/* ── TAB: Section Settings ── */
function kwl_resume_tab_sections() {
    $cfg = kwl_resume_get_sections_config();
    ?>
    <div class="kwl-section-card">
        <h2><?php esc_html_e( 'Section Settings', 'kwl-resume' ); ?></h2>
        <p class="description" style="margin-bottom:20px">
            <?php esc_html_e( 'Enable or disable each section and customize its heading label. Disabled sections are hidden but their content is preserved.', 'kwl-resume' ); ?>
        </p>
        <table class="widefat kwl-sections-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Section', 'kwl-resume' ); ?></th>
                    <th><?php esc_html_e( 'Heading Label', 'kwl-resume' ); ?></th>
                    <th><?php esc_html_e( 'Enabled', 'kwl-resume' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $cfg as $key => $s ) : ?>
                <tr>
                    <td><strong><?php echo esc_html( ucfirst( $key ) ); ?></strong></td>
                    <td>
                        <input type="text" name="kwl_section_label_<?php echo esc_attr( $key ); ?>"
                               value="<?php echo esc_attr( $s['label'] ); ?>" class="regular-text">
                    </td>
                    <td>
                        <input type="checkbox" name="kwl_section_<?php echo esc_attr( $key ); ?>"
                               value="1" <?php checked( $s['enabled'], '1' ); ?>>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
