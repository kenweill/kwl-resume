<?php
/**
 * KWL Resume — functions.php
 *
 * @package kwl-resume
 * @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'KWL_RESUME_VERSION', '1.0.1' );
define( 'KWL_RESUME_DIR',     get_template_directory() );
define( 'KWL_RESUME_URI',     get_template_directory_uri() );

/* ── Includes ── */
require_once KWL_RESUME_DIR . '/inc/color-schemes.php';
require_once KWL_RESUME_DIR . '/inc/template-functions.php';
require_once KWL_RESUME_DIR . '/inc/customizer.php';
require_once KWL_RESUME_DIR . '/inc/admin-settings.php';

/* ── Theme Setup ── */
function kwl_resume_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'html5', [ 'style', 'script', 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ] );
    load_theme_textdomain( 'kwl-resume', KWL_RESUME_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'kwl_resume_setup' );

/* ── Enqueue front-end assets ── */
function kwl_resume_enqueue_assets() {
    $fonts_url = kwl_resume_get_font_url();
    if ( $fonts_url ) {
        wp_enqueue_style( 'kwl-resume-fonts', $fonts_url, [], null );
    }
    wp_enqueue_style( 'kwl-resume-main', KWL_RESUME_URI . '/assets/css/resume.css', [], KWL_RESUME_VERSION );
    wp_add_inline_style( 'kwl-resume-main', kwl_resume_dynamic_css() );
}
add_action( 'wp_enqueue_scripts', 'kwl_resume_enqueue_assets' );

/* ── Enqueue admin assets ── */
function kwl_resume_admin_enqueue( $hook ) {
    if ( strpos( $hook, 'kwl-resume' ) === false ) return;
    wp_enqueue_style(  'kwl-resume-admin',  KWL_RESUME_URI . '/assets/css/admin.css',  [], KWL_RESUME_VERSION );
    wp_enqueue_script( 'kwl-resume-admin',  KWL_RESUME_URI . '/assets/js/admin.js', [ 'jquery', 'jquery-ui-sortable' ], KWL_RESUME_VERSION, true );
    wp_localize_script( 'kwl-resume-admin', 'kwlResumeAdmin', [
        'nonce'              => wp_create_nonce( 'kwl_resume_admin' ),
        'confirmDelete'      => __( 'Are you sure you want to delete this entry?', 'kwl-resume' ),
        'labelSectionTitle'  => __( 'Section Title', 'kwl-resume' ),
        'labelEnabled'       => __( 'Enabled', 'kwl-resume' ),
        'labelRemoveSection' => __( 'Remove Section', 'kwl-resume' ),
        'labelAddEntry'      => __( 'Add Entry', 'kwl-resume' ),
    ]);
}
add_action( 'admin_enqueue_scripts', 'kwl_resume_admin_enqueue' );

/* ── Dynamic CSS (color scheme + typography) ── */
function kwl_resume_dynamic_css() {
    $scheme  = get_theme_mod( 'kwl_color_scheme', 'growth-trust' );
    $schemes = kwl_resume_color_schemes();
    $colors  = isset( $schemes[ $scheme ] ) ? $schemes[ $scheme ] : $schemes['growth-trust'];

    // Scheme colors are the source of truth.
    // Individual color pickers only override when the user has explicitly
    // saved them — get_theme_mods() only returns keys written to the DB.
    $dark    = $colors['dark'];
    $dark2   = $colors['dark2'];
    $accent  = $colors['accent'];
    $accent2 = $colors['accent2'];
    $gold    = $colors['gold'];
    $page_bg = $colors['page_bg'];

    $saved = get_theme_mods();
    if ( ! empty( $saved['kwl_color_dark'] ) )    $dark    = $saved['kwl_color_dark'];
    if ( ! empty( $saved['kwl_color_dark2'] ) )   $dark2   = $saved['kwl_color_dark2'];
    if ( ! empty( $saved['kwl_color_accent'] ) )  $accent  = $saved['kwl_color_accent'];
    if ( ! empty( $saved['kwl_color_accent2'] ) ) $accent2 = $saved['kwl_color_accent2'];
    if ( ! empty( $saved['kwl_color_gold'] ) )    $gold    = $saved['kwl_color_gold'];
    if ( ! empty( $saved['kwl_color_page_bg'] ) ) $page_bg = $saved['kwl_color_page_bg'];

    $font_pair = get_theme_mod( 'kwl_font_pair', 'roboto-slab' );
    $fonts     = kwl_resume_font_pairs();
    $fp        = isset( $fonts[ $font_pair ] ) ? $fonts[ $font_pair ] : $fonts['roboto-slab'];

    $sidebar_pos = get_theme_mod( 'kwl_sidebar_position', 'left' );
    $sidebar_w   = (int) get_theme_mod( 'kwl_sidebar_width', 280 );
    $sidebar_w   = max( 200, min( 380, $sidebar_w ) );

    $col_order = $sidebar_pos === 'right'
        ? "grid-template-columns: 1fr {$sidebar_w}px;"
        : "grid-template-columns: {$sidebar_w}px 1fr;";

    $sidebar_order = $sidebar_pos === 'right'
        ? '.kwl-sidebar { order: 2; } .kwl-main { order: 1; }'
        : '';

    $animations = get_theme_mod( 'kwl_animations', true ) ? '' : '.kwl-page { animation: none; }';
    $print_btn  = get_theme_mod( 'kwl_print_button', true ) ? '' : '.kwl-print-btn { display: none !important; }';

    // Grid layout is wrapped in min-width so it never overrides the
    // mobile media query that collapses the layout to a single column.
    return "
:root {
  --dark:         {$dark};
  --dark2:        {$dark2};
  --accent:       {$accent};
  --accent2:      {$accent2};
  --accent-gold:  {$gold};
  --page-bg:      {$page_bg};
  --font-display: {$fp['display']};
  --font-body:    {$fp['body']};
}
body { background: var(--page-bg); font-family: var(--font-body); }
@media (min-width: 681px) {
  .kwl-page { {$col_order} }
  {$sidebar_order}
}
{$animations}
{$print_btn}
";
}

/* ── Google Fonts URL builder ── */
function kwl_resume_get_font_url() {
    $font_pair = get_theme_mod( 'kwl_font_pair', 'roboto-slab' );
    $pairs     = kwl_resume_font_pairs();
    $fp        = isset( $pairs[ $font_pair ] ) ? $pairs[ $font_pair ] : $pairs['roboto-slab'];
    if ( empty( $fp['google'] ) ) return '';
    return 'https://fonts.googleapis.com/css2?' . $fp['google'] . '&display=swap';
}
