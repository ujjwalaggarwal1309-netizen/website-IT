<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function it_hardware_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'it_hardware_disable_emojis' );

/**
 * Allow SVG uploads for administrators with sanitized mime types.
 */
function it_hardware_allow_svg_uploads( $mimes ) {
	if ( current_user_can( 'administrator' ) ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 'it_hardware_allow_svg_uploads' );

/**
 * Send standard production security HTTP headers.
 */
function it_hardware_security_headers() {
	if ( ! is_admin() ) {
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'X-XSS-Protection: 1; mode=block' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: geolocation=(), camera=(), microphone=()' );
	}
}
add_action( 'send_headers', 'it_hardware_security_headers' );

/**
 * Remove WordPress generator tags and version info from scripts/styles.
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

function it_hardware_remove_version_strings( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'style_loader_src', 'it_hardware_remove_version_strings', 9999 );
add_filter( 'script_loader_src', 'it_hardware_remove_version_strings', 9999 );

/**
 * Remove unnecessary discovery links from HTML head.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Block user enumeration via ?author=N for non-logged-in users.
 */
function it_hardware_block_author_enumeration() {
	if ( ! is_admin() && isset( $_REQUEST['author'] ) && ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'init', 'it_hardware_block_author_enumeration' );

/**
 * Disable XML-RPC to protect against brute force and DDoS pingbacks.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
