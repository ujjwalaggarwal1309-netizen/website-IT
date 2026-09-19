/**
 * search.js — Product search form interactions
 *
 * Features:
 *  - Prevents empty search submission
 *  - Shows/hides the clear (×) button as user types
 *  - Strips leading/trailing whitespace before submit
 *  - Focuses search input when Ctrl+/ or / is pressed (if not in a text field)
 *  - Keyboard shortcut hint hidden visually but announced to screen readers
 *
 * Works without JS — form falls back to standard GET submission.
 *
 * @package it-hardware-supply
 */
/* global document, window */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		const form      = document.getElementById( 'product-search-form' );
		const input     = document.getElementById( 'product-search-input' );
		const clearLink = document.getElementById( 'product-search-clear' );

		if ( ! form || ! input ) return;

		// ── Prevent empty submission ─────────────────────────────────────────
		form.addEventListener( 'submit', function ( e ) {
			const trimmed = input.value.trim();
			if ( ! trimmed ) {
				e.preventDefault();
				input.focus();
				return;
			}
			// Trim whitespace before submitting.
			input.value = trimmed;
		} );

		// ── Dynamic clear button ─────────────────────────────────────────────
		// The PHP template renders the clear link only when a search is
		// already active. For real-time behaviour, we also handle the case
		// where the user types into a previously-empty field.
		if ( clearLink ) {
			// Show clear when input has value, hide when empty.
			const toggleClear = function () {
				clearLink.style.display = input.value.trim() ? '' : 'none';
			};
			input.addEventListener( 'input', toggleClear );
			toggleClear(); // Initial state.
		}

		// ── Keyboard shortcut: / to focus search ─────────────────────────────
		document.addEventListener( 'keydown', function ( e ) {
			// Only fire when not in a text input/textarea/select.
			const tag = document.activeElement ? document.activeElement.tagName : '';
			const isEditable = [ 'INPUT', 'TEXTAREA', 'SELECT' ].includes( tag )
				|| document.activeElement.isContentEditable;

			if ( ! isEditable && e.key === '/' ) {
				e.preventDefault();
				const wrap = document.querySelector( '.product-search-wrap' );
				if ( wrap ) {
					wrap.scrollIntoView( { behavior: 'smooth', block: 'center' } );
				}
				input.focus();
				input.select();
			}
		} );

		// ── Category tab bar: smooth scroll on overflow ──────────────────────
		const tabBar = document.querySelector( '.product-category-tabs' );
		if ( tabBar ) {
			const activeTab = tabBar.querySelector( '.category-tab--active' );
			if ( activeTab ) {
				// Scroll the active tab into view without affecting page scroll.
				activeTab.scrollIntoView( { behavior: 'smooth', block: 'nearest', inline: 'center' } );
			}
		}

	} );

}() );
