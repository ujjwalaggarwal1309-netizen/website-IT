<?php
/**
 * Product Template Functions
 *
 * Reusable helper functions for the Single Product template.
 * Keeps business logic out of the view layer.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns translated label for enum values.
 *
 * @param string $value The raw database value.
 * @return string The translated frontend label.
 */
function iths_translate_enum( $value ) {
	$map = array(
		'carry_in'             => __( '90 Days', 'it-hardware-supply' ),
		'on_site'              => __( 'On-Site', 'it-hardware-supply' ),
		'depot'                => __( 'Depot', 'it-hardware-supply' ),
		'nbd'                  => __( 'Next Business Day', 'it-hardware-supply' ),
		'in_stock'             => __( 'In Stock', 'it-hardware-supply' ),
		'available_on_request' => __( 'Available on Request', 'it-hardware-supply' ),
		'end_of_life'          => __( 'End of Life', 'it-hardware-supply' ),
		'discontinued'         => __( 'Discontinued', 'it-hardware-supply' ),
		'new'                  => __( 'New', 'it-hardware-supply' ),
		'refurbished'          => __( 'Refurbished', 'it-hardware-supply' ),
		'used'                 => __( 'Used', 'it-hardware-supply' ),
		'open_box'             => __( 'Open Box', 'it-hardware-supply' ),
	);
	return $map[ $value ] ?? $value;
}

/**
 * Returns the label for a meta key, applying frontend renaming rules.
 *
 * @param string $key Meta key.
 * @return string
 */
function iths_get_frontend_label( $key ) {
	$labels = array(
		'iths_networking'        => __( 'Interface', 'it-hardware-supply' ),
		'iths_storage'           => __( 'Capacity', 'it-hardware-supply' ),
		'iths_warranty_duration' => __( 'Warranty', 'it-hardware-supply' ),
		'iths_processor'         => __( 'Processor', 'it-hardware-supply' ),
		'iths_memory'            => __( 'Memory', 'it-hardware-supply' ),
		'iths_raid'              => __( 'RAID', 'it-hardware-supply' ),
		'iths_form_factor'       => __( 'Form Factor', 'it-hardware-supply' ),
		'iths_power_supply'      => __( 'Power Supply', 'it-hardware-supply' ),
		'iths_warranty_type'     => __( 'Warranty Type', 'it-hardware-supply' ),
		'iths_availability'      => __( 'Availability', 'it-hardware-supply' ),
		'iths_condition'         => __( 'Condition', 'it-hardware-supply' ),
		'iths_oem_part_number'   => __( 'OEM Part Number', 'it-hardware-supply' ),
		'iths_model_number'      => __( 'Model Number', 'it-hardware-supply' ),
		'iths_product_type'      => __( 'Product Type', 'it-hardware-supply' ),
	);
	return $labels[ $key ] ?? $key;
}

/**
 * Gets grouped specifications for a product, omitting empty values.
 *
 * @param int $post_id Product post ID.
 * @return array Grouped specifications.
 */
function iths_get_grouped_specs( $post_id ) {
	$groups = array(
		'Hardware' => array( 'iths_processor', 'iths_memory', 'iths_storage', 'iths_networking', 'iths_raid' ),
		'Physical' => array( 'iths_form_factor', 'iths_power_supply' ),
		'Commercial' => array( 'iths_warranty_duration', 'iths_availability' ),
	);

	$result = array();

	foreach ( $groups as $group_name => $keys ) {
		$items = array();
		foreach ( $keys as $key ) {
			$val = get_post_meta( $post_id, $key, true );
			if ( ! empty( $val ) ) {
				$items[ $key ] = array(
					'label' => iths_get_frontend_label( $key ),
					'value' => iths_translate_enum( $val ),
				);
			}
		}
		if ( ! empty( $items ) ) {
			$result[ $group_name ] = $items;
		}
	}
	return $result;
}

/**
 * Extracts and cleans the compatibility list.
 *
 * @param int $post_id
 * @return array
 */
function iths_get_compatibility_list( $post_id ) {
	$raw = get_post_meta( $post_id, 'iths_compatibility', true );
	if ( empty( $raw ) ) {
		return array();
	}
	// Split by comma or newline
	$parts = preg_split( '/[,|\n]+/', $raw );
	$list = array();
	foreach ( $parts as $part ) {
		$p = trim( $part );
		if ( ! empty( $p ) ) {
			$list[] = $p;
		}
	}
	return $list;
}

/**
 * Extracts key features.
 *
 * @param int $post_id
 * @return array
 */
function iths_get_key_features( $post_id ) {
	$features = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$f = get_post_meta( $post_id, 'iths_key_feature_' . $i, true );
		if ( ! empty( $f ) ) {
			$features[] = $f;
		}
	}
	return $features;
}

/**
 * Extracts Applications as list.
 *
 * @param int $post_id
 * @return array
 */
function iths_get_applications_list( $post_id ) {
	$raw = get_post_meta( $post_id, 'iths_applications', true );
	if ( empty( $raw ) ) {
		return array();
	}
	$parts = preg_split( '/[,|\n]+/', $raw );
	$list = array();
	foreach ( $parts as $part ) {
		$p = trim( $part );
		if ( ! empty( $p ) ) {
			$list[] = $p;
		}
	}
	return $list;
}

/**
 * Renders the Trust Row.
 */
function iths_render_trust_row() {
	$items = array(
		__( 'PAN India Delivery', 'it-hardware-supply' ),
		__( 'Warranty Available', 'it-hardware-supply' ),
		__( 'Enterprise Support', 'it-hardware-supply' ),
	);
	echo '<ul class="iths-trust-row">';
	foreach ( $items as $item ) {
		echo '<li><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="trust-icon"><polyline points="20 6 9 17 4 12"></polyline></svg> ' . esc_html( $item ) . '</li>';
	}
	echo '</ul>';
}

/**
 * Renders the Hero Quick Facts.
 */
function iths_render_hero_quick_facts() {
	$items = array(
		array(
			'label' => __( 'Warranty Included', 'it-hardware-supply' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
		),
		array(
			'label' => __( 'PAN India Delivery', 'it-hardware-supply' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 4v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
		),
		array(
			'label' => __( 'GST Invoice', 'it-hardware-supply' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/></svg>',
		),
		array(
			'label' => __( 'Bulk Orders Accepted', 'it-hardware-supply' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
		),
	);
	echo '<ul class="iths-hero-quick-facts">';
	foreach ( $items as $item ) {
		echo '<li>' . $item['icon'] . '<span>' . esc_html( $item['label'] ) . '</span></li>';
	}
	echo '</ul>';
}

