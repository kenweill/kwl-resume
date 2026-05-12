<?php
/**
 * KWL Resume — Template Helper Functions
 *
 * @package kwl-resume
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ─────────────────────────────────────────
   DATA GETTERS  (return saved options or
   fall back to Ken's original resume data)
───────────────────────────────────────── */

function kwl_resume_get_profile() {
    $defaults = [
        'name'         => 'Ken Weill Lumacad',
        'title'        => 'Affiliate Marketing Manager · WordPress &amp; SEO Specialist',
        'summary'      => 'Affiliate Marketing Manager with 10+ years of experience building and scaling profitable affiliate programs for e-commerce and digital brands. Uniquely positioned at the intersection of affiliate marketing strategy and hands-on WordPress/PHP development — able to both build and market digital products independently. Proven track record across major networks including AWIN, CJ, and Impact, with deep expertise in affiliate recruitment, campaign optimization, and lead generation using tools like Apollo.io.',
        'photo_url'    => '',
        'initials'     => 'KW',
        'open_to_work' => '1',
        'last_updated' => 'April 23, 2026',
    ];
    $saved = get_option( 'kwl_resume_profile', [] );
    return wp_parse_args( $saved, $defaults );
}

function kwl_resume_get_contact() {
    $defaults = [
        'location'     => 'Siquijor, Philippines',
        'remote_label' => 'Remote',
        'email'        => '',
        'linkedin'     => 'https://linkedin.com/in/kenweill',
        'linkedin_label' => 'linkedin.com/in/kenweill',
        'github'       => 'https://github.com/kenweill',
        'github_label' => 'github.com/kenweill',
        'website'      => '',
        'website_label'=> '',
        'custom_links' => [],
    ];
    $saved = get_option( 'kwl_resume_contact', [] );
    return wp_parse_args( $saved, $defaults );
}

function kwl_resume_get_experience() {
    $defaults = [
        [
            'role'    => 'Affiliate Network Application Assistant',
            'company' => 'Goodshop · via Upwork',
            'date'    => 'Nov 2025 – Present',
            'bullets' => [
                'Submit and track affiliate program applications across 9+ networks: CJ, AWIN, Flex Offers, GoAffPro, Skimlinks, AvantLink, Rakuten, Webgains, and Impact.',
                'Research and identify which networks specific merchants belong to, closing information gaps with both provided and self-sourced data.',
                'Set up and manage new affiliate network accounts with accurate credentials and complete configuration.',
                'Maintain a structured tracking system for application progress and deliver regular client progress reports.',
            ],
        ],
        [
            'role'    => 'Affiliate Manager – TikTok Outreach',
            'company' => 'AprilFTD · via Upwork',
            'date'    => 'Oct 2025 – Apr 2026',
            'bullets' => [
                'Led TikTok influencer outreach and affiliate recruitment for a client targeting the German market.',
                'Identified and vetted creators averaging 20,000+ views per video, ensuring alignment with client niche and audience demographics.',
                'Conducted outreach via TikTok DMs and email, presenting affiliate partnership opportunities with defined commission structures.',
                'Built and maintained a detailed outreach tracker covering contact status, responses, follow-ups, and conversion outcomes.',
            ],
        ],
        [
            'role'    => 'Affiliate Marketing Manager',
            'company' => 'Digital Logic (Oxford Language Club) · via Upwork',
            'date'    => 'Aug 2025 – Sep 2025',
            'bullets' => [
                'Designed and executed an affiliate recruitment strategy that grew the active partner network across ShareASale and in-house platforms.',
                'Leveraged Apollo.io for data-driven prospecting and CRM enrichment, improving outreach targeting and response rates.',
                'Streamlined onboarding and partner communication workflows to support scalable, repeatable campaign growth.',
            ],
        ],
        [
            'role'    => '3D LiDAR Annotator & Reviewer',
            'company' => 'Remotasks',
            'date'    => 'Aug 2019 – Dec 2024',
            'bullets' => [
                'Annotated complex 3D point cloud datasets for autonomous vehicle and smart device AI model training.',
                'Progressed to a reviewer role, auditing and correcting annotators\' outputs to uphold project-wide quality benchmarks.',
                'Consistently met productivity targets across five years of sustained long-term delivery.',
            ],
        ],
        [
            'role'    => 'Website Admin & Affiliate Strategist',
            'company' => 'CouponHubs / KLM Merchant Hubs',
            'date'    => 'Mar 2012 – Jan 2023',
            'bullets' => [
                'Built and managed a WordPress-powered coupon and deals website for over a decade, growing it into a functional affiliate marketing operation.',
                'Sourced, tested, and validated coupon codes from affiliate partners; wrote and published store reviews driving organic traffic.',
                'Collaborated with affiliate network partners to coordinate campaigns and maximize commission revenue.',
            ],
        ],
        [
            'role'    => 'Virtual Assistant – Affiliate Operations',
            'company' => 'CouponDunia.in',
            'date'    => 'Jun 2011 – Mar 2012',
            'bullets' => [
                'Collected and validated coupon codes and vouchers from partner merchant websites.',
                'Monitored campaign performance data to identify top-performing offers and inform content prioritization.',
            ],
        ],
        [
            'role'    => 'Owner & IT Technician',
            'company' => 'CyberLazi Internet Cafe',
            'date'    => 'Sep 2007 – Dec 2017',
            'bullets' => [
                'Owned and operated a community internet cafe, managing all technical operations and customer-facing services for a decade.',
                'Maintained hardware and software across multiple workstations, handling repairs, upgrades, and system configuration independently.',
            ],
        ],
        [
            'role'    => 'GIS Technician',
            'company' => 'Siquijor Integrated Resource Management Project (GTZ-SIRMAP)',
            'date'    => 'Jun 2004 – Dec 2004',
            'bullets' => [
                'Digitized land records for a government-backed land digitization initiative converting physical municipal and provincial maps to digital GIS datasets.',
                'Operated military-grade GPS equipment for high-precision field data collection.',
            ],
        ],
    ];
    $saved = get_option( 'kwl_resume_experience', null );
    return ( $saved !== null ) ? $saved : $defaults;
}

