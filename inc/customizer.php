<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * it_hardware_get_setting()
 *
 * Returns a theme mod value, falling back to verified company defaults.
 * All defaults reflect verified facts from VERIFIED_COMPANY_FACTS.md
 * and approved copy from WEBSITE_CONTENT.md.
 *
 * @param string $key The setting key.
 * @return mixed
 */
function it_hardware_get_setting( $key ) {
	$defaults = array(
		// -- Company identity ----------------------------------------------------
		'company_name'         => 'Infinity IT Solutions',
		'tagline'              => 'Reliable Enterprise Hardware. Trusted Solutions. PAN India Support.',
		'primary_phone'        => '8398839899',
		'email'                => 'info@infinityitsolutions.co.in',
		'working_hours'        => 'Monday - Saturday, 9:00 AM - 6:00 PM',
		'whatsapp_number'      => '918398839899',

		// -- Top bar -------------------------------------------------------------
		'top_bar_phone'        => '8398839899',
		'top_bar_email'        => 'info@infinityitsolutions.co.in',
		'top_bar_hours'        => 'Mon-Sat  9:00 AM - 6:00 PM',

		// -- Header --------------------------------------------------------------
		'header_cta_text'      => 'Enquire Now',

		// -- Product Archive -----------------------------------------------------
		'popular_searches'     => 'SAS HDD, SSD, RAID, Power Supply, Network Card',

		// -- Hero section --------------------------------------------------------
		// Content source: WEBSITE_CONTENT.md - Hero
		'hero_title'           => 'Powering Businesses with Reliable Enterprise IT Hardware Since 2018',
		// Paragraph 1 - company introduction (WEBSITE_CONTENT.md, Hero, para 1)
		'hero_para1'           => 'Infinity IT Solutions is an independent distributor and supplier of Enterprise Infrastructure Solutions, helping businesses across India build reliable, scalable, and cost-effective IT infrastructure. We specialize in enterprise servers, storage systems, workstations, desktops, and genuine server spare parts from globally recognized technology platforms.',
		// Paragraph 2 - nationwide supply + approach (WEBSITE_CONTENT.md, Hero, para 2)
		'hero_para2'           => 'With nationwide supply, professional installation support, and a customer-first approach, we deliver dependable enterprise solutions that maximize performance while optimizing IT investment.',
		// hero_subtitle kept for backward compatibility - not used by hero.php since Phase 3
		'hero_subtitle'        => 'Infinity IT Solutions is an independent distributor and supplier of Enterprise Infrastructure Solutions, helping businesses across India build reliable, scalable, and cost-effective IT infrastructure.',
		'hero_primary_button'  => 'Explore Products',
		'hero_secondary_button'=> 'Request a Quote',

		// -- About Preview (homepage section) ------------------------------------
		// Content source: WEBSITE_CONTENT.md - "About Preview"
		'about_preview_heading'=> 'Your Trusted Enterprise IT Hardware Partner',
		'about_preview_cta'    => 'Learn More About Us',

		// -- CTA banner ----------------------------------------------------------
		'cta_heading'          => 'Looking for Reliable Enterprise IT Hardware?',
		'cta_text'             => 'Whether you need enterprise servers, storage systems, workstations, desktops, or replacement server components, our specialists are ready to help you find the right solution for your business.',
		'cta_button'           => 'Request a Quote',

		// -- About section --------------------------------------------------------
		// Content source: WEBSITE_CONTENT.md - "About Page"
		// Para 1 (about_intro): founding + specialisation
		'about_intro'   => 'Founded in 2018, Infinity IT Solutions has grown into a trusted independent supplier of Enterprise Infrastructure Solutions for businesses across India.',
		// Para 2: product specialisation
		'about_para2'   => 'We specialize in supplying enterprise servers, storage systems, professional workstations, desktops, and genuine server spare parts that help organizations build dependable, scalable, and cost-effective IT infrastructure.',
		// Para 3: business objective
		'about_para3'   => 'Our objective is to provide quality enterprise hardware, practical recommendations, responsive support, and long-term value for every customer.',
		// Para 4: customer range
		'about_para4'   => 'From SMEs to large enterprises, system integrators, data centers, and IT-based organizations, we help businesses source reliable enterprise hardware that supports operational continuity and future growth.',
		// Mission / Vision / Values - editable in Customizer â†’ About Page
		'mission'       => 'To provide reliable and cost-effective enterprise IT hardware solutions supported by quality products, responsive service, and long-term customer relationships.',
		'vision'        => 'To become one of India\'s most trusted independent suppliers of Enterprise Infrastructure Solutions through professionalism, reliability, and customer-focused service.',
		'values'        => 'Reliability Â· Integrity Â· Customer Commitment Â· Professional Service Â· Quality First',
		// About CTA - WEBSITE_CONTENT.md - "About CTA"
		'about_cta_heading' => 'Let\'s Build Your IT Infrastructure Together',
		'about_cta_body'    => 'Speak with our specialists to find the right enterprise hardware solution for your organization.',
		'about_cta_button'  => 'Contact Our Experts',

		// -- Footer --------------------------------------------------------------
		'footer_description'   => 'Infinity IT Solutions is an independent distributor and supplier of Enterprise Infrastructure Solutions, providing servers, storage solutions, workstations, desktops, and genuine server spare parts to businesses across India since 2018.',
		'footer_tagline'       => 'Reliable Enterprise Hardware. Trusted Solutions. PAN India Support.',

		// -- Google Map ----------------------------------------------------------
		// Disabled until official address is confirmed by client.
		// To enable: replace the empty string with a Google Maps embed URL.
		'google_map_embed'     => '',
	);

	$value = get_theme_mod( $key, $defaults[ $key ] ?? '' );
	return $value;
}

