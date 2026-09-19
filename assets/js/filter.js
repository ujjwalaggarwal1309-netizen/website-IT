/**
 * filter.js — Product category filter interactions
 *
 * Enhances the sidebar filter with:
 *  - Keyboard navigation (arrow keys within filter list)
 *  - Smooth page transition indicator on navigation
 *  - Mobile: collapsible sidebar filter toggle
 *
 * Navigation itself uses standard <a> links (SEO-friendly, no AJAX).
 * JavaScript adds UX polish only — the page works without JS.
 *
 * @package it-hardware-supply
 */
/* global document, window */

( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {

		const filter     = document.querySelector( '.product-filter' );
		const filterList = document.querySelector( '.product-filter__list' );

		if ( ! filter ) return;

		// ── Mobile filter toggle ─────────────────────────────────────────────
		const heading = filter.querySelector( '.product-filter__heading' );

		if ( heading ) {
			// Only wire up collapse on mobile (sidebar is always visible on desktop).
			const isMobile = () => window.innerWidth < 992;

			if ( isMobile() ) {
				heading.setAttribute( 'role', 'button' );
				heading.setAttribute( 'aria-expanded', 'false' );
				heading.setAttribute( 'tabindex', '0' );
				if ( filterList ) {
					filterList.hidden = true;
				}

				const toggleFilter = function () {
					const expanded = heading.getAttribute( 'aria-expanded' ) === 'true';
					heading.setAttribute( 'aria-expanded', String( ! expanded ) );
					if ( filterList ) {
						filterList.hidden = expanded;
					}
				};

				heading.addEventListener( 'click', toggleFilter );
				heading.addEventListener( 'keydown', function ( e ) {
					if ( e.key === 'Enter' || e.key === ' ' ) {
						e.preventDefault();
						toggleFilter();
					}
				} );
			}
		}

		// ── Keyboard navigation within filter list ──────────────────────────
		if ( filterList ) {
			filterList.addEventListener( 'keydown', function ( e ) {
				const links = Array.from(
					filterList.querySelectorAll( '.product-filter__link:not([hidden])' )
				);
				const idx   = links.indexOf( document.activeElement );

				if ( e.key === 'ArrowDown' ) {
					e.preventDefault();
					const next = links[ idx + 1 ] || links[ 0 ];
					if ( next ) next.focus();
				} else if ( e.key === 'ArrowUp' ) {
					e.preventDefault();
					const prev = links[ idx - 1 ] || links[ links.length - 1 ];
					if ( prev ) prev.focus();
				} else if ( e.key === 'Home' ) {
					e.preventDefault();
					if ( links[ 0 ] ) links[ 0 ].focus();
				} else if ( e.key === 'End' ) {
					e.preventDefault();
					if ( links[ links.length - 1 ] ) links[ links.length - 1 ].focus();
				}
			} );
		}

		// ── Page transition indicator ─────────────────────────────────────────
		// Briefly shows a loading indicator on the product grid when a filter
		// link is clicked so the user knows navigation has started.
		const productGrid = document.getElementById( 'product-grid' );

		document.querySelectorAll( '.product-filter__link, .category-tab' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				if ( productGrid ) {
					productGrid.setAttribute( 'aria-busy', 'true' );
					productGrid.style.opacity = '0.5';
					productGrid.style.transition = 'opacity 200ms ease';
				}
			} );
		} );

	} );

}() );
