<?php
/**
 * Template Part: No Products Found (Empty State)
 *
 * Displayed when no products match the current query.
 *
 * Behaviour:
 *   - Search query active: show what was searched + suggest clearing
 *   - Category with no products: suggest browsing all
 *   - Generic: suggest contacting us
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_q    = get_search_query();
$archive_url = get_post_type_archive_link( 'products' );
?>
<div class="empty-state" role="status" aria-live="polite">

	<div class="empty-state__icon" aria-hidden="true">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="56" height="56"><rect x="4" y="16" width="56" height="40" rx="4"/><path d="M4 24h56"/><path d="M16 8h32"/><circle cx="32" cy="40" r="8"/><line x1="32" y1="36" x2="32" y2="40"/><line x1="32" y1="42" x2="32" y2="44"/></svg>
	</div>

	<?php if ( $search_q ) : ?>
		<h2 class="empty-state__heading"><?php esc_html_e( 'No products found', 'it-hardware-supply' ); ?></h2>
		<p class="empty-state__body">
			<?php
			printf(
				/* translators: %s: the search term */
				esc_html__( 'We couldn\'t find any products matching "%s". Try a different search term or browse all products.', 'it-hardware-supply' ),
				'<strong>' . esc_html( $search_q ) . '</strong>'
			);
			?>
		</p>
		<div class="empty-state__actions">
			<a class="btn btn-accent" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Browse All Products', 'it-hardware-supply' ); ?></a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Our Team', 'it-hardware-supply' ); ?></a>
		</div>
	<?php elseif ( is_tax( 'product-category' ) ) : ?>
		<h2 class="empty-state__heading"><?php esc_html_e( 'No products in this category yet', 'it-hardware-supply' ); ?></h2>
		<p class="empty-state__body"><?php esc_html_e( 'This category doesn\'t have any products yet. Please check back soon, or contact our team to enquire about specific hardware requirements.', 'it-hardware-supply' ); ?></p>
		<div class="empty-state__actions">
			<a class="btn btn-accent" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Browse All Products', 'it-hardware-supply' ); ?></a>
			<a class="btn btn-outline" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Our Team', 'it-hardware-supply' ); ?></a>
		</div>
	<?php else : ?>
		<h2 class="empty-state__heading"><?php esc_html_e( 'Products Coming Soon', 'it-hardware-supply' ); ?></h2>
		<p class="empty-state__body"><?php esc_html_e( 'We\'re currently adding products to our catalogue. In the meantime, please contact our team to discuss your enterprise IT hardware requirements.', 'it-hardware-supply' ); ?></p>
		<div class="empty-state__actions">
			<a class="btn btn-accent" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Our Team', 'it-hardware-supply' ); ?></a>
		</div>
	<?php endif; ?>

</div>
