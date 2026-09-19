<?php
/**
 * Import Service Provider --- Bootstrap
 *
 * Single entry point for the Infinity Import Engine.
 * Loaded by functions.php (admin-only, with a single require_once).
 *
 * Responsibilities:
 *   1. Load Composer autoloader (PhpSpreadsheet)
 *   2. Require all import module files
 *   3. Register the iths_product_group meta field
 *   4. Register SEO meta fields with show_in_rest => true
 *   5. Register the admin menu
 *   6. Enqueue admin assets (import page only)
 *   7. Register all AJAX action hooks
 *
 * Converts to a standalone plugin later:
 *   Move this file + inc/import/ directory to a new plugin.
 *   Change the require_once paths. Zero other changes needed.
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ImportServiceProvider {

	/** @var string Absolute path to the import module directory. */
	private string $import_dir;

	/** @var string Absolute path to the theme root. */
	private string $theme_dir;

	public function __construct() {
		$this->theme_dir  = get_template_directory();
		$this->import_dir = $this->theme_dir . '/inc/import/';
	}

	/**
	 * Boot the import engine.
	 * Called once from functions.php, admin-only.
	 */
	public function boot(): void {
		$this->load_dependencies();
		$this->register_hooks();
	}

	// ------ Dependency Loading ------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function load_dependencies(): void {
		// Composer autoloader (PhpSpreadsheet + PSR-4 autoload for ITHS\Import\).
		$autoload = $this->theme_dir . '/inc/vendor/autoload.php';
		if ( file_exists( $autoload ) ) {
			require_once $autoload;
		} else {
			// Log a notice but don't crash --- CSV imports still work without it.
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-warning"><p>';
				printf(
					/* translators: %s: path */
					esc_html__( 'Infinity Import Engine: Composer autoloader not found at %s. Run composer install in the theme directory to enable .xlsx support.', 'it-hardware-supply' ),
					esc_html( $this->theme_dir . '/inc/vendor/autoload.php' )
				);
				echo '</p></div>';
			} );
		}

		// Load all import modules.
		$modules = array(
			'Helpers.php',
			'Logger.php',
			'Rollback.php',
			'Mapper.php',
			'Validator.php',
			'Parser.php',
			'BatchProcessor.php',
			'Importer.php',
			'AdminPage.php',
			'Ajax.php',
		);

		foreach ( $modules as $module ) {
			require_once $this->import_dir . $module;
		}
	}

	// ------ Hook Registration ---------------------------------------------------------------------------------------------------------------------------------------------------------------

	private function register_hooks(): void {
		add_action( 'init',                  array( $this, 'register_product_group_meta' ) );
		add_action( 'init',                  array( $this, 'register_seo_meta' ) );
		add_action( 'admin_menu',            array( AdminPage::class, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// AJAX handlers (admin-only --- all use wp_ajax_ prefix).
		$actions = array(
			'iths_import_preview'  => array( Ajax::class, 'preview' ),
			'iths_import_dry_run'  => array( Ajax::class, 'dry_run' ),
			'iths_import_start'    => array( Ajax::class, 'start_import' ),
			'iths_import_batch'    => array( Ajax::class, 'process_batch' ),
			'iths_import_finalize' => array( Ajax::class, 'finalize' ),
			'iths_import_progress' => array( Ajax::class, 'progress' ),
			'iths_import_rollback' => array( Ajax::class, 'rollback' ),
			'iths_release_lock'    => array( Ajax::class, 'release_lock' ),
			'iths_export_log'      => array( Ajax::class, 'export_log' ),
		);

		foreach ( $actions as $action => $callback ) {
			add_action( 'wp_ajax_' . $action, $callback );
		}
	}

	// ------ Meta Registration ---------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Registers iths_product_group as a post meta field.
	 * This is a backward-compatible addition to the existing product fields.
	 * The 'iths_product_type' field already exists --- this adds the missing group tier.
	 */
	public function register_product_group_meta(): void {
		register_post_meta(
			'products',
			'iths_product_group',
			array(
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
				'description'       => 'Product Group --- mid-level grouping (e.g. Storage, Memory, Networking). Child of Core Category, parent of Product Type.',
			)
		);
	}

	/**
	 * Registers the three SEO meta fields with REST API support.
	 * Enables Gutenberg, REST API, and future headless access to these fields.
	 */
	public function register_seo_meta(): void {
		$seo_fields = array(
			'iths_seo_title'       => 'SEO page title for search engine result pages.',
			'iths_meta_description' => 'SEO meta description shown in search engine results.',
			'iths_focus_keyword'   => 'Primary focus keyword for SEO optimisation.',
		);

		foreach ( $seo_fields as $key => $description ) {
			register_post_meta(
				'products',
				$key,
				array(
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
					'description'       => $description,
				)
			);
		}

		// Additional import-specific meta fields (also REST-enabled).
		$import_fields = array(
			'iths_hero_image'          => 'Hero image filename (e.g. dell-r740-front.webp). No upload --- filename reference only.',
			'iths_gallery_image_1'     => 'Gallery image 1 filename.',
			'iths_applications'        => 'Typical use cases and application environments for this product.',
			'iths_qa_score'            => 'QA Score assigned during Sprint 2C production QA audit.',
			'iths_qa_status'           => 'QA Status: Approved, Approved With Minor Issues, Needs Review, Do Not Import.',
			'iths_import_session_id'   => 'ID of the import session that last created or updated this product.',
			'iths_series'              => 'Product series or product line (e.g. ProLiant Gen10, PowerEdge 14G).',
		);

		foreach ( $import_fields as $key => $description ) {
			register_post_meta(
				'products',
				$key,
				array(
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_textarea_field',
					'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
					'description'       => $description,
				)
			);
		}

		// Revision history --- array type, not a string.
		register_post_meta(
			'products',
			'iths_revision_history',
			array(
				'show_in_rest'      => array(
					'schema' => array(
						'type'  => 'array',
						'items' => array( 'type' => 'object' ),
					),
				),
				'single'            => true,
				'type'              => 'array',
				'auth_callback'     => fn() => current_user_can( 'edit_posts' ),
				'description'       => 'Append-only revision history. Each entry records changed meta values, session ID, timestamp, and user ID.',
			)
		);
	}

	// ------ Asset Enqueuing ---------------------------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Enqueues the import admin CSS and JS --- only on the Import Products page.
	 *
	 * @param string $hook Current admin page hook suffix.
	 */
	public function enqueue_assets( string $hook ): void {
		// Only load on the import admin page.
		if ( false === strpos( $hook, AdminPage::PAGE_SLUG ) ) {
			return;
		}

		$theme_uri = get_template_directory_uri();
		$version   = wp_get_theme()->get( 'Version' );

		wp_enqueue_style(
			'iths-import-admin',
			$theme_uri . '/assets/css/import-admin.css',
			array(),
			$version
		);

		wp_enqueue_script(
			'iths-import-admin',
			$theme_uri . '/assets/js/import-admin.js',
			array( 'jquery' ),
			$version,
			true // Load in footer.
		);

		// Pass AJAX URL and nonce to JS.
		wp_localize_script(
			'iths-import-admin',
			'ithsImport',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'iths_import_action' ),
				'i18n'    => array(
					'confirmImport'  => __( 'Start the import now? This will write products to the database. Run a Dry Run first if you haven\'t already.', 'it-hardware-supply' ),
					'confirmRollback' => __( 'Are you sure you want to rollback? This will permanently delete created products and restore updated products to their previous values.', 'it-hardware-supply' ),
					'importing'      => __( 'Importing---', 'it-hardware-supply' ),
					'complete'       => __( 'Import Complete', 'it-hardware-supply' ),
					'dryRunLabel'    => __( 'Dry Run Complete --- No data was written.', 'it-hardware-supply' ),
					'batchProgress'  => __( 'Processing batch {done} of {total}---', 'it-hardware-supply' ),
				),
			)
		);
	}
}

