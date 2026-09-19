<?php get_header(); ?>
<main class="site-main">
	<div class="container page-layout">
		<h1><?php single_tag_title(); ?></h1>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="archive-card">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php the_excerpt(); ?></p>
			</article>
		<?php endwhile; endif; ?>
	</div>
</main>
<?php get_footer(); ?>
