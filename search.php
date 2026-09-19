<?php get_header(); ?>
<main id="content" class="site-main">
	<div class="container page-layout">
		<h1><?php printf( esc_html__( 'Search results for: %s', 'it-hardware-supply' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<article class="archive-card">
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php the_excerpt(); ?></p>
			</article>
		<?php endwhile; else : ?>
			<p><?php esc_html_e( 'No results found.', 'it-hardware-supply' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
