<?php
/**
 * archive-products.php — Enterprise Products Archive Template
 *
 * @package it-hardware-supply
 */

get_header();

// Fetch Popular Searches from customizer
$popular_searches_str = it_hardware_get_setting( 'popular_searches' );
$popular_searches = array_filter( array_map( 'trim', explode( ',', $popular_searches_str ) ) );

// Fetch terms for taxonomies
$categories = get_terms( array( 'taxonomy' => 'product-category', 'hide_empty' => false ) );
$brands     = get_terms( array( 'taxonomy' => 'product-brand', 'hide_empty' => false ) );
$series     = get_terms( array( 'taxonomy' => 'product-series', 'hide_empty' => false ) );

global $wpdb;

function get_distinct_meta_values( $meta_key ) {
	global $wpdb;
	$transient_key = 'iths_meta_values_' . $meta_key;
	$values = get_transient( $transient_key );
	if ( false === $values ) {
		$values = $wpdb->get_col( $wpdb->prepare(
			"SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE pm.meta_key = %s AND p.post_status = 'publish' AND p.post_type = 'products' AND pm.meta_value != ''",
			$meta_key
		) );
		set_transient( $transient_key, $values, HOUR_IN_SECONDS * 24 );
	}
	return $values;
}

$product_types  = get_distinct_meta_values( 'iths_product_type' );
$availabilities = get_distinct_meta_values( 'iths_availability' );
$conditions     = get_distinct_meta_values( 'iths_condition' );
$warranty_types = get_distinct_meta_values( 'iths_warranty_type' );

$total_posts = (int) $GLOBALS['wp_query']->found_posts;
?>

