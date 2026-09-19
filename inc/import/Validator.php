<?php
/**
 * Import Row Validator
 *
 * Validates a single mapped product row before it reaches the database.
 * Returns a structured ValidationResult containing errors (blocking) and
 * warnings (non-blocking).
 *
 * Runs the exact same validation pipeline in both real imports and Dry Run
 * mode --- the only difference is whether writes occur downstream.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Validator {

	// ------ Skip Detection ------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Returns true if the row should be skipped entirely (e.g. duplicate row).
	 *
	 * Checks the 'content_status' control field for values starting with
	 * 'Duplicate' --- as set by the Sprint 2A/2B enrichment process.
	 *
	 * @param array $mapped_row Mapped import row.
	 * @return bool
	 */
	public static function should_skip( array $mapped_row ): bool {
		$status = strtolower( trim( $mapped_row['content_status'] ?? '' ) );
		return str_starts_with( $status, 'duplicate' );
	}

	// ------ Full Validation ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Validates a mapped import row.
	 *
	 * @param array $mapped_row Mapped row from Mapper::map_row().
	 * @return array {
	 *   valid:    bool     --- False only if a BLOCKING error exists.
	 *   errors:   string[] --- Blocking issues that prevent import.
	 *   warnings: string[] --- Non-blocking advisories.
	 * }
	 */
	public static function validate( array $mapped_row ): array {
		$errors   = array();
		$warnings = array();

		// ------ Required field checks ---------------------------------------------------------------------------------------------------------------------------
		if ( empty( $mapped_row['post_title'] ) ) {
			$errors[] = 'Product Name is required but is empty.';
		}

		if ( empty( $mapped_row['iths_oem_part_number'] ) && empty( $mapped_row['iths_model_number'] ) ) {
			$errors[] = 'At least one of OEM Part Number or Model Number must be provided for matching.';
		}

		if ( empty( $mapped_row['product-category'] ) ) {
			$warnings[] = 'Core Category is missing --- product will be imported without a category.';
		}

		if ( empty( $mapped_row['product-brand'] ) ) {
			$warnings[] = 'Brand is missing --- product will be imported without a brand taxonomy.';
		}

		// ------ Slug validation ---------------------------------------------------------------------------------------------------------------------------------------------
		if ( ! empty( $mapped_row['post_name'] ) ) {
			$slug = $mapped_row['post_name'];
			if ( $slug !== sanitize_title( $slug ) ) {
				$warnings[] = "Slug '{$slug}' contains invalid characters and will be sanitised on import.";
			}
		}

		// ------ SEO field length warnings ---------------------------------------------------------------------------------------------------------------
		if ( ! empty( $mapped_row['iths_seo_title'] ) && strlen( $mapped_row['iths_seo_title'] ) > 80 ) {
			$len        = strlen( $mapped_row['iths_seo_title'] );
			$warnings[] = "SEO Title is {$len} characters. Consider reviewing for search engine display.";
		}

		if ( ! empty( $mapped_row['iths_meta_description'] ) ) {
			$len = strlen( $mapped_row['iths_meta_description'] );
			if ( $len > 165 ) {
				$warnings[] = "Meta Description is {$len} characters (over 165). May be truncated in SERPs.";
			} elseif ( $len < 80 ) {
				$warnings[] = "Meta Description is only {$len} characters. Consider expanding for better SEO.";
			}
		}

		// ------ QA Status check ---------------------------------------------------------------------------------------------------------------------------------------------
		$qa_status = $mapped_row['iths_qa_status'] ?? '';
		if ( ! empty( $qa_status ) && 'Do Not Import' === $qa_status ) {
			$errors[] = "QA Status is 'Do Not Import' --- this product was flagged during QA and must not be imported.";
		}

		// ------ Part number format advisory ---------------------------------------------------------------------------------------------------------
		if ( ! empty( $mapped_row['iths_oem_part_number'] ) ) {
			$pn = $mapped_row['iths_oem_part_number'];
			if ( strlen( $pn ) > 100 ) {
				$warnings[] = "OEM Part Number is unusually long (" . strlen( $pn ) . " chars). Verify it is correct.";
			}
		}

		// ------ Key Features count advisory ---------------------------------------------------------------------------------------------------------
		$has_kf1 = ! empty( $mapped_row['iths_key_feature_1'] );
		$has_kf2 = ! empty( $mapped_row['iths_key_feature_2'] );
		$has_kf3 = ! empty( $mapped_row['iths_key_feature_3'] );

		if ( $has_kf1 && ( ! $has_kf2 || ! $has_kf3 ) ) {
			$count      = (int) $has_kf1 + (int) $has_kf2 + (int) $has_kf3;
			$warnings[] = "Only {$count} of 3 Key Features are populated.";
		}

		/**
		 * Filter: iths_import_validate_row
		 * Allows custom validation rules to be added via filter.
		 *
		 * @param array $errors   Current blocking errors.
		 * @param array $warnings Current non-blocking warnings.
		 * @param array $mapped_row The mapped import row.
		 */
		[ $errors, $warnings ] = apply_filters(
			'iths_import_validate_row',
			[ $errors, $warnings ],
			$mapped_row
		);

		return array(
			'valid'    => empty( $errors ),
			'errors'   => $errors,
			'warnings' => $warnings,
		);
	}

	/**
	 * Checks for missing meta fields and returns a list of blank required-ish fields.
	 * Used for the 'missing_meta' report metric.
	 *
	 * @param array $mapped_row
	 * @return string[] List of blank field labels.
	 */
	public static function get_missing_meta( array $mapped_row ): array {
		$important_meta = array(
			'iths_short_description' => 'Short Description',
			'iths_long_description'  => 'Overview',
			'iths_key_feature_1'     => 'Key Features',
			'iths_hero_image'        => 'Hero Image',
		);

		$missing = array();
		foreach ( $important_meta as $key => $label ) {
			if ( empty( $mapped_row[ $key ] ) ) {
				$missing[] = $label;
			}
		}
		return $missing;
	}
}

