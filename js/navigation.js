/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Search overlay toggle.
	 */
	const searchToggle = document.querySelector( '.search-toggle' );
	const searchOverlay = document.getElementById( 'search-overlay' );
	const searchClose = document.querySelector( '.search-overlay__close' );

	if ( searchToggle && searchOverlay ) {
		searchToggle.addEventListener( 'click', function() {
			const isOpen = searchOverlay.classList.contains( 'is-active' );
			if ( isOpen ) {
				searchOverlay.classList.remove( 'is-active' );
				searchOverlay.setAttribute( 'aria-hidden', 'true' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
			} else {
				searchOverlay.classList.add( 'is-active' );
				searchOverlay.setAttribute( 'aria-hidden', 'false' );
				searchToggle.setAttribute( 'aria-expanded', 'true' );
				const searchInput = searchOverlay.querySelector( '.search-field' );
				if ( searchInput ) {
					searchInput.focus();
				}
			}
		} );

		if ( searchClose ) {
			searchClose.addEventListener( 'click', function() {
				searchOverlay.classList.remove( 'is-active' );
				searchOverlay.setAttribute( 'aria-hidden', 'true' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
				searchToggle.focus();
			} );
		}

		document.addEventListener( 'keydown', function( event ) {
			if ( event.key === 'Escape' && searchOverlay.classList.contains( 'is-active' ) ) {
				searchOverlay.classList.remove( 'is-active' );
				searchOverlay.setAttribute( 'aria-hidden', 'true' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
				searchToggle.focus();
			}
		} );

		document.addEventListener( 'click', function( event ) {
			if (
				searchOverlay.classList.contains( 'is-active' ) &&
				! searchOverlay.contains( event.target ) &&
				! searchToggle.contains( event.target )
			) {
				searchOverlay.classList.remove( 'is-active' );
				searchOverlay.setAttribute( 'aria-hidden', 'true' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}
	/**
	 * Shop Archive category dropdowns for touch devices.
	 */
	const shopCategories = document.querySelectorAll( '.shop-archive__category-wrapper.has-dropdown' );

	if ( shopCategories.length > 0 ) {
		shopCategories.forEach( function( wrapper ) {
			const link = wrapper.querySelector( '.shop-archive__category-link' );

			link.addEventListener( 'touchstart', function( event ) {
				if ( ! wrapper.classList.contains( 'is-open' ) ) {
					event.preventDefault();

					// Close other open category dropdowns
					shopCategories.forEach( function( otherWrapper ) {
						if ( otherWrapper !== wrapper ) {
							otherWrapper.classList.remove( 'is-open' );
						}
					} );

					wrapper.classList.add( 'is-open' );
				}
			}, { passive: false } );
		} );

		// Close dropdowns when clicking outside
		document.addEventListener( 'touchstart', function( event ) {
			shopCategories.forEach( function( wrapper ) {
				if ( ! wrapper.contains( event.target ) ) {
					wrapper.classList.remove( 'is-open' );
				}
			} );
		} );
	}
}() );
