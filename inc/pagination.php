<?php
/**
 * inc/pagination.php — Accessible pagination for product archives.
 *
 * Uses WordPress paginate_links() with nav > ul > li pattern.
 * aria-label="Pagination" on <nav>, aria-current="page" on active link.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return HTML pagination nav for the current query.
 *
 * @return string HTML string, or empty string when only one page.
 */
function it_hardware_pagination() {
	global $wp_query;

	$total_pages = (int) $wp_query->max_num_pages;
	if ( $total_pages <= 1 ) {
		return '';
	}

	$big     = 999999999;
	$current = max( 1, (int) get_query_var( 'paged' ) );

	$links = paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $current,
		'total'     => $total_pages,
		'type'      => 'array',
		'prev_text' => '&laquo; Prev',
		'next_text' => 'Next &raquo;',
	) );

	if ( ! $links ) {
		return '';
	}

	$output = '<nav class="pagination" aria-label="' . esc_attr__( 'Product archive pagination', 'it-hardware-supply' ) . '">';
	$output .= '<ul class="pagination__list" role="list">';

	foreach ( $links as $link ) {
		// Mark the current page item for accessibility.
		if ( strpos( $link, 'current' ) !== false ) {
			$link    = str_replace( 'class="page-numbers current"', 'class="page-numbers current" aria-current="page"', $link );
			$output .= '<li class="pagination__item pagination__item--current">' . $link . '</li>';
		} elseif ( strpos( $link, 'dots' ) !== false ) {
			$output .= '<li class="pagination__item pagination__item--dots">' . $link . '</li>';
		} else {
			$output .= '<li class="pagination__item">' . $link . '</li>';
		}
	}

	$output .= '</ul>';
	$output .= sprintf(
		'<p class="pagination__status screen-reader-text">%s</p>',
		esc_html( sprintf(
			/* translators: 1: current page number, 2: total pages */
			__( 'Page %1$s of %2$s', 'it-hardware-supply' ),
			$current,
			$total_pages
		) )
	);
	$output .= '</nav>';

	return $output;
}
