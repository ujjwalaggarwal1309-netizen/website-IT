	<footer class="site-footer" role="contentinfo">
		<div class="container footer-grid">

			<?php /* ── Column 1: Logo + Description ─────────────────────────────── */ ?>
			<div class="footer-column">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> &mdash; <?php esc_attr_e( 'Homepage', 'it-hardware-supply' ); ?>">
					<?php
					$footer_logo_path = get_template_directory() . '/assets/logo/logo-light.svg';
					if ( file_exists( $footer_logo_path ) ) {
						$svg_content = file_get_contents( $footer_logo_path );
						$svg_content = str_replace( '<svg ', '<svg class="footer-logo__img" ', $svg_content );
						echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						echo '<span>' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					}
					?>
				</a>
				<p class="footer-tagline"><?php echo esc_html( it_hardware_get_setting( 'footer_tagline' ) ); ?></p>
				<p class="footer-description"><?php echo esc_html( it_hardware_get_setting( 'footer_description' ) ); ?></p>
				<div class="footer-trust-badges">
					<div class="footer-badge">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
						<span><?php esc_html_e( 'PAN India Support', 'it-hardware-supply' ); ?></span>
					</div>
					<div class="footer-badge">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
						<span><?php esc_html_e( 'Since 2018', 'it-hardware-supply' ); ?></span>
					</div>
					<div class="footer-badge">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
						<span><?php esc_html_e( 'Warranty Included', 'it-hardware-supply' ); ?></span>
					</div>
				</div>
				<div class="social-links">
					<?php echo it_hardware_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- function output is pre-escaped. ?>
				</div>
			</div>

			<?php /* ── Column 2: Contact + Working Hours ──────────────────────────── */ ?>
			<div class="footer-column">
				<h3><?php esc_html_e( 'Contact', 'it-hardware-supply' ); ?></h3>

				<?php $ph = it_hardware_get_setting( 'primary_phone' ); if ( $ph ) : ?>
				<p class="footer-contact-item">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $ph ) ); ?>"><?php echo esc_html( $ph ); ?></a>
				</p>
				<?php endif; ?>

				<?php $em = it_hardware_get_setting( 'email' ); if ( $em ) : ?>
				<p class="footer-contact-item">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
					<a href="mailto:<?php echo esc_attr( $em ); ?>"><?php echo esc_html( $em ); ?></a>
				</p>
				<?php endif; ?>

				<?php $wh = it_hardware_get_setting( 'working_hours' ); if ( $wh ) : ?>
				<div class="footer-hours">
					<p class="footer-hours__label"><?php esc_html_e( 'Working Hours', 'it-hardware-supply' ); ?></p>
					<p class="footer-hours__value"><?php echo esc_html( $wh ); ?></p>
				</div>
				<?php endif; ?>
			</div>

		</div>
		<div class="footer-bottom">
			<div class="container footer-bottom-inner">
				<p><?php
				printf(
					/* translators: 1: founding year, 2: current year, 3: company name */
					esc_html__( '© %1$s–%2$s %3$s. All Rights Reserved.', 'it-hardware-supply' ),
					'2018',
					esc_html( date_i18n( 'Y' ) ),
					esc_html( it_hardware_get_setting( 'company_name' ) )
				);
				?></p>
			</div>
		</div>
	</footer>
	<a href="#top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'it-hardware-supply' ); ?>">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/arrow-up.svg' ); ?>" alt="Back to top" aria-hidden="true" />
	</a>
	<a class="floating-whatsapp" href="https://wa.me/<?php echo esc_attr( it_hardware_get_setting( 'whatsapp_number' ) ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'it-hardware-supply' ); ?>">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/whatsapp.svg?v=' . time() ); ?>" alt="Chat on WhatsApp" />
	</a>
</div>
<?php wp_footer(); ?>
</body>
</html>
