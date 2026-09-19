<?php
/**
 * Template Part: Brands We Support
 *
 * Displays the grid of supported enterprise hardware brands on the homepage.
 * Uses premium typography styling on elevated cards.
 *
 * Content source: WEBSITE_CONTENT.md — "Brands We Support"
 * Data source:    VERIFIED_COMPANY_FACTS.md — "Supported Brands" (9 brands)
 *
 * IMPORTANT: These are supported platforms only.
 * Infinity IT Solutions is NOT an authorized partner, dealer, or
 * official representative of any listed brand.
 * The disclaimer below must always be visible. Do not remove it.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Verified supported brands.
 * Source: VERIFIED_COMPANY_FACTS.md — "Supported Brands"
 * Do NOT add or remove brands without client confirmation.
 */
$brands = array(
	'HP',
	'Dell',
	'Lenovo',
	'Cisco',
	'Fujitsu',
	'Oracle',
	'IBM',
	'NetApp',
);
?>
<section class="brands-section fade-in" aria-labelledby="brands-heading">

	<?php /* Decorative circuit SVG — left */ ?>
	<div class="brands-deco brands-deco--left" aria-hidden="true">
		<svg width="260" height="640" viewBox="0 0 260 640" fill="none" xmlns="http://www.w3.org/2000/svg">
			<!-- Vertical lines (Top segment) -->
			<line x1="40" y1="0" x2="40" y2="110" stroke="currentColor" stroke-width="1"/>
			<line x1="100" y1="0" x2="100" y2="110" stroke="currentColor" stroke-width="1"/>
			<line x1="160" y1="0" x2="160" y2="110" stroke="currentColor" stroke-width="1"/>
			<!-- Vertical lines (Bottom segment) -->
			<line x1="40" y1="280" x2="40" y2="640" stroke="currentColor" stroke-width="1"/>
			<line x1="100" y1="280" x2="100" y2="640" stroke="currentColor" stroke-width="1"/>
			<line x1="160" y1="280" x2="160" y2="640" stroke="currentColor" stroke-width="1"/>
			
			<!-- Horizontal lines -->
			<line x1="0" y1="50" x2="260" y2="50" stroke="currentColor" stroke-width="1"/>
			<line x1="0" y1="280" x2="260" y2="280" stroke="currentColor" stroke-width="1"/>
			<line x1="0" y1="550" x2="260" y2="550" stroke="currentColor" stroke-width="1"/>
			
			<!-- Intersection Circles -->
			<circle cx="40" cy="50" r="5" fill="currentColor"/>
			<circle cx="100" cy="50" r="5" fill="currentColor"/>
			<circle cx="160" cy="50" r="5" fill="currentColor"/>
			<circle cx="40" cy="280" r="5" fill="currentColor"/>
			<circle cx="100" cy="280" r="5" fill="currentColor"/>
			<circle cx="160" cy="280" r="5" fill="currentColor"/>
			<circle cx="40" cy="550" r="5" fill="currentColor"/>
			<circle cx="100" cy="550" r="5" fill="currentColor"/>
			<circle cx="160" cy="550" r="5" fill="currentColor"/>
			
			<!-- Circuit Rectangles -->
			<rect x="55" y="70" width="30" height="20" rx="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
			<rect x="115" y="415" width="30" height="20" rx="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
		</svg>
	</div>

	<?php /* Decorative circuit SVG — right */ ?>
	<div class="brands-deco brands-deco--right" aria-hidden="true">
		<svg width="260" height="640" viewBox="0 0 260 640" fill="none" xmlns="http://www.w3.org/2000/svg">
			<!-- Vertical lines (Top segment) -->
			<line x1="100" y1="0" x2="100" y2="110" stroke="currentColor" stroke-width="1"/>
			<line x1="160" y1="0" x2="160" y2="110" stroke="currentColor" stroke-width="1"/>
			<line x1="220" y1="0" x2="220" y2="110" stroke="currentColor" stroke-width="1"/>
			<!-- Vertical lines (Bottom segment) -->
			<line x1="100" y1="280" x2="100" y2="640" stroke="currentColor" stroke-width="1"/>
			<line x1="160" y1="280" x2="160" y2="640" stroke="currentColor" stroke-width="1"/>
			<line x1="220" y1="280" x2="220" y2="640" stroke="currentColor" stroke-width="1"/>
			
			<!-- Horizontal lines -->
			<line x1="0" y1="50" x2="260" y2="50" stroke="currentColor" stroke-width="1"/>
			<line x1="0" y1="280" x2="260" y2="280" stroke="currentColor" stroke-width="1"/>
			<line x1="0" y1="550" x2="260" y2="550" stroke="currentColor" stroke-width="1"/>
			
			<!-- Intersection Circles -->
			<circle cx="100" cy="50" r="5" fill="currentColor"/>
			<circle cx="160" cy="50" r="5" fill="currentColor"/>
			<circle cx="220" cy="50" r="5" fill="currentColor"/>
			<circle cx="100" cy="280" r="5" fill="currentColor"/>
			<circle cx="160" cy="280" r="5" fill="currentColor"/>
			<circle cx="220" cy="280" r="5" fill="currentColor"/>
			<circle cx="100" cy="550" r="5" fill="currentColor"/>
			<circle cx="160" cy="550" r="5" fill="currentColor"/>
			<circle cx="220" cy="550" r="5" fill="currentColor"/>
			
			<!-- Circuit Rectangles -->
			<rect x="115" y="70" width="30" height="20" rx="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
			<rect x="175" y="415" width="30" height="20" rx="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
		</svg>
	</div>

	<div class="brands-glow" aria-hidden="true"></div>

	<div class="container" style="position:relative;z-index:1;">
		<div class="section-heading">
			<span class="section-label"><?php esc_html_e( 'Technology Platforms', 'it-hardware-supply' ); ?></span>
			<h2 id="brands-heading"><?php esc_html_e( 'Technology Platforms We Support', 'it-hardware-supply' ); ?></h2>
			<p class="brands-subtitle"><?php esc_html_e( 'Supporting enterprise ecosystems trusted by businesses across India.', 'it-hardware-supply' ); ?></p>
		</div>

		<div class="brands-marquee">
			<ul class="brands-marquee__track" role="list" aria-label="<?php esc_attr_e( 'Supported enterprise hardware brands', 'it-hardware-supply' ); ?>">
				<?php foreach ( $brands as $brand ) : ?>
				<li class="brand-badge">
					<span class="brand-badge__name"><?php echo esc_html( $brand ); ?></span>
					<span class="orange-divider" aria-hidden="true"></span>
				</li>
				<?php endforeach; ?>
				<?php /* Duplicate for seamless loop */ ?>
				<?php foreach ( $brands as $brand ) : ?>
				<li class="brand-badge" aria-hidden="true">
					<span class="brand-badge__name"><?php echo esc_html( $brand ); ?></span>
					<span class="orange-divider" aria-hidden="true"></span>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="brands-disclaimer-card">
			<span class="brands-disclaimer-card__icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
			</span>
			<h3 class="brands-disclaimer-card__title"><?php esc_html_e( 'SUPPORTED PLATFORMS ONLY', 'it-hardware-supply' ); ?></h3>
			<p class="brands-disclaimer-card__body"><?php esc_html_e( 'The listed platforms represent compatible technologies and do not imply authorized partnership or certification.', 'it-hardware-supply' ); ?></p>
		</div>

		<div class="brands-cta">
			<a
				class="btn btn-outline"
				href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ?: home_url( '/products/' ) ); ?>"
				aria-label="<?php esc_attr_e( 'Browse our full product range', 'it-hardware-supply' ); ?>"
			><?php esc_html_e( 'Browse Product Range', 'it-hardware-supply' ); ?></a>
		</div>
	</div>
</section>
