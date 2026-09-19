/**
 * contact.js — Contact form client-side validation
 *
 * - Real-time inline error messages (no alert() dialogs)
 * - Accessible error announcements via aria-live="polite" spans
 * - Validates on blur (field-level) and on submit (all fields)
 * - Indicates loading state on submit button
 * - Scrolls to first error on failed submit
 * - Compatible with the server-side PRG validation in inc/contact.php
 *
 * @package it-hardware-supply
 */
/* global document, window, URLSearchParams */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		document.querySelectorAll( '.product-enquire' ).forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				document.body.dataset.enquiry = 'open';
			} );
		} );

		// Pre-fill subject based on ?product= query parameter
		const urlParams = new URLSearchParams( window.location.search );
		const productParam = urlParams.get( 'product' );
		const subjectSelect = document.getElementById( 'contact-subject' );

		if ( productParam && subjectSelect ) {
			// Check if option exists
			let optionExists = false;
			Array.from( subjectSelect.options ).forEach( function ( opt ) {
				if ( opt.value === productParam ) {
					optionExists = true;
				}
			} );

			if ( ! optionExists ) {
				const newOption = document.createElement( 'option' );
				newOption.value = 'Enquiry: ' + productParam;
				newOption.textContent = 'Enquiry: ' + productParam;
				subjectSelect.appendChild( newOption );
				subjectSelect.value = newOption.value;
			} else {
				subjectSelect.value = productParam;
			}
		}

		const form       = document.getElementById( 'contact-enquiry-form' );
		const submitBtn  = document.getElementById( 'contact-submit-btn' );
		const statusMsg  = document.getElementById( 'form-status-message' );

		if ( ! form ) return;

		// Scroll to + focus the status message on page load (PRG redirect result).
		if ( statusMsg ) {
			statusMsg.scrollIntoView( { behavior: 'smooth', block: 'center' } );
			statusMsg.focus();
		}

		// ── Field validation rules ─────────────────────────────────────────────
		const rules = {
			'contact-name':    { required: true, minLength: 2, errorId: 'contact-name-error',    label: 'Full Name' },
			'contact-email':   { required: true, isEmail: true, errorId: 'contact-email-error',  label: 'Email Address' },
			'contact-phone':   { required: true, minLength: 7, errorId: 'contact-phone-error',   label: 'Phone Number' },
			'contact-message': { required: true, minLength: 10, errorId: 'contact-message-error', label: 'Message' },
		};

		/**
		 * Validate a single field. Returns error string or empty string.
		 *
		 * @param {HTMLInputElement|HTMLTextAreaElement} field
		 * @param {Object} rule
		 * @returns {string}
		 */
		function validateField( field, rule ) {
			const val = field.value.trim();

			if ( rule.required && ! val ) {
				return rule.label + ' is required.';
			}

			if ( val && rule.minLength && val.length < rule.minLength ) {
				return rule.label + ' must be at least ' + rule.minLength + ' characters.';
			}

			if ( val && rule.isEmail ) {
				// Simple RFC-5321-compatible pattern.
				const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if ( ! emailPattern.test( val ) ) {
					return 'Please enter a valid email address.';
				}
			}

			return '';
		}

		/**
		 * Show or clear error message for a field.
		 *
		 * @param {HTMLElement} errorEl  — The <span id="field-error">
		 * @param {HTMLElement} inputEl  — The input/textarea
		 * @param {string}      message  — Error text or '' to clear
		 */
		function setError( errorEl, inputEl, message ) {
			if ( message ) {
				errorEl.textContent = message;
				errorEl.removeAttribute( 'hidden' );
				inputEl.setAttribute( 'aria-invalid', 'true' );
				inputEl.classList.add( 'input-error' );
			} else {
				errorEl.textContent = '';
				errorEl.setAttribute( 'hidden', '' );
				inputEl.removeAttribute( 'aria-invalid' );
				inputEl.classList.remove( 'input-error' );
			}
		}

		// ── Per-field blur validation ──────────────────────────────────────────
		Object.keys( rules ).forEach( function ( fieldId ) {
			const field   = document.getElementById( fieldId );
			const rule    = rules[ fieldId ];
			const errorEl = document.getElementById( rule.errorId );

			if ( ! field || ! errorEl ) return;

			// Initially hidden.
			errorEl.setAttribute( 'hidden', '' );

			field.addEventListener( 'blur', function () {
				const error = validateField( field, rule );
				setError( errorEl, field, error );
			} );

			// Clear error as soon as user starts correcting the field.
			field.addEventListener( 'input', function () {
				if ( field.getAttribute( 'aria-invalid' ) === 'true' ) {
					const error = validateField( field, rule );
					setError( errorEl, field, error );
				}
			} );
		} );

		// ── Submit validation ──────────────────────────────────────────────────
		form.addEventListener( 'submit', function ( event ) {

			let firstErrorField = null;
			let hasErrors       = false;

			Object.keys( rules ).forEach( function ( fieldId ) {
				const field   = document.getElementById( fieldId );
				const rule    = rules[ fieldId ];
				const errorEl = document.getElementById( rule.errorId );

				if ( ! field || ! errorEl ) return;

				const error = validateField( field, rule );
				setError( errorEl, field, error );

				if ( error ) {
					hasErrors = true;
					if ( ! firstErrorField ) {
						firstErrorField = field;
					}
				}
			} );

			if ( hasErrors ) {
				event.preventDefault();

				// Scroll to and focus the first invalid field.
				if ( firstErrorField ) {
					firstErrorField.scrollIntoView( { behavior: 'smooth', block: 'center' } );
					firstErrorField.focus();
				}
				return;
			}

			// ── Loading state on submit button ─────────────────────────────────
			if ( submitBtn ) {
				submitBtn.setAttribute( 'disabled', 'disabled' );
				submitBtn.setAttribute( 'aria-busy', 'true' );
				submitBtn.textContent = 'Sending\u2026';
			}
		} );

	} );

}() );
