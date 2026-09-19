<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breadcrumb navigation.
 *
 * Outputs a semantic <nav aria-label="Breadcrumb"> with a <ol> list.
 * Uses <ol> (ordered list) per ARIA Authoring Practices breadcrumb pattern.
 * The last item carries aria-current="page" per WCAG 2.4.8.
 *
 * @package it-hardware-supply
 */
function it_hardware_breadcrumbs() {

	$items = array(
		array(
			'label' => 'Home',
			'url'   => esc_url( home_url( '/' ) ),
		),
	);

	if ( is_post_type_archive( 'products' ) ) {
		$items[] = array( 'label' => esc_html__( 'Products', 'it-hardware-supply' ), 'url' => '' );
	} elseif ( is_singular( 'products' ) ) {
		$items[] = array(
			'label' => esc_html__( 'Products', 'it-hardware-supply' ),
			'url'   => esc_url( get_post_type_archive_link( 'products' ) ),
		);
		$items[] = array( 'label' => esc_html( get_the_title() ), 'url' => '' );
	} elseif ( is_page() ) {
		$items[] = array( 'label' => esc_html( get_the_title() ), 'url' => '' );
	} elseif ( is_search() ) {
		$items[] = array( 'label' => esc_html__( 'Search', 'it-hardware-supply' ), 'url' => '' );
	} elseif ( is_tax() ) {
		$term    = get_queried_object();
		$items[] = array(
			'label' => esc_html__( 'Products', 'it-hardware-supply' ),
			'url'   => esc_url( get_post_type_archive_link( 'products' ) ),
		);
		$items[] = array( 'label' => esc_html( $term->name ), 'url' => '' );
	}

	$last_idx = count( $items ) - 1;

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'it-hardware-supply' ) . '">';
	echo '<ol>';

	foreach ( $items as $idx => $item ) {
		$is_last = ( $idx === $last_idx );

		if ( $is_last ) {
			// Last item: no link, aria-current="page"
			echo '<li><span aria-current="page">' . $item['label'] . '</span></li>';
		} else {
			// Ancestor item: linked
			echo '<li><a href="' . $item['url'] . '">' . $item['label'] . '</a></li>';
		}
	}

	echo '</ol>';
	echo '</nav>';
}
