<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register product thumbnail image sizes.
 * Hooked to after_setup_theme (called from setup.php already for the theme,
 * but we add here so products.php is self-contained).
 */
add_action( 'after_setup_theme', function () {
	add_image_size( 'product-card', 480, 320, true );
}, 11 );

/**
 * Register the 'products' custom post type.
 */
function it_hardware_register_products() {
	register_post_type( 'products', array(
		'labels'       => array(
			'name'          => __( 'Products', 'it-hardware-supply' ),
			'singular_name' => __( 'Product', 'it-hardware-supply' ),
			'add_new_item'  => __( 'Add New Product', 'it-hardware-supply' ),
			'edit_item'     => __( 'Edit Product', 'it-hardware-supply' ),
			'view_item'     => __( 'View Product', 'it-hardware-supply' ),
			'search_items'  => __( 'Search Products', 'it-hardware-supply' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'rewrite'      => array( 'slug' => 'products' ),
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes', 'revisions' ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-archive',
	) );
}
add_action( 'init', 'it_hardware_register_products' );

/**
 * Adjust the main query for product archives.
 *
 * 1. Sets posts_per_page from the Customizer setting.
 * 2. When a text search is submitted from the products search form
 *    (post_type=products is sent as a hidden field), ensures the
 *    query stays scoped to the products CPT.
 *
 * @param WP_Query $query The current query object.
 */
function it_hardware_products_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$is_product_context = (
		is_post_type_archive( 'products' )
		|| is_tax( 'product-category' )
		|| ( $query->is_search() && isset( $_GET['post_type'] ) && 'products' === sanitize_key( $_GET['post_type'] ) ) // phpcs:ignore WordPress.Security.NonceVerification
	);

	if ( $is_product_context ) {
		$query->set( 'posts_per_page', get_theme_mod( 'products_per_page', 9 ) );
		// Scope searches to products CPT so blog posts are never mixed in.
		if ( $query->is_search() ) {
			$query->set( 'post_type', 'products' );
			add_filter( 'posts_join', 'iths_search_join', 10, 2 );
			add_filter( 'posts_where', 'iths_search_where', 10, 2 );
			add_filter( 'posts_distinct', 'iths_search_distinct', 10, 2 );
			add_filter( 'posts_orderby', 'iths_search_orderby', 10, 2 );
		}
	}
}
add_action( 'pre_get_posts', 'it_hardware_products_query' );

/**
 * Return HTML for a grid of related products (same taxonomy term).
 *
 * @param int $post_id The current product post ID.
 * @param int $count   Number of related products to fetch.
 * @return string HTML output, or empty string if none found.
 */
function it_hardware_related_products( $post_id, $count = 3 ) {
	$terms = wp_get_post_terms( $post_id, 'product-category' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	$term_ids = wp_list_pluck( $terms, 'term_id' );
	$query    = new WP_Query( array(
		'post_type'      => 'products',
		'post__not_in'   => array( $post_id ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'product-category',
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		),
		'posts_per_page' => $count,
		'orderby'        => 'rand',
	) );
	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/product-card' );
		}
	}
	wp_reset_postdata();
	return ob_get_clean();
}
