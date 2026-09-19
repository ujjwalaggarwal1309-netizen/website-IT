<?php
/**
 * OG Meta Supplement
 * Forces og:image, og:site_name and twitter cards even when RankMath is active
 * (RankMath free omits several OG fields)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function iths_supplement_og_meta() {
    // Only run if RankMath is active (otherwise our main seo.php handles it)
    if ( ! class_exists( 'RankMath' ) ) { return; }

    $og_image  = esc_url( get_template_directory_uri() . '/assets/images/og-image.jpg' );
    if ( is_singular() && has_post_thumbnail() ) {
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
        if ( ! empty( $thumb[0] ) ) { $og_image = esc_url( $thumb[0] ); }
    }
    $site_name = esc_attr( get_bloginfo( 'name' ) );
    $og_title  = is_singular() ? esc_attr( get_the_title() . ' | ' . $site_name ) : esc_attr( $site_name . ' | Enterprise IT Hardware' );
    $desc      = is_singular() && has_excerpt() ? esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) : esc_attr( 'Infinity IT Solutions - enterprise servers, storage, workstations and spare parts. PAN India supply since 2018.' );
    ?>
    <meta property="og:image"       content="<?php echo $og_image; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name"   content="<?php echo $site_name; ?>">
    <meta property="og:locale"      content="en_IN">
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo $og_title; ?>">
    <meta name="twitter:description" content="<?php echo $desc; ?>">
    <meta name="twitter:image"       content="<?php echo $og_image; ?>">
    <?php
}
add_action( 'wp_head', 'iths_supplement_og_meta', 6 );
