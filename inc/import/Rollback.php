<?php
/**
 * Import Rollback Engine
 *
 * Records a pre-import snapshot of every product touched during a session,
 * then uses that snapshot to fully undo the import if requested.
 *
 * Snapshot storage: wp_options key 'iths_rollback_{session_id}'
 * Each entry records:
 *   - action: 'created' | 'updated'
 *   - post_id: WordPress post ID
 *   - old_meta: snapshot of all iths_* meta values before update (updates only)
 *   - old_terms: snapshot of taxonomy terms before update (updates only)
 *
 * Rollback behaviour:
 *   - 'created' posts --- permanently deleted (wp_delete_post)
 *   - 'updated' posts --- all meta values and taxonomy assignments restored
 *
 * @package ITHS\Import
 */

namespace ITHS\Import;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rollback {

	/** @var string Prefix for rollback option keys. */
	const OPTION_PREFIX = 'iths_rollback_';

	/** @var string Rollback index option key (tracks all session IDs). */
	const INDEX_KEY = 'iths_rollback_index';

	/** @var string Current session ID. */
	private string $session_id;

	/** @var array Snapshot entries for the current session. */
	private array $entries = array();

	/**
	 * Initialises a rollback tracker for a new import session.
	 *
	 * @param string $session_id
	 */
	public function __construct( string $session_id ) {
		$this->session_id = $session_id;
	}

	/**
	 * Records a newly created post so it can be deleted on rollback.
	 *
	 * @param int    $post_id  WordPress post ID.
	 * @param string $name     Product name (for display in rollback UI).
	 */
	public function record_create( int $post_id, string $name = '' ): void {
		$this->entries[] = array(
			'action'   => 'created',
			'post_id'  => $post_id,
			'name'     => $name,
			'old_meta' => array(),
			'old_terms' => array(),
		);
	}

	/**
	 * Records an updated post, snapshotting all relevant meta and taxonomy
	 * assignments before changes were written.
	 *
	 * @param int    $post_id      WordPress post ID.
	 * @param string $name         Product name.
	 * @param array  $old_meta     Snapshot of iths_* meta keys => values.
	 * @param array  $old_terms    Snapshot of taxonomy => term_ids[].
	 */
	public function record_update( int $post_id, string $name, array $old_meta, array $old_terms ): void {
		$this->entries[] = array(
			'action'    => 'updated',
			'post_id'   => $post_id,
			'name'      => $name,
			'old_meta'  => $old_meta,
			'old_terms' => $old_terms,
		);
	}

	/**
	 * Persists the current session snapshot to the database.
	 * Should be called at the end of a successful import session.
	 */
	public function save(): void {
		if ( empty( $this->entries ) ) {
			return;
		}

		$snapshot = array(
			'session_id' => $this->session_id,
			'saved_at'   => current_time( 'c' ),
			'entries'    => $this->entries,
		);

		update_option( self::OPTION_PREFIX . $this->session_id, $snapshot, false );

		// Update the index of available rollback sessions.
		$index   = get_option( self::INDEX_KEY, array() );
		$index[] = array(
			'session_id' => $this->session_id,
			'saved_at'   => $snapshot['saved_at'],
			'count'      => count( $this->entries ),
		);
		update_option( self::INDEX_KEY, $index, false );
	}

