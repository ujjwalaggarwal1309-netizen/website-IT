<?php
/**
 * Batch Processor
 *
 * Splits an import session's rows into fixed-size batches (default: 25),
 * stores them in WordPress transients, and processes them one batch at a
 * time in response to AJAX requests from the browser.
 *
 * This prevents PHP execution timeouts on large imports and enables
 * real-time progress bar updates.
 *
 * Transient keys:
 *   iths_batch_{session_id}_meta   --- batch count and total rows
 *   iths_batch_{session_id}_{n}    --- serialised rows for batch n
 *   iths_batch_{session_id}_done   --- count of processed batches
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BatchProcessor {

	/** @var int Default products per batch. */
	const DEFAULT_BATCH_SIZE = ITHS_IMPORT_BATCH_SIZE;

	/** @var int Transient TTL: 2 hours. */
	const TRANSIENT_TTL = 2 * HOUR_IN_SECONDS;

	/** @var string */
	private string $session_id;

	/** @var int */
	private int $batch_size;

	/**
	 * @param string $session_id Unique session identifier.
	 * @param int    $batch_size Products per batch.
	 */
	public function __construct( string $session_id, int $batch_size = self::DEFAULT_BATCH_SIZE ) {
		$this->session_id = $session_id;
		$this->batch_size = max( 1, (int) apply_filters( 'iths_import_batch_size', $batch_size ) );
	}

	// ------ Storage ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Stores all rows as batches in transients.
	 * Call this once before starting the AJAX batch loop.
	 *
	 * @param array[] $rows All mapped + validated rows to import.
	 * @return int Number of batches created.
	 */
	public function store_batches( array $rows ): int {
		$batches      = array_chunk( $rows, $this->batch_size );
		$batch_count  = count( $batches );

		foreach ( $batches as $index => $batch ) {
			set_transient(
				$this->batch_key( $index ),
				$batch,
				self::TRANSIENT_TTL
			);
		}

		set_transient(
			$this->meta_key(),
			array(
				'total_batches' => $batch_count,
				'total_rows'    => count( $rows ),
				'batch_size'    => $this->batch_size,
				'done'          => 0,
			),
			self::TRANSIENT_TTL
		);

		return $batch_count;
	}

	/**
	 * Retrieves a specific batch of rows.
	 *
	 * @param int $batch_index Zero-based batch index.
	 * @return array[]|null Rows for this batch, or null if not found.
	 */
	public function get_batch( int $batch_index ): ?array {
		$data = get_transient( $this->batch_key( $batch_index ) );
		return ( false !== $data && is_array( $data ) ) ? $data : null;
	}

	/**
	 * Marks a batch as processed and updates the done counter.
	 *
	 * @param int $batch_index
	 */
	public function mark_batch_done( int $batch_index ): void {
		delete_transient( $this->batch_key( $batch_index ) );

		$meta = get_transient( $this->meta_key() );
		if ( is_array( $meta ) ) {
			$meta['done'] = ( $meta['done'] ?? 0 ) + 1;
			set_transient( $this->meta_key(), $meta, self::TRANSIENT_TTL );
		}
	}

	// ------ Progress ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Returns current import progress.
	 *
	 * @return array {
	 *   total_batches: int,
	 *   total_rows:    int,
	 *   done:          int,
	 *   pct:           int   --- 0-100
	 *   complete:      bool
	 * }
	 */
	public function get_progress(): array {
		$meta = get_transient( $this->meta_key() );

		if ( ! is_array( $meta ) ) {
			return array( 'total_batches' => 0, 'total_rows' => 0, 'done' => 0, 'pct' => 0, 'complete' => true );
		}

		$total    = (int) ( $meta['total_batches'] ?? 0 );
		$done     = (int) ( $meta['done']          ?? 0 );
		$pct      = $total > 0 ? (int) round( ( $done / $total ) * 100 ) : 100;
		$complete = $done >= $total;

		return array(
			'total_batches' => $total,
			'total_rows'    => (int) ( $meta['total_rows'] ?? 0 ),
			'batch_size'    => (int) ( $meta['batch_size'] ?? $this->batch_size ),
			'done'          => $done,
			'pct'           => $pct,
			'complete'      => $complete,
		);
	}

	/**
	 * Returns the total number of batches for this session.
	 *
	 * @return int
	 */
	public function get_total_batches(): int {
		$meta = get_transient( $this->meta_key() );
		return is_array( $meta ) ? (int) ( $meta['total_batches'] ?? 0 ) : 0;
	}

	// ------ Cleanup ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Removes all transients for this session.
	 * Called on completion and on error cleanup.
	 */
	public function clear(): void {
		$meta = get_transient( $this->meta_key() );

		if ( is_array( $meta ) ) {
			$total = (int) ( $meta['total_batches'] ?? 0 );
			for ( $i = 0; $i < $total; $i++ ) {
				delete_transient( $this->batch_key( $i ) );
			}
		}

		delete_transient( $this->meta_key() );
	}

	/**
	 * Static cleanup: removes all import-related transients for a session ID.
	 * Used for force-clean on stale sessions.
	 *
	 * @param string $session_id
	 */
	public static function force_clear( string $session_id ): void {
		global $wpdb;

		$like = $wpdb->esc_like( '_transient_iths_batch_' . $session_id ) . '%';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$like
			)
		);

		$like_timeout = $wpdb->esc_like( '_transient_timeout_iths_batch_' . $session_id ) . '%';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$like_timeout
			)
		);
	}

	// ------ Private ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function batch_key( int $index ): string {
		return 'iths_batch_' . $this->session_id . '_' . $index;
	}

	private function meta_key(): string {
		return 'iths_batch_' . $this->session_id . '_meta';
	}
}

