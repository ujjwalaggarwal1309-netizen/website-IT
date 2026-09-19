<?php
/**
 * Template Part: Mission Card
 *
 * Renders three cards: Mission, Vision, Core Values.
 * Used inside the .mission-grid container on the About page.
 *
 * Content source: WEBSITE_CONTENT.md — "Mission", "Vision", "Core Values"
 * Data source:    VERIFIED_COMPANY_FACTS.md
 *
 * All three values are editable through the WordPress Customizer
 * under Theme Settings → About Page.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core Values list.
 * Source: WEBSITE_CONTENT.md — "Core Values"
 * Source: VERIFIED_COMPANY_FACTS.md
 * Do NOT add or remove values without client confirmation.
 */
$core_values = array(
	__( 'Reliability', 'it-hardware-supply' ),
	__( 'Integrity', 'it-hardware-supply' ),
	__( 'Customer Commitment', 'it-hardware-supply' ),
	__( 'Professional Service', 'it-hardware-supply' ),
	__( 'Quality First', 'it-hardware-supply' ),
);
?>

<?php /* Mission card */ ?>
<div class="card-premium category-card">
	<div class="category-card__content">
		<div class="card-premium__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32">
				<circle cx="12" cy="12" r="10"/>
				<polyline points="12 6 12 12 16 14"/>
			</svg>
		</div>
		<h3 class="card-premium__title"><?php esc_html_e( 'Our Mission', 'it-hardware-supply' ); ?></h3>
		<div class="card-premium__divider" aria-hidden="true"></div>
		<p class="card-premium__description"><?php esc_html_e( 'To empower Indian enterprises with high-performance, cost-effective IT hardware solutions, backed by uncompromising quality, rapid deployment, and dedicated long-term technical support.', 'it-hardware-supply' ); ?></p>
	</div>
</div>

<?php /* Vision card */ ?>
<div class="card-premium category-card">
	<div class="category-card__content">
		<div class="card-premium__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32">
				<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
				<circle cx="12" cy="12" r="3"/>
			</svg>
		</div>
		<h3 class="card-premium__title"><?php esc_html_e( 'Our Vision', 'it-hardware-supply' ); ?></h3>
		<div class="card-premium__divider" aria-hidden="true"></div>
		<p class="card-premium__description"><?php esc_html_e( 'To be India\'s premier independent provider of enterprise IT infrastructure, driving business growth through reliable hardware ecosystems and unmatched customer commitment.', 'it-hardware-supply' ); ?></p>
	</div>
</div>

<?php /* Values card */ ?>
<div class="card-premium category-card">
	<div class="category-card__content">
		<div class="card-premium__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
				<polyline points="22 4 12 14.01 9 11.01"/>
			</svg>
		</div>
		<h3 class="card-premium__title"><?php esc_html_e( 'Our Values', 'it-hardware-supply' ); ?></h3>
		<div class="card-premium__divider" aria-hidden="true"></div>
		<ul class="mission-values-list">
			<?php foreach ( $core_values as $val ) : ?>
				<li>
					<svg class="val-check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#163FA8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
					<?php echo esc_html( $val ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
