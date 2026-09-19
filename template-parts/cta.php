<?php
/**
 * Template Part: CTA Banner
 *
 * Generic CTA section used on the homepage and any page that needs
 * the standard "Request a Quote" call-to-action.
 *
 * Text is editable via Theme Settings → Homepage in the Customizer.
 * Keys: cta_heading, cta_text, cta_button
 *
 * For the About page, use template-parts/about-cta.php instead —
 * it has distinct copy per WEBSITE_CONTENT.md — "About CTA".
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cta-section" aria-labelledby="cta-section-heading">
	<div class="container cta-card">
		<?php /* Decorative SVG: wave graphics and network line-art */ ?>
		<div class="cta-card__deco" aria-hidden="true">
			<svg viewBox="0 0 1200 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
				<path d="M0 200 C300 100 900 300 1200 200" stroke="currentColor" stroke-width="2" fill="none"/>
				<path d="M0 250 C400 150 800 350 1200 250" stroke="currentColor" stroke-width="1.5" fill="none" stroke-dasharray="8 8"/>
				<circle cx="300" cy="166" r="6" fill="currentColor"/>
				<circle cx="900" cy="266" r="6" fill="currentColor"/>
				<circle cx="600" cy="200" r="4" fill="currentColor"/>
				<line x1="300" y1="166" x2="600" y2="200" stroke="currentColor" stroke-width="1.5"/>
				<line x1="900" y1="266" x2="600" y2="200" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>
		<div class="cta-card__inner">
			<h2 id="cta-section-heading"><?php echo esc_html( it_hardware_get_setting( 'cta_heading' ) ); ?></h2>
			<p><?php echo esc_html( it_hardware_get_setting( 'cta_text' ) ); ?></p>
			<a class="btn btn-accent" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" id="homepage-cta-button"><?php echo esc_html( it_hardware_get_setting( 'cta_button' ) ); ?></a>
		</div>
	</div>
</section>
