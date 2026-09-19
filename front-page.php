<?php
/**
 * front-page.php — Homepage template
 *
 * Section order per WEBSITE_CONTENT.md and WEBSITE_DESIGN_SPEC.md:
 *   1. Hero
 *   2. Product Category Grid
 *   3. About Preview         ← Phase 3 addition
 *   4. Why Choose Us         ← Phase 3 addition
 *   5. Stats Bar
 *   6. Brands We Support     ← Phase 3 addition
 *   7. CTA Banner
 *
 * @package it-hardware-supply
 */

get_header();
?>
<main id="content" class="site-main" role="main">
	<?php get_template_part( 'template-parts/hero' ); ?>
	<?php get_template_part( 'template-parts/about-preview' ); ?>
	<?php get_template_part( 'template-parts/category-card' ); ?>
	<?php get_template_part( 'template-parts/why-choose-us' ); ?>
	<?php get_template_part( 'template-parts/stats' ); ?>
	<?php get_template_part( 'template-parts/brands' ); ?>
	<?php get_template_part( 'template-parts/cta' ); ?>
</main>
<?php get_footer(); ?>
