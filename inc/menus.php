<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function it_hardware_menus_init() {
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'it-hardware-supply' ),
		'mobile'  => __( 'Mobile Menu', 'it-hardware-supply' ),
	) );
}
add_action( 'after_setup_theme', 'it_hardware_menus_init' );
