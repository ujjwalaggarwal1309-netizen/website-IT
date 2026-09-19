<?php
/**
 * Import Column Mapper
 *
 * Defines the canonical mapping between spreadsheet column headers and their
 * WordPress targets (post fields, taxonomies, and meta fields).
 *
 * All mappings are exposed via the 'iths_import_column_map' filter, enabling
 * future column additions without code changes.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mapper {

	// ------ Column Type Constants ---------------------------------------------------------------------------------------------------------------------------------------------------

	/** Maps to a WordPress post table field (title, name, status). */
	const TYPE_POST = 'post';

	/** Maps to a WordPress taxonomy (product-category, product-brand). */
	const TYPE_TAXONOMY = 'taxonomy';

	/** Maps to a WordPress post meta field. */
	const TYPE_META = 'meta';

	/** Used only for import logic (skip detection, etc.) --- not stored. */
	const TYPE_CONTROL = 'control';

	// ------ Column Map ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Returns the canonical column map for the Infinity IT Solutions spreadsheet.
	 *
	 * Each entry:
	 *   'header'   => Spreadsheet column header (case-insensitive match).
	 *   'type'     => One of TYPE_POST | TYPE_TAXONOMY | TYPE_META | TYPE_CONTROL.
	 *   'target'   => Post field name, taxonomy slug, or meta key.
	 *   'required' => Whether the field must be non-empty for a valid row.
	 *   'sanitize' => Sanitization type: 'text' | 'textarea' | 'url'.
	 *
	 * Expose via filter to allow future extensions without code changes.
	 *
	 * @return array[]
	 */
	public static function get_column_map(): array {
		$map = array(

			// ------ Control Fields (logic only, not stored) ---------------------------------------------------------
			array(
				'header'   => 'Product ID',
				'type'     => self::TYPE_CONTROL,
				'target'   => 'product_id',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Content Status',
				'type'     => self::TYPE_CONTROL,
				'target'   => 'content_status',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Catalog Visibility',
				'type'     => self::TYPE_CONTROL,
				'target'   => 'catalog_visibility',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Post Table Fields ---------------------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'Product Name',
				'type'     => self::TYPE_POST,
				'target'   => 'post_title',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Slug',
				'type'     => self::TYPE_POST,
				'target'   => 'post_name',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Publish',
				'type'     => self::TYPE_POST,
				'target'   => 'post_status',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Taxonomy Fields ---------------------------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'Core Category',
				'type'     => self::TYPE_TAXONOMY,
				'target'   => 'product-category',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Brand',
				'type'     => self::TYPE_TAXONOMY,
				'target'   => 'product-brand',
				'required' => true,
				'sanitize' => 'text',
			),

			// ------ Meta Fields --- Identity ------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'Model',
				'type'     => self::TYPE_META,
				'target'   => 'iths_model_number',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'OEM Part Number',
				'type'     => self::TYPE_META,
				'target'   => 'iths_oem_part_number',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'OEM Part No',
				'type'     => self::TYPE_META,
				'target'   => 'iths_oem_part_number',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Part Number',
				'type'     => self::TYPE_META,
				'target'   => 'iths_oem_part_number',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'OEM PN',
				'type'     => self::TYPE_META,
				'target'   => 'iths_oem_part_number',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Manufacturer Part Number',
				'type'     => self::TYPE_META,
				'target'   => 'iths_oem_part_number',
				'required' => true,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Product Group',
				'type'     => self::TYPE_META,
				'target'   => 'iths_product_group',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Product Type',
				'type'     => self::TYPE_META,
				'target'   => 'iths_product_type',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Series',
				'type'     => self::TYPE_META,
				'target'   => 'iths_series',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Meta Fields --- Content ---------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'Short Description',
				'type'     => self::TYPE_META,
				'target'   => 'iths_short_description',
				'required' => false,
				'sanitize' => 'textarea',
			),
			array(
				'header'   => 'Overview',
				'type'     => self::TYPE_META,
				'target'   => 'iths_long_description',
				'required' => false,
				'sanitize' => 'textarea',
			),
			array(
				'header'   => 'Key Features',
				'type'     => self::TYPE_META,
				'target'   => 'iths_key_features_raw', // Processed by split_key_features().
				'required' => false,
				'sanitize' => 'textarea',
			),
			array(
				'header'   => 'Applications',
				'type'     => self::TYPE_META,
				'target'   => 'iths_applications',
				'required' => false,
				'sanitize' => 'textarea',
			),

			// ------ Meta Fields --- Technical Specifications ------------------------------------------------------------
			array(
				'header'   => 'Compatibility',
				'type'     => self::TYPE_META,
				'target'   => 'iths_compatibility',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Processor',
				'type'     => self::TYPE_META,
				'target'   => 'iths_processor',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Memory',
				'type'     => self::TYPE_META,
				'target'   => 'iths_memory',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Storage Capacity',
				'type'     => self::TYPE_META,
				'target'   => 'iths_storage',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Interface',
				'type'     => self::TYPE_META,
				'target'   => 'iths_networking',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Form Factor',
				'type'     => self::TYPE_META,
				'target'   => 'iths_form_factor',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Condition',
				'type'     => self::TYPE_META,
				'target'   => 'iths_condition',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Warranty Type',
				'type'     => self::TYPE_META,
				'target'   => 'iths_warranty_type',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Availability',
				'type'     => self::TYPE_META,
				'target'   => 'iths_availability',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Warranty',
				'type'     => self::TYPE_META,
				'target'   => 'iths_warranty_duration',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Meta Fields --- SEO (REST-enabled) ---------------------------------------------------------------------------
			array(
				'header'   => 'SEO Title',
				'type'     => self::TYPE_META,
				'target'   => 'iths_seo_title',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Meta Description',
				'type'     => self::TYPE_META,
				'target'   => 'iths_meta_description',
				'required' => false,
				'sanitize' => 'textarea',
			),
			array(
				'header'   => 'Focus Keyword',
				'type'     => self::TYPE_META,
				'target'   => 'iths_focus_keyword',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Meta Fields --- Media ---------------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'Hero Image',
				'type'     => self::TYPE_META,
				'target'   => 'iths_hero_image',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'Gallery Image 1',
				'type'     => self::TYPE_META,
				'target'   => 'iths_gallery_image_1',
				'required' => false,
				'sanitize' => 'text',
			),

			// ------ Meta Fields --- QA ---------------------------------------------------------------------------------------------------------------------------
			array(
				'header'   => 'QA Score',
				'type'     => self::TYPE_META,
				'target'   => 'iths_qa_score',
				'required' => false,
				'sanitize' => 'text',
			),
			array(
				'header'   => 'QA Status',
				'type'     => self::TYPE_META,
				'target'   => 'iths_qa_status',
				'required' => false,
				'sanitize' => 'text',
			),
		);

		/**
		 * Filter: iths_import_column_map
		 *
		 * Allows adding, removing, or modifying column mappings without
		 * modifying this file. Future columns require only a filter hook.
		 *
		 * @param array[] $map Array of column definition arrays.
		 */
		return apply_filters( 'iths_import_column_map', $map );
	}

	// ------ Row Mapping ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Maps a raw spreadsheet row (header => value) to a structured import row.
	 *
	 * Uses case-insensitive, trimmed header matching to handle minor formatting
	 * variations in the spreadsheet without breaking the import.
	 *
	 * @param array $raw_row       Associative array: raw header => raw value.
	 * @param array $column_map    Result of get_column_map().
	 * @return array Structured import row keyed by 'target'.
	 */
	public static function map_row( array $raw_row, array $column_map ): array {
		// Build a case-insensitive lookup: normalised_header => column_def.
		$header_lookup = array();
		foreach ( $column_map as $col ) {
			$normalised                    = strtolower( trim( $col['header'] ) );
			$header_lookup[ $normalised ]  = $col;
		}

		$mapped = array();

		foreach ( $raw_row as $raw_header => $raw_value ) {
			$normalised = strtolower( trim( (string) $raw_header ) );

			if ( ! isset( $header_lookup[ $normalised ] ) ) {
				continue; // Unknown column --- skip silently.
			}

			$col    = $header_lookup[ $normalised ];
			$value  = trim( (string) $raw_value );
			$target = $col['target'];

			// Sanitize on map so all downstream code receives clean data.
			$mapped[ $target ] = iths_sanitize_meta_value( $value, $col['sanitize'] );
			$mapped[ '__type_' . $target ] = $col['type'];
		}

		// Post-process Key Features: split on pipe delimiter.
		if ( isset( $mapped['iths_key_features_raw'] ) ) {
			$features = self::split_key_features( $mapped['iths_key_features_raw'] );
			$mapped['iths_key_feature_1'] = $features[0] ?? '';
			$mapped['iths_key_feature_2'] = $features[1] ?? '';
			$mapped['iths_key_feature_3'] = $features[2] ?? '';
			unset( $mapped['iths_key_features_raw'], $mapped['__type_iths_key_features_raw'] );
		}

		// Normalise post_status value from spreadsheet.
		if ( isset( $mapped['post_status'] ) ) {
			$mapped['post_status'] = ( 'yes' === strtolower( $mapped['post_status'] ) )
				? 'publish'
				: 'draft';
		} else {
			$mapped['post_status'] = 'draft'; // Safe default.
		}

		// Apply sensible defaults if not provided by the spreadsheet.
		if ( empty( $mapped['iths_condition'] ) ) {
			$mapped['iths_condition'] = 'new';
		}
		if ( empty( $mapped['iths_availability'] ) ) {
			$mapped['iths_availability'] = 'available_on_request';
		}
		if ( empty( $mapped['iths_warranty_type'] ) ) {
			$mapped['iths_warranty_type'] = 'carry_in';
		}

		return $mapped;
	}

	/**
	 * Splits a pipe-delimited Key Features string into an array of up to 3 features.
	 *
	 * @param string $raw Pipe-delimited string (e.g. 'Feature A | Feature B | Feature C').
	 * @return string[] Array of up to 3 trimmed feature strings.
	 */
	public static function split_key_features( string $raw ): array {
		$parts    = explode( '|', $raw );
		$features = array();
		foreach ( $parts as $part ) {
			$trimmed = trim( $part );
			if ( '' !== $trimmed ) {
				$features[] = $trimmed;
			}
		}
		return array_slice( $features, 0, 3 );
	}

	/**
	 * Returns all column definitions of a given type.
	 *
	 * @param string $type One of TYPE_POST | TYPE_TAXONOMY | TYPE_META | TYPE_CONTROL.
	 * @return array[]
	 */
	public static function get_columns_by_type( string $type ): array {
		return array_filter(
			self::get_column_map(),
			fn( $col ) => $col['type'] === $type
		);
	}
}

