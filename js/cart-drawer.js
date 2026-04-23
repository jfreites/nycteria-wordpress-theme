/**
 * Cart Drawer — Nycteria Store
 *
 * Handles open/close, AJAX quantity updates, AJAX item removal, and
 * integration with WooCommerce cart fragment events.
 *
 * Dependencies: jQuery (already enqueued by WooCommerce — used only for
 * WC custom events bridge). All DOM manipulation uses vanilla JS.
 */
( function ( $ ) {
	'use strict';

	// ─── Config & references ─────────────────────────────────────────────────

	var cfg      = window.nycteriaCart || {};
	var drawer   = document.getElementById( 'cart-drawer' );

	if ( ! drawer || ! cfg.ajaxUrl ) {
		return;
	}

	var overlay   = drawer.querySelector( '.cart-drawer__overlay' );
	var closeBtn  = drawer.querySelector( '.cart-drawer__close' );
	var panel     = drawer.querySelector( '.cart-drawer__panel' );
	var countEl   = document.querySelector( '.header-cart-count' );
	var cartLink  = document.querySelector( '.header-cart-link' );

	// ─── Open / Close ─────────────────────────────────────────────────────────

	function openDrawer() {
		drawer.setAttribute( 'aria-hidden', 'false' );
		drawer.classList.add( 'is-open' );
		document.body.classList.add( 'cart-drawer-open' );
		// Move focus to the close button for keyboard/screen-reader users.
		if ( closeBtn ) {
			closeBtn.focus();
		}
	}

	function closeDrawer() {
		drawer.setAttribute( 'aria-hidden', 'true' );
		drawer.classList.remove( 'is-open' );
		document.body.classList.remove( 'cart-drawer-open' );
		// Return focus to the element that opened the drawer.
		if ( cartLink ) {
			cartLink.focus();
		}
	}

	// ─── Trigger open from header cart icon ──────────────────────────────────

	if ( cartLink ) {
		cartLink.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			openDrawer();
		} );
	}

	// ─── Close triggers ───────────────────────────────────────────────────────

	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', closeDrawer );
	}

	if ( overlay ) {
		overlay.addEventListener( 'click', closeDrawer );
	}

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && drawer.classList.contains( 'is-open' ) ) {
			closeDrawer();
		}
	} );

	// ─── AJAX helper (fetch API) ──────────────────────────────────────────────

	function post( action, data ) {
		var params = new URLSearchParams( { action: action, nonce: cfg.nonce } );
		Object.keys( data ).forEach( function ( key ) {
			params.append( key, data[ key ] );
		} );

		return fetch( cfg.ajaxUrl, {
			method:  'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body:    params.toString(),
		} ).then( function ( res ) {
			if ( ! res.ok ) {
				throw new Error( 'Network response was not ok.' );
			}
			return res.json();
		} );
	}

	// ─── Update drawer content after AJAX response ────────────────────────────

	function updateDrawer( data ) {
		// Replace inner HTML.
		if ( data.drawer_html ) {
			var inner = drawer.querySelector( '.cart-drawer__inner' );
			if ( inner ) {
				inner.outerHTML = data.drawer_html;
			}
			bindItemEvents();
		}

		// Sync the header badge count.
		if ( data.cart_count !== undefined && countEl ) {
			countEl.textContent = data.cart_count;
		}

		// Notify WooCommerce that fragments may need refreshing so other
		// WC components (e.g., mini-cart widgets) stay in sync.
		$( document.body ).trigger( 'wc_fragment_refresh' );
	}

	// ─── Loading state ────────────────────────────────────────────────────────

	function setLoading( itemEl, state ) {
		itemEl.classList.toggle( 'is-loading', state );
		itemEl.querySelectorAll( 'button' ).forEach( function ( btn ) {
			btn.disabled = state;
		} );
	}

	// ─── Quantity change ─────────────────────────────────────────────────────

	function handleQtyChange( itemEl, delta ) {
		var key   = itemEl.dataset.key;
		var qtyEl = itemEl.querySelector( '.cart-drawer__qty-value' );
		if ( ! qtyEl ) return;

		var current = parseInt( qtyEl.textContent, 10 ) || 1;
		var newQty  = Math.max( 0, current + delta );

		setLoading( itemEl, true );

		post( cfg.updateAction, { cart_item_key: key, quantity: newQty } )
			.then( function ( res ) {
				if ( res.success ) {
					updateDrawer( res.data );
				}
			} )
			.catch( function ( err ) {
				console.error( 'Cart update error:', err );
				setLoading( itemEl, false );
			} );
	}

	// ─── Remove item ─────────────────────────────────────────────────────────

	function handleRemove( itemEl ) {
		var key = itemEl.dataset.key;
		setLoading( itemEl, true );

		post( cfg.removeAction, { cart_item_key: key } )
			.then( function ( res ) {
				if ( res.success ) {
					updateDrawer( res.data );
				}
			} )
			.catch( function ( err ) {
				console.error( 'Cart remove error:', err );
				setLoading( itemEl, false );
			} );
	}

	// ─── Bind per-item events ─────────────────────────────────────────────────
	// Called on init and after every inner HTML replacement.

	function bindItemEvents() {
		var inner = drawer.querySelector( '.cart-drawer__inner' );
		if ( ! inner ) return;

		inner.querySelectorAll( '.cart-drawer__item' ).forEach( function ( itemEl ) {
			var minus  = itemEl.querySelector( '.cart-drawer__qty-btn--minus' );
			var plus   = itemEl.querySelector( '.cart-drawer__qty-btn--plus' );
			var remove = itemEl.querySelector( '.cart-drawer__remove' );

			if ( minus ) {
				minus.addEventListener( 'click', function () {
					handleQtyChange( itemEl, -1 );
				} );
			}

			if ( plus ) {
				plus.addEventListener( 'click', function () {
					handleQtyChange( itemEl, 1 );
				} );
			}

			if ( remove ) {
				remove.addEventListener( 'click', function () {
					handleRemove( itemEl );
				} );
			}
		} );
	}

	// ─── WooCommerce event bridge (jQuery required for WC custom events) ──────

	// Open drawer automatically whenever a product is added to the cart.
	$( document.body ).on( 'added_to_cart', function () {
		openDrawer();
	} );

	// Re-bind item events after WooCommerce refreshes fragments
	// (e.g., after using the native "Add to cart" button on shop/product pages).
	$( document.body ).on( 'wc_fragments_refreshed wc_cart_button_updated', function () {
		bindItemEvents();
	} );

	// ─── Init ─────────────────────────────────────────────────────────────────

	bindItemEvents();

} ( jQuery ) );