/**
 * Parse a date string like "Nov 2025", "Apr 2026", "2004" into a comparable
 * integer. Returns PHP_INT_MAX for "Present" so active jobs sort first.
 */
function kwl_resume_parse_date( $str ) {
    $str = trim( $str );
    if ( empty( $str ) ) return 0;

    $months = [
        'jan'=>1,'feb'=>2,'mar'=>3,'apr'=>4,'may'=>5,'jun'=>6,
        'jul'=>7,'aug'=>8,'sep'=>9,'oct'=>10,'nov'=>11,'dec'=>12,
    ];

    if ( preg_match( '/present|now/i', $str ) ) return PHP_INT_MAX;

    if ( preg_match( '/([a-z]{3})\s+(\d{4})/i', $str, $m ) ) {
        $mon = $months[ strtolower( $m[1] ) ] ?? 1;
        return (int) $m[2] * 100 + $mon;
    }

    if ( preg_match( '/^\d{4}$/', $str ) ) return (int) $str * 100;

    return 0;
}

/**
 * Sort experience entries: active jobs first (newest start date first among
 * those), then ended jobs newest end date first, then newest start date first.
 */
function kwl_resume_sort_experience( array $items ) {
    usort( $items, function( $a, $b ) {
        $parts_a = preg_split( '/\s*[–—-]\s*/', $a['date'] ?? '', 2 );
        $parts_b = preg_split( '/\s*[–—-]\s*/', $b['date'] ?? '', 2 );

        $start_a = kwl_resume_parse_date( $parts_a[0] ?? '' );
        $end_a   = kwl_resume_parse_date( $parts_a[1] ?? $parts_a[0] ?? '' );
        $start_b = kwl_resume_parse_date( $parts_b[0] ?? '' );
        $end_b   = kwl_resume_parse_date( $parts_b[1] ?? $parts_b[0] ?? '' );

        if ( $end_b !== $end_a ) return $end_b <=> $end_a;
        return $start_b <=> $start_a;
    } );

    return $items;
}

function kwl_resume_get_skills() {
    $defaults = [
        'Affiliate Marketing', 'Program Management', 'Lead Generation',
        'SEO & Content', 'WordPress / PHP', 'Apollo.io',
        'Campaign Optimization', 'AWIN / CJ / Impact', 'Partner Recruitment',
        'Email Outreach', 'Data Analysis', 'E-Commerce',
        'MikroTik / RouterOS', 'GIS & Geospatial', 'Remote Work',
    ];
    $saved = get_option( 'kwl_resume_skills', null );
    return ( $saved !== null ) ? $saved : $defaults;
}

function kwl_resume_get_education() {
    $defaults = [
        [
            'institution' => 'Asian College of Science &amp; Technology',
            'degree'      => 'BS Computer Science',
            'date'        => '1998 – 2002',
        ],
        [
            'institution' => 'Asian College of Science &amp; Technology',
            'degree'      => 'Diploma in Electronics &amp; Computer Technology',
            'date'        => '1996 – 1998',
        ],
    ];
    $saved = get_option( 'kwl_resume_education', null );
    return ( $saved !== null ) ? $saved : $defaults;
}

