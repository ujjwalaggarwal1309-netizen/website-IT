<?php get_header(); ?>
<main id="content" class="site-main">
	<section class="error-page">
		<div class="container error-container">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404-illustration.svg' ); ?>" alt="" />
			<h1><?php esc_html_e( '404', 'it-hardware-supply' ); ?></h1>
			<h2><?php esc_html_e( 'Page Not Found', 'it-hardware-supply' ); ?></h2>
			<p><?php esc_html_e( 'The page you requested could not be found. Please return home or browse the product catalogue.', 'it-hardware-supply' ); ?></p>
			<div class="button-group">
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return Home', 'it-hardware-supply' ); ?></a>
				<a class="btn btn-secondary" href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ); ?>"><?php esc_html_e( 'Browse Products', 'it-hardware-supply' ); ?></a>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
