<?php
/**
 * Template Part: Product Filter (Sidebar)
 *
 * Renders the hierarchical product category filter in the sidebar.
 * Shows parent categories first, with child categories indented.
 * Active category and its ancestors are highlighted.
 *
 * - Keyboard accessible (native <a> links)
 * - SEO-friendly URL per-term (get_term_link)
 * - Active state via aria-current="page"
 * - Product count shown in parentheses
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$queried_obj    = get_queried_object();
$active_term_id = ( $queried_obj instanceof WP_Term ) ? $queried_obj->term_id : 0;
$archive_url    = get_post_type_archive_link( 'products' );

// Fetch top-level parent categories.
$parent_terms = get_terms( array(
	'taxonomy'   => 'product-category',
	'parent'     => 0,
	'hide_empty' => false,
	'orderby'    => 'name',
	'order'      => 'ASC',
) );

if ( is_wp_error( $parent_terms ) || empty( $parent_terms ) ) {
	return;
}
?>
<div class="product-filter" role="navigation" aria-label="<?php esc_attr_e( 'Product categories', 'it-hardware-supply' ); ?>">

	<h2 class="product-filter__heading"><?php esc_html_e( 'Categories', 'it-hardware-supply' ); ?></h2>

	<ul class="product-filter__list" role="list">

		<?php /* All Products link */ ?>
		<li class="product-filter__item">
			<a
				href="<?php echo esc_url( $archive_url ); ?>"
				class="product-filter__link<?php echo ! $active_term_id ? ' product-filter__link--active' : ''; ?>"
				<?php echo ! $active_term_id ? 'aria-current="page"' : ''; ?>
			>
				<span class="product-filter__label"><?php esc_html_e( 'All Products', 'it-hardware-supply' ); ?></span>
			</a>
		</li>

		<?php foreach ( $parent_terms as $parent_term ) :
			$is_parent_active = ( $active_term_id === $parent_term->term_id );

			// Check if active term is a child of this parent.
			$child_terms = get_terms( array(
				'taxonomy'   => 'product-category',
				'parent'     => $parent_term->term_id,
				'hide_empty' => false,
				'orderby'    => 'name',
			) );
			$has_children   = ! is_wp_error( $child_terms ) && ! empty( $child_terms );
			$is_ancestor    = false;
			if ( $has_children && $active_term_id ) {
				foreach ( $child_terms as $child ) {
					if ( $child->term_id === $active_term_id ) {
						$is_ancestor = true;
						break;
					}
				}
			}
		?>
		<li class="product-filter__item<?php echo ( $is_parent_active || $is_ancestor ) ? ' product-filter__item--open' : ''; ?>">
			<a
				href="<?php echo esc_url( get_term_link( $parent_term ) ); ?>"
				class="product-filter__link<?php echo $is_parent_active ? ' product-filter__link--active' : ''; ?>"
				<?php echo $is_parent_active ? 'aria-current="page"' : ''; ?>
			>
				<span class="product-filter__label"><?php echo esc_html( $parent_term->name ); ?></span>
				<?php if ( $parent_term->count > 0 ) : ?>
				<span class="product-filter__count" aria-label="<?php echo esc_attr( sprintf( __( '%s products', 'it-hardware-supply' ), $parent_term->count ) ); ?>">(<?php echo (int) $parent_term->count; ?>)</span>
				<?php endif; ?>
			</a>

			<?php /* Child categories — show when parent is active or is ancestor */ ?>
			<?php if ( $has_children && ( $is_parent_active || $is_ancestor ) ) : ?>
			<ul class="product-filter__sublist" role="list">
				<?php foreach ( $child_terms as $child_term ) :
					$is_child_active = ( $active_term_id === $child_term->term_id );
				?>
				<li class="product-filter__item product-filter__item--child">
					<a
						href="<?php echo esc_url( get_term_link( $child_term ) ); ?>"
						class="product-filter__link product-filter__link--child<?php echo $is_child_active ? ' product-filter__link--active' : ''; ?>"
						<?php echo $is_child_active ? 'aria-current="page"' : ''; ?>
					>
						<span class="product-filter__label"><?php echo esc_html( $child_term->name ); ?></span>
						<?php if ( $child_term->count > 0 ) : ?>
						<span class="product-filter__count">(<?php echo (int) $child_term->count; ?>)</span>
						<?php endif; ?>
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		</li>
		<?php endforeach; ?>
	</ul>
</div>
