<?php
/**
 * inc/enquiries.php — Customer Enquiries Database
 *
 * Registers a private Custom Post Type to store contact form submissions.
 * Prevents manual creation of records (read-only for admins).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Register the 'enquiry' CPT
add_action( 'init', 'it_hardware_register_enquiries_cpt' );
function it_hardware_register_enquiries_cpt() {
	$labels = array(
		'name'               => _x( 'Enquiries', 'Post Type General Name', 'it-hardware-supply' ),
		'singular_name'      => _x( 'Enquiry', 'Post Type Singular Name', 'it-hardware-supply' ),
		'menu_name'          => __( 'Enquiries', 'it-hardware-supply' ),
		'all_items'          => __( 'All Enquiries', 'it-hardware-supply' ),
		'view_item'          => __( 'View Enquiry', 'it-hardware-supply' ),
		'search_items'       => __( 'Search Enquiries', 'it-hardware-supply' ),
		'not_found'          => __( 'No enquiries found', 'it-hardware-supply' ),
		'not_found_in_trash' => __( 'No enquiries found in Trash', 'it-hardware-supply' ),
	);
	
	$args = array(
		'label'               => __( 'Enquiry', 'it-hardware-supply' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 26, // Below comments
		'menu_icon'           => 'dashicons-email-alt',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capabilities'        => array(
			'create_posts' => 'do_not_allow', // Prevents manual creation
		),
		'map_meta_cap'        => true,
	);
	
	register_post_type( 'enquiry', $args );
}

// 2. Add Meta Box for Contact Details
add_action( 'add_meta_boxes', 'it_hardware_enquiry_meta_boxes' );
function it_hardware_enquiry_meta_boxes() {
	add_meta_box(
		'enquiry_details',
		__( 'Customer Details', 'it-hardware-supply' ),
		'it_hardware_enquiry_meta_box_html',
		'enquiry',
		'side',
		'high'
	);
}

function it_hardware_enquiry_meta_box_html( $post ) {
	$name    = get_post_meta( $post->ID, '_enquiry_name', true );
	$email   = get_post_meta( $post->ID, '_enquiry_email', true );
	$phone   = get_post_meta( $post->ID, '_enquiry_phone', true );
	$company = get_post_meta( $post->ID, '_enquiry_company', true );

	echo '<div style="padding: 10px 0;">';
	echo '<p style="margin-top:0;"><strong>' . esc_html__( 'Name:', 'it-hardware-supply' ) . '</strong><br>' . esc_html( $name ) . '</p>';
	echo '<p><strong>' . esc_html__( 'Email:', 'it-hardware-supply' ) . '</strong><br><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
	echo '<p><strong>' . esc_html__( 'Phone:', 'it-hardware-supply' ) . '</strong><br><a href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></p>';
	
	if ( ! empty( $company ) ) {
		echo '<p><strong>' . esc_html__( 'Company:', 'it-hardware-supply' ) . '</strong><br>' . esc_html( $company ) . '</p>';
	}
	echo '</div>';
}

// 3. Custom columns in admin list
add_filter( 'manage_enquiry_posts_columns', 'it_hardware_set_custom_enquiry_columns' );
function it_hardware_set_custom_enquiry_columns( $columns ) {
	unset( $columns['date'] );
	$columns['title'] = __( 'Subject', 'it-hardware-supply' );
	$columns['enquiry_name'] = __( 'Name', 'it-hardware-supply' );
	$columns['enquiry_email'] = __( 'Email', 'it-hardware-supply' );
	$columns['date'] = __( 'Date Received', 'it-hardware-supply' );
	return $columns;
}

add_action( 'manage_enquiry_posts_custom_column' , 'it_hardware_custom_enquiry_column', 10, 2 );
function it_hardware_custom_enquiry_column( $column, $post_id ) {
	switch ( $column ) {
		case 'enquiry_name':
			echo esc_html( get_post_meta( $post_id, '_enquiry_name', true ) );
			break;
		case 'enquiry_email':
			$email = get_post_meta( $post_id, '_enquiry_email', true );
			echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			break;
	}
}
