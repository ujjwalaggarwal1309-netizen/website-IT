<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function it_hardware_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'it-hardware-supply' ),
		'id'            => 'sidebar',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Column One', 'it-hardware-supply' ),
		'id'            => 'footer-column-one',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Column Two', 'it-hardware-supply' ),
		'id'            => 'footer-column-two',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Column Three', 'it-hardware-supply' ),
		'id'            => 'footer-column-three',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
	) );
}
add_action( 'widgets_init', 'it_hardware_widgets_init' );
