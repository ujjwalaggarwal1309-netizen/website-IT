<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the contact/enquiry form submission.
 *
 * Security:  Verifies WordPress nonce before processing.
 * Sanitisation: All fields sanitised per WordPress coding standards.
 * Email: Sends to the verified company email address.
 * Pattern: Post-Redirect-Get - prevents duplicate submissions on page refresh.
 */
function it_hardware_handle_contact_form() {

	// Only process when the hidden form identifier is present.
	if ( ! isset( $_POST['it_hardware_contact_submit'] ) ) {
		return;
	}

	// Nonce verification - exits silently on failure to prevent information leakage.
	if ( ! isset( $_POST['it_hardware_contact_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['it_hardware_contact_nonce'] ), 'it_hardware_contact_form' )
	) {
		wp_die( esc_html__( 'Security check failed. Please go back and try again.', 'it-hardware-supply' ), 403 );
	}

	// Sanitise all form fields.
	$name    = isset( $_POST['contact_name'] )    ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) )       : '';
	$email   = isset( $_POST['contact_email'] )   ? sanitize_email( wp_unslash( $_POST['contact_email'] ) )            : '';
	$phone   = isset( $_POST['contact_phone'] )   ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) )       : '';
	$company = isset( $_POST['contact_company'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_company'] ) )     : '';
	$subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) )     : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

	// Basic required-field check.
	if ( empty( $name ) || empty( $email ) || empty( $phone ) || empty( $message ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'incomplete', wp_get_referer() ) );
		exit;
	}

	// Validate email format.
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'invalid-email', wp_get_referer() ) );
		exit;
	}

	// Verified company email address (VERIFIED_COMPANY_FACTS.md).
	$recipient    = 'info@infinityitsolutions.co.in';
	$mail_subject = sprintf(
		/* translators: %s = subject field value from form */
		__( 'New Enquiry: %s', 'it-hardware-supply' ),
		$subject
	);

	// --- NEW: Save to Database ---
	$post_id = wp_insert_post( array(
		'post_type'    => 'enquiry',
		'post_title'   => sprintf( 'Enquiry from %s: %s', $name, $subject ),
		'post_content' => $message,
		'post_status'  => 'private',
	) );

	if ( $post_id && ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_enquiry_name', $name );
		update_post_meta( $post_id, '_enquiry_email', $email );
		update_post_meta( $post_id, '_enquiry_phone', $phone );
		update_post_meta( $post_id, '_enquiry_company', $company );
	}
	// -----------------------------

	$mail_body = sprintf(
		/* translators: placeholders are form field values */
		__(
			"You have received a new enquiry from the Infinity IT Solutions website.\n\n" .
			"Name:    %1\$s\n" .
			"Email:   %2\$s\n" .
			"Phone:   %3\$s\n" .
			"Company: %4\$s\n" .
			"Subject: %5\$s\n\n" .
			"Message:\n%6\$s",
			'it-hardware-supply'
		),
		$name,
		$email,
		$phone,
		$company,
		$subject,
		$message
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $recipient, $mail_subject, $mail_body, $headers );

	// Post-Redirect-Get: prevent re-submission on page refresh.
	$status = $sent ? 'sent' : 'error';
	wp_safe_redirect( add_query_arg( 'enquiry', $status, wp_get_referer() ) );
	exit;
}
add_action( 'init', 'it_hardware_handle_contact_form' );
