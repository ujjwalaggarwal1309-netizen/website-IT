<?php
/**
 * Product Archive AJAX Handler
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_iths_filter_products', 'iths_filter_products_ajax' );
add_action( 'wp_ajax_nopriv_iths_filter_products', 'iths_filter_products_ajax' );

function iths_filter_products_ajax() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'iths_archive_nonce' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	$args = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'paged'          => isset( $_POST['paged'] ) ? max( 1, intval( $_POST['paged'] ) ) : 1,
	);

	// 1. Search Logic
	if ( ! empty( $_POST['search'] ) ) {
		$search_term = sanitize_text_field( wp_unslash( $_POST['search'] ) );
		$args['s'] = $search_term;
		
		add_filter( 'posts_join', 'iths_search_join', 10, 2 );
		add_filter( 'posts_where', 'iths_search_where', 10, 2 );
		add_filter( 'posts_distinct', 'iths_search_distinct', 10, 2 );
		add_filter( 'posts_orderby', 'iths_search_orderby', 10, 2 );
	}

	// 2. Taxonomies
	$tax_query = array();
	$tax_keys = array( 'product-category', 'product-brand', 'product-series' );
	foreach ( $tax_keys as $tax ) {
		if ( ! empty( $_POST[ $tax ] ) && is_array( $_POST[ $tax ] ) ) {
			$terms = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $tax ] ) );
			$tax_query[] = array(
				'taxonomy' => $tax,
				'field'    => 'slug',
				'terms'    => $terms,
			);
		}
	}
	if ( ! empty( $tax_query ) ) {
		$tax_query['relation'] = 'AND';
		$args['tax_query'] = $tax_query;
	}

	// 3. Meta Filters
	$meta_query = array();
	$meta_keys = array( 'iths_product_type', 'iths_availability', 'iths_condition', 'iths_warranty_type' );
	foreach ( $meta_keys as $meta ) {
		if ( ! empty( $_POST[ $meta ] ) && is_array( $_POST[ $meta ] ) ) {
			$values = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $meta ] ) );
			$meta_query[] = array(
				'key'     => $meta,
				'value'   => $values,
				'compare' => 'IN',
			);
		}
	}
	if ( ! empty( $meta_query ) ) {
		$meta_query['relation'] = 'AND';
		$args['meta_query'] = $meta_query;
	}

	// 4. Sorting
	if ( ! empty( $_POST['sort'] ) ) {
		$sort = sanitize_text_field( $_POST['sort'] );
		switch ( $sort ) {
			case 'az':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'brand':
				// Fallback to title sort if brand sorting is complex without taxonomy join
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'updated':
				$args['orderby'] = 'modified';
				$args['order']   = 'DESC';
				break;
			case 'category':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'newest':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
		}
	}

	$query = new WP_Query( $args );

	// Remove search filters
	if ( ! empty( $_POST['search'] ) ) {
		remove_filter( 'posts_join', 'iths_search_join', 10 );
		remove_filter( 'posts_where', 'iths_search_where', 10 );
		remove_filter( 'posts_distinct', 'iths_search_distinct', 10 );
		remove_filter( 'posts_orderby', 'iths_search_orderby', 10 );
	}

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$template = locate_template( 'template-parts/product-card.php' );
			if ( $template ) {
				include $template;
			}
		}
		wp_reset_postdata();
	}

	$html = ob_get_clean();

	ob_start();
	if ( function_exists( 'it_hardware_pagination_custom' ) ) {
		it_hardware_pagination_custom( $query );
	} else {
		// Fallback simple pagination html if our custom function is not yet there
		$total_pages = $query->max_num_pages;
		$current_page = max( 1, $args['paged'] );
		if ( $total_pages > 1 ) {
			echo '<ul class="pagination__list">';
			for ( $i = 1; $i <= $total_pages; $i++ ) {
				$class = ( $i == $current_page ) ? 'pagination__item pagination__item--current' : 'pagination__item';
				echo '<li class="' . esc_attr( $class ) . '"><a href="#" class="page-numbers" data-page="' . $i . '">' . $i . '</a></li>';
			}
			echo '</ul>';
		}
	}
	$pagination = ob_get_clean();

	wp_send_json_success( array(
		'html'       => $html,
		'pagination' => $pagination,
		'count'      => $query->found_posts,
		'empty'      => ( 0 === $query->found_posts ),
	) );
}

/* ── Custom Search Filters for Priority (OEM PN > Model > Title) ───────── */

