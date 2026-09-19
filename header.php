<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to main content', 'it-hardware-supply' ); ?></a>
<div class="site-wrapper" id="top">
	<div class="top-bar">
		<div class="container top-bar-inner">
			<div class="top-bar-links">
				<a href="tel:<?php echo esc_attr( it_hardware_get_setting( 'top_bar_phone' ) ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
					<?php echo esc_html( it_hardware_get_setting( 'top_bar_phone' ) ); ?>
				</a>
				<span class="top-bar-sep" aria-hidden="true">|</span>
				<a href="mailto:<?php echo esc_attr( it_hardware_get_setting( 'top_bar_email' ) ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
					<?php echo esc_html( it_hardware_get_setting( 'top_bar_email' ) ); ?>
				</a>
			</div>
			<div class="top-bar-hours">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F68B1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				<?php echo esc_html( it_hardware_get_setting( 'top_bar_hours' ) ); ?>
			</div>
		</div>
	</div>
	<header class="site-header" role="banner">
		<div class="container header-inner">
			<?php if ( has_custom_logo() ) : ?>
				<div class="site-logo-custom">
					<?php
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					$logo_path      = $custom_logo_id ? get_attached_file( $custom_logo_id ) : false;

					if ( $logo_path && strpos( $logo_path, '.svg' ) !== false && file_exists( $logo_path ) ) {
						// Inline the SVG so it inherits web fonts
						$svg_content = file_get_contents( $logo_path );
						// Remove hardcoded dimensions to let CSS scale it
						$svg_content = preg_replace( '/(width|height)="[^"]*"/i', '', $svg_content );
						$svg_content = str_replace( '<svg ', '<svg class="site-logo__img" ', $svg_content );
						echo '<a class="site-logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
						echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo '</a>';
					} else {
						the_custom_logo();
					}
					?>
				</div>
			<?php else : ?>
				<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> &mdash; <?php esc_attr_e( 'Go to homepage', 'it-hardware-supply' ); ?>">
					<?php
					$logo_path = get_template_directory() . '/assets/logo/logo-primary.svg';
					if ( file_exists( $logo_path ) ) {
						// Inline the SVG so it inherits web fonts (like Inter)
						$svg_content = file_get_contents( $logo_path );
						$svg_content = str_replace( '<svg ', '<svg class="site-logo__img" ', $svg_content );
						// Use wp_kses or allow it raw since it's an internal trusted asset
						echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '<span>' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					}
					?>
				</a>
			<?php endif; ?>
			<nav class="site-nav" aria-label="Primary navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'fallback_cb' => false ) ); ?>
			</nav>
			<a class="btn header-cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<?php echo esc_html( it_hardware_get_setting( 'header_cta_text' ) ); ?> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
			</a>
			<button class="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'it-hardware-supply' ); ?>" aria-expanded="false" aria-controls="mobile-nav">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/menu.svg' ); ?>" alt="Open navigation menu" aria-hidden="true" />
			</button>
		</div>
	</header>
	<nav class="mobile-nav" id="mobile-nav" aria-hidden="true" aria-label="<?php esc_attr_e( 'Mobile navigation', 'it-hardware-supply' ); ?>">
		<?php wp_nav_menu( array( 'theme_location' => 'mobile', 'menu_class' => 'mobile-menu', 'fallback_cb' => false ) ); ?>
	</nav>
