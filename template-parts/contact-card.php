<?php
/**
 * Template Part: Contact Information Cards
 *
 * Renders contact info cards with icons:
 *   - Phone    (8398839899)
 *   - Email    (info@infinityitsolutions.co.in)
 *   - Working Hours (Monday-Saturday 9 AM-6 PM)
 *
 * All values pull from Customizer â†’ Company Information.
 * POLICY: Do not display a full street address until the client confirms it.
 *
 * Data source: VERIFIED_COMPANY_FACTS.md
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone        = it_hardware_get_setting( 'primary_phone' );
$email        = it_hardware_get_setting( 'email' );
$working_hrs  = it_hardware_get_setting( 'working_hours' );
$address      = it_hardware_get_setting( 'company_address' );
$wa_number    = it_hardware_get_setting( 'whatsapp_number' );
$wa_url       = $wa_number ? 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $wa_number ) : '';
?>

<div class="contact-card-list" role="list" aria-label="<?php esc_attr_e( 'Contact information', 'it-hardware-supply' ); ?>">

	<?php /* Phone card */ ?>
	<div class="contact-card" role="listitem">
		<div class="contact-card__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.64a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
		</div>
		<div class="contact-card__body">
			<h3 class="contact-card__title"><?php esc_html_e( 'Phone', 'it-hardware-supply' ); ?></h3>
			<?php if ( $phone ) : ?>
				<a class="contact-card__detail contact-card__link" href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
			<?php endif; ?>
			<?php if ( $wa_url ) : ?>
				<a class="contact-card__whatsapp" href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat on WhatsApp - opens in a new window', 'it-hardware-supply' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="14" height="14" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.555 4.123 1.527 5.857L0 24l6.305-1.654A11.947 11.947 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 0 1-5.007-1.369l-.359-.213-3.722.977.993-3.624-.234-.372A9.818 9.818 0 1 1 12 21.818z"/></svg>
					<?php esc_html_e( 'Available on WhatsApp', 'it-hardware-supply' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<?php /* Email card */ ?>
	<div class="contact-card" role="listitem">
		<div class="contact-card__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
		</div>
		<div class="contact-card__body">
			<h3 class="contact-card__title"><?php esc_html_e( 'Email', 'it-hardware-supply' ); ?></h3>
			<?php if ( $email ) : ?>
				<a class="contact-card__detail contact-card__link" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
			<p class="contact-card__note"><?php esc_html_e( 'We respond within one business day.', 'it-hardware-supply' ); ?></p>
		</div>
	</div>

	<?php /* Working Hours card */ ?>
	<div class="contact-card" role="listitem">
		<div class="contact-card__icon" aria-hidden="true">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="22" height="22"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
		</div>
		<div class="contact-card__body">
			<h3 class="contact-card__title"><?php esc_html_e( 'Working Hours', 'it-hardware-supply' ); ?></h3>
			<p class="contact-card__detail"><?php echo wp_kses_post( str_replace( ', ', '<br>', $working_hrs ) ); ?></p>
			<p class="contact-card__note"><?php esc_html_e( 'Closed on Sundays and public holidays.', 'it-hardware-supply' ); ?></p>
		</div>
	</div>

</div>
