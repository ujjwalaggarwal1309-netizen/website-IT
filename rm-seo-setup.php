<?php
/**
 * RankMath Full SEO Setup - Run once, then DELETE!
 */
// Navigate up 4 levels from /wp-content/themes/theme/ to reach wp-load.php
require_once( dirname(__FILE__) . '/../../../../wp-load.php' );

if ( ! current_user_can('manage_options') ) {
    die('Please log into WordPress as admin first, then revisit this URL.');
}

echo '<pre style="font-family:monospace;padding:20px;background:#f0f0f0;">';
echo "=== RankMath SEO Setup ===\n\n";

$pages_data = array(
    'home'    => array(
        'keyword'     => 'enterprise IT hardware India',
        'title'       => 'Infinity IT Solutions | Enterprise IT Hardware Supplier India',
        'description' => 'Infinity IT Solutions delivers enterprise IT hardware across India. Servers, storage, workstations and server spare parts. PAN India supply since 2018.',
        'content'     => 'Infinity IT Solutions is a trusted B2B supplier of enterprise IT hardware across India. We supply servers, storage solutions, workstations, business desktops and genuine server spare parts to businesses of all sizes. PAN India delivery and installation support since 2018.',
    ),
    'about'   => array(
        'keyword'     => 'IT hardware supplier India',
        'title'       => 'About Us | Infinity IT Solutions - Enterprise IT Hardware Supplier',
        'description' => 'Founded in 2018, Infinity IT Solutions is a trusted independent enterprise IT hardware supplier across India. Learn about our mission, vision and values.',
        'content'     => 'Infinity IT Solutions was founded in 2018 with a mission to deliver reliable enterprise IT hardware to businesses across India. As an independent IT hardware supplier in India, we provide servers, storage, workstations, desktops, and genuine server spare parts. Our team provides PAN India supply and installation support.',
    ),
    'contact' => array(
        'keyword'     => 'contact IT hardware supplier India',
        'title'       => 'Contact Us | Infinity IT Solutions - Enterprise IT Hardware',
        'description' => 'Contact Infinity IT Solutions for enterprise IT hardware enquiries. Call 8398839899 or email info@infinityitsolutions.co.in. Available Mon-Sat 9AM-6PM.',
        'content'     => 'Contact Infinity IT Solutions for enterprise IT hardware enquiries. We supply servers, storage, workstations, and spare parts across India. Call 8398839899 or email info@infinityitsolutions.co.in. Monday to Saturday, 9 AM to 6 PM.',
    ),
);

foreach ( $pages_data as $slug => $data ) {
    if ( $slug === 'home' ) {
        $front_id = get_option('page_on_front');
        $page = $front_id ? get_post($front_id) : null;
        if (!$page) {
            $r = get_posts(array('post_type'=>'page','name'=>'home','posts_per_page'=>1,'post_status'=>'any'));
            $page = !empty($r) ? $r[0] : null;
        }
    } else {
        $r = get_posts(array('post_type'=>'page','name'=>$slug,'posts_per_page'=>1,'post_status'=>'any'));
        $page = !empty($r) ? $r[0] : null;
    }

    if ( ! $page ) { echo "[SKIP] Page not found: $slug\n"; continue; }

    // Add body content if page is empty (needed for RankMath analysis)
    if ( empty( trim( strip_tags( $page->post_content ) ) ) ) {
        wp_update_post(array('ID' => $page->ID, 'post_content' => $data['content']));
        echo "[CONTENT] Added body text to: {$page->post_title}\n";
    }

    update_post_meta($page->ID, 'rank_math_focus_keyword', $data['keyword']);
    update_post_meta($page->ID, 'rank_math_title',         $data['title']);
    update_post_meta($page->ID, 'rank_math_description',   $data['description']);

    echo "[OK] {$page->post_title} (ID:{$page->ID}) => keyword: {$data['keyword']}\n";
}

// Bulk product keywords
echo "\nBulk-setting product focus keywords...\n";
$products = get_posts(array('post_type'=>'products','posts_per_page'=>-1,'post_status'=>'publish'));
$done = 0;
foreach ( $products as $p ) {
    if ( empty( get_post_meta($p->ID, 'rank_math_focus_keyword', true) ) ) {
        $kw = strtolower( implode(' ', array_slice( explode(' ', trim($p->post_title)), 0, 4 ) ) );
        update_post_meta($p->ID, 'rank_math_focus_keyword', $kw);
        $done++;
    }
}
echo "[OK] Set keywords for $done products.\n";
echo "\n=== ALL DONE! Please DELETE this file now for security. ===\n";
echo '</pre>';
