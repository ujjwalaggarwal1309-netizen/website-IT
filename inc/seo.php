<?php
/**
 * inc/seo.php - Theme-level SEO meta output
 *
 * Outputs per-page <meta name="description"> and Open Graph tags
 * into <head> via the wp_head hook.
 *
 * These are fallback descriptions used when no SEO plugin (e.g. Yoast, RankMath)
 * is active. If an SEO plugin is active it will override these via its own hooks.
 *
 * Content source: WEBSITE_CONTENT.md
 * Data source:    VERIFIED_COMPANY_FACTS.md
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the meta description for the current page/context.
 * All descriptions follow WEBSITE_CONTENT.md content rules:
 *   - Professional B2B tone
 *   - No pricing
 *   - No invented statistics
 *   - No claim of authorized partnership
 *
 * @return string Plain-text meta description (max ~160 chars).
 */
function it_hardware_get_meta_description() {

	// Individual page with a custom excerpt - use that first.
	if ( is_singular() && has_excerpt() ) {
		return wp_strip_all_tags( get_the_excerpt() );
	}

	// Context-specific descriptions.
	if ( is_front_page() || is_home() ) {
		return 'Infinity IT Solutions - independent distributor of Enterprise Infrastructure Solutions. Servers, storage, workstations, desktops, and spare parts. PAN India supply since 2018.';
	}

	if ( is_page_template( 'page-about.php' ) || ( is_page() && 'about' === get_post_field( 'post_name', get_the_ID() ) ) ) {
		return 'Founded in 2018, Infinity IT Solutions is a trusted independent supplier of Enterprise Infrastructure Solutions for businesses across India. Learn about our mission, vision, and values.';
	}

	if ( is_page_template( 'page-contact.php' ) || ( is_page() && 'contact' === get_post_field( 'post_name', get_the_ID() ) ) ) {
		return 'Contact Infinity IT Solutions for enterprise IT hardware enquiries. Phone: 8398839899. Email: info@infinityitsolutions.co.in. Mon-Sat 9 AM-6 PM.';
	}

	if ( is_post_type_archive( 'products' ) ) {
		return 'Explore our range of Enterprise Infrastructure Solutions - servers, storage, workstations, desktops, and genuine server spare parts. PAN India supply.';
	}

	if ( is_singular( 'products' ) ) {
		return wp_strip_all_tags( get_the_excerpt() ) ?: 'Enterprise Infrastructure Solutions from Infinity IT Solutions. Quality products, professional support, PAN India delivery.';
	}

	if ( is_tax( 'product-category' ) ) {
		$term = get_queried_object();
		if ( $term && $term->description ) {
			return wp_strip_all_tags( $term->description );
		}
		return 'Browse ' . esc_html( $term->name ?? 'enterprise hardware' ) . ' from Infinity IT Solutions. Enterprise Infrastructure Solutions, PAN India supply.';
	}

	// Generic site-wide fallback.
	return 'Infinity IT Solutions - B2B supplier of Enterprise Infrastructure Solutions, servers, storage, and spare parts. PAN India supply and installation support since 2018.';
}

/**
 * Returns the Open Graph title for the current page/context.
 *
 * @return string OG title string.
 */
function it_hardware_get_og_title() {
	$site_name = get_bloginfo( 'name' );

	if ( is_front_page() || is_home() ) {
		return $site_name . ' - Enterprise IT Hardware Supplier, PAN India';
	}

	if ( is_singular() || is_page() ) {
		return get_the_title() . ' | ' . $site_name;
	}

	if ( is_post_type_archive( 'products' ) ) {
		return 'Enterprise IT Hardware Products | ' . $site_name;
	}

	if ( is_tax( 'product-category' ) ) {
		$term = get_queried_object();
		return esc_html( $term->name ?? 'Products' ) . ' | ' . $site_name;
	}

	return get_the_title() . ' | ' . $site_name;
}

/**
 * Output <meta> description and Open Graph tags in <head>.
 * Skips output if a known SEO plugin is already active to avoid duplication.
 */
function it_hardware_output_seo_meta() {

	// Defer to SEO plugins - they handle their own output.
	if (
		defined( 'WPSEO_VERSION' )         // Yoast SEO
		|| class_exists( 'RankMath' )      // RankMath
		|| class_exists( 'AIOSEO_Plugin' ) // All in One SEO
	) {
		return;
	}

	$description = it_hardware_get_meta_description();
	$og_title    = it_hardware_get_og_title();
	// -- Canonical / og:url --------------------------------------------------
	// Must be the clean, pagination-free canonical URL for each context.
	if ( is_singular() || is_page() ) {
		// Single post/page: use WordPress permalink (already canonical).
		$canonical_url = get_permalink();
	} elseif ( is_tax() || is_category() || is_tag() ) {
		// Taxonomy archive: get_term_link() returns the clean term URL.
		$queried = get_queried_object();
		$canonical_url = ( $queried instanceof WP_Term ) ? get_term_link( $queried ) : home_url( '/' );
		if ( is_wp_error( $canonical_url ) ) {
			$canonical_url = home_url( '/' );
		}
	} elseif ( is_post_type_archive() ) {
		// CPT archive (e.g. /products/): clean archive link without query args.
		$canonical_url = get_post_type_archive_link( get_post_type() ?: 'post' ) ?: home_url( '/' );
	} elseif ( is_home() || is_front_page() ) {
		$canonical_url = home_url( '/' );
	} else {
		// Fallback: current URL without query string.
		$canonical_url = home_url( parse_url( add_query_arg( array() ), PHP_URL_PATH ) ?: '/' );
	}
	$og_url = esc_url( $canonical_url );
	$og_image    = esc_url( get_template_directory_uri() . '/assets/images/og-image.jpg' );
	$site_name   = esc_attr( get_bloginfo( 'name' ) );

	// Check for custom OG image set via Featured Image.
	if ( is_singular() && has_post_thumbnail() ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'og-image' );
		if ( ! empty( $thumb[0] ) ) {
			$og_image = esc_url( $thumb[0] );
		}
	}
	?>
	<meta name="description" content="<?php echo esc_attr( $description ); ?>">

	<!-- Open Graph / Facebook -->
	<meta property="og:type"        content="<?php echo is_singular() ? 'article' : 'website'; ?>">
	<meta property="og:url"         content="<?php echo $og_url; ?>">
	<meta property="og:title"       content="<?php echo esc_attr( $og_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
	<meta property="og:image"       content="<?php echo $og_image; ?>">
	<meta property="og:site_name"   content="<?php echo $site_name; ?>">
	<meta property="og:locale"      content="en_IN">

	<!-- Twitter Card -->
	<meta name="twitter:card"        content="summary_large_image">
	<meta name="twitter:title"       content="<?php echo esc_attr( $og_title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
	<meta name="twitter:image"       content="<?php echo $og_image; ?>">

	<!-- Canonical URL -->
	<link rel="canonical" href="<?php echo $og_url; ?>">
	<?php
}
add_action( 'wp_head', 'it_hardware_output_seo_meta', 5 );
