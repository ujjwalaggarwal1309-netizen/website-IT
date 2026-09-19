<?php
/**
 * Importer --- Main Orchestrator
 *
 * Coordinates all import modules: parse --- validate --- match --- write --- log.
 * Owns the import session lifecycle, import lock, and dry-run mode.
 *
 * Product matching priority (as per architecture):
 *   1. OEM Part Number  (iths_oem_part_number)
 *   2. Model Number     (iths_model_number)
 *   3. No match --- CREATE new product
 *   Product Name is NEVER used for matching.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Importer {

	// ------ Lock ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	const LOCK_KEY = 'iths_import_lock';

	/** @var string */
	private string $session_id;

	/** @var Logger */
	private Logger $logger;

	/** @var Rollback */
	private Rollback $rollback;

	/** @var float Microtime at session start. */
	private float $start_time;

	/**
	 * Acquires the import lock and initialises a new session.
	 * Returns a WP_Error if the lock could not be acquired.
	 *
	 * @param string $filename  Original uploaded filename (for logging).
	 * @return self|\WP_Error
	 */
	public static function start_session( string $filename ) {
		// Check for existing lock.
		$lock = get_transient( self::LOCK_KEY );
		if ( $lock ) {
			return new \WP_Error(
				'iths_import_locked',
				sprintf(
					/* translators: 1: user name, 2: start time */
					__( 'An import is already in progress (started by %1$s at %2$s). Please wait or ask an administrator to release the lock.', 'it-hardware-supply' ),
					esc_html( $lock['user_name'] ?? 'Unknown' ),
					esc_html( $lock['started_at'] ?? '' )
				)
			);
		}

		$instance             = new self();
		$instance->session_id = iths_generate_session_id();
		$instance->start_time = microtime( true );
		$instance->logger     = new Logger();
		$instance->rollback   = new Rollback( $instance->session_id );

		$user    = wp_get_current_user();
		$user_id = get_current_user_id();

		// Acquire lock.
		set_transient(
			self::LOCK_KEY,
			array(
				'session_id' => $instance->session_id,
				'user_id'    => $user_id,
				'user_name'  => $user->display_name,
				'started_at' => current_time( 'c' ),
			),
			ITHS_IMPORT_LOCK_TTL
		);

		$instance->logger->start_session( $instance->session_id, $filename, $user_id );

		return $instance;
	}

	/**
	 * Initialises a dry-run session (no lock acquired, no DB writes).
	 *
	 * @param string $filename
	 * @return self
	 */
	public static function start_dry_run( string $filename ): self {
		$instance             = new self();
		$instance->session_id = iths_generate_session_id();
		$instance->start_time = microtime( true );
		$instance->logger     = new Logger();
		$instance->rollback   = new Rollback( $instance->session_id );

		$instance->logger->start_session( $instance->session_id, $filename, get_current_user_id() );
		$instance->logger->set_dry_run( true );

		return $instance;
	}

	// ------ Preview (read-only, no validation, just classification) ---------------------------------------------

	/**
	 * Parses the file and classifies every row as Create/Update/Skip.
	 * Returns data for the preview table. No database writes.
	 *
	 * @param string $file_path Path to uploaded file.
	 * @param string $file_type 'xlsx' or 'csv'.
	 * @return array { rows: array[], errors: string[], parse_errors: string[] }
	 */
	public static function preview( string $file_path, string $file_type ): array {
		$parser = new Parser( $file_path, $file_type );
		if ( ! $parser->parse() ) {
			return array( 'rows' => array(), 'errors' => $parser->get_errors(), 'parse_errors' => $parser->get_errors() );
		}

		$column_map  = Mapper::get_column_map();
		$preview_rows = array();

		foreach ( $parser->get_rows() as $raw_row ) {
			$mapped = Mapper::map_row( $raw_row, $column_map );

			$product_id = $mapped['product_id'] ?? '';
			$name       = $mapped['post_title']  ?? '';
			$brand      = $mapped['product-brand'] ?? '';
			$category   = $mapped['product-category'] ?? '';
			$part_num   = $mapped['iths_oem_part_number'] ?? '';
			$status     = $mapped['content_status'] ?? '';
			$qa_status  = $mapped['iths_qa_status'] ?? '';

			// Determine action.
			if ( Validator::should_skip( $mapped ) ) {
				$action  = 'skip';
				$reason  = 'Duplicate';
				$post_id = null;
			} else {
				$existing = self::match_existing_product( $mapped );
				if ( $existing ) {
					$action  = 'update';
					$reason  = '';
					$post_id = $existing;
				} else {
					$action  = 'create';
					$reason  = '';
					$post_id = null;
				}
			}

			// Image warning.
			$hero_warning = '';
			if ( ! empty( $mapped['iths_hero_image'] ) && ! iths_hero_image_exists( $mapped['iths_hero_image'] ) ) {
				$hero_warning = 'Image file missing';
			}

			$preview_rows[] = array(
				'product_id'   => $product_id,
				'name'         => $name,
				'action'       => $action,
				'reason'       => $reason,
				'brand'        => $brand,
				'category'     => $category,
				'part_num'     => $part_num,
				'post_id'      => $post_id,
				'hero_warning' => $hero_warning,
				'qa_status'    => $qa_status,
			);
		}

		return array(
			'rows'         => $preview_rows,
			'parse_errors' => array(),
			'summary'      => array(
				'total'   => count( $preview_rows ),
				'create'  => count( array_filter( $preview_rows, fn( $r ) => 'create' === $r['action'] ) ),
				'update'  => count( array_filter( $preview_rows, fn( $r ) => 'update' === $r['action'] ) ),
				'skip'    => count( array_filter( $preview_rows, fn( $r ) => 'skip'   === $r['action'] ) ),
				'warning' => count( array_filter( $preview_rows, fn( $r ) => ! empty( $r['hero_warning'] ) ) ),
			),
		);
	}

	// ------ Dry Run ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Runs the full import pipeline without writing to the database.
	 * Uses the exact same validation path as a real import.
	 *
	 * @param string $file_path
	 * @param string $file_type
	 * @return array Full report array identical to a real import report.
	 */
	public static function dry_run( string $file_path, string $file_type ): array {
		$instance = self::start_dry_run( basename( $file_path ) );
		return $instance->run_pipeline( $file_path, $file_type, true );
	}

	// ------ Real Import ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Parses the file and stores all rows in batch transients for processing.
	 * Returns the session ID and total batch count for the AJAX loop.
	 *
	 * @param string $file_path
	 * @param string $file_type
	 * @return array|\WP_Error { session_id, total_batches, total_rows } or WP_Error.
	 */
	public function prepare_batches( string $file_path, string $file_type ) {
		$parser = new Parser( $file_path, $file_type );
		if ( ! $parser->parse() ) {
			$this->release_lock();
			return new \WP_Error( 'parse_failed', implode( ' ', $parser->get_errors() ) );
		}

		$column_map  = Mapper::get_column_map();
		$mapped_rows = array();

		foreach ( $parser->get_rows() as $raw_row ) {
			$mapped_rows[] = Mapper::map_row( $raw_row, $column_map );
		}

		$batch_processor = new BatchProcessor( $this->session_id );
		$total_batches   = $batch_processor->store_batches( $mapped_rows );

		// Store session ID in a short-lived transient for AJAX handlers.
		set_transient( 'iths_active_session', $this->session_id, HOUR_IN_SECONDS );

		return array(
			'session_id'    => $this->session_id,
			'total_batches' => $total_batches,
			'total_rows'    => $parser->count(),
		);
	}

	/**
	 * Processes a single batch. Called once per AJAX batch request.
	 *
	 * @param string $session_id  The active session ID.
	 * @param int    $batch_index Zero-based batch index.
	 * @return array { success, processed, created, updated, skipped, failed, warnings, errors[] }
	 */
	public static function process_batch( string $session_id, int $batch_index ): array {
		$batch_processor = new BatchProcessor( $session_id );
		$rows            = $batch_processor->get_batch( $batch_index );

		if ( null === $rows ) {
			return array( 'success' => false, 'error' => 'Batch not found --- it may have expired.' );
		}

		$logger   = new Logger();
		$rollback = new Rollback( $session_id );

		// Reload logger state from saved session (for stats continuity).
		$logger->start_session( $session_id, '', get_current_user_id() );

		$result = array(
			'success'   => true,
			'processed' => 0,
			'created'   => 0,
			'updated'   => 0,
			'skipped'   => 0,
			'failed'    => 0,
			'warnings'  => array(),
			'errors'    => array(),
		);

		foreach ( $rows as $mapped ) {
			$result['processed']++;
			$product_id = $mapped['product_id'] ?? '?';
			$name       = $mapped['post_title']  ?? '';
			$part_num   = $mapped['iths_oem_part_number'] ?? '';
			$row_warnings = array();

			try {
				// Skip duplicate rows.
				if ( Validator::should_skip( $mapped ) ) {
					$logger->log_product( 'skipped', $product_id, $name, null, $part_num );
					$result['skipped']++;
					continue;
				}

				// Validate.
				$validation = Validator::validate( $mapped );
				if ( ! $validation['valid'] ) {
					$error_msg = implode( ' | ', $validation['errors'] );
					$logger->log_product( 'failed', $product_id, $name, null, $part_num, array(), $error_msg );
					$result['failed']++;
					$result['errors'][] = "[{$product_id}] {$error_msg}";
					continue;
				}

				$row_warnings = array_merge( $row_warnings, $validation['warnings'] );

				// Check for missing meta (stat only).
				$missing_meta = Validator::get_missing_meta( $mapped );
				if ( ! empty( $missing_meta ) ) {
					$logger->increment_stat( 'missing_meta', count( $missing_meta ) );
				}

				// Hero image check.
				if ( ! empty( $mapped['iths_hero_image'] ) && ! iths_hero_image_exists( $mapped['iths_hero_image'] ) ) {
					$row_warnings[] = 'Hero image not found: ' . $mapped['iths_hero_image'];
					$logger->increment_stat( 'missing_images' );
				}

				// Match existing product.
				$existing_post_id = self::match_existing_product( $mapped );

				if ( $existing_post_id ) {
					// UPDATE.
					$old_meta  = Rollback::snapshot_meta( $existing_post_id );
					$old_terms = Rollback::snapshot_terms( $existing_post_id );

					$write_result = self::write_product( $existing_post_id, $mapped, $session_id, false );

					if ( is_wp_error( $write_result ) ) {
						throw new \RuntimeException( $write_result->get_error_message() );
					}

					// Record revision history (changed fields only).
					self::write_revision_history( $existing_post_id, $session_id, $old_meta, $mapped );

					$rollback->record_update( $existing_post_id, $name, $old_meta, $old_terms );
					$logger->log_product( 'updated', $product_id, $name, $existing_post_id, $part_num, $row_warnings );
					$result['updated']++;
				} else {
					// CREATE.
					$new_post_id = self::write_product( 0, $mapped, $session_id, true );

					if ( is_wp_error( $new_post_id ) ) {
						throw new \RuntimeException( $new_post_id->get_error_message() );
					}

					$rollback->record_create( (int) $new_post_id, $name );
					$logger->log_product( 'created', $product_id, $name, (int) $new_post_id, $part_num, $row_warnings );
					$result['created']++;
				}

				if ( ! empty( $row_warnings ) ) {
					$result['warnings'] = array_merge( $result['warnings'], $row_warnings );
				}
			} catch ( \Throwable $e ) {
				$logger->log_product( 'failed', $product_id, $name, null, $part_num, array(), $e->getMessage() );
				$result['failed']++;
				$result['errors'][] = "[{$product_id}] " . $e->getMessage();
				// Continue processing remaining products in this batch.
			}
		}

		// Save rollback snapshot after each batch.
		$rollback->save();

		$batch_processor->mark_batch_done( $batch_index );

		return $result;
	}

	/**
	 * Finalises the import: releases the lock and logs end-of-session stats.
	 *
	 * @param string $session_id
	 * @param array  $accumulated_stats Stats accumulated across all batch results.
	 * @param float  $start_time        Microtime at the beginning of the full import.
	 * @return array Final report.
	 */
	public static function finalize( string $session_id, array $accumulated_stats, float $start_time ): array {
		// Release lock.
		self::release_lock_static();

		// Clean up batch transients.
		BatchProcessor::force_clear( $session_id );

		// Clear active session transient.
		delete_transient( 'iths_active_session' );

		$end_time = microtime( true );
		$duration = round( $end_time - $start_time, 3 );

		$total_processed = ( $accumulated_stats['created'] ?? 0 )
			+ ( $accumulated_stats['updated'] ?? 0 )
			+ ( $accumulated_stats['failed']  ?? 0 );

		$avg_time = $total_processed > 0
			? round( $duration / $total_processed, 4 )
			: 0;

		$peak_memory = memory_get_peak_usage( true );

		$report = array_merge(
			$accumulated_stats,
			array(
				'session_id'                    => $session_id,
				'duration_s'                    => $duration,
				'duration_formatted'            => iths_format_duration( $duration ),
				'peak_memory_bytes'             => $peak_memory,
				'peak_memory_formatted'         => iths_format_bytes( $peak_memory ),
				'avg_time_per_product'          => $avg_time,
				'avg_time_per_product_formatted' => iths_format_duration( $avg_time ),
			)
		);

		// Persist the final log.
		$logger = new Logger();
		$logger->start_session( $session_id, $accumulated_stats['filename'] ?? '', get_current_user_id() );
		foreach ( $accumulated_stats as $k => $v ) {
			// Map batch-level stats to logger stats format.
		}
		// Note: detailed per-product logging happens in process_batch().
		// Here we just store the aggregate report as a meta option.
		update_option( 'iths_last_import_report_' . $session_id, $report, false );

		return $report;
	}

	// ------ Product Write ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Creates or updates a WordPress product post.
	 *
	 * @param int   $post_id    0 for create, existing post ID for update.
	 * @param array $mapped     Mapped import row.
	 * @param string $session_id Current session ID.
	 * @param bool  $is_create  True for create, false for update.
	 * @return int|\WP_Error Post ID on success, WP_Error on failure.
	 */
	private static function write_product( int $post_id, array $mapped, string $session_id, bool $is_create ) {
		$title  = $mapped['post_title']  ?? '';
		$slug   = $mapped['post_name']   ?? '';
		$status = $mapped['post_status'] ?? 'draft';

		// Generate unique slug if creating.
		if ( $is_create ) {
			$desired_slug = ! empty( $slug ) ? $slug : sanitize_title( $title );
			$slug         = iths_generate_unique_slug( $desired_slug );
		}

		$post_data = array(
			'post_title'  => sanitize_text_field( $title ),
			'post_name'   => $slug,
			'post_status' => in_array( $status, array( 'publish', 'draft', 'private' ), true ) ? $status : 'draft',
			'post_type'   => 'products',
			'post_content' => '', // Content is in meta.
		);

		if ( $is_create ) {
			$result = wp_insert_post( $post_data, true );
		} else {
			$post_data['ID'] = $post_id;
			$result          = wp_update_post( $post_data, true );
		}

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$new_post_id = (int) $result;

		// Write all meta fields.
		self::write_meta( $new_post_id, $mapped, $session_id );

		// Assign taxonomies.
		$tax_warnings = self::write_taxonomies( $new_post_id, $mapped );

		return $new_post_id;
	}

	/**
	 * Writes all product meta fields to the database.
	 *
	 * @param int    $post_id
	 * @param array  $mapped
	 * @param string $session_id
	 */
	private static function write_meta( int $post_id, array $mapped, string $session_id ): void {
		// All fields with type META.
		$meta_targets = array_map(
			fn( $col ) => $col['target'],
			array_filter( Mapper::get_column_map(), fn( $col ) => Mapper::TYPE_META === $col['type'] )
		);

		// Exclude the raw key features field (already split).
		$meta_targets = array_filter( $meta_targets, fn( $t ) => 'iths_key_features_raw' !== $t );

		// Also include the 3 split key feature fields.
		$meta_targets = array_merge( array_values( $meta_targets ), array(
			'iths_key_feature_1',
			'iths_key_feature_2',
			'iths_key_feature_3',
		) );

		foreach ( $meta_targets as $meta_key ) {
			if ( isset( $mapped[ $meta_key ] ) ) {
				update_post_meta( $post_id, $meta_key, $mapped[ $meta_key ] );
			}
		}

		// Always write the session ID.
		update_post_meta( $post_id, 'iths_import_session_id', $session_id );
	}

	/**
	 * Assigns taxonomy terms to a product post.
	 * Auto-creates missing terms if allowed.
	 *
	 * @param int   $post_id
	 * @param array $mapped
	 * @return string[] Warnings for missing or unresolvable terms.
	 */
	private static function write_taxonomies( int $post_id, array $mapped ): array {
		$warnings   = array();
		$taxonomies = array( 'product-category', 'product-brand' );

		foreach ( $taxonomies as $taxonomy ) {
			$term_name = $mapped[ $taxonomy ] ?? '';
			if ( empty( $term_name ) ) {
				$warnings[] = "Missing taxonomy term for '{$taxonomy}'.";
				continue;
			}

			$auto_create = (bool) apply_filters( 'iths_import_auto_create_terms', true, $taxonomy );
			$term_id     = iths_get_or_create_term( $taxonomy, $term_name, $auto_create );

			if ( null === $term_id ) {
				$warnings[] = "Could not find or create term '{$term_name}' in taxonomy '{$taxonomy}'.";
				continue;
			}

			wp_set_object_terms( $post_id, array( $term_id ), $taxonomy );
		}

		return $warnings;
	}

	/**
	 * Appends a revision history entry to a product that was just updated.
	 * Only records fields that actually changed value.
	 *
	 * @param int    $post_id
	 * @param string $session_id
	 * @param array  $old_meta    Previous meta snapshot.
	 * @param array  $new_mapped  Current mapped row.
	 */
	private static function write_revision_history( int $post_id, string $session_id, array $old_meta, array $new_mapped ): void {
		$changes = array();

		foreach ( $new_mapped as $field_key => $new_value ) {
			// Only track actual meta fields.
			if ( ! str_starts_with( $field_key, 'iths_' ) ) {
				continue;
			}
			// Skip the session ID itself and revision history key.
			if ( in_array( $field_key, array( 'iths_import_session_id', 'iths_revision_history' ), true ) ) {
				continue;
			}

			$old_value = $old_meta[ $field_key ] ?? '';

			// Only record if the value genuinely changed.
			if ( (string) $old_value !== (string) $new_value ) {
				$changes[ $field_key ] = array(
					'previous' => $old_value,
					'new'      => $new_value,
				);
			}
		}

		if ( empty( $changes ) ) {
			return; // No changes --- don't add a revision entry.
		}

		$history   = get_post_meta( $post_id, 'iths_revision_history', true );
		$history   = is_array( $history ) ? $history : array();
		$history[] = array(
			'session_id' => $session_id,
			'timestamp'  => current_time( 'c' ),
			'user_id'    => get_current_user_id(),
			'changes'    => $changes,
		);

		update_post_meta( $post_id, 'iths_revision_history', $history );
	}

	// ------ Product Matching ------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Attempts to find an existing product matching the given mapped row.
	 *
	 * Priority:
	 *   1. OEM Part Number  (iths_oem_part_number)
	 *   2. Model Number     (iths_model_number)
	 *
	 * Product Name is NEVER used for matching.
	 *
	 * @param array $mapped Mapped import row.
	 * @return int|null Post ID if found, null if not found.
	 */
	private static function match_existing_product( array $mapped ): ?int {
		// Priority 1: OEM Part Number.
		$part_num = trim( $mapped['iths_oem_part_number'] ?? '' );
		if ( ! empty( $part_num ) ) {
			$found = self::find_by_meta( 'iths_oem_part_number', $part_num );
			if ( $found ) {
				return $found;
			}
		}

		// Priority 2: Model Number.
		$model = trim( $mapped['iths_model_number'] ?? '' );
		if ( ! empty( $model ) ) {
			$found = self::find_by_meta( 'iths_model_number', $model );
			if ( $found ) {
				return $found;
			}
		}

		return null;
	}

	/**
	 * Finds an existing 'products' post by a single meta key/value pair.
	 *
	 * @param string $meta_key
	 * @param string $meta_value
	 * @return int|null Post ID, or null.
	 */
	private static function find_by_meta( string $meta_key, string $meta_value ): ?int {
		$query = new \WP_Query( array(
			'post_type'      => 'products',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => $meta_key,
					'value'   => $meta_value,
					'compare' => '=',
				),
			),
		) );

		if ( $query->have_posts() ) {
			return (int) $query->posts[0];
		}

		return null;
	}

	// ------ Lock Management ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Returns the current lock data, or null if no lock exists.
	 *
	 * @return array|null
	 */
	public static function get_lock(): ?array {
		$lock = get_transient( self::LOCK_KEY );
		return ( is_array( $lock ) ) ? $lock : null;
	}

	/**
	 * Force-releases the import lock. Only callable by admins.
	 */
	public static function release_lock_static(): void {
		delete_transient( self::LOCK_KEY );
	}

	private function release_lock(): void {
		delete_transient( self::LOCK_KEY );
	}

	// ------ Dry Run Pipeline ------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Runs the full parse --- validate --- match pipeline without DB writes.
	 *
	 * @param string $file_path
	 * @param string $file_type
	 * @param bool   $dry_run
	 * @return array Report.
	 */
	private function run_pipeline( string $file_path, string $file_type, bool $dry_run ): array {
		$parser = new Parser( $file_path, $file_type );
		if ( ! $parser->parse() ) {
			return array( 'success' => false, 'parse_errors' => $parser->get_errors() );
		}

		$column_map  = Mapper::get_column_map();
		$stats       = array(
			'created' => 0, 'updated' => 0, 'skipped' => 0, 'duplicates' => 0,
			'failed' => 0, 'warnings' => 0, 'missing_images' => 0,
			'missing_taxonomies' => 0, 'missing_meta' => 0,
		);
		$rows_detail = array();
		$all_warnings = array();
		$all_errors   = array();

		foreach ( $parser->get_rows() as $raw_row ) {
			$mapped     = Mapper::map_row( $raw_row, $column_map );
			$product_id = $mapped['product_id'] ?? '?';
			$name       = $mapped['post_title']  ?? '';
			$part_num   = $mapped['iths_oem_part_number'] ?? '';
			$row_warnings = array();

			if ( Validator::should_skip( $mapped ) ) {
				$stats['skipped']++;
				$stats['duplicates']++;
				$rows_detail[] = array( 'action' => 'skip', 'product_id' => $product_id, 'name' => $name );
				continue;
			}

			$validation = Validator::validate( $mapped );
			if ( ! $validation['valid'] ) {
				$stats['failed']++;
				$all_errors[]  = "[{$product_id}] " . implode( ' | ', $validation['errors'] );
				$rows_detail[] = array( 'action' => 'failed', 'product_id' => $product_id, 'name' => $name, 'errors' => $validation['errors'] );
				continue;
			}

			$row_warnings = array_merge( $row_warnings, $validation['warnings'] );

			$missing_meta = Validator::get_missing_meta( $mapped );
			if ( ! empty( $missing_meta ) ) {
				$stats['missing_meta'] += count( $missing_meta );
			}

			if ( ! empty( $mapped['iths_hero_image'] ) && ! iths_hero_image_exists( $mapped['iths_hero_image'] ) ) {
				$row_warnings[] = 'Hero image not found: ' . $mapped['iths_hero_image'];
				$stats['missing_images']++;
			}

			if ( empty( $mapped['product-category'] ) || empty( $mapped['product-brand'] ) ) {
				$stats['missing_taxonomies']++;
			}

			$existing = self::match_existing_product( $mapped );
			if ( $existing ) {
				$stats['updated']++;
				$action = 'update';
			} else {
				$stats['created']++;
				$action = 'create';
			}

			if ( ! empty( $row_warnings ) ) {
				$stats['warnings'] += count( $row_warnings );
				$all_warnings       = array_merge( $all_warnings, $row_warnings );
			}

			$rows_detail[] = array(
				'action'     => $action,
				'product_id' => $product_id,
				'name'       => $name,
				'part_num'   => $part_num,
				'warnings'   => $row_warnings,
			);
		}

		$end_time    = microtime( true );
		$duration    = round( $end_time - $this->start_time, 3 );
		$total_proc  = $stats['created'] + $stats['updated'] + $stats['failed'];
		$peak_memory = memory_get_peak_usage( true );

		return array(
			'success'                        => true,
			'dry_run'                        => $dry_run,
			'session_id'                     => $this->session_id,
			'stats'                          => $stats,
			'rows'                           => $rows_detail,
			'warnings'                       => $all_warnings,
			'errors'                         => $all_errors,
			'duration_s'                     => $duration,
			'duration_formatted'             => iths_format_duration( $duration ),
			'peak_memory_formatted'          => iths_format_bytes( $peak_memory ),
			'avg_time_per_product'           => $total_proc > 0 ? round( $duration / $total_proc, 4 ) : 0,
			'avg_time_per_product_formatted' => iths_format_duration( $total_proc > 0 ? $duration / $total_proc : 0 ),
		);
	}
}

