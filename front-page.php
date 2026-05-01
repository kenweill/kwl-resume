<?php
/**
 * KWL Resume — Front Page Template
 *
 * @package kwl-resume
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$profile   = kwl_resume_get_profile();
$contact   = kwl_resume_get_contact();
$sections  = kwl_resume_get_sections_config();
$show_print = get_theme_mod( 'kwl_print_button', true );

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo esc_attr( wp_strip_all_tags( $profile['summary'] ) ); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php if ( $show_print ) : ?>
<button class="kwl-print-btn" onclick="window.print()" title="<?php esc_attr_e( 'Print / Save as PDF', 'kwl-resume' ); ?>">🖨</button>
<?php endif; ?>

<div class="kwl-page">

    <!-- ══ SIDEBAR ══ -->
    <aside class="kwl-sidebar">

        <?php /* ── Avatar + Name ── */ ?>
        <div class="kwl-sidebar-header">
            <div class="kwl-avatar">
                <?php if ( ! empty( $profile['photo_url'] ) ) : ?>
                <img src="<?php echo esc_url( $profile['photo_url'] ); ?>"
                     alt="<?php echo esc_attr( $profile['name'] ); ?>"
                     onerror="this.style.display='none';this.parentElement.innerHTML='<?php echo esc_js( $profile['initials'] ); ?>';">
                <?php else : ?>
                <?php echo esc_html( $profile['initials'] ); ?>
                <?php endif; ?>
            </div>
            <div class="kwl-sidebar-name"><?php echo esc_html( $profile['name'] ); ?></div>
            <div class="kwl-sidebar-title"><?php echo wp_kses_post( $profile['title'] ); ?></div>
        </div>

        <div class="kwl-s-divider"></div>

        <?php /* ── Contact ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'contact' ) ) : ?>
        <div class="kwl-s-block">
            <div class="kwl-s-label"><?php echo esc_html( $sections['contact']['label'] ); ?></div>
            <ul class="kwl-contact-list">
                <?php if ( ! empty( $contact['location'] ) ) : ?>
                <li>
                    <span class="kwl-ico">📍</span>
                    <span><?php echo esc_html( $contact['location'] ); ?>
                    <?php if ( ! empty( $contact['remote_label'] ) ) : ?>
                    <br><em><?php echo esc_html( $contact['remote_label'] ); ?></em>
                    <?php endif; ?>
                    </span>
                </li>
                <?php endif; ?>
                <?php if ( ! empty( $contact['email'] ) ) : ?>
                <li>
                    <span class="kwl-ico">✉</span>
                    <a href="mailto:<?php echo esc_attr( $contact['email'] ); ?>"><?php echo esc_html( $contact['email'] ); ?></a>
                </li>
                <?php endif; ?>
                <?php if ( ! empty( $contact['linkedin'] ) ) : ?>
                <li>
                    <span class="kwl-ico">in</span>
                    <a href="<?php echo esc_url( $contact['linkedin'] ); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html( $contact['linkedin_label'] ?: $contact['linkedin'] ); ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ( ! empty( $contact['github'] ) ) : ?>
                <li>
                    <span class="kwl-ico">{ }</span>
                    <a href="<?php echo esc_url( $contact['github'] ); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html( $contact['github_label'] ?: $contact['github'] ); ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php if ( ! empty( $contact['website'] ) ) : ?>
                <li>
                    <span class="kwl-ico">🌐</span>
                    <a href="<?php echo esc_url( $contact['website'] ); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html( $contact['website_label'] ?: $contact['website'] ); ?>
                    </a>
                </li>
                <?php endif; ?>
                <?php foreach ( $contact['custom_links'] as $link ) : ?>
                <li>
                    <span class="kwl-ico"><?php echo esc_html( $link['icon'] ?: '🔗' ); ?></span>
                    <?php if ( ! empty( $link['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener">
                        <?php echo esc_html( $link['label'] ?: $link['url'] ); ?>
                    </a>
                    <?php else : ?>
                    <span><?php echo esc_html( $link['label'] ); ?></span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="kwl-s-divider"></div>
        <?php endif; ?>

        <?php /* ── Skills ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'skills' ) ) :
              $skills = kwl_resume_get_skills(); ?>
        <div class="kwl-s-block">
            <div class="kwl-s-label"><?php echo esc_html( $sections['skills']['label'] ); ?></div>
            <div class="kwl-skills-cloud">
                <?php foreach ( $skills as $skill ) : ?>
                <span class="kwl-skill-tag"><?php echo esc_html( $skill ); ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="kwl-s-divider"></div>
        <?php endif; ?>

        <?php /* ── Education ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'education' ) ) :
              $education = kwl_resume_get_education(); ?>
        <div class="kwl-s-block">
            <div class="kwl-s-label"><?php echo esc_html( $sections['education']['label'] ); ?></div>
            <?php foreach ( $education as $edu ) : ?>
            <div class="kwl-edu-block">
                <strong><?php echo kwl_resume_esc_output( $edu['institution'] ); ?></strong>
                <div class="kwl-edu-degree"><?php echo kwl_resume_esc_output( $edu['degree'] ); ?></div>
                <div><?php echo esc_html( $edu['date'] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="kwl-s-divider"></div>
        <?php endif; ?>

        <?php /* ── Certifications ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'certifications' ) ) :
              $certs = kwl_resume_get_certifications(); ?>
        <div class="kwl-s-block">
            <div class="kwl-s-label"><?php echo esc_html( $sections['certifications']['label'] ); ?></div>
            <ul class="kwl-cert-list">
                <?php foreach ( $certs as $cert ) : ?>
                <li>
                    <strong><?php echo esc_html( $cert['name'] ); ?></strong>
                    <?php echo esc_html( $cert['issuer'] ); ?>
                    <?php if ( ! empty( $cert['date'] ) ) : ?>
                    <em><?php echo esc_html( '· ' . $cert['date'] ); ?></em>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </aside>

    <!-- ══ MAIN CONTENT ══ -->
    <main class="kwl-main">

        <header class="kwl-main-header">
            <div class="kwl-main-name"><?php echo esc_html( strtoupper( $profile['name'] ) ); ?></div>
            <div class="kwl-main-title"><?php echo wp_kses_post( $profile['title'] ); ?></div>
        </header>

        <?php /* ── Summary ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'summary' ) && ! empty( $profile['summary'] ) ) : ?>
        <section class="kwl-section">
            <div class="kwl-section-label"><?php echo esc_html( $sections['summary']['label'] ); ?></div>
            <p class="kwl-summary-text"><?php echo wp_kses_post( $profile['summary'] ); ?></p>
        </section>
        <?php endif; ?>

        <?php /* ── Experience ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'experience' ) ) :
              $experience = kwl_resume_get_experience(); ?>
        <section class="kwl-section">
            <div class="kwl-section-label"><?php echo esc_html( $sections['experience']['label'] ); ?></div>
            <?php foreach ( $experience as $job ) : ?>
            <div class="kwl-exp-item">
                <div class="kwl-exp-header">
                    <div class="kwl-exp-role"><?php echo esc_html( $job['role'] ); ?></div>
                    <div class="kwl-exp-date"><?php echo esc_html( $job['date'] ); ?></div>
                </div>
                <div class="kwl-exp-company"><?php echo esc_html( $job['company'] ); ?></div>
                <?php if ( ! empty( $job['bullets'] ) ) : ?>
                <ul class="kwl-exp-bullets">
                    <?php foreach ( $job['bullets'] as $bullet ) : ?>
                    <li><?php echo esc_html( $bullet ); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>

        <?php /* ── Projects ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'projects' ) ) :
              $projects = kwl_resume_get_projects(); ?>
        <section class="kwl-section">
            <div class="kwl-section-label"><?php echo esc_html( $sections['projects']['label'] ); ?></div>
            <div class="kwl-proj-list">
                <?php foreach ( $projects as $proj ) : ?>
                <div class="kwl-proj-item">
                    <div class="kwl-proj-header">
                        <strong><?php echo esc_html( $proj['name'] ); ?></strong>
                        <?php if ( ! empty( $proj['type'] ) ) : ?>
                        <span class="kwl-proj-type"><?php echo esc_html( $proj['type'] ); ?></span>
                        <?php endif; ?>
                    </div>
                    <p><?php echo wp_kses_post( $proj['description'] ); ?></p>
                    <?php if ( ! empty( $proj['url'] ) ) : ?>
                    <div class="kwl-proj-link">
                        <a href="<?php echo esc_url( $proj['url'] ); ?>" target="_blank" rel="noopener">
                            <?php echo esc_html( $proj['url_label'] ?: $proj['url'] ); ?> →
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php /* ── Custom Sections ── */ ?>
        <?php $custom_sections = kwl_resume_get_custom_sections();
        foreach ( $custom_sections as $cs ) :
            if ( empty( $cs['enabled'] ) || $cs['enabled'] === '0' ) continue;
            if ( empty( $cs['entries'] ) ) continue; ?>
        <section class="kwl-section">
            <div class="kwl-section-label"><?php echo esc_html( $cs['title'] ); ?></div>
            <?php foreach ( $cs['entries'] as $entry ) : ?>
            <div class="kwl-cs-entry">
                <?php if ( ! empty( $entry['heading'] ) ) : ?>
                <div class="kwl-cs-header">
                    <div class="kwl-cs-heading"><?php echo esc_html( $entry['heading'] ); ?></div>
                    <?php if ( ! empty( $entry['date'] ) ) : ?>
                    <div class="kwl-cs-date"><?php echo esc_html( $entry['date'] ); ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if ( ! empty( $entry['subheading'] ) ) : ?>
                <div class="kwl-cs-subheading"><?php echo esc_html( $entry['subheading'] ); ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $entry['description'] ) ) : ?>
                <p class="kwl-cs-desc"><?php echo wp_kses_post( $entry['description'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $entry['bullets'] ) ) : ?>
                <ul class="kwl-exp-bullets">
                    <?php foreach ( $entry['bullets'] as $bullet ) : ?>
                    <li><?php echo esc_html( $bullet ); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </section>
        <?php endforeach; ?>

        <?php /* ── Footer ── */ ?>
        <?php if ( kwl_resume_section_enabled( 'footer' ) ) :
              $show_otw = get_theme_mod( 'kwl_show_open_to_work', true ); ?>
        <footer class="kwl-resume-footer">
            <div class="kwl-footer-name"><?php echo esc_html( strtoupper( $profile['name'] ) ); ?></div>
            <div class="kwl-footer-title"><?php echo wp_kses_post( $profile['title'] ); ?></div>
            <?php if ( ! empty( $contact['linkedin'] ) ) : ?>
            <a href="<?php echo esc_url( $contact['linkedin'] ); ?>" target="_blank" rel="noopener" class="kwl-footer-linkedin">
                🔗 <?php esc_html_e( 'Connect on LinkedIn', 'kwl-resume' ); ?>
            </a>
            <?php endif; ?>
            <?php if ( $show_otw && ! empty( $profile['open_to_work'] ) && $profile['open_to_work'] === '1' ) : ?>
            <div class="kwl-footer-open">🌍 <?php esc_html_e( 'Open to new opportunities and collaborations', 'kwl-resume' ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $profile['last_updated'] ) ) : ?>
            <div class="kwl-footer-date">
                <?php printf( esc_html__( 'Last updated: %s', 'kwl-resume' ), esc_html( $profile['last_updated'] ) ); ?>
            </div>
            <?php endif; ?>
        </footer>
        <?php endif; ?>

    </main>
</div>

<?php wp_footer(); ?>
</body>
</html>
