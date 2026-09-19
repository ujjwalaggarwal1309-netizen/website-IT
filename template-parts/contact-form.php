<?php
/**
 * Template Part: Contact / Enquiry Form
 *
 * Security:
 *   - WordPress nonce (wp_nonce_field) protects against CSRF
 *   - Handler in inc/contact.php sanitises and validates all server-side
 *
 * Accessibility:
 *   - Each input has a unique id + matching <label for="">
 *   - Required fields declared with required + aria-required="true"
 *   - Error messages use aria-describedby on each field
 *   - Live error region announced via aria-live="polite"
 *   - Success/error banners use role="alert"
 *   - Form has aria-labelledby pointing to its own heading
 *
 * Validation:
 *   - Client-side: assets/js/contact.js (inline error messages, no alert())
 *   - Server-side: inc/contact.php (sanitise → validate → PRG redirect)
 *
 * Fields per WEBSITE_CONTENT.md:
 *   Full Name*, Email Address*, Phone Number*, Company Name, Subject, Message*
 * Button text per WEBSITE_CONTENT.md: "Send Enquiry"
 *
 * @package it-hardware-supply
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Read PRG redirect status from query string.
$enquiry_status = isset( $_GET['enquiry'] ) ? sanitize_key( $_GET['enquiry'] ) : '';

// Pre-fill form values from POST on validation failure (stored in GET param is not appropriate;
// instead values come from the JS-only path where the form never submitted).
?>

<div class="contact-form-wrap">

	<h2 class="contact-form-heading" id="contact-form-heading">
		<?php esc_html_e( 'Send an Enquiry', 'it-hardware-supply' ); ?>
	</h2>

	<?php /* ── Status messages (PRG pattern) ─────────────────────────────── */ ?>
	<?php if ( 'sent' === $enquiry_status ) : ?>
		<div class="form-message form-message--success" role="alert" aria-live="assertive" tabindex="-1" id="form-status-message">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" aria-hidden="true"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0z" clip-rule="evenodd"/></svg>
			<?php esc_html_e( 'Thank you for your enquiry. We will get back to you within one business day.', 'it-hardware-supply' ); ?>
		</div>
	<?php elseif ( 'incomplete' === $enquiry_status ) : ?>
		<div class="form-message form-message--error" role="alert" aria-live="assertive" tabindex="-1" id="form-status-message">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/></svg>
			<?php esc_html_e( 'Please complete all required fields before submitting.', 'it-hardware-supply' ); ?>
		</div>
	<?php elseif ( 'invalid-email' === $enquiry_status ) : ?>
		<div class="form-message form-message--error" role="alert" aria-live="assertive" tabindex="-1" id="form-status-message">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/></svg>
			<?php esc_html_e( 'Please enter a valid email address.', 'it-hardware-supply' ); ?>
		</div>
	<?php elseif ( 'error' === $enquiry_status ) : ?>
		<div class="form-message form-message--error" role="alert" aria-live="assertive" tabindex="-1" id="form-status-message">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1-9a1 1 0 0 0-1 1v4a1 1 0 1 0 2 0V6a1 1 0 0 0-1-1z" clip-rule="evenodd"/></svg>
			<?php esc_html_e( 'There was a problem sending your enquiry. Please email us directly or try again.', 'it-hardware-supply' ); ?>
		</div>
	<?php endif; ?>

	<?php /* Only show form if not successfully submitted */ ?>
	<?php if ( 'sent' !== $enquiry_status ) : ?>

	<?php /* Client-side live error region */ ?>
	<div id="contact-form-errors" class="form-error-summary" role="alert" aria-live="polite" aria-atomic="true" hidden></div>

	<form
		class="contact-form"
		method="post"
		action="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
		novalidate
		aria-labelledby="contact-form-heading"
		id="contact-enquiry-form"
	>
		<?php wp_nonce_field( 'it_hardware_contact_form', 'it_hardware_contact_nonce' ); ?>
		<input type="hidden" name="it_hardware_contact_submit" value="1" />

		<?php /* Row 1+2: Name and Email side by side on wide screens */ ?>
		<div class="form-row-group">

			<div class="form-row">
				<label for="contact-name">
					<?php esc_html_e( 'Full Name', 'it-hardware-supply' ); ?>
					<span class="required" aria-hidden="true">*</span>
					<span class="screen-reader-text"><?php esc_html_e( '(required)', 'it-hardware-supply' ); ?></span>
				</label>
				<input
					id="contact-name"
					name="contact_name"
					type="text"
					autocomplete="name"
					required
					aria-required="true"
					aria-describedby="contact-name-error"
					class="form-input"
				/>
				<span id="contact-name-error" class="field-error" role="alert" aria-live="polite"></span>
			</div>

			<div class="form-row">
				<label for="contact-email">
					<?php esc_html_e( 'Email Address', 'it-hardware-supply' ); ?>
					<span class="required" aria-hidden="true">*</span>
					<span class="screen-reader-text"><?php esc_html_e( '(required)', 'it-hardware-supply' ); ?></span>
				</label>
				<input
					id="contact-email"
					name="contact_email"
					type="email"
					autocomplete="email"
					required
					aria-required="true"
					aria-describedby="contact-email-error"
					class="form-input"
				/>
				<span id="contact-email-error" class="field-error" role="alert" aria-live="polite"></span>
			</div>

		</div>

		<?php /* Row 3+4: Phone and Company side by side */ ?>
		<div class="form-row-group">

			<div class="form-row">
				<label for="contact-phone">
					<?php esc_html_e( 'Phone Number', 'it-hardware-supply' ); ?>
					<span class="required" aria-hidden="true">*</span>
					<span class="screen-reader-text"><?php esc_html_e( '(required)', 'it-hardware-supply' ); ?></span>
				</label>
				<input
					id="contact-phone"
					name="contact_phone"
					type="tel"
					autocomplete="tel"
					required
					aria-required="true"
					aria-describedby="contact-phone-error"
					class="form-input"
				/>
				<span id="contact-phone-error" class="field-error" role="alert" aria-live="polite"></span>
			</div>

			<div class="form-row">
				<label for="contact-company">
					<?php esc_html_e( 'Company Name', 'it-hardware-supply' ); ?>
				</label>
				<input
					id="contact-company"
					name="contact_company"
					type="text"
					autocomplete="organization"
					class="form-input"
				/>
			</div>

		</div>

		<?php /* Row 5: Subject dropdown */ ?>
		<div class="form-row">
			<label for="contact-subject"><?php esc_html_e( 'Subject', 'it-hardware-supply' ); ?></label>
			<div class="select-wrap">
				<select id="contact-subject" name="contact_subject" class="form-input form-select">
					<option value="General Enquiry"><?php esc_html_e( 'General Enquiry', 'it-hardware-supply' ); ?></option>
					<option value="Enterprise Servers"><?php esc_html_e( 'Enterprise Servers', 'it-hardware-supply' ); ?></option>
					<option value="Storage Solutions"><?php esc_html_e( 'Storage Solutions', 'it-hardware-supply' ); ?></option>
					<option value="Professional Workstations"><?php esc_html_e( 'Professional Workstations', 'it-hardware-supply' ); ?></option>
					<option value="Business Desktops"><?php esc_html_e( 'Business Desktops', 'it-hardware-supply' ); ?></option>
					<option value="Server Spare Parts"><?php esc_html_e( 'Server Spare Parts', 'it-hardware-supply' ); ?></option>
					<option value="On-site Installation"><?php esc_html_e( 'On-site Installation Support', 'it-hardware-supply' ); ?></option>
					<option value="Other"><?php esc_html_e( 'Other', 'it-hardware-supply' ); ?></option>
				</select>
				<span class="select-arrow" aria-hidden="true">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 0 1 1.414 0L10 10.586l3.293-3.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 0-1.414z" clip-rule="evenodd"/></svg>
				</span>
			</div>
		</div>

		<?php /* Row 6: Message textarea */ ?>
		<div class="form-row">
			<label for="contact-message">
				<?php esc_html_e( 'Message', 'it-hardware-supply' ); ?>
				<span class="required" aria-hidden="true">*</span>
				<span class="screen-reader-text"><?php esc_html_e( '(required)', 'it-hardware-supply' ); ?></span>
			</label>
			<textarea
				id="contact-message"
				name="contact_message"
				rows="6"
				required
				aria-required="true"
				aria-describedby="contact-message-error"
				class="form-input form-textarea"
				placeholder="<?php esc_attr_e( 'Describe your IT infrastructure requirement...', 'it-hardware-supply' ); ?>"
			></textarea>
			<span id="contact-message-error" class="field-error" role="alert" aria-live="polite"></span>
		</div>

		<?php /* Row 7: Required fields note + Submit */ ?>
		<div class="form-footer">
			<p class="required-note">
				<span class="required" aria-hidden="true">*</span>
				<?php esc_html_e( 'Required fields', 'it-hardware-supply' ); ?>
			</p>
			<button class="btn btn-accent btn-full" type="submit" id="contact-submit-btn">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
				<?php esc_html_e( 'Send Enquiry', 'it-hardware-supply' ); ?>
			</button>
		</div>

	</form>

	<?php endif; ?>

</div>
