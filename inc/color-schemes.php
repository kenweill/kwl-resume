<?php
/**
 * KWL Resume — Color Schemes
 *
 * @package kwl-resume
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function kwl_resume_color_schemes() {
    return [
        'growth-trust' => [
            'label'   => __( 'Growth & Trust (Default)', 'kwl-resume' ),
            'dark'    => '#0A1929',
            'dark2'   => '#0F2440',
            'accent'  => '#0D9488',
            'accent2' => '#14B8A6',
            'gold'    => '#EAB308',
            'page_bg' => '#E8EEF4',
            'preview' => [ '#0A1929', '#0D9488', '#EAB308' ],
        ],
        'midnight' => [
            'label'   => __( 'Midnight', 'kwl-resume' ),
            'dark'    => '#0D1B2A',
            'dark2'   => '#122030',
            'accent'  => '#F59E0B',
            'accent2' => '#FCD34D',
            'gold'    => '#FBBF24',
            'page_bg' => '#E8EBF0',
            'preview' => [ '#0D1B2A', '#F59E0B', '#FBBF24' ],
        ],
        'crimson' => [
            'label'   => __( 'Crimson', 'kwl-resume' ),
            'dark'    => '#1A0A0A',
            'dark2'   => '#2A0F0F',
            'accent'  => '#DC2626',
            'accent2' => '#EF4444',
            'gold'    => '#FBBF24',
            'page_bg' => '#F0EAEA',
            'preview' => [ '#1A0A0A', '#DC2626', '#FBBF24' ],
        ],
        'forest' => [
            'label'   => __( 'Forest', 'kwl-resume' ),
            'dark'    => '#0A1A0D',
            'dark2'   => '#0F2614',
            'accent'  => '#16A34A',
            'accent2' => '#22C55E',
            'gold'    => '#BEF264',
            'page_bg' => '#EAF0E8',
            'preview' => [ '#0A1A0D', '#16A34A', '#BEF264' ],
        ],
        'slate' => [
            'label'   => __( 'Slate', 'kwl-resume' ),
            'dark'    => '#1E293B',
            'dark2'   => '#263445',
            'accent'  => '#8B5CF6',
            'accent2' => '#A78BFA',
            'gold'    => '#C4B5FD',
            'page_bg' => '#EBE8F4',
            'preview' => [ '#1E293B', '#8B5CF6', '#C4B5FD' ],
        ],
        'amber' => [
            'label'   => __( 'Amber', 'kwl-resume' ),
            'dark'    => '#1C1207',
            'dark2'   => '#27180A',
            'accent'  => '#D97706',
            'accent2' => '#F59E0B',
            'gold'    => '#FCD34D',
            'page_bg' => '#F0EAE0',
            'preview' => [ '#1C1207', '#D97706', '#FCD34D' ],
        ],
        'rose' => [
            'label'   => __( 'Rose', 'kwl-resume' ),
            'dark'    => '#1A0812',
            'dark2'   => '#260D1C',
            'accent'  => '#E11D48',
            'accent2' => '#FB7185',
            'gold'    => '#FDA4AF',
            'page_bg' => '#F0E8EC',
            'preview' => [ '#1A0812', '#E11D48', '#FDA4AF' ],
        ],
        'indigo' => [
            'label'   => __( 'Indigo', 'kwl-resume' ),
            'dark'    => '#0F0A2E',
            'dark2'   => '#160F3E',
            'accent'  => '#06B6D4',
            'accent2' => '#22D3EE',
            'gold'    => '#A5F3FC',
            'page_bg' => '#E8EAF4',
            'preview' => [ '#0F0A2E', '#06B6D4', '#A5F3FC' ],
        ],
    ];
}

function kwl_resume_font_pairs() {
    return [
        'roboto-slab' => [
            'label'   => __( 'Roboto Slab + Roboto (Default)', 'kwl-resume' ),
            'display' => "'Roboto Slab', serif",
            'body'    => "'Roboto', sans-serif",
            'google'  => 'family=Roboto+Slab:wght@400;500;600;700&family=Roboto:wght@300;400;500;700',
        ],
        'playfair' => [
            'label'   => __( 'Playfair Display + Source Sans 3', 'kwl-resume' ),
            'display' => "'Playfair Display', serif",
            'body'    => "'Source Sans 3', sans-serif",
            'google'  => 'family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@300;400;600',
        ],
        'merriweather' => [
            'label'   => __( 'Merriweather + Open Sans', 'kwl-resume' ),
            'display' => "'Merriweather', serif",
            'body'    => "'Open Sans', sans-serif",
            'google'  => 'family=Merriweather:wght@400;700&family=Open+Sans:wght@300;400;600',
        ],
        'dm-serif' => [
            'label'   => __( 'DM Serif Display + DM Sans', 'kwl-resume' ),
            'display' => "'DM Serif Display', serif",
            'body'    => "'DM Sans', sans-serif",
            'google'  => 'family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;700',
        ],
        'libre-baskerville' => [
            'label'   => __( 'Libre Baskerville + Lato', 'kwl-resume' ),
            'display' => "'Libre Baskerville', serif",
            'body'    => "'Lato', sans-serif",
            'google'  => 'family=Libre+Baskerville:wght@400;700&family=Lato:wght@300;400;700',
        ],
        'josefin' => [
            'label'   => __( 'Josefin Slab + Josefin Sans', 'kwl-resume' ),
            'display' => "'Josefin Slab', serif",
            'body'    => "'Josefin Sans', sans-serif",
            'google'  => 'family=Josefin+Slab:wght@400;600;700&family=Josefin+Sans:wght@300;400;600',
        ],
    ];
}