	// ------ Static Execution Methods ---------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * Executes the rollback for a given session ID.
	 *
	 * @param string $session_id Session to roll back.
	 * @return array Result: { success: bool, deleted: int, restored: int, errors: string[] }
	 */
	public static function execute( string $session_id ): array {
		$snapshot = get_option( self::OPTION_PREFIX . $session_id );

		if ( ! $snapshot || empty( $snapshot['entries'] ) ) {
			return array(
				'success'  => false,
				'deleted'  => 0,
				'restored' => 0,
				'errors'   => array( 'Rollback snapshot not found for session: ' . $session_id ),
			);
		}

		$deleted  = 0;
		$restored = 0;
		$errors   = array();

		foreach ( $snapshot['entries'] as $entry ) {
			$post_id = (int) ( $entry['post_id'] ?? 0 );
			if ( ! $post_id ) {
				continue;
			}

			if ( 'created' === $entry['action'] ) {
				// Hard-delete the post --- it didn't exist before the import.
				$result = wp_delete_post( $post_id, true );
				if ( $result ) {
					$deleted++;
				} else {
					$errors[] = "Failed to delete post ID {$post_id} ({$entry['name']}).";
				}
			} elseif ( 'updated' === $entry['action'] ) {
				// Restore old meta values.
				foreach ( $entry['old_meta'] as $meta_key => $old_value ) {
					update_post_meta( $post_id, $meta_key, $old_value );
				}

				// Restore old taxonomy assignments.
				foreach ( $entry['old_terms'] as $taxonomy => $term_ids ) {
					$term_ids = array_map( 'intval', (array) $term_ids );
					wp_set_object_terms( $post_id, $term_ids, $taxonomy );
				}

				$restored++;
			}
		}

		// Clean up the snapshot.
		self::clear_session( $session_id );

		return array(
			'success'  => empty( $errors ),
			'deleted'  => $deleted,
			'restored' => $restored,
			'errors'   => $errors,
		);
	}

	/**
	 * Returns all available rollback sessions (most recent first).
	 *
	 * @return array[]
	 */
	public static function get_sessions(): array {
		$index = get_option( self::INDEX_KEY, array() );
		if ( ! is_array( $index ) ) {
			return array();
		}
		return array_reverse( $index );
	}

	/**
	 * Returns the most recent available rollback session, or null.
	 *
	 * @return array|null
	 */
	public static function get_latest_session(): ?array {
		$sessions = self::get_sessions();
		return ! empty( $sessions ) ? $sessions[0] : null;
	}

	/**
	 * Deletes the rollback snapshot for a specific session.
	 *
	 * @param string $session_id
	 */
	public static function clear_session( string $session_id ): void {
		delete_option( self::OPTION_PREFIX . $session_id );

		$index = get_option( self::INDEX_KEY, array() );
		if ( is_array( $index ) ) {
			$index = array_filter( $index, fn( $i ) => ( $i['session_id'] ?? '' ) !== $session_id );
			update_option( self::INDEX_KEY, array_values( $index ), false );
		}
	}

	/**
	 * Takes a snapshot of all iths_* meta fields for a post (before update).
	 *
	 * @param int $post_id
	 * @return array<string, mixed>
	 */
	public static function snapshot_meta( int $post_id ): array {
		// Get all registered product fields.
		$fields   = function_exists( 'it_hardware_get_product_fields' )
			? it_hardware_get_product_fields()
			: array();

		// Add import-specific fields.
		$extra_keys = array(
			'iths_product_group',
			'iths_applications',
			'iths_hero_image',
			'iths_gallery_image_1',
			'iths_seo_title',
			'iths_meta_description',
			'iths_focus_keyword',
			'iths_qa_score',
			'iths_qa_status',
			'iths_import_session_id',
			'iths_revision_history',
		);

		$snapshot = array();

		foreach ( $fields as $field ) {
			$key              = $field['key'];
			$snapshot[ $key ] = get_post_meta( $post_id, $key, true );
		}

		foreach ( $extra_keys as $key ) {
			if ( ! isset( $snapshot[ $key ] ) ) {
				$snapshot[ $key ] = get_post_meta( $post_id, $key, true );
			}
		}

		return $snapshot;
	}

	/**
	 * Takes a snapshot of taxonomy term assignments for a post.
	 *
	 * @param int $post_id
	 * @return array<string, int[]>
	 */
	public static function snapshot_terms( int $post_id ): array {
		$taxonomies = array( 'product-category', 'product-brand' );
		$snapshot   = array();

		foreach ( $taxonomies as $taxonomy ) {
			$terms                 = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'ids' ) );
			$snapshot[ $taxonomy ] = ! is_wp_error( $terms ) ? $terms : array();
		}

		return $snapshot;
	}
}

