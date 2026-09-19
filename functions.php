<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/breadcrumbs.php';
require_once get_template_directory() . '/inc/pagination.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/performance.php';
require_once get_template_directory() . '/inc/product-taxonomy.php'; // Must load before products CPT
require_once get_template_directory() . '/inc/products.php';
require_once get_template_directory() . '/inc/product-fields.php';
require_once get_template_directory() . '/inc/product-ajax.php';
require_once get_template_directory() . '/inc/product-template-functions.php';
require_once get_template_directory() . '/inc/company-settings.php';
require_once get_template_directory() . '/inc/contact.php';
require_once get_template_directory() . '/inc/social.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/og-supplement.php';
require_once get_template_directory() . '/inc/schema.php';
require_once get_template_directory() . '/inc/enquiries.php';

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/import/ImportServiceProvider.php';
	( new ITHS\Import\ImportServiceProvider() )->boot();
	require_once get_template_directory() . '/inc/image-acquisition.php';
}

add_action( 'init', 'it_hardware_remove_localwp_favicon' );
function it_hardware_remove_localwp_favicon() {
	delete_option( 'site_icon' );
}

