<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup: supports, image sizes, content width.
 */
function it_hardware_theme_setup() {
	load_theme_textdomain( 'it-hardware-supply', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 96,
		'width'       => 440,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );
	add_theme_support( 'custom-background' );
	add_theme_support( 'custom-header' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_image_size( 'hero', 1400, 900, true );
	add_image_size( 'product-card', 600, 420, true );
	add_image_size( 'product-detail', 1200, 900, true );
	add_image_size( 'about-image', 900, 600, true );
	add_image_size( 'og-image', 1200, 630, true );
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'it_hardware_theme_setup' );

/**
 * Output favicon and PWA manifest link tags in <head>.
 *
 * Modern browsers: SVG favicon (favicon.svg) — no image files required.
 * Legacy browsers: PNG/ICO fallback (replace files if generated via Inkscape/Figma).
 * PWA: site.webmanifest for Android homescreen icon.
 *
 * Source: LOGO_ASSET_PACK.md / Phase 2 brand assets
 */
function it_hardware_favicons() {
	$favicon_dir = get_template_directory_uri() . '/assets/favicon/';
	?>
	<link rel="icon" type="image/svg+xml" href="<?php echo esc_url( $favicon_dir . 'favicon.svg?v=3' ); ?>">
	<link rel="manifest" href="<?php echo esc_url( $favicon_dir . 'site.webmanifest' ); ?>">
	<meta name="theme-color" content="#102C7A">
	<?php
}
add_action( 'wp_head', 'it_hardware_favicons', 1 );
