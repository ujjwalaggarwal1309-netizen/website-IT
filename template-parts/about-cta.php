<?php
/**
 * Template Part: About CTA
 *
 * Displays the About page-specific CTA section.
 * Heading and button are distinct from the homepage CTA
 * per WEBSITE_CONTENT.md — "About CTA":
 *   Heading: "Let's Build Your IT Infrastructure Together"
 *   Body:    "Speak with our specialists to find the right enterprise
 *             hardware solution for your organization."
 *   Button:  "Contact Our Experts" → /contact/
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="cta-section about-cta-section" aria-labelledby="about-cta-heading">
	<div class="container cta-card">
		<?php /* Decorative SVG: circuit graphics, wave patterns, orange glow */ ?>
		<div class="cta-card__deco" aria-hidden="true">
			<svg viewBox="0 0 1200 400" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
				<path d="M0 250 C300 150 900 350 1200 250" stroke="rgba(255,255,255,0.05)" stroke-width="2" fill="none"/>
				<path d="M0 300 C400 200 800 400 1200 300" stroke="rgba(255,255,255,0.03)" stroke-width="1.5" fill="none" stroke-dasharray="6 6"/>
				<path d="M200 400 L200 300 L300 200 L400 200" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" fill="none"/>
				<circle cx="400" cy="200" r="4" fill="rgba(255,255,255,0.2)"/>
				<path d="M900 0 L900 100 L1000 200 L1100 200" stroke="rgba(255,255,255,0.1)" stroke-width="1.5" fill="none"/>
				<circle cx="1100" cy="200" r="4" fill="rgba(255,255,255,0.2)"/>
			</svg>
		</div>
		<div class="cta-card__inner">
			<h2 id="about-cta-heading"><?php echo esc_html( it_hardware_get_setting( 'about_cta_heading' ) ); ?></h2>
			<p><?php echo esc_html( it_hardware_get_setting( 'about_cta_body' ) ); ?></p>
			<a
				class="btn btn-accent"
				href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
				id="about-cta-button"
			><?php echo esc_html( it_hardware_get_setting( 'about_cta_button' ) ); ?></a>
		</div>
	</div>
</section>
