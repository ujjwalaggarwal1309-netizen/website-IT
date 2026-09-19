<?php
/**
 * Template Part: Stats Bar
 *
 * Displays four verified company highlights on a dark background.
 * All values are verified facts from VERIFIED_COMPANY_FACTS.md.
 * No invented statistics. No client counts.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="stats-section" aria-label="<?php esc_attr_e( 'Company highlights', 'it-hardware-supply' ); ?>">

	<?php /* Decorative world map SVG background */ ?>
	<div class="stats-world-map" aria-hidden="true">
		<svg viewBox="0 0 900 450" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
			<path d="M150 200 Q180 180 200 200 Q220 220 240 200 Q260 180 280 200 L280 240 Q260 260 240 240 Q220 220 200 240 Q180 260 150 240 Z" stroke="currentColor" stroke-width="1" fill="none"/>
			<path d="M320 150 Q360 130 400 150 Q440 170 480 150 Q520 130 560 150 L560 200 Q520 220 480 200 Q440 180 400 200 Q360 220 320 200 Z" stroke="currentColor" stroke-width="1" fill="none"/>
			<path d="M580 160 Q620 140 660 160 Q700 180 740 160 L740 200 Q700 220 660 200 Q620 180 580 200 Z" stroke="currentColor" stroke-width="1" fill="none"/>
			<path d="M200 260 Q240 240 280 260 Q320 280 360 260 L360 300 Q320 320 280 300 Q240 280 200 300 Z" stroke="currentColor" stroke-width="1" fill="none"/>
			<path d="M400 240 Q450 220 500 240 Q550 260 600 240 L600 280 Q550 300 500 280 Q450 260 400 280 Z" stroke="currentColor" stroke-width="1" fill="none"/>
			<circle cx="230" cy="220" r="3" fill="currentColor"/>
			<circle cx="450" cy="175" r="3" fill="currentColor"/>
			<circle cx="660" cy="180" r="3" fill="currentColor"/>
			<circle cx="310" cy="280" r="3" fill="currentColor"/>
			<circle cx="530" cy="260" r="3" fill="currentColor"/>
			<path d="M100 120 L800 120" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 8"/>
			<path d="M100 330" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 8"/>
		</svg>
	</div>

	<div class="container">
		<div class="stats-grid">

			<div class="stat-card">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( 'Reduce Downtime', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Genuine replacement parts to keep your systems running.', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( 'Increase Availability', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Fast sourcing for legacy and modern enterprise hardware.', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( 'Extend Hardware Life', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Cost-effective solutions to maximize your infrastructure investment.', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( 'Enterprise Support', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Expert guidance before and after your purchase.', 'it-hardware-supply' ); ?></span>
			</div>


		</div>
	</div>
</section>
