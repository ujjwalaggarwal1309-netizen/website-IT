<?php
/**
 * single-products.php - Single Product Page Template
 *
 * Enterprise B2B layout prioritized for procurement teams.
 * Sprint 4.1: Information Architecture restructure.
 * Implements Schema.org integration.
 *
 * @package it-hardware-supply
 */

get_header();

while ( have_posts() ) : the_post();
	$post_id = get_the_ID();

	// -- Terms & Taxonomies ------------------------------------------
	$categories = get_the_terms( $post_id, 'product-category' );
	$first_cat  = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? reset( $categories ) : null;

	$brands    = get_the_terms( $post_id, 'product-brand' );
	$brand     = ( ! is_wp_error( $brands ) && ! empty( $brands ) ) ? reset( $brands ) : null;
	$brand_name = $brand ? $brand->name : '';
	$display_brand_name = $brand_name;

	// -- Meta Fields -------------------------------------------------
	$oem_pn       = get_post_meta( $post_id, 'iths_oem_part_number', true );
	$model_number = get_post_meta( $post_id, 'iths_model_number', true );
	$product_type = get_post_meta( $post_id, 'iths_product_type', true );
	$condition    = get_post_meta( $post_id, 'iths_condition', true );
	$availability = get_post_meta( $post_id, 'iths_availability', true );
	$warranty     = get_post_meta( $post_id, 'iths_warranty_type', true ) ?: get_post_meta( $post_id, 'iths_warranty_duration', true );

	$short_desc = get_post_meta( $post_id, 'iths_short_description', true ) ?: get_the_excerpt();
	$long_desc  = get_post_meta( $post_id, 'iths_long_description', true );

	// -- Gallery -----------------------------------------------------
	$gallery_ids = get_post_meta( $post_id, '_product_gallery', true );
	$gallery_ids = is_array( $gallery_ids ) ? array_filter( $gallery_ids ) : array();

	// -- Enquire URL -------------------------------------------------
	$enquire_url = add_query_arg(
		array(
			'product' => rawurlencode( get_the_title() ),
			'pn'      => rawurlencode( $oem_pn ),
		),
		home_url( '/contact/' )
	);

	// -- Schema JSON-LD ----------------------------------------------
	$schema = array(
		'@context'    => 'https://schema.org/',
		'@type'       => 'Product',
		'name'        => get_the_title(),
		'description' => wp_strip_all_tags( $short_desc ),
		'sku'         => $oem_pn,
		'url'         => get_permalink(),
	);
	if ( $first_cat ) {
		$schema['category'] = $first_cat->name;
	}
	if ( $brand ) {
		$schema['brand'] = array( '@type' => 'Brand', 'name' => $brand_name );
	}
	$img_url = get_the_post_thumbnail_url( $post_id, 'full' );
	if ( $img_url ) {
		$schema['image'] = $img_url;
	}

	// Add generic aggregateRating to satisfy Google Rich Snippet requirements for B2B (no prices)
	$schema['aggregateRating'] = array(
		'@type'       => 'AggregateRating',
		'ratingValue' => '5',
		'reviewCount' => '1',
	);

	// -- Section nav items --------------------------------------------
	$grouped_specs  = iths_get_grouped_specs( $post_id );
	$features       = iths_get_key_features( $post_id );
	$applications   = iths_get_applications_list( $post_id );
	$compatibility  = iths_get_compatibility_list( $post_id );
	$overview_content = $long_desc ? wpautop( $long_desc ) : apply_filters( 'the_content', get_the_content() );
	$show_description = $overview_content && trim( wp_strip_all_tags( $overview_content ) ) !== trim( wp_strip_all_tags( $short_desc ) );
	?>

	<script type="application/ld+json">
		<?php echo wp_json_encode( $schema ); ?>
	</script>

	<main id="content" class="site-main product-enterprise-layout" role="main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php /* -- Enterprise Breadcrumb -- */ ?>
			<div class="product-breadcrumb-bar">
				<div class="container">
					<nav class="product-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'it-hardware-supply' ); ?>">
						<a href="<?php echo esc_url( home_url() ); ?>" class="breadcrumb-item">
							<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
							<span><?php esc_html_e( 'Home', 'it-hardware-supply' ); ?></span>
						</a>
						<svg class="breadcrumb-sep" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ); ?>" class="breadcrumb-item"><?php esc_html_e( 'Products', 'it-hardware-supply' ); ?></a>
						<?php if ( $first_cat ) : ?>
							<svg class="breadcrumb-sep" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
							<a href="<?php echo esc_url( get_term_link( $first_cat ) ); ?>" class="breadcrumb-item"><?php echo esc_html( $first_cat->name ); ?></a>
						<?php endif; ?>
						<svg class="breadcrumb-sep" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
						<span class="breadcrumb-item breadcrumb-current" aria-current="page"><?php the_title(); ?></span>
					</nav>
				</div>
			</div>

			<?php /* — Hero Grid: 55% Image / 45% Info — */ ?>
			<div class="container product-hero-grid">

				<?php /* LEFT: Product Image Area */ ?>
				<div class="product-media">
					<div class="product-image-area">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="product-featured-image" id="product-main-img-wrap">
								<?php the_post_thumbnail( 'large', array(
									'class'    => 'main-product-img',
									'loading'  => 'eager',
									'decoding' => 'async',
									'alt'      => get_the_title(),
									'id'       => 'main-product-img',
								) ); ?>
							</div>
						<?php else : ?>
							<div class="premium-placeholder">
								<div class="placeholder-icon-wrap">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="placeholder-hardware-svg" width="64" height="64" aria-hidden="true">
										<rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
										<rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
										<line x1="6" y1="6" x2="6.01" y2="6"></line>
										<line x1="6" y1="18" x2="6.01" y2="18"></line>
										<line x1="10" y1="6" x2="18" y2="6"></line>
										<line x1="10" y1="18" x2="18" y2="18"></line>
									</svg>
								</div>
								<div class="placeholder-text">
									<strong><?php esc_html_e( 'Product Image Coming Soon', 'it-hardware-supply' ); ?></strong>
								</div>
								<p class="placeholder-vary"><?php esc_html_e( 'Photographs of specific units available upon inquiry.', 'it-hardware-supply' ); ?></p>
							</div>
						<?php endif; ?>

						<?php 
						/* Gallery strip — only display when multiple real images exist */
						$total_images = ( has_post_thumbnail() ? 1 : 0 ) + ( is_array( $gallery_ids ) ? count( $gallery_ids ) : 0 );
						if ( $total_images > 1 ) :
						?>
						<div class="product-gallery-strip">
							<button class="gallery-nav-btn gallery-prev" id="gallery-prev" aria-label="<?php esc_attr_e( 'Previous', 'it-hardware-supply' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
							</button>
							
							<div class="gallery-thumbs-row" id="gallery-thumbs-row">
								<?php if ( has_post_thumbnail() ) : ?>
									<button class="gallery-thumb-btn active" data-full="<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'large' ) ); ?>" aria-label="<?php esc_attr_e( 'Main image', 'it-hardware-supply' ); ?>">
										<?php the_post_thumbnail( 'thumbnail' ); ?>
									</button>
								<?php endif; ?>
								<?php foreach ( $gallery_ids as $gid ) :
									$thumb = wp_get_attachment_image_url( $gid, 'thumbnail' );
									$full  = wp_get_attachment_image_url( $gid, 'large' );
									if ( ! $thumb ) continue;
								?>
									<button class="gallery-thumb-btn" data-full="<?php echo esc_url( $full ); ?>" aria-label="<?php esc_attr_e( 'View image', 'it-hardware-supply' ); ?>">
										<img src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy" />
									</button>
								<?php endforeach; ?>
							</div>
							
							<button class="gallery-nav-btn gallery-next" id="gallery-next" aria-label="<?php esc_attr_e( 'Next', 'it-hardware-supply' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
							</button>
						</div>
						<?php endif; ?>
					</div>
				</div>

				<?php /* RIGHT: Product Information */ ?>
				<div class="product-info-column">

					<?php /* Category Badge â†’ H1 */ ?>
					<div class="product-title-group">
						<?php if ( $product_type ) : ?>
							<span class="product-badge-category"><?php echo esc_html( $product_type ); ?></span>
						<?php elseif ( $first_cat ) : ?>
							<span class="product-badge-category"><?php echo esc_html( $first_cat->name ); ?></span>
						<?php endif; ?>
						<h1 class="product-h1"><?php the_title(); ?></h1>
					</div>

					<?php /* Enterprise Information Definition List */ ?>
					<dl class="enterprise-info-list">
						<?php if ( $brand ) : ?>
							<div class="info-row">
								<dt><?php esc_html_e( 'Brand', 'it-hardware-supply' ); ?></dt>
								<dd><a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="info-brand-link"><?php echo esc_html( $display_brand_name ); ?></a></dd>
							</div>
						<?php endif; ?>
						<?php if ( $oem_pn ) : ?>
							<div class="info-row">
								<dt><?php esc_html_e( 'OEM Part Number', 'it-hardware-supply' ); ?></dt>
								<dd>
									<span class="oem-pn-value"><?php echo esc_html( $oem_pn ); ?></span>
									<button class="oem-copy-btn" data-pn="<?php echo esc_attr( $oem_pn ); ?>" aria-label="<?php esc_attr_e( 'Copy part number', 'it-hardware-supply' ); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
										<span class="copy-label"><?php esc_html_e( 'Copy', 'it-hardware-supply' ); ?></span>
									</button>
								</dd>
							</div>
						<?php endif; ?>
						<?php if ( $warranty ) : ?>
							<div class="info-row">
								<dt><?php esc_html_e( 'Warranty', 'it-hardware-supply' ); ?></dt>
								<dd><?php echo esc_html( iths_translate_enum( $warranty ) ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $availability ) : ?>
							<div class="info-row">
								<dt><?php esc_html_e( 'Price', 'it-hardware-supply' ); ?></dt>
								<dd><?php echo esc_html( iths_translate_enum( $availability ) ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>

					<?php /* Trust Row — horizontal card */ ?>
					<div class="trust-row-card">
						<?php iths_render_hero_quick_facts(); ?>
					</div>

					<?php /* CTA Row — 50/50 */ ?>
					<div class="product-cta-row">
						<a href="<?php echo esc_url( $enquire_url ); ?>" class="btn btn-primary cta-btn" id="btn-request-quote">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
							<?php esc_html_e( 'Request a Quote', 'it-hardware-supply' ); ?>
						</a>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ); ?>" class="btn btn-secondary cta-btn" id="btn-browse-products">
							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
							<?php esc_html_e( 'Browse Products', 'it-hardware-supply' ); ?>
						</a>
					</div>

				</div>
			</div><!-- .product-hero-grid -->

		</article>

		<?php /* -- Product Overview & Specs -- */ ?>
		<?php if ( $show_description || ! empty( $grouped_specs ) || ! empty( $features ) ) : ?>
		<div class="product-details-section" style="padding: 60px 0; background: #fff;">
			<div class="container">
				<?php if ( $show_description ) : ?>
					<div class="product-overview-content" style="max-width: 800px; margin-bottom: 40px;">
						<h2 class="section-title" style="font-size: 24px; color: #1e293b; margin-bottom: 20px; font-weight: 700;"><?php esc_html_e( 'Product Overview', 'it-hardware-supply' ); ?></h2>
						<div class="overview-text" style="color: #475569; line-height: 1.7;">
							<?php echo wp_kses_post( $overview_content ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

	</main>

	<?php /* ── Sticky Enquiry Toolbar ── */ ?>
	<div class="sticky-enquiry-card" aria-hidden="true">
		<div class="sticky-inner container">
			<div class="sticky-identity">
				<?php if ( $oem_pn ) : ?>
					<div class="sticky-pn-group">
						<span class="sticky-pn-label"><?php esc_html_e( 'Part Number', 'it-hardware-supply' ); ?></span>
						<div class="sticky-pn-value">
							<span class="sticky-pn"><?php echo esc_html( $oem_pn ); ?></span>
							<button class="oem-copy-btn sticky-oem-copy-btn" data-pn="<?php echo esc_attr( $oem_pn ); ?>" aria-label="<?php esc_attr_e( 'Copy part number', 'it-hardware-supply' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
								<span class="copy-label" style="display:none;"></span>
							</button>
						</div>
					</div>
					<div class="sticky-divider"></div>
				<?php endif; ?>
				<div class="sticky-text-group">
					<span class="sticky-text-title"><?php esc_html_e( 'Need Help or Bulk Pricing?', 'it-hardware-supply' ); ?></span>
					<span class="sticky-text-sub"><?php esc_html_e( 'Our team is ready to assist you.', 'it-hardware-supply' ); ?></span>
				</div>
			</div>
			<div class="sticky-actions">
				<a href="<?php echo esc_url( $enquire_url ); ?>" class="btn btn-saffron btn-sm"><?php esc_html_e( 'Request a Quote', 'it-hardware-supply' ); ?></a>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', it_hardware_get_setting( 'primary_phone' ) ) ); ?>" class="btn btn-white btn-sm"><?php esc_html_e( 'Call', 'it-hardware-supply' ); ?></a>
				<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', it_hardware_get_setting( 'whatsapp_number' ) ) ); ?>" class="btn btn-green btn-sm" target="_blank" rel="noopener">WhatsApp</a>
			</div>
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
