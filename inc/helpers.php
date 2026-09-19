<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function it_hardware_get_page_header( $title = '' ) {
	return '<section class="page-header"><div class="container"><h1>' . esc_html( $title ) . '</h1></div></section>';
}
