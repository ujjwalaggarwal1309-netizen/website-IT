<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function it_hardware_social_links() {
	$socials = array(
		'facebook' => get_theme_mod( 'facebook' ),
		'linkedin' => get_theme_mod( 'linkedin' ),
		'instagram' => get_theme_mod( 'instagram' ),
		'twitter' => get_theme_mod( 'twitter' ),
		'youtube' => get_theme_mod( 'youtube' ),
	);
	$links = array();
	foreach ( $socials as $key => $url ) {
		if ( ! empty( $url ) ) {
			$links[] = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( ucfirst( $key ) ) . '</a>';
		}
	}
	return implode( '', $links );
}
