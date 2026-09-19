<?php
/**
 * taxonomy-product-category.php — Product Category Archive Template
 *
 * Same layout as archive-products.php but for a specific category.
 * Reuses the same template parts; category context is already set
 * by WordPress via the main query.
 *
 * @package it-hardware-supply
 */

get_header();

$queried_term = get_queried_object();
$term_name    = $queried_term instanceof WP_Term ? $queried_term->name : '';
$term_desc    = $queried_term instanceof WP_Term ? $queried_term->description : '';
$total_posts  = (int) $GLOBALS['wp_query']->found_posts;
?>
<main id="content" class="site-main" role="main">

	<?php /* ── Page Header ──────────────────────────────────────────────── */ ?>
	<header class="page-header page-header-products" aria-labelledby="products-page-title">

		<?php /* Blurred left glow circle */ ?>
		<div class="page-header__glow-left" aria-hidden="true"></div>

		<?php /* 5% opacity circuit pattern */ ?>
		<div class="page-header__circuit" aria-hidden="true">
			<svg viewBox="0 0 1440 380" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
				<line x1="0" y1="80" x2="1440" y2="80" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="200" x2="1440" y2="200" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="320" x2="1440" y2="320" stroke="currentColor" stroke-width="1"/>
				<line x1="120" y1="0" x2="120" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="360" y1="0" x2="360" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="720" y1="0" x2="720" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1080" y1="0" x2="1080" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1320" y1="0" x2="1320" y2="380" stroke="currentColor" stroke-width="1"/>
				<circle cx="120" cy="80" r="5" fill="currentColor"/>
				<circle cx="360" cy="80" r="5" fill="currentColor"/>
				<circle cx="720" cy="80" r="5" fill="currentColor"/>
				<circle cx="1080" cy="80" r="5" fill="currentColor"/>
				<circle cx="1320" cy="80" r="5" fill="currentColor"/>
				<circle cx="120" cy="200" r="5" fill="currentColor"/>
				<circle cx="360" cy="200" r="5" fill="currentColor"/>
				<circle cx="720" cy="200" r="5" fill="currentColor"/>
				<circle cx="1080" cy="200" r="5" fill="currentColor"/>
				<circle cx="120" cy="320" r="5" fill="currentColor"/>
				<circle cx="360" cy="320" r="5" fill="currentColor"/>
				<circle cx="720" cy="320" r="5" fill="currentColor"/>
				<rect x="200" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="580" y="230" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="900" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="1200" y="240" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
			</svg>
		</div>

		<div class="container page-header__inner">
			<span class="page-header__label"><?php esc_html_e( 'PRODUCT RANGE', 'it-hardware-supply' ); ?></span>
			<h1 id="products-page-title"><?php echo esc_html( $term_name ); ?></h1>
			<div class="page-header__divider" aria-hidden="true"></div>
			<?php if ( $term_desc ) : ?>
				<p class="page-header__subtitle"><?php echo esc_html( $term_desc ); ?></p>
			<?php else : ?>
				<p class="page-header__subtitle"><?php esc_html_e( 'Explore enterprise infrastructure solutions for every critical workload.', 'it-hardware-supply' ); ?></p>
			<?php endif; ?>
			<?php if ( function_exists( 'it_hardware_breadcrumbs' ) ) { it_hardware_breadcrumbs(); } ?>
		</div>
	</header>

	<section class="products-page">
		<div class="container">

			<?php /* ── Category Tab Bar ───────────────────────────────────── */ ?>
			<?php
			$all_terms = get_terms( array(
				'taxonomy'   => 'product-category',
				'parent'     => 0,
				'hide_empty' => true,
			) );
			if ( ! is_wp_error( $all_terms ) && ! empty( $all_terms ) ) :
			?>
			<nav class="product-category-tabs" aria-label="<?php esc_attr_e( 'Product categories', 'it-hardware-supply' ); ?>">
				<a
					href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ); ?>"
					class="category-tab"
				><?php esc_html_e( 'All Products', 'it-hardware-supply' ); ?></a>
				<?php foreach ( $all_terms as $term ) :
					$is_current = ( $queried_term instanceof WP_Term && $queried_term->term_id === $term->term_id );
				?>
				<a
					href="<?php echo esc_url( get_term_link( $term ) ); ?>"
					class="category-tab<?php echo $is_current ? ' category-tab--active' : ''; ?>"
					<?php echo $is_current ? 'aria-current="page"' : ''; ?>
				><?php echo esc_html( $term->name ); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php endif; ?>

			<div class="products-layout">

				<aside class="products-sidebar" aria-label="<?php esc_attr_e( 'Filter products by category', 'it-hardware-supply' ); ?>">
					<?php get_template_part( 'template-parts/product-filter' ); ?>
				</aside>

				<div class="products-content">

					<?php get_template_part( 'template-parts/product-search' ); ?>

					<div class="products-meta">
						<p class="products-count" aria-live="polite" aria-atomic="true">
							<?php
							printf(
								esc_html( _n(
									'%1$s product in %2$s',
									'%1$s products in %2$s',
									$total_posts,
									'it-hardware-supply'
								) ),
								'<strong>' . number_format_i18n( $total_posts ) . '</strong>',
								'<strong>' . esc_html( $term_name ) . '</strong>'
							);
							?>
						</p>
						<div class="active-filters">
							<span class="active-filter-chip">
								<?php echo esc_html( $term_name ); ?>
								<a
									href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ); ?>"
									class="active-filter-chip__remove"
									aria-label="<?php echo esc_attr( sprintf( __( 'Remove filter: %s', 'it-hardware-supply' ), $term_name ) ); ?>"
								>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="14" height="14" aria-hidden="true"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414z" clip-rule="evenodd"/></svg>
								</a>
							</span>
						</div>
					</div>

					<div id="product-grid" class="product-grid" aria-label="<?php esc_attr_e( 'Products', 'it-hardware-supply' ); ?>">
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/product-card' ); ?>
							<?php endwhile; ?>
						<?php else : ?>
							<?php get_template_part( 'template-parts/content-none' ); ?>
						<?php endif; ?>
					</div>

					<?php
					if ( $GLOBALS['wp_query']->max_num_pages > 1 ) {
						echo it_hardware_pagination();
					}
					?>

				</div>
			</div>
		</div>
	</section>

</main>
<?php get_footer(); ?>
