<?php
/**
 * KWL Resume — Customizer
 *
 * @package kwl-resume
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', 'kwl_resume_customizer_register' );

function kwl_resume_customizer_register( $wp_customize ) {

    /* ── Panel ── */
    $wp_customize->add_panel( 'kwl_resume_panel', [
        'title'    => __( 'KWL Resume', 'kwl-resume' ),
        'priority' => 30,
    ]);

    /* ════════════════════════════════════
       SECTION: Color Scheme
    ════════════════════════════════════ */
    $wp_customize->add_section( 'kwl_colors', [
        'title' => __( 'Color Scheme', 'kwl-resume' ),
        'panel' => 'kwl_resume_panel',
    ]);

    $schemes       = kwl_resume_color_schemes();
    $scheme_choices = [];
    foreach ( $schemes as $key => $s ) {
        $scheme_choices[ $key ] = $s['label'];
    }

    $wp_customize->add_setting( 'kwl_color_scheme', [
        'default'           => 'growth-trust',
        'sanitize_callback' => 'kwl_sanitize_scheme',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_color_scheme', [
        'label'   => __( 'Preset Color Scheme', 'kwl-resume' ),
        'section' => 'kwl_colors',
        'type'    => 'select',
        'choices' => $scheme_choices,
    ]);

    // Individual color overrides
    $color_overrides = [
        'kwl_color_dark'   => [ __( 'Sidebar Background',    'kwl-resume' ), '#0A1929' ],
        'kwl_color_dark2'  => [ __( 'Sidebar Secondary BG',  'kwl-resume' ), '#0F2440' ],
        'kwl_color_accent' => [ __( 'Accent Color',          'kwl-resume' ), '#0D9488' ],
        'kwl_color_accent2'=> [ __( 'Accent Hover Color',    'kwl-resume' ), '#14B8A6' ],
        'kwl_color_gold'   => [ __( 'Highlight / Gold',      'kwl-resume' ), '#EAB308' ],
        'kwl_color_page_bg'=> [ __( 'Page Background',       'kwl-resume' ), '#E8EEF4' ],
    ];
    foreach ( $color_overrides as $setting => $meta ) {
        $wp_customize->add_setting( $setting, [
            'default'           => $meta[1],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, [
            'label'       => $meta[0],
            'section'     => 'kwl_colors',
            'description' => __( 'Overrides the preset scheme color.', 'kwl-resume' ),
        ]));
    }

    /* ════════════════════════════════════
       SECTION: Typography
    ════════════════════════════════════ */
    $wp_customize->add_section( 'kwl_typography', [
        'title' => __( 'Typography', 'kwl-resume' ),
        'panel' => 'kwl_resume_panel',
    ]);

    $font_pairs = kwl_resume_font_pairs();
    $font_choices = [];
    foreach ( $font_pairs as $key => $fp ) {
        $font_choices[ $key ] = $fp['label'];
    }

    $wp_customize->add_setting( 'kwl_font_pair', [
        'default'           => 'roboto-slab',
        'sanitize_callback' => 'kwl_sanitize_font_pair',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_font_pair', [
        'label'   => __( 'Font Pairing', 'kwl-resume' ),
        'section' => 'kwl_typography',
        'type'    => 'select',
        'choices' => $font_choices,
    ]);

    /* ════════════════════════════════════
       SECTION: Layout
    ════════════════════════════════════ */
    $wp_customize->add_section( 'kwl_layout', [
        'title' => __( 'Layout', 'kwl-resume' ),
        'panel' => 'kwl_resume_panel',
    ]);

    $wp_customize->add_setting( 'kwl_sidebar_position', [
        'default'           => 'left',
        'sanitize_callback' => 'kwl_sanitize_sidebar_position',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_sidebar_position', [
        'label'   => __( 'Sidebar Position', 'kwl-resume' ),
        'section' => 'kwl_layout',
        'type'    => 'radio',
        'choices' => [
            'left'  => __( 'Left', 'kwl-resume' ),
            'right' => __( 'Right', 'kwl-resume' ),
        ],
    ]);

    $wp_customize->add_setting( 'kwl_sidebar_width', [
        'default'           => 280,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_sidebar_width', [
        'label'       => __( 'Sidebar Width (px)', 'kwl-resume' ),
        'description' => __( 'Between 200 and 380 pixels.', 'kwl-resume' ),
        'section'     => 'kwl_layout',
        'type'        => 'range',
        'input_attrs' => [ 'min' => 200, 'max' => 380, 'step' => 10 ],
    ]);

    /* ════════════════════════════════════
       SECTION: Sections Visibility
    ════════════════════════════════════ */
    $wp_customize->add_section( 'kwl_visibility', [
        'title'       => __( 'Section Visibility', 'kwl-resume' ),
        'description' => __( 'Toggle individual resume sections on or off. Content is preserved — just hidden.', 'kwl-resume' ),
        'panel'       => 'kwl_resume_panel',
    ]);

    $visibility_sections = [
        'kwl_show_summary'       => __( 'Show Summary', 'kwl-resume' ),
        'kwl_show_experience'    => __( 'Show Experience', 'kwl-resume' ),
        'kwl_show_skills'        => __( 'Show Skills', 'kwl-resume' ),
        'kwl_show_education'     => __( 'Show Education', 'kwl-resume' ),
        'kwl_show_certifications'=> __( 'Show Certifications', 'kwl-resume' ),
        'kwl_show_projects'      => __( 'Show Projects', 'kwl-resume' ),
        'kwl_show_contact'       => __( 'Show Contact', 'kwl-resume' ),
        'kwl_show_footer'        => __( 'Show Footer', 'kwl-resume' ),
    ];
    foreach ( $visibility_sections as $setting => $label ) {
        $wp_customize->add_setting( $setting, [
            'default'           => true,
            'sanitize_callback' => 'kwl_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control( $setting, [
            'label'   => $label,
            'section' => 'kwl_visibility',
            'type'    => 'checkbox',
        ]);
    }

    /* ════════════════════════════════════
       SECTION: Extras
    ════════════════════════════════════ */
    $wp_customize->add_section( 'kwl_extras', [
        'title' => __( 'Extras', 'kwl-resume' ),
        'panel' => 'kwl_resume_panel',
    ]);

    $wp_customize->add_setting( 'kwl_animations', [
        'default'           => true,
        'sanitize_callback' => 'kwl_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_animations', [
        'label'   => __( 'Enable Page Load Animation', 'kwl-resume' ),
        'section' => 'kwl_extras',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting( 'kwl_print_button', [
        'default'           => true,
        'sanitize_callback' => 'kwl_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_print_button', [
        'label'   => __( 'Show Print / Save as PDF Button', 'kwl-resume' ),
        'section' => 'kwl_extras',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting( 'kwl_show_open_to_work', [
        'default'           => true,
        'sanitize_callback' => 'kwl_sanitize_checkbox',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control( 'kwl_show_open_to_work', [
        'label'   => __( 'Show "Open to Opportunities" in Footer', 'kwl-resume' ),
        'section' => 'kwl_extras',
        'type'    => 'checkbox',
    ]);
}

/* ── Sanitize callbacks ── */
function kwl_sanitize_scheme( $value ) {
    $schemes = kwl_resume_color_schemes();
    return array_key_exists( $value, $schemes ) ? $value : 'growth-trust';
}
function kwl_sanitize_font_pair( $value ) {
    $pairs = kwl_resume_font_pairs();
    return array_key_exists( $value, $pairs ) ? $value : 'roboto-slab';
}
function kwl_sanitize_sidebar_position( $value ) {
    return in_array( $value, [ 'left', 'right' ], true ) ? $value : 'left';
}
function kwl_sanitize_checkbox( $value ) {
    return (bool) $value;
}
