<?php get_header(); ?>
<main id="content" class="site-main">
	<div class="container page-layout">
		<h1><?php the_archive_title(); ?></h1>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="archive-card">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php the_excerpt(); ?></p>
			</article>
		<?php endwhile; endif; ?>
		<?php echo it_hardware_pagination(); ?>
	</div>
</main>
<?php get_footer(); ?>