/**
 * Register WordPress Customizer panels, sections, settings, and controls.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer manager object.
 */
function it_hardware_customize_register( $wp_customize ) {

	// -- Panel: Company Settings ----------------------------------------------
	$wp_customize->add_panel( 'it_hardware_general', array(
		'title'    => __( 'Theme Settings', 'it-hardware-supply' ),
		'priority' => 30,
	) );

	// Section: Company Information
	$wp_customize->add_section( 'it_hardware_company', array(
		'title'    => __( 'Company Information', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 10,
	) );

	$company_fields = array(
		'company_name'    => array( 'label' => __( 'Company Name', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'tagline'         => array( 'label' => __( 'Company Tagline', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'primary_phone'   => array( 'label' => __( 'Primary Phone', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'email'           => array( 'label' => __( 'Email Address', 'it-hardware-supply' ), 'type' => 'email', 'sanitize' => 'sanitize_email' ),
		'working_hours'   => array( 'label' => __( 'Working Hours', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'whatsapp_number' => array( 'label' => __( 'WhatsApp Number (digits only, with country code - e.g. 918398839899)', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $company_fields as $key => $args ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => $args['sanitize'] ) );
		$wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'it_hardware_company', 'type' => $args['type'] ) );
	}

	// Section: Top Bar
	$wp_customize->add_section( 'it_hardware_topbar', array(
		'title'    => __( 'Top Bar', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 20,
	) );

	$topbar_fields = array(
		'top_bar_phone'    => array( 'label' => __( 'Top Bar Phone', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'top_bar_email'    => array( 'label' => __( 'Top Bar Email', 'it-hardware-supply' ), 'type' => 'email', 'sanitize' => 'sanitize_email' ),
		'top_bar_hours'    => array( 'label' => __( 'Top Bar Hours', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $topbar_fields as $key => $args ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => $args['sanitize'] ) );
		$wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'it_hardware_topbar', 'type' => $args['type'] ) );
	}

	// Section: Header
	$wp_customize->add_section( 'it_hardware_header', array(
		'title'    => __( 'Header', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'header_cta_text', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'header_cta_text', array( 'label' => __( 'Header CTA Button Text', 'it-hardware-supply' ), 'section' => 'it_hardware_header', 'type' => 'text' ) );

	// Section: Product Archive
	$wp_customize->add_section( 'it_hardware_product_archive', array(
		'title'    => __( 'Product Archive', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 35,
	) );

	$wp_customize->add_setting( 'popular_searches', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'popular_searches', array( 'label' => __( 'Popular Searches (comma separated)', 'it-hardware-supply' ), 'section' => 'it_hardware_product_archive', 'type' => 'text' ) );

	// Section: Homepage
	$wp_customize->add_section( 'it_hardware_homepage', array(
		'title'    => __( 'Homepage', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 40,
	) );

	$homepage_fields = array(
		'hero_title'            => array( 'label' => __( 'Hero Headline', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'hero_subtitle'         => array( 'label' => __( 'Hero Description Paragraph', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'hero_primary_button'   => array( 'label' => __( 'Hero Primary Button Text', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'hero_secondary_button' => array( 'label' => __( 'Hero Secondary Button Text', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'cta_heading'           => array( 'label' => __( 'CTA Banner Heading', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'cta_text'              => array( 'label' => __( 'CTA Banner Body Text', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'cta_button'            => array( 'label' => __( 'CTA Banner Button Text', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $homepage_fields as $key => $args ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => $args['sanitize'] ) );
		$wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'it_hardware_homepage', 'type' => $args['type'] ) );
	}

	// Section: About Page
	$wp_customize->add_section( 'it_hardware_about', array(
		'title'    => __( 'About Page', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 50,
	) );

	$about_fields = array(
		'about_intro'       => array( 'label' => __( 'About Paragraph 1 - Founding', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'about_para2'       => array( 'label' => __( 'About Paragraph 2 - Specialisation', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'about_para3'       => array( 'label' => __( 'About Paragraph 3 - Objective', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'about_para4'       => array( 'label' => __( 'About Paragraph 4 - Customer Range', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'mission'           => array( 'label' => __( 'Mission Statement', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'vision'            => array( 'label' => __( 'Vision Statement', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'values'            => array( 'label' => __( 'Core Values (display only - edit list in mission-card.php)', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'about_cta_heading' => array( 'label' => __( 'About CTA Heading', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
		'about_cta_body'    => array( 'label' => __( 'About CTA Body Text', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'about_cta_button'  => array( 'label' => __( 'About CTA Button Text', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $about_fields as $key => $args ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => $args['sanitize'] ) );
		$wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'it_hardware_about', 'type' => $args['type'] ) );
	}


	// Section: Footer
	$wp_customize->add_section( 'it_hardware_footer', array(
		'title'    => __( 'Footer', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 60,
	) );

	$footer_fields = array(
		'footer_description' => array( 'label' => __( 'Footer Company Description', 'it-hardware-supply' ), 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field' ),
		'footer_tagline'     => array( 'label' => __( 'Footer Tagline', 'it-hardware-supply' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field' ),
	);

	foreach ( $footer_fields as $key => $args ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => $args['sanitize'] ) );
		$wp_customize->add_control( $key, array( 'label' => $args['label'], 'section' => 'it_hardware_footer', 'type' => $args['type'] ) );
	}

	// Section: Social Media
	$wp_customize->add_section( 'it_hardware_social', array(
		'title'    => __( 'Social Media', 'it-hardware-supply' ),
		'panel'    => 'it_hardware_general',
		'priority' => 70,
	) );

	$social_fields = array(
		'facebook'  => __( 'Facebook URL', 'it-hardware-supply' ),
		'linkedin'  => __( 'LinkedIn URL', 'it-hardware-supply' ),
		'instagram' => __( 'Instagram URL', 'it-hardware-supply' ),
		'twitter'   => __( 'Twitter / X URL', 'it-hardware-supply' ),
		'youtube'   => __( 'YouTube URL', 'it-hardware-supply' ),
	);

	foreach ( $social_fields as $key => $label ) {
		$wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'it_hardware_social', 'type' => 'url' ) );
	}
}
add_action( 'customize_register', 'it_hardware_customize_register' );
