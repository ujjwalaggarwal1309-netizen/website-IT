<?php
/**
 * Product Meta Fields — Admin Metabox
 *
 * Registers a structured admin metabox for all product meta fields.
 * Fields are stored as standard WordPress post meta (key/value pairs),
 * making them accessible to any future theme, plugin, or import script
 * without any modification.
 *
 * Field Groups:
 *   A. Basic Information   — model_number, oem_part_number, short_description, long_description,
 *                            product_type, condition, key_feature_1/2/3
 *   B. Technical Specs     — processor, memory, storage, raid, networking, form_factor,
 *                            power_supply, compatibility, warranty (legacy), warranty_duration,
 *                            warranty_type, availability
 *   C. Gallery Images      — _product_gallery (array of attachment IDs)
 *
 * Prefixed with 'iths_' (Infinity IT Hardware Supply) to avoid collisions.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── Field Schema ───────────────────────────────────────────────────────────── */

/**
 * Returns the canonical list of all registered product meta fields.
 * This single source of truth is used by:
 *   - the metabox renderer
 *   - the save handler
 *   - the CSV importer (future)
 *   - REST API registration
 *   - any frontend template that calls get_post_meta()
 *
 * @return array[]
 */
function it_hardware_get_product_fields() {
	return array(

		/* ── A. Basic Information ─────────────────────────────────────────── */
		array(
			'key'         => 'iths_model_number',
			'label'       => __( 'Model Number', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. PowerEdge R740',
			'description' => __( 'The manufacturer model number or product name.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_oem_part_number',
			'label'       => __( 'OEM Part Number', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. SNPCPC7GC/16G',
			'description' => __( 'Original Equipment Manufacturer part number. Used for exact-match sourcing.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_short_description',
			'label'       => __( 'Short Description', 'it-hardware-supply' ),
			'type'        => 'textarea',
			'group'       => 'basic',
			'rows'        => 3,
			'placeholder' => 'One-line summary shown on product cards.',
			'description' => __( 'Displayed on product listing cards. Keep under 160 characters.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_long_description',
			'label'       => __( 'Long Description', 'it-hardware-supply' ),
			'type'        => 'textarea',
			'group'       => 'basic',
			'rows'        => 6,
			'placeholder' => 'Detailed product description for the single product page.',
			'description' => __( 'Full product description. Shown on single product page below specifications.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_product_group',
			'label'       => __( 'Product Group', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. Storage / Memory / Networking',
			'description' => __( 'Mid-level product group used for filtering. Child of Core Category, parent of Product Type.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_product_type',
			'label'       => __( 'Product Type', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. SAS HDD / DDR4 RDIMM / Network Switch',
			'description' => __( 'Specific product type used for filtering and import mapping.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_condition',
			'label'       => __( 'Condition', 'it-hardware-supply' ),
			'type'        => 'select',
			'group'       => 'basic',
			'options'     => array(
				'new'            => __( 'New', 'it-hardware-supply' ),
				'refurbished'    => __( 'Refurbished', 'it-hardware-supply' ),
				'used'           => __( 'Used', 'it-hardware-supply' ),
				'open_box'       => __( 'Open Box', 'it-hardware-supply' ),
			),
			'description' => __( 'Physical condition of the unit being sold.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_key_feature_1',
			'label'       => __( 'Key Feature 1', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. Dual 10GbE Ports Included',
			'description' => __( 'First highlight shown as a bullet point on the product page.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_key_feature_2',
			'label'       => __( 'Key Feature 2', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. Hot-Plug Redundant PSU',
			'description' => __( 'Second highlight shown as a bullet point on the product page.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_key_feature_3',
			'label'       => __( 'Key Feature 3', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'basic',
			'placeholder' => 'e.g. PAN India Warranty Coverage',
			'description' => __( 'Third highlight shown as a bullet point on the product page.', 'it-hardware-supply' ),
		),

		/* ── B. Technical Specifications ──────────────────────────────────── */
		array(
			'key'         => 'iths_processor',
			'label'       => __( 'Processor', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. Intel Xeon Silver 4214R, 2.4GHz, 12 Cores',
			'description' => __( 'CPU model, speed, and core count.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_memory',
			'label'       => __( 'Memory', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 16GB DDR4 2666MHz RDIMM',
			'description' => __( 'RAM type, capacity, and speed.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_storage',
			'label'       => __( 'Storage', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 2× 600GB 10K SAS HDD',
			'description' => __( 'Storage drives included (type, capacity, RPM/interface).', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_raid',
			'label'       => __( 'RAID', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. PERC H730P Mini, RAID 0/1/5/6/10',
			'description' => __( 'RAID controller model and supported RAID levels.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_networking',
			'label'       => __( 'Networking', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 4× 1GbE LOM + 2× 10GbE SFP+',
			'description' => __( 'Onboard and addon network interfaces.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_form_factor',
			'label'       => __( 'Form Factor', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 2U Rack / Tower / Desktop',
			'description' => __( 'Physical form factor of the hardware.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_power_supply',
			'label'       => __( 'Power Supply', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 2× 750W Hot-Plug Redundant PSU',
			'description' => __( 'Power supply wattage, redundancy, and hot-plug support.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_compatibility',
			'label'       => __( 'Compatibility', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. Dell PowerEdge R740 / R740xd',
			'description' => __( 'Compatible systems or platforms. Important for spare parts.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_warranty',
			'label'       => __( 'Warranty (Legacy)', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 90 Days / 1 Year',
			'description' => __( 'Legacy single warranty field. Kept for backwards compatibility. Use Warranty Duration + Type for new entries.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_warranty_duration',
			'label'       => __( 'Warranty Duration', 'it-hardware-supply' ),
			'type'        => 'text',
			'group'       => 'specs',
			'placeholder' => 'e.g. 90 Days / 6 Months / 1 Year',
			'description' => __( 'How long the warranty lasts.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_warranty_type',
			'label'       => __( 'Warranty Type', 'it-hardware-supply' ),
			'type'        => 'select',
			'group'       => 'specs',
			'options'     => array(
				'carry_in'   => __( 'Carry-In', 'it-hardware-supply' ),
				'on_site'    => __( 'On-Site', 'it-hardware-supply' ),
				'depot'      => __( 'Depot', 'it-hardware-supply' ),
				'nbd'        => __( 'Next Business Day', 'it-hardware-supply' ),
			),
			'description' => __( 'Type of warranty service provided.', 'it-hardware-supply' ),
		),
		array(
			'key'         => 'iths_availability',
			'label'       => __( 'Availability', 'it-hardware-supply' ),
			'type'        => 'select',
			'group'       => 'specs',
			'options'     => array(
				'in_stock'           => __( 'In Stock', 'it-hardware-supply' ),
				'available_on_request' => __( 'Available on Request', 'it-hardware-supply' ),
				'end_of_life'        => __( 'End of Life', 'it-hardware-supply' ),
				'discontinued'       => __( 'Discontinued', 'it-hardware-supply' ),
			),
			'description' => __( 'Current availability status. Shown as a badge on product pages.', 'it-hardware-supply' ),
		),
	);
}

/* ── Metabox Registration ───────────────────────────────────────────────────── */

function it_hardware_register_product_metaboxes() {
	add_meta_box(
		'iths_basic_info',
		__( 'Basic Information', 'it-hardware-supply' ),
		'it_hardware_render_basic_info_metabox',
		'products',
		'normal',
		'high'
	);

	add_meta_box(
		'iths_tech_specs',
		__( 'Technical Specifications', 'it-hardware-supply' ),
		'it_hardware_render_tech_specs_metabox',
		'products',
		'normal',
		'high'
	);

	add_meta_box(
		'iths_gallery',
		__( 'Product Gallery Images', 'it-hardware-supply' ),
		'it_hardware_render_gallery_metabox',
		'products',
		'side',
		'low'
	);
}
add_action( 'add_meta_boxes', 'it_hardware_register_product_metaboxes' );

/* ── Metabox Renderers ──────────────────────────────────────────────────────── */

/**
 * Renders the Basic Information metabox.
 *
 * @param WP_Post $post Current post object.
 */
function it_hardware_render_basic_info_metabox( $post ) {
	wp_nonce_field( 'iths_save_product_meta', 'iths_product_meta_nonce' );
	$fields = array_filter( it_hardware_get_product_fields(), function( $f ) {
		return 'basic' === $f['group'];
	} );
	it_hardware_render_field_group( $post->ID, $fields );
}

/**
 * Renders the Technical Specifications metabox.
 *
 * @param WP_Post $post Current post object.
 */
function it_hardware_render_tech_specs_metabox( $post ) {
	$fields = array_filter( it_hardware_get_product_fields(), function( $f ) {
		return 'specs' === $f['group'];
	} );
	it_hardware_render_field_group( $post->ID, $fields );
}

/**
 * Renders the Gallery metabox.
 *
 * @param WP_Post $post Current post object.
 */
function it_hardware_render_gallery_metabox( $post ) {
	$gallery_ids = get_post_meta( $post->ID, '_product_gallery', true );
	if ( ! is_array( $gallery_ids ) ) {
		$gallery_ids = array();
	}
	$gallery_ids_string = implode( ',', $gallery_ids );
	?>
	<div id="iths-gallery-wrap">
		<input type="hidden" id="iths_gallery_ids" name="iths_gallery_ids" value="<?php echo esc_attr( $gallery_ids_string ); ?>">
		<div id="iths-gallery-preview" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;">
			<?php foreach ( $gallery_ids as $img_id ) : ?>
				<?php $thumb = wp_get_attachment_image_src( $img_id, 'thumbnail' ); ?>
				<?php if ( $thumb ) : ?>
					<img src="<?php echo esc_url( $thumb[0] ); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:4px;" alt="">
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<button type="button" id="iths-gallery-btn" class="button">
			<?php esc_html_e( 'Add / Edit Gallery Images', 'it-hardware-supply' ); ?>
		</button>
		<p class="description"><?php esc_html_e( 'Additional product images shown in the gallery on the single product page.', 'it-hardware-supply' ); ?></p>
	</div>
	<script>
	jQuery(function($){
		$('#iths-gallery-btn').on('click', function(){
			var frame = wp.media({
				title: '<?php esc_html_e( 'Select Gallery Images', 'it-hardware-supply' ); ?>',
				button: { text: '<?php esc_html_e( 'Add to Gallery', 'it-hardware-supply' ); ?>' },
				multiple: true
			});
			frame.on('select', function(){
				var attachments = frame.state().get('selection').toJSON();
				var ids = attachments.map(a => a.id);
				$('#iths_gallery_ids').val(ids.join(','));
				var preview = '';
				attachments.forEach(function(a){
					if (a.sizes && a.sizes.thumbnail) {
						preview += '<img src="'+a.sizes.thumbnail.url+'" style="width:60px;height:60px;object-fit:cover;border-radius:4px;" alt="">';
					}
				});
				$('#iths-gallery-preview').html(preview);
			});
			frame.open();
		});
	});
	</script>
	<?php
}

/**
 * Shared field group renderer.
 *
 * @param int   $post_id Post ID.
 * @param array $fields  Subset of fields from it_hardware_get_product_fields().
 */
function it_hardware_render_field_group( $post_id, $fields ) {
	echo '<table class="form-table" style="margin:0;">';
	foreach ( $fields as $field ) {
		$value = get_post_meta( $post_id, $field['key'], true );
		echo '<tr>';
		echo '<th style="width:180px;padding:12px 10px;"><label for="' . esc_attr( $field['key'] ) . '">' . esc_html( $field['label'] ) . '</label></th>';
		echo '<td style="padding:10px;">';

		switch ( $field['type'] ) {
			case 'textarea':
				printf(
					'<textarea id="%s" name="%s" rows="%d" placeholder="%s" style="width:100%%;font-family:monospace;">%s</textarea>',
					esc_attr( $field['key'] ),
					esc_attr( $field['key'] ),
					(int) ( $field['rows'] ?? 3 ),
					esc_attr( $field['placeholder'] ?? '' ),
					esc_textarea( $value )
				);
				break;

			case 'select':
				echo '<select id="' . esc_attr( $field['key'] ) . '" name="' . esc_attr( $field['key'] ) . '" style="width:100%;max-width:400px;">';
				echo '<option value="">' . esc_html__( '— Select —', 'it-hardware-supply' ) . '</option>';
				foreach ( $field['options'] as $opt_key => $opt_label ) {
					printf(
						'<option value="%s"%s>%s</option>',
						esc_attr( $opt_key ),
						selected( $value, $opt_key, false ),
						esc_html( $opt_label )
					);
				}
				echo '</select>';
				break;

			default: // text
				printf(
					'<input type="text" id="%s" name="%s" value="%s" placeholder="%s" style="width:100%%;max-width:500px;">',
					esc_attr( $field['key'] ),
					esc_attr( $field['key'] ),
					esc_attr( $value ),
					esc_attr( $field['placeholder'] ?? '' )
				);
				break;
		}

		if ( ! empty( $field['description'] ) ) {
			echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

/* ── Save Handler ───────────────────────────────────────────────────────────── */

/**
 * Saves all product meta fields when the post is saved.
 *
 * @param int $post_id The post ID being saved.
 */
function it_hardware_save_product_meta( $post_id ) {
	// Security checks
	if (
		! isset( $_POST['iths_product_meta_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['iths_product_meta_nonce'] ) ), 'iths_save_product_meta' )
	) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( 'products' !== get_post_type( $post_id ) ) {
		return;
	}

	// Save all registered text/textarea/select fields
	foreach ( it_hardware_get_product_fields() as $field ) {
		$key = $field['key'];
		if ( isset( $_POST[ $key ] ) ) {
			$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			update_post_meta( $post_id, $key, sanitize_textarea_field( $raw ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	// Save gallery image IDs
	if ( isset( $_POST['iths_gallery_ids'] ) ) {
		$raw_ids = sanitize_text_field( wp_unslash( $_POST['iths_gallery_ids'] ) );
		$ids     = array_filter( array_map( 'absint', explode( ',', $raw_ids ) ) );
		update_post_meta( $post_id, '_product_gallery', $ids );
	} else {
		delete_post_meta( $post_id, '_product_gallery' );
	}
}
add_action( 'save_post_products', 'it_hardware_save_product_meta' );

/* ── REST API Registration ──────────────────────────────────────────────────── */

/**
 * Exposes all product meta fields in the REST API.
 * Required for Gutenberg / block editor compatibility and future headless use.
 */
function it_hardware_register_product_meta_rest() {
	foreach ( it_hardware_get_product_fields() as $field ) {
		register_post_meta(
			'products',
			$field['key'],
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'string',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	// Gallery field (array of integers)
	register_post_meta(
		'products',
		'_product_gallery',
		array(
			'show_in_rest'  => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array( 'type' => 'integer' ),
				),
			),
			'single'        => true,
			'type'          => 'array',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'it_hardware_register_product_meta_rest' );

/* ── Legacy Helper (kept for backwards compatibility with templates) ─────────── */

/**
 * Renders the technical specs block for a given product.
 * Used by single-products.php template.
 *
 * @param int $post_id Product post ID.
 */
function it_hardware_render_product_fields( $post_id ) {
	$fields = array_filter( it_hardware_get_product_fields(), function( $f ) {
		return 'specs' === $f['group'];
	} );
	$meta   = array();
	foreach ( $fields as $field ) {
		$value = get_post_meta( $post_id, $field['key'], true );
		if ( ! empty( $value ) ) {
			$meta[] = '<div class="spec-item"><strong>' . esc_html( $field['label'] ) . ':</strong> <span>' . esc_html( $value ) . '</span></div>';
		}
	}
	if ( empty( $meta ) ) {
		return;
	}
	echo '<div class="product-specs"><h3>' . esc_html__( 'Specifications', 'it-hardware-supply' ) . '</h3>' . implode( '', $meta ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
}
