<?php
/**
 * Template Name: Contact Page
 *
 * Section order per WEBSITE_CONTENT.md — "Contact Page":
 *   1. Page Header           (blue gradient, H1, subtitle)
 *   2. Contact Information   (info cards + enquiry form side-by-side)
 *   3. Working Hours strip   (dark bar)
 *   4. WhatsApp CTA          (green accent section)
 *   5. Google Maps           (placeholder or live embed)
 *
 * @package it-hardware-supply
 */

get_header();
?>
<main id="content" class="site-main" role="main">

	<?php /* ── 1. Page Header ─────────────────────────────────────────────── */ ?>
	<header class="page-header" aria-labelledby="contact-page-title">

		<?php /* Blurred left glow circle */ ?>
		<div class="page-header__glow-left" aria-hidden="true"></div>

		<?php /* 5% opacity circuit pattern */ ?>
		<div class="page-header__circuit" aria-hidden="true">
			<svg viewBox="0 0 1440 380" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
				<line x1="0" y1="80" x2="1440" y2="80" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="200" x2="1440" y2="200" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="320" x2="1440" y2="320" stroke="currentColor" stroke-width="1"/>
				<line x1="120" y1="0" x2="120" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="360" y1="0" x2="360" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="720" y1="0" x2="720" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1080" y1="0" x2="1080" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1320" y1="0" x2="1320" y2="380" stroke="currentColor" stroke-width="1"/>
				<circle cx="120" cy="80" r="5" fill="currentColor"/>
				<circle cx="360" cy="80" r="5" fill="currentColor"/>
				<circle cx="720" cy="80" r="5" fill="currentColor"/>
				<circle cx="1080" cy="80" r="5" fill="currentColor"/>
				<circle cx="1320" cy="80" r="5" fill="currentColor"/>
				<circle cx="120" cy="200" r="5" fill="currentColor"/>
				<circle cx="360" cy="200" r="5" fill="currentColor"/>
				<circle cx="720" cy="200" r="5" fill="currentColor"/>
				<circle cx="1080" cy="200" r="5" fill="currentColor"/>
				<circle cx="120" cy="320" r="5" fill="currentColor"/>
				<circle cx="360" cy="320" r="5" fill="currentColor"/>
				<circle cx="720" cy="320" r="5" fill="currentColor"/>
				<rect x="200" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="580" y="230" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="900" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="1200" y="240" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
			</svg>
		</div>

		<?php /* Decorative SVG — phone/headset, left */ ?>
		<div class="page-header__deco page-header__deco--left" aria-hidden="true">
			<svg width="260" height="260" viewBox="0 0 260 260" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle cx="130" cy="90" r="60" stroke="currentColor" stroke-width="2"/>
				<path d="M90 90 Q90 60 130 60 Q170 60 170 90 Q170 110 155 120 L155 130 L145 130 L145 120 Q130 128 115 120 L115 130 L105 130 L105 120 Q90 110 90 90Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="110" y="130" width="40" height="12" rx="6" stroke="currentColor" stroke-width="1.5"/>
				<path d="M130 142 L130 170" stroke="currentColor" stroke-width="1.5"/>
				<path d="M100 170 L160 170" stroke="currentColor" stroke-width="1.5"/>
				<path d="M40 180 C40 165 55 155 70 160 L90 168 C100 172 100 185 90 190 L85 192 C85 200 100 215 115 215 L117 210 C122 200 135 200 140 210 L148 230 C153 245 143 260 128 258 C75 255 35 210 40 180Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
			</svg>
		</div>

		<?php /* Decorative SVG — envelope, right */ ?>
		<div class="page-header__deco page-header__deco--right" aria-hidden="true">
			<svg width="260" height="200" viewBox="0 0 260 200" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="20" y="30" width="220" height="150" rx="10" stroke="currentColor" stroke-width="2"/>
				<path d="M20 50 L130 115 L240 50" stroke="currentColor" stroke-width="2"/>
				<path d="M20 180 L90 115" stroke="currentColor" stroke-width="1.5"/>
				<path d="M240 180 L170 115" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>

		<div class="container page-header__inner">
			<span class="page-header__label"><?php esc_html_e( 'CONTACT US', 'it-hardware-supply' ); ?></span>
			<h1 id="contact-page-title"><?php esc_html_e( "Contact Our Experts", 'it-hardware-supply' ); ?></h1>
			<div class="page-header__divider" aria-hidden="true"></div>
			<p class="page-header__subtitle"><?php esc_html_e( "Let's discuss your business requirements.", 'it-hardware-supply' ); ?></p>

			<?php if ( function_exists( 'it_hardware_breadcrumbs' ) ) { it_hardware_breadcrumbs(); } ?>
		</div>
	</header>

	<?php /* ── 2. Contact Section: Info Cards + Form ──────────────────────── */ ?>
	<section class="contact-section fade-in" aria-labelledby="contact-section-heading">
		<div class="container">
			<div class="section-heading">
				<span class="section-label"><?php esc_html_e( 'Get In Touch', 'it-hardware-supply' ); ?></span>
				<h2 id="contact-section-heading"><?php esc_html_e( 'We\'re Here to Help', 'it-hardware-supply' ); ?></h2>
				<p><?php esc_html_e( 'Whether you\'re upgrading infrastructure, replacing components, or planning a new deployment, our team is ready to assist.', 'it-hardware-supply' ); ?></p>
			</div>

			<div class="contact-layout">
				<div class="contact-info-column">
					<?php get_template_part( 'template-parts/contact-card' ); ?>
				</div>
				<div class="contact-form-column">
					<?php get_template_part( 'template-parts/contact-form' ); ?>
				</div>
			</div>
		</div>
	</section>

	<?php /* ── 3. Working Hours Strip ───────────────────────────────────────── */ ?>
	<div class="working-hours-strip" aria-label="<?php esc_attr_e( 'Business hours information', 'it-hardware-supply' ); ?>">
		
		<?php /* 4% opacity circuit background */ ?>
		<div class="working-hours-strip__bg" aria-hidden="true">
			<svg viewBox="0 0 1440 380" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
				<line x1="0" y1="80" x2="1440" y2="80" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="200" x2="1440" y2="200" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="320" x2="1440" y2="320" stroke="currentColor" stroke-width="1"/>
				<line x1="120" y1="0" x2="120" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="360" y1="0" x2="360" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="720" y1="0" x2="720" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1080" y1="0" x2="1080" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1320" y1="0" x2="1320" y2="380" stroke="currentColor" stroke-width="1"/>
				<circle cx="120" cy="80" r="5" fill="currentColor"/>
				<circle cx="360" cy="80" r="5" fill="currentColor"/>
				<circle cx="720" cy="80" r="5" fill="currentColor"/>
				<circle cx="1080" cy="80" r="5" fill="currentColor"/>
				<circle cx="1320" cy="80" r="5" fill="currentColor"/>
				<circle cx="120" cy="200" r="5" fill="currentColor"/>
				<circle cx="360" cy="200" r="5" fill="currentColor"/>
				<circle cx="720" cy="200" r="5" fill="currentColor"/>
				<circle cx="1080" cy="200" r="5" fill="currentColor"/>
				<circle cx="120" cy="320" r="5" fill="currentColor"/>
				<circle cx="360" cy="320" r="5" fill="currentColor"/>
				<circle cx="720" cy="320" r="5" fill="currentColor"/>
				<rect x="200" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="580" y="230" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="900" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="1200" y="240" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
			</svg>
		</div>

		<div class="container working-hours-inner">
			<div class="working-hours-item">
				<span class="working-hours-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				</span>
				<div>
					<strong><?php esc_html_e( 'WORKING HOURS', 'it-hardware-supply' ); ?></strong>
					<span><?php echo wp_kses_post( it_hardware_get_setting( 'working_hours' ) ); ?></span>
				</div>
			</div>
			

			<div class="working-hours-item">
				<span class="working-hours-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
				</span>
				<div>
					<strong><?php esc_html_e( 'CALL US', 'it-hardware-supply' ); ?></strong>
					<a href="tel:<?php echo esc_attr( it_hardware_get_setting( 'primary_phone' ) ); ?>"><?php echo esc_html( it_hardware_get_setting( 'primary_phone' ) ); ?></a>
				</div>
			</div>
			


			<div class="working-hours-item">
				<span class="working-hours-icon" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="32" height="32"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
				</span>
				<div>
					<strong><?php esc_html_e( 'EMAIL US', 'it-hardware-supply' ); ?></strong>
					<a href="mailto:<?php echo esc_attr( it_hardware_get_setting( 'email' ) ); ?>"><?php echo esc_html( it_hardware_get_setting( 'email' ) ); ?></a>
				</div>
			</div>
		</div>
	</div>





</main>
<?php get_footer(); ?>
