<?php
/**
 * Template Part: Homepage Product Categories Grid
 *
 * Source: VERIFIED_COMPANY_FACTS.md + WEBSITE_CONTENT.md
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$homepage_categories = array(
	array(
		'name'        => __( 'Enterprise Servers', 'it-hardware-supply' ),
		'slug'        => 'enterprise-servers',
		'badge'       => __( 'Mission Critical', 'it-hardware-supply' ),
		'description' => __( 'Reliable rack, tower, and enterprise server solutions for virtualisation, databases, cloud environments, and business-critical workloads.', 'it-hardware-supply' ),
		'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>',
		'illustration'=> get_template_directory_uri() . '/assets/images/illustrations/products/enterprise-servers.svg',
	),
	array(
		'name'        => __( 'Storage Solutions', 'it-hardware-supply' ),
		'slug'        => 'storage-solutions',
		'badge'       => __( 'Scalable Storage', 'it-hardware-supply' ),
		'description' => __( 'Scalable enterprise storage systems including SAN, NAS, SSD, and NVMe solutions for secure data management.', 'it-hardware-supply' ),
		'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>',
		'illustration'=> get_template_directory_uri() . '/assets/images/illustrations/products/storage-solutions.svg',
	),
	array(
		'name'        => __( 'Professional Workstations', 'it-hardware-supply' ),
		'slug'        => 'professional-workstations',
		'badge'       => __( 'High Performance', 'it-hardware-supply' ),
		'description' => __( 'High-performance workstations for engineering, CAD, AI, architecture, multimedia, and technical workloads.', 'it-hardware-supply' ),
		'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="14" height="14" rx="1"/><path d="M8 21h8"/><path d="M12 17v4"/><path d="M20 7h2v2h-2z"/><path d="M20 12h2v2h-2z"/></svg>',
		'illustration'=> get_template_directory_uri() . '/assets/images/illustrations/products/professional-workstations.svg',
	),
	array(
		'name'        => __( 'Business Desktops', 'it-hardware-supply' ),
		'slug'        => 'business-desktops',
		'badge'       => __( 'Office Ready', 'it-hardware-supply' ),
		'description' => __( 'Dependable desktop systems designed for modern office productivity and enterprise environments.', 'it-hardware-supply' ),
		'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
		'illustration'=> get_template_directory_uri() . '/assets/images/illustrations/products/business-desktops.svg',
	),
	array(
		'name'        => __( 'Server Spare Parts', 'it-hardware-supply' ),
		'slug'        => 'server-spare-parts',
		'badge'       => __( 'Genuine Components', 'it-hardware-supply' ),
		'description' => __( 'Enterprise replacement components including processors, memory, storage drives, RAID controllers, HBA cards, power supplies, and motherboards.', 'it-hardware-supply' ),
		'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/><polyline points="15 5 19 9"/></svg>',
		'illustration'=> get_template_directory_uri() . '/assets/images/illustrations/products/server-spare-parts.svg',
	),
);
?>
<section class="category-section fade-in" aria-labelledby="category-heading">
	<div class="container">
		<div class="section-heading">
			<span class="section-label"><?php esc_html_e( 'Product Categories', 'it-hardware-supply' ); ?></span>
			<h2 id="category-heading"><?php esc_html_e( 'Our Product Range', 'it-hardware-supply' ); ?></h2>
			<p><?php esc_html_e( 'Trusted Enterprise Infrastructure Solutions for every critical infrastructure requirement.', 'it-hardware-supply' ); ?></p>
		</div>
		<div class="premium-grid">
			<?php foreach ( $homepage_categories as $cat ) :
				$term = get_term_by( 'slug', $cat['slug'], 'product-category' );
				$link = ( $term && ! is_wp_error( $term ) )
					? get_term_link( $term )
					: get_post_type_archive_link( 'products' );
				$link = $link && ! is_wp_error( $link ) ? $link : home_url( '/products/' );
			?>
			<div class="card-premium category-card">
				<div class="category-card__content">
					<div class="card-premium__icon"><?php echo $cat['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<h3 class="card-premium__title"><?php echo esc_html( $cat['name'] ); ?></h3>
					<div class="card-premium__divider" aria-hidden="true"></div>
					<p class="card-premium__description"><?php echo esc_html( $cat['description'] ); ?></p>
					
					<div class="category-card__badge-wrap" style="margin-bottom: 24px;">
						<span class="category-badge" style="display:inline-block; padding: 8px 14px; background: rgba(22,63,168,.08); color: #163FA8; border-radius: 999px; font-size: 12px; font-family: 'Inter', sans-serif; font-weight: 700; letter-spacing: 0.5px;"><?php echo esc_html( $cat['badge'] ); ?></span>
					</div>

					<a href="<?php echo esc_url( $link ); ?>" class="card-premium__link" aria-label="<?php echo esc_attr( sprintf( __( 'View %s products', 'it-hardware-supply' ), $cat['name'] ) ); ?>">
						<span class="card-premium__link-text"><?php esc_html_e( 'View Products', 'it-hardware-supply' ); ?></span>
						<svg class="card-premium__link-arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
				<img class="card-illustration" src="<?php echo esc_url( $cat['illustration'] ); ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>" aria-hidden="true">
			</div>
			<?php endforeach; ?>
		</div>
		<div class="category-section__cta">
			<a class="btn btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'products' ) ?: home_url( '/products/' ) ); ?>">
				<?php esc_html_e( 'View All Products', 'it-hardware-supply' ); ?>
			</a>
		</div>
	</div>
</section>

