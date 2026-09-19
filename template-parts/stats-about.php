<?php
/**
 * Template Part: Stats Bar (About)
 *
 * Displays the original four verified company highlights on a dark background.
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
		<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">

			<div class="stat-card stat-card--large">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( '2018', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Established', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card stat-card--large">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( 'PAN India', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Supply & Support', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card stat-card--large">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( '90 Days to 1 Year', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Warranty Coverage', 'it-hardware-supply' ); ?></span>
			</div>

			<div class="stat-divider" aria-hidden="true"></div>

			<div class="stat-card stat-card--large">
				<div class="stat-card__icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
				</div>
				<strong class="stat-card__value"><?php esc_html_e( '5 Core', 'it-hardware-supply' ); ?></strong>
				<span class="stat-card__label"><?php esc_html_e( 'Categories', 'it-hardware-supply' ); ?></span>
			</div>

		</div>
	</div>
</section>
