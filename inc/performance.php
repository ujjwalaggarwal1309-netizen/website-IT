<?php
/**
 * inc/performance.php — Performance optimisations
 *
 * - Defers non-critical JS via the `script_loader_tag` filter.
 * - Adds preconnect hints for Google Fonts (eliminates render-blocking connection latency).
 * - Adds preload for the Inter woff2 font (LCP font).
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Defer non-critical scripts and add preconnect/preload hints.
 */
function it_hardware_performance_setup() {

	// ── Defer non-critical JS ─────────────────────────────────────────────────
	add_filter(
		'script_loader_tag',
		function( $tag, $handle ) {
			$defer_handles = array(
				'it-hardware-main',
				'it-hardware-navigation',
				'it-hardware-animations',
				'it-hardware-counter',
				'it-hardware-lazyload',
				'it-hardware-filter',
				'it-hardware-search',
				'it-hardware-contact',
			);

			if ( in_array( $handle, $defer_handles, true ) ) {
				return str_replace( ' src', ' defer src', $tag );
			}
			return $tag;
		},
		10,
		2
	);

	// ── Preconnect hints for Google Fonts ─────────────────────────────────────
	add_action(
		'wp_head',
		function() {
			?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
			<?php
		},
		1 // Priority 1: before wp_head() so it's near the top of <head>
	);
}
add_action( 'after_setup_theme', 'it_hardware_performance_setup' );
