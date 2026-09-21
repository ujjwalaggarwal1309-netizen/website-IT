<?php
/**
 * Template Part: Product Card
 *
 * Displays a single product in the product grid.
 *
 * Features:
 *   - Featured image with lazy loading
 *   - Category badge (first taxonomy term)
 *   - Product title linked to single product page
 *   - Excerpt (18 words)
 *   - "Enquire Now" CTA â†’ /contact/?product={title}
 *
 * Policy:
 *   - No pricing
 *   - No cart / add-to-cart
 *   - Every card ends with "Enquire Now" per WEBSITE_CONTENT.md
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get the first taxonomy term for the category badge.
$terms      = get_the_terms( get_the_ID(), 'product-category' );
$first_term = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? reset( $terms ) : null;

// Get Brand
$brands = get_the_terms( get_the_ID(), 'product-brand' );
$brand  = ( ! is_wp_error( $brands ) && ! empty( $brands ) ) ? reset( $brands ) : null;

// Get OEM Part Number
$oem_pn = get_post_meta( get_the_ID(), 'iths_oem_part_number', true );

// Build the Enquire Now URL with product context.
$enquire_url = add_query_arg(
	'product',
	rawurlencode( get_the_title() ),
	home_url( '/contact/' )
);
?>
<article
	class="product-card"
	aria-labelledby="product-title-<?php the_ID(); ?>"
	
>
	<?php /* â”€â”€ Thumbnail â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */ ?>
	<a href="<?php the_permalink(); ?>" class="product-card__image-wrap" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php
			the_post_thumbnail(
				'product-card',
				array(
					'class'   => 'product-card__image responsive-image',
					'loading' => 'lazy',
					'decoding' => 'async',
					'itemprop' => 'image',
					'alt'     => get_the_title(),
				)
			);
			?>
		<?php else : ?>
			<div class="product-card__image-placeholder" aria-hidden="true">
				<div class="product-card__placeholder-content">
					<svg class="product-card__placeholder-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="36" height="36" aria-hidden="true">
						<rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
						<rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
						<line x1="6" y1="6" x2="6.01" y2="6"></line>
						<line x1="6" y1="18" x2="6.01" y2="18"></line>
					</svg>
					<span class="product-card__placeholder-text"><?php esc_html_e( 'Image Coming Soon', 'it-hardware-supply' ); ?></span>
				</div>
			</div>
		<?php endif; ?>
		<?php /* Category badge overlaid on image */ ?>
		<?php if ( $first_term ) : ?>
			<span class="product-card__badge"><?php echo esc_html( $first_term->name ); ?></span>
		<?php endif; ?>
		
		<?php /* 
		<!-- TODO: Compare Checkbox -->
		<label class="product-card__compare custom-checkbox">
			<input type="checkbox" name="compare[]" value="<?php the_ID(); ?>">
			<span class="checkmark"></span>
			<span class="screen-reader-text">Compare <?php the_title(); ?></span>
		</label>
		*/ ?>
	</a>

	<?php /* â”€â”€ Card Body â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */ ?>
	<div class="product-card__body">
		<h3
			id="product-title-<?php the_ID(); ?>"
			class="product-card__title"
			itemprop="name"
		>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="product-card__meta">
			<?php if ( $brand ) : ?>
				<div class="product-card__meta-chip">
					<span class="product-card__meta-label"><?php esc_html_e( 'Brand:', 'it-hardware-supply' ); ?></span>
					<span class="product-card__meta-value"><?php echo esc_html( $brand->name ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $oem_pn ) : ?>
				<div class="product-card__meta-chip">
					<span class="product-card__meta-label"><?php esc_html_e( 'OEM PN:', 'it-hardware-supply' ); ?></span>
					<span class="product-card__meta-value"><?php echo esc_html( $oem_pn ); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<?php /* â”€â”€ Enquire Now CTA â€” per WEBSITE_CONTENT.md â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */ ?>
		<a
			class="btn btn-primary product-card__cta"
			href="<?php echo esc_url( $enquire_url ); ?>"
			aria-label="<?php echo esc_attr( sprintf( __( 'Request a quote for %s', 'it-hardware-supply' ), get_the_title() ) ); ?>"
			id="enquire-<?php the_ID(); ?>"
		>
			<span><?php esc_html_e( 'Request a Quote', 'it-hardware-supply' ); ?></span>
			<svg class="product-card__cta-icon" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
		</a>
	</div>
</article>

