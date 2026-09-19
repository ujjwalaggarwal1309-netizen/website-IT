<?php
/**
 * Import Logger
 *
 * Manages structured, per-session import logs stored in WordPress options.
 * Each session log records full metadata, per-product results, and all
 * warnings and errors encountered during the import run.
 *
 * Storage: wp_options key 'iths_import_logs' (serialized array of sessions,
 * capped at 50 sessions --- oldest pruned automatically).
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Logger {

	/** @var string WordPress option key for the log store. */
	const OPTION_KEY = 'iths_import_logs';

	/** @var int Maximum number of log sessions to retain. */
	const MAX_SESSIONS = 50;

	/** @var string Current session ID. */
	private string $session_id;

	/** @var array Current session log data. */
	private array $session = array();

	/**
	 * Initialises a new logging session.
	 *
	 * @param string $session_id Unique session ID.
	 * @param string $filename   Original uploaded filename.
	 * @param int    $user_id    ID of the user running the import.
	 */
	public function start_session( string $session_id, string $filename, int $user_id ): void {
		$this->session_id = $session_id;
		$this->session    = array(
			'session_id'      => $session_id,
			'filename'        => sanitize_file_name( $filename ),
			'user_id'         => $user_id,
			'user_name'       => get_userdata( $user_id ) ? get_userdata( $user_id )->display_name : 'Unknown',
			'wp_version'      => get_bloginfo( 'version' ),
			'theme_version'   => wp_get_theme()->get( 'Version' ),
			'started_at'      => current_time( 'c' ),
			'ended_at'        => null,
			'duration_s'      => null,
			'is_dry_run'      => false,
			'stats'           => array(
				'created'             => 0,
				'updated'             => 0,
				'skipped'             => 0,
				'duplicates'          => 0,
				'failed'              => 0,
				'warnings'            => 0,
				'missing_images'      => 0,
				'missing_taxonomies'  => 0,
				'missing_meta'        => 0,
				'peak_memory_bytes'   => 0,
				'avg_time_per_product' => 0,
			),
			'products'        => array(), // Per-product results.
			'warnings'        => array(), // Global warnings.
			'errors'          => array(), // Global errors.
		);
	}

	/**
	 * Marks the session as a dry run.
	 */
	public function set_dry_run( bool $is_dry_run ): void {
		$this->session['is_dry_run'] = $is_dry_run;
	}

	/**
	 * Records the result of processing a single product row.
	 *
	 * @param string      $action     'created', 'updated', 'skipped', 'duplicate', 'failed'.
	 * @param string      $product_id The Product ID from the spreadsheet (e.g. PRD-0001).
	 * @param string      $name       Product name.
	 * @param int|null    $post_id    WordPress post ID (null for skipped/failed).
	 * @param string|null $part_num   OEM Part Number.
	 * @param array       $warnings   Per-product warnings.
	 * @param string|null $error      Error message if failed.
	 */
	public function log_product(
		string $action,
		string $product_id,
		string $name,
		?int $post_id,
		?string $part_num = null,
		array $warnings = array(),
		?string $error = null
	): void {
		$this->session['products'][] = array(
			'action'     => $action,
			'product_id' => $product_id,
			'name'       => $name,
			'post_id'    => $post_id,
			'part_num'   => $part_num,
			'warnings'   => $warnings,
			'error'      => $error,
		);

		// Update stats counters.
		$stat_key = $action;
		if ( isset( $this->session['stats'][ $stat_key ] ) ) {
			$this->session['stats'][ $stat_key ]++;
		}
		if ( ! empty( $warnings ) ) {
			$this->session['stats']['warnings'] += count( $warnings );
		}
	}

	/**
	 * Increments a named stats counter.
	 *
	 * @param string $key Counter key (e.g. 'missing_images').
	 * @param int    $by  Amount to increment by.
	 */
	public function increment_stat( string $key, int $by = 1 ): void {
		if ( isset( $this->session['stats'][ $key ] ) ) {
			$this->session['stats'][ $key ] += $by;
		}
	}

	/**
	 * Adds a global warning (not tied to a specific product).
	 *
	 * @param string $message Warning message.
	 */
	public function add_warning( string $message ): void {
		$this->session['warnings'][] = $message;
		$this->session['stats']['warnings']++;
	}

	/**
	 * Adds a global error (not tied to a specific product).
	 *
	 * @param string $message Error message.
	 */
	public function add_error( string $message ): void {
		$this->session['errors'][] = $message;
	}

	/**
	 * Finalises the session, computes derived stats, and persists the log.
	 *
	 * @param float $start_microtime Microtime from the start of the session.
	 */
	public function end_session( float $start_microtime ): void {
		$end_time    = microtime( true );
		$duration    = round( $end_time - $start_microtime, 3 );
		$peak_memory = memory_get_peak_usage( true );

		$total_processed = $this->session['stats']['created']
			+ $this->session['stats']['updated']
			+ $this->session['stats']['failed'];

		$avg_time = $total_processed > 0
			? round( $duration / $total_processed, 4 )
			: 0;

		$this->session['ended_at']                              = current_time( 'c' );
		$this->session['duration_s']                            = $duration;
		$this->session['stats']['peak_memory_bytes']            = $peak_memory;
		$this->session['stats']['peak_memory_formatted']        = iths_format_bytes( $peak_memory );
		$this->session['stats']['duration_formatted']           = iths_format_duration( $duration );
		$this->session['stats']['avg_time_per_product']         = $avg_time;
		$this->session['stats']['avg_time_per_product_formatted'] = iths_format_duration( $avg_time );

		$this->persist();
	}

	/**
	 * Returns the current session's stats array.
	 *
	 * @return array
	 */
	public function get_stats(): array {
		return $this->session['stats'] ?? array();
	}

	/**
	 * Returns the full current session data.
	 *
	 * @return array
	 */
	public function get_session(): array {
		return $this->session;
	}

	// ------ Static Retrieval Methods ---------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Returns all stored log sessions (most recent first).
	 *
	 * @return array[]
	 */
	public static function get_all_logs(): array {
		$logs = get_option( self::OPTION_KEY, array() );
		return is_array( $logs ) ? array_reverse( $logs ) : array();
	}

	/**
	 * Returns a single log session by session ID.
	 *
	 * @param string $session_id
	 * @return array|null
	 */
	public static function get_log( string $session_id ): ?array {
		foreach ( self::get_all_logs() as $log ) {
			if ( isset( $log['session_id'] ) && $log['session_id'] === $session_id ) {
				return $log;
			}
		}
		return null;
	}

	/**
	 * Deletes a single log session by session ID.
	 *
	 * @param string $session_id
	 */
	public static function delete_log( string $session_id ): void {
		$logs = get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $logs ) ) {
			return;
		}
		$logs = array_filter( $logs, fn( $l ) => ( $l['session_id'] ?? '' ) !== $session_id );
		update_option( self::OPTION_KEY, array_values( $logs ), false );
	}

	/**
	 * Exports a log session as a CSV string.
	 *
	 * @param string $session_id
	 * @return string|null CSV content, or null if session not found.
	 */
	public static function export_log_csv( string $session_id ): ?string {
		$log = self::get_log( $session_id );
		if ( ! $log ) {
			return null;
		}

		ob_start();
		$out = fopen( 'php://output', 'w' );

		// Header row.
		fputcsv( $out, array( 'Action', 'Product ID', 'Product Name', 'Part Number', 'Post ID', 'Warnings', 'Error' ) );

		foreach ( $log['products'] ?? array() as $p ) {
			fputcsv( $out, array(
				$p['action']     ?? '',
				$p['product_id'] ?? '',
				$p['name']       ?? '',
				$p['part_num']   ?? '',
				$p['post_id']    ?? '',
				implode( ' | ', $p['warnings'] ?? array() ),
				$p['error']      ?? '',
			) );
		}

		fclose( $out );
		return ob_get_clean();
	}

	// ------ Private ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Persists the current session to the WordPress options table.
	 * Prunes oldest sessions if MAX_SESSIONS is exceeded.
	 */
	private function persist(): void {
		$logs   = get_option( self::OPTION_KEY, array() );
		$logs   = is_array( $logs ) ? $logs : array();
		$logs[] = $this->session;

		// Keep only the most recent MAX_SESSIONS sessions.
		if ( count( $logs ) > self::MAX_SESSIONS ) {
			$logs = array_slice( $logs, - self::MAX_SESSIONS );
		}

		update_option( self::OPTION_KEY, $logs, false );
	}
}

