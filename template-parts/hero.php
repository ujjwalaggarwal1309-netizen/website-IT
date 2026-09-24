<?php
/**
 * Template Part: Hero Section
 *
 * Displays the homepage hero with headline, feature badges,
 * primary and secondary CTA buttons, and photorealistic hardware image.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="hero-section" aria-labelledby="hero-heading">
	<!-- Background Elements -->
	<div class="hero-bg" aria-hidden="true">
		<!-- Particles and wave are managed via CSS / SVGs -->
		<svg class="hero-wave" viewBox="0 0 1440 320" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.03)" d="M0,192L48,176C96,160,192,128,288,144C384,160,480,224,576,218.7C672,213,768,139,864,128C960,117,1056,171,1152,192C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
	</div>

	<div class="container hero-layout">
		<!-- LEFT COLUMN -->
		<div class="hero-col-left">
			<div class="hero-pill">
				<span class="hero-pill__dot" aria-hidden="true"></span>
				<?php esc_html_e( 'TRUSTED ACROSS INDIA SINCE 2018', 'it-hardware-supply' ); ?>
			</div>

			<h1 id="hero-heading">
				Powering Businesses<br>
				with Reliable Enterprise<br>
				IT Hardware <span class="hero-highlight">Since 2018</span>
			</h1>

			<p class="hero-description">Infinity IT Solutions delivers dependable enterprise infrastructure solutions across India. From servers and storage to mission-critical spare parts, we help businesses build, maintain, and scale their IT environments with confidence.</p>

			<div class="button-group" role="group" aria-label="<?php esc_attr_e( 'Hero calls to action', 'it-hardware-supply' ); ?>">
				<a class="btn hero-btn-primary" href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ?: home_url( '/products/' ) ); ?>">
					<?php esc_html_e( 'Browse Products', 'it-hardware-supply' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
				</a>

				<a class="btn hero-btn-secondary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
					<?php esc_html_e( 'Contact Us', 'it-hardware-supply' ); ?>
				</a>
			</div>
		</div>

		<!-- RIGHT COLUMN -->
		<div class="hero-col-right">
			<div class="hero-hardware">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/illustrations/about/server.svg' ); ?>" alt="Enterprise Server Rack SVG" class="hero-illustration" loading="eager" fetchpriority="high">
				<!-- Glowing floating dots overlay -->
				<svg class="hero-particles" width="100%" height="100%" viewBox="0 0 400 400" aria-hidden="true" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none;">
					<circle cx="15%" cy="20%" r="3" class="hero-particle hp-1" />
					<circle cx="85%" cy="30%" r="2.5" class="hero-particle hp-2" />
					<circle cx="75%" cy="75%" r="3.5" class="hero-particle hp-3" />
					<circle cx="20%" cy="70%" r="2" class="hero-particle hp-4" />
					<circle cx="45%" cy="10%" r="3" class="hero-particle hp-5" />
				</svg>

				<!-- Floating Features Bubbles -->
				<div class="hero-bubble bubble-1">
					<div class="bubble-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
					</div>
					<div class="bubble-text">
						<strong>Enterprise Grade</strong>
						<span>Quality You Can Trust</span>
					</div>
				</div>
				<div class="hero-bubble bubble-2">
					<div class="bubble-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
					</div>
					<div class="bubble-text">
						<strong>PAN India Delivery</strong>
						<span>Fast & Reliable</span>
					</div>
				</div>
				<div class="hero-bubble bubble-3">
					<div class="bubble-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
					</div>
					<div class="bubble-text">
						<strong>Wide Range</strong>
						<span>200+ Products</span>
					</div>
				</div>
				<div class="hero-bubble bubble-4">
					<div class="bubble-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>
					</div>
					<div class="bubble-text">
						<strong>Expert Support</strong>
						<span>Always Here to Help</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