function iths_search_join( $join, $query ) {
	global $wpdb;
	if ( $query->is_search() ) {
		$join .= " LEFT JOIN {$wpdb->postmeta} pm_search ON ({$wpdb->posts}.ID = pm_search.post_id AND pm_search.meta_key IN ('iths_oem_part_number', 'iths_model_number')) ";
	}
	return $join;
}

function iths_search_where( $where, $query ) {
	global $wpdb;
	if ( $query->is_search() ) {
		$search_term = $query->get('s');
		$search_term_like = '%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%';
		
		// Modify the WHERE clause to include our meta values
		$where = preg_replace(
			"/\(\s*{$wpdb->posts}\.post_title\s+LIKE\s*('[^']+')\s*\)/",
			"({$wpdb->posts}.post_title LIKE $1) OR (pm_search.meta_value LIKE '{$search_term_like}')",
			$where
		);
	}
	return $where;
}

function iths_search_distinct( $distinct, $query ) {
	if ( $query->is_search() ) {
		return 'DISTINCT';
	}
	return $distinct;
}

function iths_search_orderby( $orderby, $query ) {
	global $wpdb;
	if ( $query->is_search() ) {
		$search_term = $query->get('s');
		// Give highest priority to EXACT OEM Part Number match, then EXACT Model, then LIKE matches.
		$exact_oem = $wpdb->prepare( "pm_search.meta_value = %s AND pm_search.meta_key = 'iths_oem_part_number' DESC", $search_term );
		$exact_model = $wpdb->prepare( "pm_search.meta_value = %s AND pm_search.meta_key = 'iths_model_number' DESC", $search_term );
		$like_term = esc_sql( $wpdb->esc_like( $search_term ) );
		
		$orderby = "{$exact_oem}, {$exact_model}, {$wpdb->posts}.post_title LIKE '%{$like_term}%' DESC, {$wpdb->posts}.post_date DESC";
	}
	return $orderby;
}

add_action( 'wp_ajax_iths_search_suggestions', 'iths_search_suggestions_ajax' );
add_action( 'wp_ajax_nopriv_iths_search_suggestions', 'iths_search_suggestions_ajax' );

function iths_search_suggestions_ajax() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'iths_archive_nonce' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	$term = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
	if ( empty( $term ) ) {
		wp_send_json_success( array() );
	}

	$args = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		's'              => $term,
	);

	add_filter( 'posts_join', 'iths_search_join', 10, 2 );
	add_filter( 'posts_where', 'iths_search_where', 10, 2 );
	add_filter( 'posts_distinct', 'iths_search_distinct', 10, 2 );
	add_filter( 'posts_orderby', 'iths_search_orderby', 10, 2 );

	$query = new WP_Query( $args );

	remove_filter( 'posts_join', 'iths_search_join', 10 );
	remove_filter( 'posts_where', 'iths_search_where', 10 );
	remove_filter( 'posts_distinct', 'iths_search_distinct', 10 );
	remove_filter( 'posts_orderby', 'iths_search_orderby', 10 );

	$results = array();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			
			$brands = get_the_terms( get_the_ID(), 'product-brand' );
			$brand_name = ( ! is_wp_error( $brands ) && ! empty( $brands ) ) ? reset( $brands )->name : '';

			$oem = get_post_meta( get_the_ID(), 'iths_oem_part_number', true );
			$type = get_post_meta( get_the_ID(), 'iths_product_type', true );

			$results[] = array(
				'title'  => get_the_title(),
				'url'    => get_permalink(),
				'brand'  => $brand_name,
				'oem_pn' => $oem ? $oem : '',
				'type'   => $type ? $type : '',
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( $results );
}