<main id="content" class="site-main archive-main" role="main">

	<header class="page-header" aria-labelledby="archive-title">
		<div class="page-header__glow-left" aria-hidden="true"></div>
		<div class="page-header__circuit" aria-hidden="true">
			<svg viewBox="0 0 1440 380" preserveAspectRatio="xMidYMid slice" fill="none" xmlns="http://www.w3.org/2000/svg">
				<line x1="0" y1="80" x2="1440" y2="80" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="200" x2="1440" y2="200" stroke="currentColor" stroke-width="1"/>
				<line x1="0" y1="320" x2="1440" y2="320" stroke="currentColor" stroke-width="1"/>
				<line x1="120" y1="0" x2="120" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="360" y1="0" x2="360" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="720" y1="0" x2="720" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1080" y1="0" x2="1080" y2="380" stroke="currentColor" stroke-width="1"/>
				<line x1="1320" y1="0" x2="1320" y2="380" stroke="currentColor" stroke-width="1"/>
				<circle cx="120" cy="80" r="5" fill="currentColor"/>
				<circle cx="360" cy="80" r="5" fill="currentColor"/>
				<circle cx="720" cy="80" r="5" fill="currentColor"/>
				<circle cx="1080" cy="80" r="5" fill="currentColor"/>
				<circle cx="1320" cy="80" r="5" fill="currentColor"/>
				<circle cx="120" cy="200" r="5" fill="currentColor"/>
				<circle cx="360" cy="200" r="5" fill="currentColor"/>
				<circle cx="720" cy="200" r="5" fill="currentColor"/>
				<circle cx="1080" cy="200" r="5" fill="currentColor"/>
				<circle cx="120" cy="320" r="5" fill="currentColor"/>
				<circle cx="360" cy="320" r="5" fill="currentColor"/>
				<circle cx="720" cy="320" r="5" fill="currentColor"/>
				<rect x="200" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="580" y="230" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="900" y="110" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
				<rect x="1200" y="240" width="40" height="24" rx="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
			</svg>
		</div>

		<div class="page-header__deco page-header__deco--left" aria-hidden="true">
			<svg width="320" height="260" viewBox="0 0 320 260" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="20" y="20" width="240" height="220" rx="8" stroke="currentColor" stroke-width="2"/>
				<rect x="32" y="36" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="50" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="50" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="72" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="86" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="86" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="108" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="122" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="122" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="144" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="158" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="158" r="5" stroke="currentColor" stroke-width="1.5"/>
				<rect x="32" y="180" width="216" height="28" rx="4" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="224" cy="194" r="5" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="240" cy="194" r="5" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>

		<div class="page-header__deco page-header__deco--right" aria-hidden="true">
			<svg width="220" height="260" viewBox="0 0 220 260" fill="none" xmlns="http://www.w3.org/2000/svg">
				<rect x="20" y="20" width="80" height="80" rx="6" stroke="currentColor" stroke-width="2"/>
				<rect x="40" y="40" width="40" height="40" rx="3" stroke="currentColor" stroke-width="1.5"/>
				<rect x="120" y="30" width="80" height="50" rx="6" stroke="currentColor" stroke-width="2"/>
				<rect x="20" y="130" width="180" height="30" rx="6" stroke="currentColor" stroke-width="2"/>
				<circle cx="40" cy="145" r="8" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="170" cy="145" r="8" stroke="currentColor" stroke-width="1.5"/>
				<rect x="20" y="180" width="180" height="30" rx="6" stroke="currentColor" stroke-width="2"/>
				<circle cx="40" cy="195" r="8" stroke="currentColor" stroke-width="1.5"/>
				<circle cx="170" cy="195" r="8" stroke="currentColor" stroke-width="1.5"/>
			</svg>
		</div>

		<div class="container page-header__inner">
			<span class="page-header__label"><?php esc_html_e( 'PRODUCTS', 'it-hardware-supply' ); ?></span>
			<h1 id="archive-title"><?php esc_html_e( 'All Enterprise IT Hardware', 'it-hardware-supply' ); ?></h1>
			<div class="page-header__divider" aria-hidden="true"></div>
			<p class="page-header__subtitle"><?php esc_html_e( 'OEM server parts, storage, networking, memory and enterprise infrastructure.', 'it-hardware-supply' ); ?></p>
			<?php if ( function_exists( 'it_hardware_breadcrumbs' ) ) { it_hardware_breadcrumbs(); } ?>
		</div>
	</header>

	<div class="container">
		<?php /* ── Enterprise Search Section ──────────────────────────────── */ ?>
		<section class="enterprise-search-block">
			<div class="search-area">
				<h2><?php esc_html_e( 'Search Enterprise IT Hardware', 'it-hardware-supply' ); ?></h2>
				<div class="search-field-wrapper">
					<input type="search" id="archive-search-input" class="enterprise-search-input" placeholder="<?php esc_attr_e( 'OEM Part Number, Model Number or Product Name', 'it-hardware-supply' ); ?>" autocomplete="off" />
					<button type="button" class="enterprise-search-btn" aria-label="<?php esc_attr_e( 'Search', 'it-hardware-supply' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					</button>
				</div>
				<?php if ( ! empty( $popular_searches ) ) : ?>
					<div class="popular-searches">
						<span class="popular-label"><?php esc_html_e( 'Popular:', 'it-hardware-supply' ); ?></span>
						<?php $popular_i = 0; foreach ( $popular_searches as $term ) : ?>
							<button type="button" class="popular-search-chip <?php echo ( 0 === $popular_i ) ? 'highlight' : ''; ?>" data-term="<?php echo esc_attr( $term ); ?>"><?php echo esc_html( $term ); ?></button>
						<?php $popular_i++; endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="help-card">
				<h3><?php esc_html_e( 'Can\'t Find Your Part?', 'it-hardware-supply' ); ?></h3>
				<p><?php esc_html_e( 'Tell us your server model or OEM part number. We\'ll help identify the correct compatible hardware.', 'it-hardware-supply' ); ?></p>
				<p class="help-trust-indicator">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
					<?php esc_html_e( 'OEM Compatibility Assistance', 'it-hardware-supply' ); ?>
				</p>
				<div class="help-card-actions">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Talk to Sales', 'it-hardware-supply' ); ?></a>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', it_hardware_get_setting( 'primary_phone' ) ) ); ?>" class="help-icon-btn" aria-label="<?php esc_attr_e( 'Call Us', 'it-hardware-supply' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
					</a>
					<?php $wa = it_hardware_get_setting( 'whatsapp_number' ); if ( $wa ) : ?>
						<a href="https://wa.me/<?php echo esc_attr( $wa ); ?>" target="_blank" rel="noopener noreferrer" class="help-icon-btn help-icon-btn--whatsapp" aria-label="<?php esc_attr_e( 'WhatsApp', 'it-hardware-supply' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<?php /* ── Archive Toolbar ────────────────────────────────────────── */ ?>
		<div class="archive-toolbar">
			<div class="toolbar-results">
				<span class="toolbar-count-total" id="archive-total-count"><?php echo $total_posts > 0 ? number_format_i18n( $total_posts ) . ' Products' : '0 Products'; ?></span>
				<span class="toolbar-count-showing" id="archive-showing-count"><?php echo $total_posts > 0 ? 'Showing 1&ndash;' . min( 12, $total_posts ) : 'No products to display'; ?></span>
			</div>
			<div class="toolbar-actions">
				<button type="button" class="btn-mobile-filters" id="btn-mobile-filters">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
					<?php esc_html_e( 'Filters', 'it-hardware-supply' ); ?> <span class="mobile-filter-count"></span>
				</button>
				<div class="sort-wrapper">
					<label for="archive-sort" class="screen-reader-text"><?php esc_html_e( 'Sort By', 'it-hardware-supply' ); ?></label>
					<select id="archive-sort" class="archive-sort-select" name="archive_sort">
						<option value="newest"><?php esc_html_e( 'Sort: Newest', 'it-hardware-supply' ); ?></option>
						<option value="az"><?php esc_html_e( 'Sort: A–Z', 'it-hardware-supply' ); ?></option>
						<option value="brand"><?php esc_html_e( 'Sort: Brand', 'it-hardware-supply' ); ?></option>
						<option value="category"><?php esc_html_e( 'Sort: Category', 'it-hardware-supply' ); ?></option>
						<option value="updated"><?php esc_html_e( 'Sort: Recently Updated', 'it-hardware-supply' ); ?></option>
					</select>
				</div>
				<div class="view-toggle">
					<button type="button" class="view-btn view-btn--grid active" aria-label="<?php esc_attr_e( 'Grid View', 'it-hardware-supply' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
					</button>
					<button type="button" class="view-btn view-btn--list" aria-label="<?php esc_attr_e( 'List View', 'it-hardware-supply' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
					</button>
				</div>
			</div>
		</div>

		<?php /* ── Main Layout (Sidebar + Grid) ──────────────────────────── */ ?>
		<div class="archive-layout">
			
			<?php /* Sidebar Filters */ ?>
			<aside class="archive-sidebar" id="archive-sidebar">
				<div class="sidebar-header">
					<span class="sidebar-title"><?php esc_html_e( 'Filters', 'it-hardware-supply' ); ?></span>
					<button type="button" class="btn-clear-filters" id="btn-clear-filters"><?php esc_html_e( 'Clear All', 'it-hardware-supply' ); ?></button>
					<button type="button" class="btn-close-sidebar" id="btn-close-sidebar" aria-label="<?php esc_attr_e( 'Close Filters', 'it-hardware-supply' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
					</button>
				</div>
				<form class="sidebar-content" id="archive-filters-form">
					
					<?php if ( ! is_wp_error( $categories ) && ! empty( $categories ) ) : ?>
					<div class="filter-group open">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Category', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $categories as $term ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="product-category[]" value="<?php echo esc_attr( $term->slug ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $term->name ); ?></span>
									<span class="count"><?php echo esc_html( $term->count ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $brands ) && ! empty( $brands ) ) : ?>
					<div class="filter-group open">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Brand', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $brands as $term ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="product-brand[]" value="<?php echo esc_attr( $term->slug ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $term->name ); ?></span>
									<span class="count"><?php echo esc_html( $term->count ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $series ) && ! empty( $series ) ) : ?>
					<div class="filter-group open">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Series', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $series as $term ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="product-series[]" value="<?php echo esc_attr( $term->slug ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $term->name ); ?></span>
									<span class="count"><?php echo esc_html( $term->count ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $product_types ) ) : ?>
					<div class="filter-group">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Product Type', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $product_types as $val ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="iths_product_type[]" value="<?php echo esc_attr( $val ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $val ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $availabilities ) ) : ?>
					<div class="filter-group">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Availability', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $availabilities as $val ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="iths_availability[]" value="<?php echo esc_attr( $val ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $val ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $conditions ) ) : ?>
					<div class="filter-group">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Condition', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $conditions as $val ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="iths_condition[]" value="<?php echo esc_attr( $val ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $val ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $warranty_types ) ) : ?>
					<div class="filter-group">
						<button type="button" class="filter-group-toggle">
							<?php esc_html_e( 'Warranty', 'it-hardware-supply' ); ?>
							<svg class="chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
						</button>
						<div class="filter-group-content">
							<?php foreach ( $warranty_types as $val ) : ?>
								<label class="custom-checkbox">
									<input type="checkbox" name="iths_warranty_type[]" value="<?php echo esc_attr( $val ); ?>">
									<span class="checkmark"></span>
									<span class="label-text"><?php echo esc_html( $val ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

				</form>
			</aside>

			<?php /* Product Grid */ ?>
			<div class="archive-content">
				<div class="active-filters-bar" id="active-filters-bar"></div>
				
				<div id="archive-product-grid" class="product-grid" aria-live="polite">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'template-parts/product-card' ); ?>
						<?php endwhile; ?>
					<?php else : ?>
						<div class="archive-empty">
							<svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
							<h3><?php esc_html_e( 'No matching products found.', 'it-hardware-supply' ); ?></h3>
							<p><?php esc_html_e( 'Try another OEM Part Number, remove one or more filters, or contact our sales engineers.', 'it-hardware-supply' ); ?></p>
							<div class="empty-actions">
								<button type="button" class="btn btn-outline" id="btn-empty-clear"><?php esc_html_e( 'Clear Filters', 'it-hardware-supply' ); ?></button>
								<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Talk to Sales', 'it-hardware-supply' ); ?></a>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div id="archive-pagination" class="archive-pagination">
					<?php if ( function_exists( 'it_hardware_pagination' ) ) { it_hardware_pagination(); } else { the_posts_pagination(); } ?>
				</div>
			</div>
		</div>

	</div>
</main>

<?php
get_footer();
