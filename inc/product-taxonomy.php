<?php
/**
 * Product Taxonomies
 *
 * Registers two taxonomies:
 *   1. product-category  — Hierarchical (like WP categories). Seeded with the 5
 *                          verified primary categories from VERIFIED_COMPANY_FACTS.md.
 *   2. product-brand     — Flat (like WP tags). Brand terms created during import.
 *
 * Primary categories (matches homepage category-card.php):
 *   1. Enterprise Servers
 *   2. Storage Solutions
 *   3. Professional Workstations
 *   4. Business Desktops
 *   5. Server Spare Parts
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ── 1. Product Category Taxonomy ──────────────────────────────────────────── */

function it_hardware_register_product_taxonomy() {
	register_taxonomy(
		'product-category',
		array( 'products' ),
		array(
			'hierarchical'       => true,
			'labels'             => array(
				'name'              => __( 'Product Categories', 'it-hardware-supply' ),
				'singular_name'     => __( 'Product Category', 'it-hardware-supply' ),
				'search_items'      => __( 'Search Categories', 'it-hardware-supply' ),
				'all_items'         => __( 'All Categories', 'it-hardware-supply' ),
				'parent_item'       => __( 'Parent Category', 'it-hardware-supply' ),
				'parent_item_colon' => __( 'Parent Category:', 'it-hardware-supply' ),
				'edit_item'         => __( 'Edit Category', 'it-hardware-supply' ),
				'update_item'       => __( 'Update Category', 'it-hardware-supply' ),
				'add_new_item'      => __( 'Add New Category', 'it-hardware-supply' ),
				'new_item_name'     => __( 'New Category Name', 'it-hardware-supply' ),
				'menu_name'         => __( 'Categories', 'it-hardware-supply' ),
				'view_item'         => __( 'View Category', 'it-hardware-supply' ),
				'not_found'         => __( 'No categories found.', 'it-hardware-supply' ),
				'back_to_items'     => __( '← Back to Categories', 'it-hardware-supply' ),
			),
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rest_base'          => 'product-categories',
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'product-category',
				'with_front' => false,
			),
		)
	);

	/* Seed the 5 verified primary categories (idempotent — safe to run every load) */
	$primary_categories = array(
		array( 'name' => 'Enterprise Servers',         'slug' => 'enterprise-servers' ),
		array( 'name' => 'Storage Solutions',           'slug' => 'storage-solutions' ),
		array( 'name' => 'Professional Workstations',   'slug' => 'professional-workstations' ),
		array( 'name' => 'Business Desktops',           'slug' => 'business-desktops' ),
		array( 'name' => 'Server Spare Parts',          'slug' => 'server-spare-parts' ),
	);

	foreach ( $primary_categories as $cat ) {
		if ( ! term_exists( $cat['slug'], 'product-category' ) ) {
			wp_insert_term(
				$cat['name'],
				'product-category',
				array( 'slug' => $cat['slug'] )
			);
		}
	}
}
add_action( 'init', 'it_hardware_register_product_taxonomy', 5 );

/* ── 2. Product Brand Taxonomy ─────────────────────────────────────────────── */

function it_hardware_register_product_brand_taxonomy() {
	register_taxonomy(
		'product-brand',
		array( 'products' ),
		array(
			'hierarchical'       => false,   // Flat — like tags
			'labels'             => array(
				'name'          => __( 'Brands', 'it-hardware-supply' ),
				'singular_name' => __( 'Brand', 'it-hardware-supply' ),
				'search_items'  => __( 'Search Brands', 'it-hardware-supply' ),
				'all_items'     => __( 'All Brands', 'it-hardware-supply' ),
				'edit_item'     => __( 'Edit Brand', 'it-hardware-supply' ),
				'update_item'   => __( 'Update Brand', 'it-hardware-supply' ),
				'add_new_item'  => __( 'Add New Brand', 'it-hardware-supply' ),
				'new_item_name' => __( 'New Brand Name', 'it-hardware-supply' ),
				'menu_name'     => __( 'Brands', 'it-hardware-supply' ),
				'not_found'     => __( 'No brands found.', 'it-hardware-supply' ),
				'back_to_items' => __( '← Back to Brands', 'it-hardware-supply' ),
			),
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rest_base'          => 'product-brands',
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'product-brand',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'it_hardware_register_product_brand_taxonomy', 5 );

/* ── 3. Product Series Taxonomy ────────────────────────────────────────────── */

function it_hardware_register_product_series_taxonomy() {
	register_taxonomy(
		'product-series',
		array( 'products' ),
		array(
			'hierarchical'       => false,
			'labels'             => array(
				'name'          => __( 'Series', 'it-hardware-supply' ),
				'singular_name' => __( 'Series', 'it-hardware-supply' ),
				'search_items'  => __( 'Search Series', 'it-hardware-supply' ),
				'all_items'     => __( 'All Series', 'it-hardware-supply' ),
				'edit_item'     => __( 'Edit Series', 'it-hardware-supply' ),
				'update_item'   => __( 'Update Series', 'it-hardware-supply' ),
				'add_new_item'  => __( 'Add New Series', 'it-hardware-supply' ),
				'new_item_name' => __( 'New Series Name', 'it-hardware-supply' ),
				'menu_name'     => __( 'Series', 'it-hardware-supply' ),
				'not_found'     => __( 'No series found.', 'it-hardware-supply' ),
				'back_to_items' => __( '← Back to Series', 'it-hardware-supply' ),
			),
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rest_base'          => 'product-series',
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => 'product-series',
				'with_front' => false,
			),
		)
	);
}
add_action( 'init', 'it_hardware_register_product_series_taxonomy', 5 );
