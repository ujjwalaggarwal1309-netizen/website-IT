<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme stylesheets and scripts.
 *
 * Google Fonts (Inter) is preloaded here.
 * All JS files are deferred via inc/performance.php.
 */
function it_hardware_enqueue_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	// Google Fonts — Inter (weights used: 400, 500, 600, 700, 800).
	wp_enqueue_style(
		'it-hardware-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	// Main stylesheet (imports all CSS partials).
	wp_enqueue_style(
		'it-hardware-style',
		get_template_directory_uri() . '/style.css',
		array( 'it-hardware-fonts' ),
		$theme_version
	);

	// Global scripts — always loaded.
	wp_enqueue_script( 'it-hardware-main',       get_template_directory_uri() . '/assets/js/main.js',       array(), $theme_version, true );
	wp_enqueue_script( 'it-hardware-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), $theme_version, true );
	wp_enqueue_script( 'it-hardware-animations', get_template_directory_uri() . '/assets/js/animations.js', array(), $theme_version, true );
	wp_enqueue_script( 'it-hardware-counter',    get_template_directory_uri() . '/assets/js/counter.js',    array(), $theme_version, true );
	wp_enqueue_script( 'it-hardware-lazyload',   get_template_directory_uri() . '/assets/js/lazyload.js',   array(), $theme_version, true );

	// Products-only scripts.
	if ( is_post_type_archive( 'products' ) || is_tax( 'product-category' ) || is_tax( 'product-brand' ) || is_tax( 'product-series' ) ) {
		wp_enqueue_script( 'it-hardware-product-archive', get_template_directory_uri() . '/assets/js/product-archive.js', array( 'jquery' ), $theme_version, true );
		wp_localize_script( 'it-hardware-product-archive', 'ithsArchive', array(
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'iths_archive_nonce' ),
			'contactUrl' => get_permalink( get_page_by_path( 'contact' ) ) ?: home_url( '/contact/' ),
		) );
	}

	// Contact page script — loads on contact page and single product pages (for ?product= pre-fill).
	if ( is_page_template( 'page-contact.php' ) || is_page( 'contact' ) || is_singular( 'products' ) ) {
		wp_enqueue_script( 'it-hardware-contact', get_template_directory_uri() . '/assets/js/contact.js', array(), $theme_version, true );
	}

	// Single product interaction script.
	if ( is_singular( 'products' ) ) {
		wp_enqueue_script( 'it-hardware-product-single', get_template_directory_uri() . '/assets/js/product-single.js', array(), $theme_version, true );
	}
}
add_action( 'wp_enqueue_scripts', 'it_hardware_enqueue_scripts' );

/**
 * Enqueue admin scripts and styles for specific custom screens.
 */
function it_hardware_admin_enqueue_scripts( $hook_suffix ) {
	// Only load on our custom OEM Image Acquisition page
	if ( $hook_suffix !== 'products_page_oem-image-acquisition' ) {
		return;
	}

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'it-hardware-admin-image-acquisition', get_template_directory_uri() . '/assets/css/admin-image-acquisition.css', array(), $theme_version );
	
	wp_enqueue_script( 'it-hardware-admin-image-acquisition', get_template_directory_uri() . '/assets/js/admin-image-acquisition.js', array( 'jquery' ), $theme_version, true );
	wp_localize_script( 'it-hardware-admin-image-acquisition', 'ithsAcquisition', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'iths_image_acquisition_nonce' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'it_hardware_admin_enqueue_scripts' );
