<?php get_header(); ?>
<main id="content" class="site-main">
	<div class="container page-layout">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-entry' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-image">
						<?php the_post_thumbnail( 'large', array( 'class' => 'responsive-image' ) ); ?>
					</div>
				<?php endif; ?>
				<header class="entry-header">
					<h1><?php the_title(); ?></h1>
				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
