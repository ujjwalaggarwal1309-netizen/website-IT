<?php
/**
 * inc/schema.php - JSON-LD Structured Data Output
 *
 * Outputs Google-compliant JSON-LD structured data in the <head>.
 * Automatically disables if major SEO plugins are active.
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output all relevant JSON-LD schemas for the current page context.
 */
function it_hardware_output_json_ld() {
	// Defer to SEO plugins if active.
	if (
		defined( 'WPSEO_VERSION' )         // Yoast SEO
		|| class_exists( 'RankMath' )      // RankMath
		|| class_exists( 'AIOSEO_Plugin' ) // All in One SEO
	) {
		return;
	}

	$schemas = array();

	// 1. Organization Schema (Always present as publisher/provider)
	$organization = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'Organization',
		'@id'          => home_url( '/#organization' ),
		'name'         => 'Infinity IT Solutions',
		'url'          => home_url( '/' ),
		'logo'         => get_template_directory_uri() . '/assets/logo/logo-primary.svg',
		'foundingDate' => '2018',
		'contactPoint' => array(
			'@type'       => 'ContactPoint',
			'telephone'   => '8398839899',
			'email'       => 'info@infinityitsolutions.co.in',
			'contactType' => 'customer service',
			'areaServed'  => 'IN', // India
			'availableLanguage' => 'English'
		),
	);
	$schemas[] = $organization;

	// 2. WebSite Schema (Homepage)
	if ( is_front_page() || is_home() ) {
		$website = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'@id'      => home_url( '/#website' ),
			'url'      => home_url( '/' ),
			'name'     => 'Infinity IT Solutions',
			'publisher' => array(
				'@id' => home_url( '/#organization' )
			)
		);
		$schemas[] = $website;
	}

	// 3. ContactPage Schema
	if ( is_page_template( 'page-contact.php' ) || ( is_page() && 'contact' === get_post_field( 'post_name', get_the_ID() ) ) ) {
		$contact_page = array(
			'@context' => 'https://schema.org',
			'@type'    => 'ContactPage',
			'@id'      => get_permalink() . '#webpage',
			'url'      => get_permalink(),
			'name'     => get_the_title(),
			'isPartOf' => array( '@id' => home_url( '/#website' ) ),
			'about'    => array( '@id' => home_url( '/#organization' ) )
		);
		$schemas[] = $contact_page;
	}
	// 4. AboutPage Schema
	elseif ( is_page_template( 'page-about.php' ) || ( is_page() && 'about' === get_post_field( 'post_name', get_the_ID() ) ) ) {
		$about_page = array(
			'@context' => 'https://schema.org',
			'@type'    => 'AboutPage',
			'@id'      => get_permalink() . '#webpage',
			'url'      => get_permalink(),
			'name'     => get_the_title(),
			'isPartOf' => array( '@id' => home_url( '/#website' ) ),
			'about'    => array( '@id' => home_url( '/#organization' ) )
		);
		$schemas[] = $about_page;
	}
	// Generic WebPage Schema (For other pages/posts)
	elseif ( is_singular() && ! is_singular( 'products' ) ) {
		$webpage = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'@id'      => get_permalink() . '#webpage',
			'url'      => get_permalink(),
			'name'     => get_the_title(),
			'isPartOf' => array( '@id' => home_url( '/#website' ) ),
		);
		$schemas[] = $webpage;
	}

	// 5. Product Schema
	if ( is_singular( 'products' ) ) {
		$post_id = get_the_ID();
		// Fetch product taxonomy to get the category name for 'category' property
		$terms = get_the_terms( $post_id, 'product-category' );
		$category_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'Enterprise IT Hardware';

		$product = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'@id'         => get_permalink() . '#product',
			'name'        => get_the_title(),
			'description' => wp_strip_all_tags( get_the_excerpt() ?: get_post_field( 'post_content', $post_id ) ),
			'category'    => $category_name,
			'url'         => get_permalink()
		);

		// Product Image
		if ( has_post_thumbnail() ) {
			$product['image'] = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
		}

		// Supported brands (Add as Brand if defined in meta, or use Infinity IT Solutions as the seller context)
		// Since we don't have a specific Brand meta field defined in the roadmap for individual products (only global supported brands),
		// we omit the 'brand' property to avoid unsupported schema properties, or we can use the generic organization.
		// It's safer to omit 'brand' and 'offers' since this is a catalog-only site (B2B, no pricing).

		$schemas[] = $product;
	}

	// 6. BreadcrumbList Schema
	$breadcrumbs = array();
	$breadcrumbs[] = array(
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => 'Home',
		'item'     => home_url( '/' )
	);
	
	$position = 2;

	if ( is_singular( 'products' ) ) {
		$terms = get_the_terms( get_the_ID(), 'product-category' );
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Products',
			'item'     => get_post_type_archive_link( 'products' )
		);
		if ( $terms && ! is_wp_error( $terms ) ) {
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $terms[0]->name,
				'item'     => get_term_link( $terms[0] )
			);
		}
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_title(),
			'item'     => get_permalink()
		);
	} elseif ( is_tax( 'product-category' ) ) {
		$term = get_queried_object();
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position++,
			'name'     => 'Products',
			'item'     => get_post_type_archive_link( 'products' )
		);
		if ( $term->parent ) {
			$parent = get_term( $term->parent, 'product-category' );
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position++,
				'name'     => $parent->name,
				'item'     => get_term_link( $parent )
			);
		}
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => $term->name,
			'item'     => get_term_link( $term )
		);
	} elseif ( is_post_type_archive( 'products' ) ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => 'Enterprise IT Hardware Solutions',
			'item'     => get_post_type_archive_link( 'products' )
		);
	} elseif ( is_page() && ! is_front_page() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'name'     => get_the_title(),
			'item'     => get_permalink()
		);
	}

	if ( count( $breadcrumbs ) > 1 ) {
		$breadcrumb_schema = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'@id'             => home_url( '/#breadcrumb' ),
			'itemListElement' => $breadcrumbs
		);
		$schemas[] = $breadcrumb_schema;
	}

	// Output the schemas
	if ( ! empty( $schemas ) ) {
		echo "\n<!-- JSON-LD Structured Data -->\n";
		foreach ( $schemas as $schema ) {
			// JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE keeps the output clean
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
		}
	}
}
add_action( 'wp_head', 'it_hardware_output_json_ld', 6 );
