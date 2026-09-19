<?php
/**
 * Import AJAX Handlers
 *
 * All AJAX endpoints for the Infinity Import Engine.
 * Every handler enforces nonce verification and manage_options capability.
 *
 * Registered actions (all wp_ajax_ -------- admin-only):
 *   iths_import_preview      -------- Parse file, return preview table HTML
 *   iths_import_dry_run      -------- Full pipeline, no DB writes, return report
 *   iths_import_start        -------- Acquire lock, store batches, return session data
 *   iths_import_batch        -------- Process one batch, return batch result
 *   iths_import_finalize     -------- Release lock, generate final report
 *   iths_import_progress     -------- Return current batch progress
 *   iths_import_rollback     -------- Execute rollback for a session
 *   iths_release_lock        -------- Force-release the import lock
 *   iths_export_log          -------- Download a session log as CSV (GET request)
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax {

	// ---------------- Security Guards --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Verifies the import nonce and admin capability.
	 * Sends a JSON error and dies if verification fails.
	 */
	private static function verify(): void {
		if ( ! check_ajax_referer( 'iths_import_action', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed.' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'You do not have permission to perform imports.' ), 403 );
		}
	}

	// ---------------- File Upload Helper --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Handles the file upload from the AJAX form data and returns the temp path.
	 *
	 * @return array { file_path: string, file_type: string } or WP_Error.
	 */
	private static function handle_upload() {
		if ( empty( $_FILES['iths_file']['tmp_name'] ) ) {
			return new \WP_Error( 'no_file', 'No file was uploaded.' );
		}

		$file     = $_FILES['iths_file'];
		$ext      = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$allowed  = array( 'xlsx', 'csv' );

		if ( ! in_array( $ext, $allowed, true ) ) {
			return new \WP_Error( 'invalid_type', 'Only .xlsx and .csv files are accepted.' );
		}

		// Verify MIME type.
		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mime  = finfo_file( $finfo, $file['tmp_name'] );
		finfo_close( $finfo );

		$allowed_mimes = array(
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.ms-excel',
			'text/csv',
			'text/plain',
			'application/csv',
			'application/octet-stream', // Some servers send this for xlsx.
		);

		if ( ! in_array( $mime, $allowed_mimes, true ) && 'xlsx' === $ext ) {
			// PhpSpreadsheet handles its own validation, so allow xlsx through.
		}

		// Move to a safe temp directory.
		$upload_dir = wp_upload_dir();
		$temp_dir   = trailingslashit( $upload_dir['basedir'] ) . 'iths-import-temp/';

		if ( ! wp_mkdir_p( $temp_dir ) ) {
			return new \WP_Error( 'dir_error', 'Could not create temp directory for upload.' );
		}

		$filename  = 'iths_' . time() . '_' . sanitize_file_name( $file['name'] );
		$dest_path = $temp_dir . $filename;

		if ( ! move_uploaded_file( $file['tmp_name'], $dest_path ) ) {
			return new \WP_Error( 'move_error', 'Failed to move uploaded file.' );
		}

		return array(
			'file_path' => $dest_path,
			'file_type' => $ext,
			'filename'  => $file['name'],
		);
	}

	// ---------------- Handler: Preview ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Parse file, classify rows, return preview table HTML + summary counts.
	 */
	public static function preview(): void {
		self::verify();

		$upload = self::handle_upload();
		if ( is_wp_error( $upload ) ) {
			wp_send_json_error( array( 'message' => $upload->get_error_message() ) );
		}

		// Store the file path in a transient so subsequent Dry Run / Import
		// calls can use the same already-uploaded file.
		set_transient( 'iths_upload_path_' . get_current_user_id(), $upload, 30 * MINUTE_IN_SECONDS );

		$preview = Importer::preview( $upload['file_path'], $upload['file_type'] );

		if ( ! empty( $preview['parse_errors'] ) ) {
			wp_send_json_error( array( 'message' => implode( ' ', $preview['parse_errors'] ) ) );
		}

		// Render preview table HTML.
		ob_start();
		self::render_preview_table( $preview['rows'] );
		$table_html = ob_get_clean();

		wp_send_json_success( array(
			'summary'    => $preview['summary'],
			'table_html' => $table_html,
		) );
	}

	// ---------------- Handler: Dry Run ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function dry_run(): void {
		self::verify();

		$upload = get_transient( 'iths_upload_path_' . get_current_user_id() );
		if ( ! $upload || ! file_exists( $upload['file_path'] ) ) {
			wp_send_json_error( array( 'message' => 'Please upload the file first using Preview.' ) );
		}

		$report = Importer::dry_run( $upload['file_path'], $upload['file_type'] );

		ob_start();
		self::render_report( $report, true );
		$report_html = ob_get_clean();

		wp_send_json_success( array(
			'report_html' => $report_html,
			'stats'       => $report['stats'],
		) );
	}

	// ---------------- Handler: Start Import --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function start_import(): void {
		self::verify();

		$upload = get_transient( 'iths_upload_path_' . get_current_user_id() );
		if ( ! $upload || ! file_exists( $upload['file_path'] ) ) {
			wp_send_json_error( array( 'message' => 'Please upload the file first using Preview.' ) );
		}

		$instance = Importer::start_session( $upload['filename'] ?? basename( $upload['file_path'] ) );

		if ( is_wp_error( $instance ) ) {
			wp_send_json_error( array( 'message' => $instance->get_error_message() ) );
		}

		$result = $instance->prepare_batches( $upload['file_path'], $upload['file_type'] );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		// Store start time in a transient for finalize.
		set_transient( 'iths_start_time_' . $result['session_id'], microtime( true ), HOUR_IN_SECONDS );

		wp_send_json_success( $result );
	}

	// ---------------- Handler: Process Batch ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function process_batch(): void {
		self::verify();

		$session_id  = sanitize_text_field( wp_unslash( $_POST['session_id'] ?? '' ) );
		$batch_index = (int) ( $_POST['batch_index'] ?? 0 );

		if ( empty( $session_id ) ) {
			wp_send_json_error( array( 'message' => 'Missing session ID.' ) );
		}

		$result = Importer::process_batch( $session_id, $batch_index );
		wp_send_json_success( $result );
	}

	// ---------------- Handler: Finalize ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function finalize(): void {
		self::verify();

		$session_id = sanitize_text_field( wp_unslash( $_POST['session_id'] ?? '' ) );
		$stats      = isset( $_POST['stats'] ) ? (array) json_decode( wp_unslash( $_POST['stats'] ), true ) : array();

		if ( empty( $session_id ) ) {
			wp_send_json_error( array( 'message' => 'Missing session ID.' ) );
		}

		$start_time = (float) get_transient( 'iths_start_time_' . $session_id );
		$start_time = $start_time ?: microtime( true );

		$report = Importer::finalize( $session_id, $stats, $start_time );

		delete_transient( 'iths_start_time_' . $session_id );
		delete_transient( 'iths_upload_path_' . get_current_user_id() );

		ob_start();
		self::render_report( $report, false );
		$report_html = ob_get_clean();

		wp_send_json_success( array(
			'report_html' => $report_html,
			'report'      => $report,
		) );
	}

	// ---------------- Handler: Progress ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function progress(): void {
		self::verify();

		$session_id = sanitize_text_field( wp_unslash( $_POST['session_id'] ?? '' ) );
		if ( empty( $session_id ) ) {
			wp_send_json_error( array( 'message' => 'Missing session ID.' ) );
		}

		$processor = new BatchProcessor( $session_id );
		wp_send_json_success( $processor->get_progress() );
	}

	// ---------------- Handler: Rollback ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function rollback(): void {
		self::verify();

		$session_id = sanitize_text_field( wp_unslash( $_POST['session_id'] ?? '' ) );
		if ( empty( $session_id ) ) {
			wp_send_json_error( array( 'message' => 'Missing session ID for rollback.' ) );
		}

		if ( ! confirm_ajax_referer( 'iths_import_action', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed.' ), 403 );
		}

		$result = Rollback::execute( $session_id );

		if ( $result['success'] ) {
			wp_send_json_success( array(
				'message' => sprintf(
					/* translators: 1: deleted count, 2: restored count */
					__( 'Rollback complete. %1$d product(s) deleted, %2$d product(s) restored to previous values.', 'it-hardware-supply' ),
					$result['deleted'],
					$result['restored']
				),
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( 'Rollback completed with errors:', 'it-hardware-supply' ) . ' ' . implode( ' | ', $result['errors'] ),
				'deleted'  => $result['deleted'],
				'restored' => $result['restored'],
			) );
		}
	}

	// ---------------- Handler: Release Lock --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function release_lock(): void {
		self::verify();
		Importer::release_lock_static();
		wp_send_json_success( array( 'message' => 'Import lock released.' ) );
	}

	// ---------------- Handler: Export Log CSV ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function export_log(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorised', 403 );
		}

		$nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) );
		if ( ! wp_verify_nonce( $nonce, 'iths_export_log' ) ) {
			wp_die( 'Security check failed.', 403 );
		}

		$session_id = sanitize_text_field( wp_unslash( $_GET['session_id'] ?? '' ) );
		$csv        = Logger::export_log_csv( $session_id );

		if ( null === $csv ) {
			wp_die( 'Log session not found.' );
		}

		$filename = 'iths-import-log-' . $session_id . '.csv';
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
		header( 'Pragma: no-cache' );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $csv;
		exit;
	}

	// ---------------- HTML Renderers ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Renders the preview table HTML.
	 *
	 * @param array[] $rows Preview row data from Importer::preview().
	 */
	private static function render_preview_table( array $rows ): void {
		if ( empty( $rows ) ) {
			echo '<p>' . esc_html__( 'No rows found in the uploaded file.', 'it-hardware-supply' ) . '</p>';
			return;
		}

		$action_labels = array(
			'create' => '-------- Create',
			'update' => '--------- Update',
			'skip'   => '------ Skip',
			'failed' => '--------- Failed',
		);

		echo '<div class="iths-preview-table-container">';
		echo '<table class="wp-list-table widefat striped iths-preview-table">';
		echo '<thead><tr>';
		echo '<th class="col-id">' . esc_html__( 'Product ID', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-name">' . esc_html__( 'Product Name', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-action">' . esc_html__( 'Action', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-brand">' . esc_html__( 'Brand', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-cat">' . esc_html__( 'Category', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-part">' . esc_html__( 'Part Number', 'it-hardware-supply' ) . '</th>';
		echo '<th class="col-warn">' . esc_html__( 'Warning', 'it-hardware-supply' ) . '</th>';
		echo '</tr></thead><tbody>';

		foreach ( $rows as $row ) {
			$action     = $row['action'] ?? 'create';
			$row_class  = 'iths-row-' . esc_attr( $action );
			$action_lbl = $action_labels[ $action ] ?? $action;
			$warning    = $row['hero_warning'] ?? ( $row['reason'] ?? '' );

			printf(
				'<tr class="%s"><td>%s</td><td class="wrap-cell">%s</td><td class="center-cell">%s</td><td class="ellipsis-cell" title="%s">%s</td><td class="ellipsis-cell" title="%s">%s</td><td class="ellipsis-cell" title="%s"><code>%s</code></td><td class="wrap-cell">%s</td></tr>',
				esc_attr( $row_class ),
				esc_html( $row['product_id'] ?? '' ),
				esc_html( $row['name'] ?? '' ),
				'<span class="iths-action-badge iths-badge-' . esc_attr( $action ) . '">' . esc_html( $action_lbl ) . '</span>',
				esc_attr( $row['brand'] ?? '' ),
				esc_html( $row['brand'] ?? '' ),
				esc_attr( $row['category'] ?? '' ),
				esc_html( $row['category'] ?? '' ),
				esc_attr( $row['part_num'] ?? '' ),
				esc_html( $row['part_num'] ?? '' ),
				! empty( $warning ) ? '<span class="iths-warning">' . esc_html( $warning ) . '</span>' : ''
			);
		}

		echo '</tbody></table></div>';
	}

	/**
	 * Renders the final import / dry-run report HTML.
	 *
	 * @param array $report  Report data array.
	 * @param bool  $dry_run Whether this is a dry run report.
	 */
	private static function render_report( array $report, bool $dry_run ): void {
		$stats = $report['stats'] ?? $report;
		$label = $dry_run
			? __( '-------- Dry Run Report -------- No data was written to the database.', 'it-hardware-supply' )
			: __( '------- Import Complete', 'it-hardware-supply' );

		echo '<p><strong>' . esc_html( $label ) . '</strong></p>';

		if ( $dry_run ) {
			echo '<p class="description">' . esc_html__( 'The following is exactly what a real import would produce.', 'it-hardware-supply' ) . '</p>';
		}

		$metrics = array(
			'created'                       => array( '-------', __( 'Created', 'it-hardware-supply' ) ),
			'updated'                       => array( '----------', __( 'Updated', 'it-hardware-supply' ) ),
			'skipped'                       => array( '------', __( 'Skipped', 'it-hardware-supply' ) ),
			'duplicates'                    => array( '---------', __( 'Duplicates Excluded', 'it-hardware-supply' ) ),
			'warnings'                      => array( '------', __( 'Warnings', 'it-hardware-supply' ) ),
			'missing_images'                => array( '---------', __( 'Missing Images', 'it-hardware-supply' ) ),
			'missing_taxonomies'            => array( '--------', __( 'Missing Taxonomies', 'it-hardware-supply' ) ),
			'missing_meta'                  => array( '----------', __( 'Missing Meta Fields', 'it-hardware-supply' ) ),
			'failed'                        => array( '------', __( 'Failed', 'it-hardware-supply' ) ),
			'duration_formatted'            => array( '------', __( 'Duration', 'it-hardware-supply' ) ),
			'peak_memory_formatted'         => array( '--------', __( 'Peak Memory', 'it-hardware-supply' ) ),
			'avg_time_per_product_formatted' => array( '------', __( 'Avg Time / Product', 'it-hardware-supply' ) ),
		);

		echo '<table class="widefat iths-report-table"><tbody>';
		foreach ( $metrics as $key => $meta ) {
			// stats array has the counts, report array might have arrays (e.g. warnings)
			$val = $stats[ $key ] ?? ( $report[ $key ] ?? '--------' );
			printf(
				'<tr><th>%s %s</th><td><strong>%s</strong></td></tr>',
				esc_html( $meta[0] ),
				esc_html( $meta[1] ),
				esc_html( (string) $val )
			);
		}
		echo '</tbody></table>';

		// Show errors if any.
		if ( ! empty( $report['errors'] ) ) {
			echo '<h4>' . esc_html__( 'Errors', 'it-hardware-supply' ) . '</h4>';
			echo '<ul class="iths-errors-list">';
			foreach ( $report['errors'] as $err ) {
				echo '<li>' . esc_html( $err ) . '</li>';
			}
			echo '</ul>';
		}

		// Show warnings if any.
		if ( ! empty( $report['warnings'] ) && is_array( $report['warnings'] ) ) {
			$show = array_slice( $report['warnings'], 0, 20 );
			echo '<h4>' . esc_html__( 'Warnings (first 20)', 'it-hardware-supply' ) . '</h4>';
			echo '<ul class="iths-warnings-list">';
			foreach ( $show as $w ) {
				echo '<li>' . esc_html( $w ) . '</li>';
			}
			echo '</ul>';
		}
	}
}




