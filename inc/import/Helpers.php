<?php
/**
 * Import Helpers --- Utility Functions
 *
 * Shared utilities used across all import engine modules.
 * No WordPress hooks registered here --- pure functions only.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ------ Constants ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/** Default upload subdirectory for hero product images. */
if ( ! defined( 'ITHS_IMPORT_IMAGE_SUBDIR' ) ) {
	define( 'ITHS_IMPORT_IMAGE_SUBDIR', 'products' );
}

/** Transient TTL for import lock (30 minutes). */
if ( ! defined( 'ITHS_IMPORT_LOCK_TTL' ) ) {
	define( 'ITHS_IMPORT_LOCK_TTL', 30 * MINUTE_IN_SECONDS );
}

/** Default batch size. */
if ( ! defined( 'ITHS_IMPORT_BATCH_SIZE' ) ) {
	define( 'ITHS_IMPORT_BATCH_SIZE', 25 );
}

// ------ Slug Utilities ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Generates a unique WordPress post slug, avoiding collisions with existing posts.
 *
 * @param string $desired_slug The slug from the spreadsheet.
 * @param int    $exclude_id   Post ID to exclude from collision check (for updates).
 * @return string Final unique slug.
 */
function iths_generate_unique_slug( string $desired_slug, int $exclude_id = 0 ): string {
	$desired_slug = sanitize_title( $desired_slug );
	if ( empty( $desired_slug ) ) {
		return '';
	}

	$slug      = $desired_slug;
	$suffix    = 2;
	$args      = array(
		'name'           => $slug,
		'post_type'      => 'products',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	);

	while ( true ) {
		$args['name'] = $slug;
		$posts        = get_posts( $args );
		if ( empty( $posts ) || ( 1 === count( $posts ) && (int) $posts[0] === $exclude_id ) ) {
			break;
		}
		$slug = $desired_slug . '-' . $suffix;
		$suffix++;
	}

	return $slug;
}

// ------ Taxonomy Cache ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Static in-memory taxonomy term cache.
 * Keyed by "{taxonomy}::{term_name_lower}".
 *
 * @var array<string, int>
 */
$iths_taxonomy_cache = array();

/**
 * Gets or creates a taxonomy term, with in-memory caching.
 *
 * @param string $taxonomy    The taxonomy slug (e.g. 'product-category').
 * @param string $term_name   The term name to look up or create.
 * @param bool   $auto_create Whether to create the term if it does not exist.
 * @return int|null Term ID on success, null if not found and auto_create is false.
 */
function iths_get_or_create_term( string $taxonomy, string $term_name, bool $auto_create = true ): ?int {
	global $iths_taxonomy_cache;

	if ( empty( $term_name ) || empty( $taxonomy ) ) {
		return null;
	}

	$cache_key = strtolower( $taxonomy ) . '::' . strtolower( trim( $term_name ) );

	if ( isset( $iths_taxonomy_cache[ $cache_key ] ) ) {
		return $iths_taxonomy_cache[ $cache_key ];
	}

	$existing = get_term_by( 'name', $term_name, $taxonomy );

	if ( $existing && ! is_wp_error( $existing ) ) {
		$iths_taxonomy_cache[ $cache_key ] = $existing->term_id;
		return $existing->term_id;
	}

	if ( ! $auto_create ) {
		return null;
	}

	$result = wp_insert_term( $term_name, $taxonomy );

	if ( is_wp_error( $result ) ) {
		// Term may have just been created in a race --- try fetching again.
		$retry = get_term_by( 'name', $term_name, $taxonomy );
		if ( $retry && ! is_wp_error( $retry ) ) {
			$iths_taxonomy_cache[ $cache_key ] = $retry->term_id;
			return $retry->term_id;
		}
		return null;
	}

	$term_id = (int) $result['term_id'];
	$iths_taxonomy_cache[ $cache_key ] = $term_id;
	return $term_id;
}

/**
 * Clears the in-memory taxonomy cache. Call between test runs.
 */
function iths_clear_taxonomy_cache(): void {
	global $iths_taxonomy_cache;
	$iths_taxonomy_cache = array();
}

// ------ Hero Image Utilities ---------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Resolves the expected full filesystem path for a hero image filename.
 *
 * @param string $filename Filename only (e.g. 'dell-r740-front.webp').
 * @return string Full path on disk.
 */
function iths_resolve_hero_image_path( string $filename ): string {
	$upload_dir = wp_upload_dir();
	$subdir     = apply_filters( 'iths_import_image_subdir', ITHS_IMPORT_IMAGE_SUBDIR );
	return trailingslashit( $upload_dir['basedir'] ) . trailingslashit( $subdir ) . ltrim( $filename, '/\\' );
}

/**
 * Checks whether a hero image file exists on disk.
 *
 * @param string $filename Filename only.
 * @return bool True if the file exists.
 */
function iths_hero_image_exists( string $filename ): bool {
	if ( empty( $filename ) ) {
		return true; // Blank filename is not an error.
	}
	return file_exists( iths_resolve_hero_image_path( $filename ) );
}

// ------ Sanitization ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Sanitizes a meta value for storage.
 *
 * @param mixed  $value Raw value.
 * @param string $type  Field type: 'text', 'textarea', 'url'.
 * @return string Sanitized string.
 */
function iths_sanitize_meta_value( $value, string $type = 'text' ): string {
	if ( null === $value || '' === $value ) {
		return '';
	}

	$value = (string) $value;

	switch ( $type ) {
		case 'textarea':
			return sanitize_textarea_field( wp_unslash( $value ) );
		case 'url':
			return esc_url_raw( $value );
		default:
			return sanitize_text_field( wp_unslash( $value ) );
	}
}

// ------ Formatting ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

/**
 * Formats bytes into a human-readable string.
 *
 * @param int $bytes Number of bytes.
 * @return string Formatted string (e.g. '12.4 MB').
 */
function iths_format_bytes( int $bytes ): string {
	if ( $bytes >= 1073741824 ) {
		return number_format( $bytes / 1073741824, 2 ) . ' GB';
	}
	if ( $bytes >= 1048576 ) {
		return number_format( $bytes / 1048576, 2 ) . ' MB';
	}
	if ( $bytes >= 1024 ) {
		return number_format( $bytes / 1024, 2 ) . ' KB';
	}
	return $bytes . ' B';
}

/**
 * Formats a duration in seconds to a human-readable string.
 *
 * @param float $seconds Duration in seconds.
 * @return string Formatted string (e.g. '1m 23s' or '45.3s').
 */
function iths_format_duration( float $seconds ): string {
	if ( $seconds >= 60 ) {
		$mins = floor( $seconds / 60 );
		$secs = round( $seconds % 60, 1 );
		return "{$mins}m {$secs}s";
	}
	return round( $seconds, 2 ) . 's';
}

/**
 * Generates a unique import session ID.
 *
 * @return string e.g. 'iths_import_20260804_124352_a3f7c912'
 */
function iths_generate_session_id(): string {
	return 'iths_import_' . gmdate( 'Ymd_His' ) . '_' . substr( wp_generate_uuid4(), 0, 8 );
}

