<?php
/**
 * Import Admin Page
 *
 * Renders the "Infinity Products --- Import Products" admin UI.
 *
 * Tabs:
 *   1. Upload & Preview --- File upload, preview table, Dry Run, Start Import
 *   2. Import History   --- Past session log table with download links
 *   3. Rollback         --- Undo the most recent import session
 *   4. Settings         --- Batch size, auto-create terms, default availability
 *
 * No frontend code is modified. All output is contained within wp-admin.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminPage {

	/** @var string Slug for the admin page. */
	const PAGE_SLUG = 'iths-import-products';

	/** @var string Settings option key. */
	const SETTINGS_KEY = 'iths_import_settings';

	/** @var string Parent menu slug (Infinity Products CPT). */
	const PARENT_SLUG = 'edit.php?post_type=products';

	// ------ Menu Registration ---------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function register_menu(): void {
		add_submenu_page(
			self::PARENT_SLUG,
			__( 'Import Products', 'it-hardware-supply' ),
			__( 'Import Products', 'it-hardware-supply' ),
			'manage_options',
			self::PAGE_SLUG,
			array( static::class, 'render_page' )
		);
	}

	// ------ Main Page ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	public static function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'it-hardware-supply' ) );
		}

		$active_tab = sanitize_key( $_GET['tab'] ?? 'upload' );
		$valid_tabs = array( 'upload', 'history', 'rollback', 'settings' );
		if ( ! in_array( $active_tab, $valid_tabs, true ) ) {
			$active_tab = 'upload';
		}

		// Handle settings save.
		if ( 'settings' === $active_tab && isset( $_POST['iths_save_settings'] ) ) {
			self::save_settings();
		}

		$page_url = admin_url( 'edit.php?post_type=products&page=' . self::PAGE_SLUG );
		?>
		<div class="wrap iths-import-wrap">
			<h1><?php esc_html_e( 'Infinity Import Engine', 'it-hardware-supply' ); ?> <span class="iths-badge">v1.0</span></h1>

			<?php self::render_lock_notice(); ?>

			<nav class="nav-tab-wrapper">
				<?php
				$tabs = array(
					'upload'   => __( '---- Upload &amp; Preview', 'it-hardware-supply' ),
					'history'  => __( '---- Import History', 'it-hardware-supply' ),
					'rollback' => __( '--- Rollback', 'it-hardware-supply' ),
					'settings' => __( '--- Settings', 'it-hardware-supply' ),
				);
				foreach ( $tabs as $slug => $label ) {
					printf(
						'<a href="%s" class="nav-tab%s">%s</a>',
						esc_url( $page_url . '&tab=' . $slug ),
						$active_tab === $slug ? ' nav-tab-active' : '',
						$label // Already translated and contains safe HTML.
					);
				}
				?>
			</nav>

			<div class="iths-tab-content">
				<?php
				switch ( $active_tab ) {
					case 'upload':
						self::render_upload_tab();
						break;
					case 'history':
						self::render_history_tab();
						break;
					case 'rollback':
						self::render_rollback_tab();
						break;
					case 'settings':
						self::render_settings_tab();
						break;
				}
				?>
			</div>
		</div>
		<?php
	}

	// ------ Tab: Upload & Preview ---------------------------------------------------------------------------------------------------------------------------------------------------

	private static function render_upload_tab(): void {
		?>
		<div class="iths-card">
			<h2><?php esc_html_e( 'Upload Product Database', 'it-hardware-supply' ); ?></h2>
			<p><?php esc_html_e( 'Upload your approved Master Product Database (.xlsx or .csv). The importer will preview the changes before writing to the database.', 'it-hardware-supply' ); ?></p>

			<form id="iths-upload-form" enctype="multipart/form-data">
				<?php wp_nonce_field( 'iths_import_action', 'iths_import_nonce' ); ?>
				<table class="form-table">
					<tr>
						<th><label for="iths-file"><?php esc_html_e( 'Product Database File', 'it-hardware-supply' ); ?></label></th>
						<td>
							<input type="file" id="iths-file" name="iths_file" accept=".xlsx,.csv" required>
							<p class="description"><?php esc_html_e( 'Accepted formats: .xlsx (recommended), .csv', 'it-hardware-supply' ); ?></p>
						</td>
					</tr>
				</table>
				<div class="iths-actions">
					<button type="button" id="iths-btn-preview" class="button button-secondary">
						<?php esc_html_e( '---- Preview Import', 'it-hardware-supply' ); ?>
					</button>
					<button type="button" id="iths-btn-dry-run" class="button button-secondary" disabled>
						<?php esc_html_e( '---- Dry Run', 'it-hardware-supply' ); ?>
					</button>
					<button type="button" id="iths-btn-import" class="button button-primary" disabled>
						<?php esc_html_e( '---- Start Import', 'it-hardware-supply' ); ?>
					</button>
				</div>
			</form>
		</div>

		<!-- Preview Summary Bar -->
		<div id="iths-preview-summary" class="iths-card" style="display:none">
			<h3><?php esc_html_e( 'Import Preview', 'it-hardware-supply' ); ?></h3>
			<div class="iths-stat-bar">
				<span class="iths-stat create"><span id="iths-count-create">0</span> <?php esc_html_e( 'Create', 'it-hardware-supply' ); ?></span>
				<span class="iths-stat update"><span id="iths-count-update">0</span> <?php esc_html_e( 'Update', 'it-hardware-supply' ); ?></span>
				<span class="iths-stat skip"><span id="iths-count-skip">0</span> <?php esc_html_e( 'Skip', 'it-hardware-supply' ); ?></span>
				<span class="iths-stat warning"><span id="iths-count-warning">0</span> <?php esc_html_e( 'Warnings', 'it-hardware-supply' ); ?></span>
			</div>
			<div id="iths-preview-table-wrap"></div>
		</div>

		<!-- Progress Section -->
		<div id="iths-progress-wrap" class="iths-card" style="display:none">
			<h3 id="iths-progress-label"><?php esc_html_e( 'Importing---', 'it-hardware-supply' ); ?></h3>
			<div class="iths-progress-bar-outer">
				<div class="iths-progress-bar-inner" id="iths-progress-bar" style="width:0%"></div>
			</div>
			<p id="iths-progress-text">0%</p>
			<ul id="iths-progress-log" class="iths-log-list"></ul>
		</div>

		<!-- Final Report -->
		<div id="iths-report-wrap" class="iths-card" style="display:none">
			<h3><?php esc_html_e( '--- Import Complete', 'it-hardware-supply' ); ?></h3>
			<div id="iths-report-content"></div>
		</div>
		<?php
	}

	// ------ Tab: Import History ---------------------------------------------------------------------------------------------------------------------------------------------------------

	private static function render_history_tab(): void {
		$logs = Logger::get_all_logs();
		?>
		<div class="iths-card">
			<h2><?php esc_html_e( 'Import History', 'it-hardware-supply' ); ?></h2>
			<?php if ( empty( $logs ) ) : ?>
				<p><?php esc_html_e( 'No import sessions recorded yet.', 'it-hardware-supply' ); ?></p>
			<?php else : ?>
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Session ID', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'File', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'User', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Date', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Type', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Created', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Updated', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Skipped', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Failed', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Duration', 'it-hardware-supply' ); ?></th>
							<th><?php esc_html_e( 'Actions', 'it-hardware-supply' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $logs as $log ) : ?>
							<tr>
								<td><code style="font-size:11px"><?php echo esc_html( substr( $log['session_id'] ?? '', 12 ) ); ?></code></td>
								<td><?php echo esc_html( $log['filename'] ?? '---' ); ?></td>
								<td><?php echo esc_html( $log['user_name'] ?? '---' ); ?></td>
								<td><?php echo esc_html( wp_date( 'd M Y H:i', strtotime( $log['started_at'] ?? '' ) ) ); ?></td>
								<td>
									<?php if ( ! empty( $log['is_dry_run'] ) ) : ?>
										<span class="iths-badge-dry"><?php esc_html_e( 'Dry Run', 'it-hardware-supply' ); ?></span>
									<?php else : ?>
										<span class="iths-badge-live"><?php esc_html_e( 'Live', 'it-hardware-supply' ); ?></span>
									<?php endif; ?>
								</td>
								<td><?php echo (int) ( $log['stats']['created'] ?? 0 ); ?></td>
								<td><?php echo (int) ( $log['stats']['updated'] ?? 0 ); ?></td>
								<td><?php echo (int) ( $log['stats']['skipped'] ?? 0 ); ?></td>
								<td><?php echo (int) ( $log['stats']['failed']  ?? 0 ); ?></td>
								<td><?php echo esc_html( $log['stats']['duration_formatted'] ?? '---' ); ?></td>
								<td>
									<a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=iths_export_log&session_id=' . urlencode( $log['session_id'] ?? '' ) . '&_wpnonce=' . wp_create_nonce( 'iths_export_log' ) ) ); ?>" class="button button-small">
										<?php esc_html_e( '--- CSV', 'it-hardware-supply' ); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}

	// ------ Tab: Rollback ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	private static function render_rollback_tab(): void {
		$latest = Rollback::get_latest_session();
		?>
		<div class="iths-card">
			<h2><?php esc_html_e( 'Rollback Last Import', 'it-hardware-supply' ); ?></h2>
			<?php if ( ! $latest ) : ?>
				<p><?php esc_html_e( 'No rollback snapshots available. Rollback data is only retained for the most recent import session.', 'it-hardware-supply' ); ?></p>
			<?php else : ?>
				<p>
					<?php
					printf(
						/* translators: 1: count, 2: session id, 3: timestamp */
						esc_html__( 'The most recent snapshot covers %1$d product(s) from session %2$s (saved at %3$s).', 'it-hardware-supply' ),
						(int) ( $latest['count'] ?? 0 ),
						'<code>' . esc_html( $latest['session_id'] ?? '' ) . '</code>',
						esc_html( $latest['saved_at'] ?? '' )
					);
					?>
				</p>
				<p class="description" style="color:#d63638">
					<?php esc_html_e( '--- Warning: This will permanently delete all newly created products from this session and restore any updated products to their previous values.', 'it-hardware-supply' ); ?>
				</p>
				<button type="button" id="iths-btn-rollback" class="button button-secondary"
					data-session="<?php echo esc_attr( $latest['session_id'] ?? '' ); ?>">
					<?php esc_html_e( '--- Rollback This Session', 'it-hardware-supply' ); ?>
				</button>
				<div id="iths-rollback-result" style="margin-top:12px"></div>
			<?php endif; ?>
		</div>
		<?php
	}

	// ------ Tab: Settings ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	private static function render_settings_tab(): void {
		$settings   = self::get_settings();
		$batch_size = (int) ( $settings['batch_size'] ?? ITHS_IMPORT_BATCH_SIZE );
		$auto_terms = (bool) ( $settings['auto_create_terms'] ?? true );
		$def_status = $settings['default_post_status'] ?? 'draft';
		$def_avail  = $settings['default_availability'] ?? 'available_on_request';
		$img_subdir = $settings['image_subdir'] ?? ITHS_IMPORT_IMAGE_SUBDIR;
		?>
		<div class="iths-card">
			<h2><?php esc_html_e( 'Import Settings', 'it-hardware-supply' ); ?></h2>
			<form method="post">
				<?php wp_nonce_field( 'iths_save_settings', 'iths_settings_nonce' ); ?>
				<input type="hidden" name="iths_save_settings" value="1">
				<table class="form-table">
					<tr>
						<th><label for="iths-batch-size"><?php esc_html_e( 'Batch Size', 'it-hardware-supply' ); ?></label></th>
						<td>
							<input type="number" id="iths-batch-size" name="batch_size" value="<?php echo esc_attr( $batch_size ); ?>" min="1" max="100" style="width:80px">
							<p class="description"><?php esc_html_e( 'Products per batch (default: 25). Reduce if your server times out.', 'it-hardware-supply' ); ?></p>
						</td>
					</tr>
					<tr>
						<th><label for="iths-auto-terms"><?php esc_html_e( 'Auto-Create Terms', 'it-hardware-supply' ); ?></label></th>
						<td>
							<label>
								<input type="checkbox" id="iths-auto-terms" name="auto_create_terms" value="1" <?php checked( $auto_terms ); ?>>
								<?php esc_html_e( 'Automatically create missing taxonomy terms (Brand, Category) during import.', 'it-hardware-supply' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><label for="iths-def-status"><?php esc_html_e( 'Default Post Status', 'it-hardware-supply' ); ?></label></th>
						<td>
							<select id="iths-def-status" name="default_post_status">
								<option value="draft" <?php selected( $def_status, 'draft' ); ?>><?php esc_html_e( 'Draft (Recommended)', 'it-hardware-supply' ); ?></option>
								<option value="publish" <?php selected( $def_status, 'publish' ); ?>><?php esc_html_e( 'Published', 'it-hardware-supply' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="iths-img-subdir"><?php esc_html_e( 'Hero Image Subdirectory', 'it-hardware-supply' ); ?></label></th>
						<td>
							<code><?php echo esc_html( wp_upload_dir()['basedir'] ); ?>/</code>
							<input type="text" id="iths-img-subdir" name="image_subdir" value="<?php echo esc_attr( $img_subdir ); ?>" style="width:200px">
							<p class="description"><?php esc_html_e( 'Subdirectory of wp-content/uploads/ where hero images are stored.', 'it-hardware-supply' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Save Settings', 'it-hardware-supply' ) ); ?>
			</form>

			<?php if ( Importer::get_lock() ) : ?>
				<hr>
				<h3><?php esc_html_e( 'Import Lock', 'it-hardware-supply' ); ?></h3>
				<?php $lock = Importer::get_lock(); ?>
				<p>
					<?php
					printf(
						esc_html__( 'Lock held by %1$s since %2$s.', 'it-hardware-supply' ),
						esc_html( $lock['user_name'] ?? 'Unknown' ),
						esc_html( $lock['started_at'] ?? '' )
					);
					?>
				</p>
				<button type="button" id="iths-btn-release-lock" class="button button-secondary">
					<?php esc_html_e( 'Force Release Lock', 'it-hardware-supply' ); ?>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	// ------ Helpers ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	private static function render_lock_notice(): void {
		$lock = Importer::get_lock();
		if ( ! $lock ) {
			return;
		}
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			sprintf(
				/* translators: 1: user name, 2: start time */
				esc_html__( '--- An import is currently in progress (started by %1$s at %2$s). Dry Run is still available.', 'it-hardware-supply' ),
				esc_html( $lock['user_name'] ?? 'Unknown' ),
				esc_html( $lock['started_at'] ?? '' )
			)
		);
	}

	// ------ Settings ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

	private static function save_settings(): void {
		if (
			! isset( $_POST['iths_settings_nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iths_settings_nonce'] ) ), 'iths_save_settings' )
		) {
			return;
		}

		$settings = array(
			'batch_size'          => max( 1, min( 100, (int) ( $_POST['batch_size'] ?? 25 ) ) ),
			'auto_create_terms'   => ! empty( $_POST['auto_create_terms'] ),
			'default_post_status' => in_array( $_POST['default_post_status'] ?? '', array( 'draft', 'publish' ), true )
				? sanitize_key( $_POST['default_post_status'] )
				: 'draft',
			'image_subdir'        => sanitize_text_field( wp_unslash( $_POST['image_subdir'] ?? 'products' ) ),
		);

		update_option( self::SETTINGS_KEY, $settings );
		add_settings_error( 'iths_settings', 'saved', __( 'Settings saved.', 'it-hardware-supply' ), 'success' );
		settings_errors( 'iths_settings' );
	}

	/**
	 * Returns current import settings with defaults.
	 *
	 * @return array
	 */
	public static function get_settings(): array {
		$defaults = array(
			'batch_size'          => ITHS_IMPORT_BATCH_SIZE,
			'auto_create_terms'   => true,
			'default_post_status' => 'draft',
			'default_availability' => 'available_on_request',
			'image_subdir'        => ITHS_IMPORT_IMAGE_SUBDIR,
		);

		$saved = get_option( self::SETTINGS_KEY, array() );
		return array_merge( $defaults, is_array( $saved ) ? $saved : array() );
	}
}

