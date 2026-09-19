<?php
/**
 * Template Part: Why Choose Us
 *
 * Displays 6 feature cards on the homepage explaining the value
 * proposition of Infinity IT Solutions.
 *
 * Content source: WEBSITE_CONTENT.md — "Why Choose Us" (6 items)
 * Data source:    VERIFIED_COMPANY_FACTS.md
 * Design source:  WEBSITE_DESIGN_SPEC.md — 3-column feature card grid
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Feature cards data.
 * All copy is verbatim from WEBSITE_CONTENT.md "Why Choose Us" section.
 * Do NOT add invented facts or statistics.
 */
$features = array(
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
		'title' => __( 'Established Since 2018', 'it-hardware-supply' ),
		'desc'  => __( 'Supplying dependable enterprise IT hardware solutions for businesses across India.', 'it-hardware-supply' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>',
		'title' => __( 'Enterprise Infrastructure Solutions', 'it-hardware-supply' ),
		'desc'  => __( 'Cost-effective enterprise hardware without compromising reliability.', 'it-hardware-supply' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
		'title' => __( 'PAN India Supply', 'it-hardware-supply' ),
		'desc'  => __( 'Efficient nationwide supply backed by responsive customer support.', 'it-hardware-supply' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
		'title' => __( 'Installation Support', 'it-hardware-supply' ),
		'desc'  => __( 'Professional installation and deployment support across India.', 'it-hardware-supply' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
		'title' => __( 'Warranty Support', 'it-hardware-supply' ),
		'desc'  => __( '90 days to 1 year warranty coverage for complete peace of mind.', 'it-hardware-supply' ),
	),
	array(
		'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		'title' => __( 'Customer-First Approach', 'it-hardware-supply' ),
		'desc'  => __( 'Personalised solutions with a strong focus on customer satisfaction.', 'it-hardware-supply' ),
	),
);
?>
<section class="why-choose-section fade-in" aria-labelledby="why-choose-heading">
	<div class="container">
		<div class="section-heading">
			<span class="section-label"><?php esc_html_e( 'Why Choose Us', 'it-hardware-supply' ); ?></span>
			<h2 id="why-choose-heading"><?php esc_html_e( 'Six Reasons Businesses Trust Us', 'it-hardware-supply' ); ?></h2>
			<p><?php esc_html_e( 'Six reasons businesses across India trust Infinity IT Solutions for their enterprise hardware requirements.', 'it-hardware-supply' ); ?></p>
		</div>

		<ul class="premium-grid" role="list">
			<?php foreach ( $features as $feature ) : ?>
			<li class="card-premium why-choose-card">
				<div class="card-premium__icon" aria-hidden="true">
					<?php echo $feature['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — static SVG defined in this file. ?>
				</div>
				<h3 class="card-premium__title"><?php echo esc_html( $feature['title'] ); ?></h3>
				<div class="card-premium__divider" aria-hidden="true"></div>
				<p class="card-premium__description"><?php echo esc_html( $feature['desc'] ); ?></p>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
