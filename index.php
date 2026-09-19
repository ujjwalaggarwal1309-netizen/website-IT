<?php get_header(); ?>
<main id="content" class="site-main">
	<section class="page-section">
		<div class="container">
			<h1><?php esc_html_e( 'Infinity IT Solutions — Enterprise IT Hardware Supplier', 'it-hardware-supply' ); ?></h1>
			<p><?php esc_html_e( 'Browse our comprehensive range of Enterprise Infrastructure Solutions and genuine server spare parts sourced for modern business infrastructure.', 'it-hardware-supply' ); ?></p>
			<a class="btn btn-accent" href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ?: home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Explore Products', 'it-hardware-supply' ); ?></a>
		</div>
	</section>
</main>
<?php get_footer(); ?>
