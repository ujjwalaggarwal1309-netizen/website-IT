<?php
/**
 * Template Part: About Preview
 *
 * Displays the "Your Trusted Enterprise IT Hardware Partner" section
 * on the homepage, positioned after the product category grid.
 *
 * Content source: WEBSITE_CONTENT.md — "About Preview"
 * Data source:    VERIFIED_COMPANY_FACTS.md
 * Design source:  WEBSITE_DESIGN_SPEC.md — two-column image + text layout
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="about-preview-section fade-in" aria-labelledby="about-preview-heading">
	<div class="about-preview-deco">
		<svg class="deco-dots" width="100%" height="100%" fill="none" aria-hidden="true"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><rect x="0" y="0" width="4" height="4" fill="currentColor" fill-opacity="0.05"></rect></pattern></defs><rect width="100%" height="100%" fill="url(#dots)"></rect></svg>
	</div>
	
	<div class="about-preview-container">
		
		<!-- Left Column: Content -->
		<div class="about-preview-left">
			<span class="about-preview-eyebrow"><?php esc_html_e( 'ABOUT INFINITY IT SOLUTIONS', 'it-hardware-supply' ); ?></span>
			<h2 id="about-preview-heading" class="about-preview-heading">
				<?php echo wp_kses_post( __( 'Reliable IT<br>Infrastructure.<br>Built Around You.', 'it-hardware-supply' ) ); ?>
			</h2>
			<p class="about-preview-desc">
				<?php esc_html_e( 'Since 2018, we\'ve been helping businesses across India build stronger IT environments with quality hardware, expert support, and dependable service.', 'it-hardware-supply' ); ?>
			</p>
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">
				<?php esc_html_e( 'Explore Solutions', 'it-hardware-supply' ); ?>
			</a>
		</div>

		<!-- Right Column: 4-Card Strip -->
		<div class="about-preview-right">
			
			<!-- Card 1: Established -->
			<div class="about-stat-card">
				<div class="about-stat-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
						<line x1="16" y1="2" x2="16" y2="6" stroke="#F58220" stroke-width="2"></line>
						<line x1="8" y1="2" x2="8" y2="6" stroke="#F58220" stroke-width="2"></line>
						<line x1="3" y1="10" x2="21" y2="10"></line>
						<rect x="7" y="14" width="3" height="3" fill="#F58220" stroke="none"></rect>
					</svg>
				</div>
				<div class="about-stat-text">
					<span class="about-stat-title"><?php esc_html_e( 'Established', 'it-hardware-supply' ); ?></span>
					<span class="about-stat-highlight"><?php esc_html_e( '2018', 'it-hardware-supply' ); ?></span>
				</div>
				<div class="about-stat-divider"></div>
			</div>

			<!-- Card 2: PAN India -->
			<div class="about-stat-card">
				<div class="about-stat-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path>
						<circle cx="12" cy="10" r="3" stroke="#F58220" stroke-width="2"></circle>
					</svg>
				</div>
				<div class="about-stat-text">
					<span class="about-stat-title"><?php esc_html_e( 'PAN India', 'it-hardware-supply' ); ?></span>
					<span class="about-stat-highlight"><?php esc_html_e( 'Supply & Installation', 'it-hardware-supply' ); ?></span>
				</div>
				<div class="about-stat-divider"></div>
			</div>

			<!-- Card 3: Warranty -->
			<div class="about-stat-card">
				<div class="about-stat-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
						<polyline points="9 12 11 14 15 10" stroke="#F58220" stroke-width="2"></polyline>
					</svg>
				</div>
				<div class="about-stat-text">
					<span class="about-stat-title"><?php esc_html_e( '90 Days &ndash; 1 Year', 'it-hardware-supply' ); ?></span>
					<span class="about-stat-highlight"><?php esc_html_e( 'Warranty', 'it-hardware-supply' ); ?></span>
				</div>
				<div class="about-stat-divider"></div>
			</div>

			<!-- Card 4: Enterprise IT -->
			<div class="about-stat-card">
				<div class="about-stat-icon">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<rect x="2" y="2" width="20" height="6" rx="1" ry="1"></rect>
						<rect x="2" y="9" width="20" height="6" rx="1" ry="1"></rect>
						<rect x="2" y="16" width="20" height="6" rx="1" ry="1"></rect>
						<circle cx="6" cy="5" r="1" fill="#F58220" stroke="none"></circle>
						<circle cx="6" cy="12" r="1" fill="#F58220" stroke="none"></circle>
						<circle cx="6" cy="19" r="1" fill="#F58220" stroke="none"></circle>
					</svg>
				</div>
				<div class="about-stat-text">
					<span class="about-stat-title"><?php esc_html_e( 'Enterprise IT', 'it-hardware-supply' ); ?></span>
					<span class="about-stat-highlight"><?php esc_html_e( 'Solutions', 'it-hardware-supply' ); ?></span>
				</div>
				<div class="about-stat-divider"></div>
			</div>

		</div>
	</div>
</section>