function kwl_resume_get_certifications() {
    $defaults = [
        [ 'name' => 'Google Ads Search Certification',     'issuer' => 'Skillshop',          'date' => 'Apr 2025 – Apr 2026' ],
        [ 'name' => 'AI-Powered Shopping Ads',             'issuer' => 'Skillshop',          'date' => 'Mar 2025 – Mar 2026' ],
        [ 'name' => 'Facebook Advertising Mastery',        'issuer' => 'NextLevel Digital',  'date' => 'Dec 2024' ],
        [ 'name' => 'General Virtual Assistance',          'issuer' => 'DICT Philippines',   'date' => 'Nov 2024' ],
        [ 'name' => 'Amazon Product Sourcing Masterclass', 'issuer' => 'FilAm VA',           'date' => 'Feb 2023' ],
        [ 'name' => 'Remote Work Learner',                 'issuer' => 'CertiProf',          'date' => 'Jan 2025' ],
        [ 'name' => 'Career Service Professional Eligibility', 'issuer' => 'Civil Service Commission', 'date' => 'Oct 2003' ],
    ];
    $saved = get_option( 'kwl_resume_certifications', null );
    return ( $saved !== null ) ? $saved : $defaults;
}

function kwl_resume_get_projects() {
    $defaults = [
        [
            'name'        => 'MikhMon CE',
            'type'        => 'Open Source',
            'description' => 'Community fork of MikhMon, a MikroTik hotspot manager upgraded for full PHP 8.x and RouterOS 6 & 7 compatibility, with automatic date/duration format detection and no manual configuration required.',
            'url'         => 'https://github.com/kenweill/mikhmon-ce',
            'url_label'   => 'github.com/kenweill/mikhmon-ce',
        ],
        [
            'name'        => 'KWL Maintenance Mode',
            'type'        => 'Open Source',
            'description' => 'Free, fully customizable WordPress maintenance/under-construction page plugin with one-click toggle, full color control, animated progress bar, role-based bypass, and proper 503 HTTP status support.',
            'url'         => 'https://github.com/kenweill/kwl-maintenance-mode',
            'url_label'   => 'github.com/kenweill/kwl-maintenance-mode',
        ],
        [
            'name'        => 'KWL Coupon WP',
            'type'        => 'Open Source',
            'description' => 'Free, open-source WordPress theme built specifically for coupon and deals websites, drawing on 10+ years of hands-on affiliate coupon site experience — no paid plugins required.',
            'url'         => 'https://github.com/kenweill/kwl-coupon-wp',
            'url_label'   => 'github.com/kenweill/kwl-coupon-wp',
        ],
    ];
    $saved = get_option( 'kwl_resume_projects', null );
    return ( $saved !== null ) ? $saved : $defaults;
}

function kwl_resume_get_custom_sections() {
    return get_option( 'kwl_resume_custom_sections', [] );
}

function kwl_resume_get_sections_config() {
    $defaults = [
        'summary'       => [ 'enabled' => '1', 'label' => __( 'Professional Summary', 'kwl-resume' ) ],
        'experience'    => [ 'enabled' => '1', 'label' => __( 'Professional Experience', 'kwl-resume' ) ],
        'skills'        => [ 'enabled' => '1', 'label' => __( 'Core Skills', 'kwl-resume' ) ],
        'education'     => [ 'enabled' => '1', 'label' => __( 'Education', 'kwl-resume' ) ],
        'certifications'=> [ 'enabled' => '1', 'label' => __( 'Certifications', 'kwl-resume' ) ],
        'projects'      => [ 'enabled' => '1', 'label' => __( 'Projects', 'kwl-resume' ) ],
        'contact'       => [ 'enabled' => '1', 'label' => __( 'Contact', 'kwl-resume' ) ],
        'footer'        => [ 'enabled' => '1', 'label' => __( 'Footer', 'kwl-resume' ) ],
    ];
    $saved = get_option( 'kwl_resume_sections', [] );
    return wp_parse_args( $saved, $defaults );
}

/* ─────────────────────────────────────────
   RENDER HELPERS
───────────────────────────────────────── */

function kwl_resume_section_enabled( $key ) {
    $cfg = kwl_resume_get_sections_config();
    return ! empty( $cfg[ $key ]['enabled'] );
}

function kwl_resume_esc_output( $str ) {
    return wp_kses_post( $str );
}
