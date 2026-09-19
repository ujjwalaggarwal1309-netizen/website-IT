<?php
/**
 * Template Name: About Page
 *
 * Section order per WEBSITE_CONTENT.md — "About Page":
 *   1. Page Header   (blue gradient, H1)
 *   2. About Section (image + 4-paragraph narrative)
 *   3. Mission / Vision / Core Values
 *   4. Industries Served
 *   5. About CTA     (uses the shared CTA template-part, overriding text)
 *
 * @package it-hardware-supply
 */

get_header();
?>
<main id="content" class="site-main" role="main">

	<?php /* ── 1. Page Header ──────────────────────────────────────────── */ ?>
	<header class="page-header" aria-labelledby="about-page-title">

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

		<?php /* Decorative SVG — server rack, left */ ?>
		<div class="page-header__deco page-header__deco--left" aria-hidden="true">
			<svg width="320" height="260" viewBox="0 0 320 260" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="20" y="20" width="240" height="220" rx="8" stroke="currentColor" stroke-width="2"/>
				<rect x="32" y="36" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="50" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="50" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="72" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="86" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="86" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="108" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="122" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="122" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="144" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="158" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="158" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="180" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="194" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="194" r="5" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>

		<?php /* Decorative SVG — abstract shapes, right */ ?>
		<div class="page-header__deco page-header__deco--right" aria-hidden="true">
			<svg width="220" height="260" viewBox="0 0 220 260" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="20" y="20" width="80" height="80" rx="6" stroke="currentColor" stroke-width="2"/>
				<rect x="40" y="40" width="40" height="40" rx="3" stroke="currentColor" stroke-width="1.5"/>
				<rect x="120" y="30" width="80" height="50" rx="6" stroke="currentColor" stroke-width="2"/>
				<rect x="20" y="130" width="180" height="30" rx="6" stroke="currentColor" stroke-width="2"/>
				<circle cx="40" cy="145" r="8" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="170" cy="145" r="8" stroke="currentColor" stroke-width="1.5"/>
				<rect x="20" y="180" width="180" height="30" rx="6" stroke="currentColor" stroke-width="2"/>
				<circle cx="40" cy="195" r="8" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="170" cy="195" r="8" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>

		<div class="container page-header__inner">
			<span class="page-header__label"><?php esc_html_e( 'ABOUT US', 'it-hardware-supply' ); ?></span>
			<h1 id="about-page-title"><?php esc_html_e( 'About Infinity IT Solutions', 'it-hardware-supply' ); ?></h1>
			<div class="page-header__divider" aria-hidden="true"></div>
			<p class="page-header__subtitle"><?php esc_html_e( 'Building dependable enterprise infrastructure solutions since 2018.', 'it-hardware-supply' ); ?></p>
			<?php if ( function_exists( 'it_hardware_breadcrumbs' ) ) { it_hardware_breadcrumbs(); } ?>
		</div>
	</header>

	<?php /* ── 2–4. About body, Mission/Vision/Values, Industries ──────── */ ?>
	<?php get_template_part( 'template-parts/about-content' ); ?>

	<?php /* ── 5. About CTA ─────────────────────────────────────────────── */ ?>
	<?php get_template_part( 'template-parts/about-cta' ); ?>

</main>
<?php get_footer(); ?>
