<?php get_header(); ?>
<main id="content" class="site-main">
	<header class="page-header" aria-labelledby="page-title-<?php echo get_the_ID(); ?>">
		<?php /* Blurred left glow circle */ ?>
		<div class="page-header__glow-left" aria-hidden="true"></div>
		<div class="container page-header__inner">
			<h1 id="page-title-<?php echo get_the_ID(); ?>"><?php the_title(); ?></h1>
			<div class="page-header__divider" aria-hidden="true"></div>
			<?php if ( function_exists( 'it_hardware_breadcrumbs' ) ) { it_hardware_breadcrumbs(); } ?>
		</div>
	</header>
	<div class="container page-layout" style="padding: 60px 0;">
		<div class="content-column">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/content', 'page' ); ?>
			<?php endwhile; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
