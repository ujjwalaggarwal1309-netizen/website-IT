<?php
/**
 * Template Part: Product Search
 *
 * Accessible search form scoped to the 'products' CPT archive.
 *
 * - Uses GET method (SEO-friendly URL)
 * - Searches only within the 'products' post type (hidden input)
 * - Search query preserved on reload
 * - Clear button shown when a search is active
 * - aria-label on form, label for input
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_q    = get_search_query();
$archive_url = get_post_type_archive_link( 'products' );
?>
<div class="product-search-wrap">
	<form
		class="product-search"
		method="get"
		action="<?php echo esc_url( $archive_url ); ?>"
		role="search"
		aria-label="<?php esc_attr_e( 'Search products', 'it-hardware-supply' ); ?>"
		id="product-search-form"
	>
		<?php /* Scope search to products CPT */ ?>
		<input type="hidden" name="post_type" value="products" />

		<label for="product-search-input" class="screen-reader-text">
			<?php esc_html_e( 'Search products', 'it-hardware-supply' ); ?>
		</label>
		<div class="product-search__inner">
			<span class="product-search__icon" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18"><path fill-rule="evenodd" d="M8 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM2 8a6 6 0 1 1 10.89 3.476l4.817 4.817a1 1 0 0 1-1.414 1.414l-4.816-4.816A6 6 0 0 1 2 8z" clip-rule="evenodd"/></svg>
			</span>
			<input
				id="product-search-input"
				class="product-search__input"
				type="search"
				name="s"
				value="<?php echo esc_attr( $search_q ); ?>"
				placeholder="<?php esc_attr_e( 'Search products, components, brands…', 'it-hardware-supply' ); ?>"
				autocomplete="off"
				aria-label="<?php esc_attr_e( 'Search products', 'it-hardware-supply' ); ?>"
			/>
			<?php if ( $search_q ) : ?>
			<a
				href="<?php echo esc_url( $archive_url ); ?>"
				class="product-search__clear"
				aria-label="<?php esc_attr_e( 'Clear search', 'it-hardware-supply' ); ?>"
				id="product-search-clear"
			>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414z" clip-rule="evenodd"/></svg>
			</a>
			<?php endif; ?>
			<button
				class="product-search__btn btn btn-accent"
				type="submit"
				aria-label="<?php esc_attr_e( 'Submit product search', 'it-hardware-supply' ); ?>"
			>
				<?php esc_html_e( 'Search', 'it-hardware-supply' ); ?>
			</button>
		</div>
	</form>
</div>
