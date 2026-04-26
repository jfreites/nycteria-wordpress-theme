( function() {
	const forms = document.querySelectorAll( '.single-product div.product form.cart' );

	if ( ! forms.length ) {
		return;
	}

	const drawer = document.getElementById( 'cart-drawer' );
	const canAjaxAddToCart = !! (
		drawer &&
		window.jQuery &&
		window.wc_add_to_cart_params &&
		window.wc_add_to_cart_params.wc_ajax_url
	);

	const syncAddToCartState = function( form ) {
		const button = form.querySelector( '.single_add_to_cart_button' );
		const variationIdField = form.querySelector( 'input[name="variation_id"]' );

		if ( ! button || ! variationIdField ) {
			return;
		}

		const isReady = !! variationIdField.value;

		button.disabled = ! isReady;
		button.classList.toggle( 'shop-single__cta-disabled', ! isReady );
		button.setAttribute( 'aria-disabled', isReady ? 'false' : 'true' );
	};

	const syncSwatches = function( form ) {
		const controls = form.querySelectorAll( '.shop-single__attribute-ui' );

		controls.forEach( function( control ) {
			const attributeName = control.getAttribute( 'data-attribute_name' );
			const select = form.querySelector( 'select[name="' + attributeName + '"]' );

			if ( ! select ) {
				return;
			}

			const currentValue = select.value;
			const buttons = control.querySelectorAll( '.shop-single__swatch' );

			buttons.forEach( function( button ) {
				const option = Array.from( select.options ).find( function( item ) {
					return item.value === button.dataset.value;
				} );
				const isDisabled = ! option || option.disabled;
				const isSelected = currentValue === button.dataset.value;

				button.classList.toggle( 'is-selected', isSelected );
				button.setAttribute( 'aria-pressed', isSelected ? 'true' : 'false' );
				button.disabled = isDisabled;
			} );
		} );
	};

	const setButtonLoading = function( button, isLoading ) {
		if ( ! button ) {
			return;
		}

		button.classList.toggle( 'loading', isLoading );

		if ( isLoading ) {
			button.dataset.wasDisabled = button.disabled ? 'true' : 'false';
			button.disabled = true;
		} else if ( button.dataset.wasDisabled !== 'true' ) {
			button.disabled = false;
		}
	};

	const submitViaAjax = function( form, button ) {
		const requestUrl = window.wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' );
		const formData = new FormData( form );
		const params = new URLSearchParams();

		formData.forEach( function( value, key ) {
			params.append( key, value );
		} );

		if ( ! params.get( 'product_id' ) ) {
			const productId = params.get( 'add-to-cart' ) || ( button ? button.value : '' );

			if ( productId ) {
				params.set( 'add-to-cart', productId );
				params.set( 'product_id', productId );
			}
		}

		if ( ! params.get( 'quantity' ) ) {
			params.set( 'quantity', '1' );
		}

		if ( form.classList.contains( 'variations_form' ) ) {
			const variationId = params.get( 'variation_id' );

			if ( variationId && variationId !== '0' ) {
				params.set( 'product_id', variationId );
			}
		}

		if ( ! params.get( 'product_id' ) ) {
			form.submit();
			return;
		}

		setButtonLoading( button, true );

		window.jQuery( document.body ).trigger( 'adding_to_cart', [ window.jQuery( button ), Object.fromEntries( params.entries() ) ] );

		fetch( requestUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
			},
			body: params.toString(),
		} )
			.then( function( response ) {
				if ( ! response.ok ) {
					throw new Error( 'Add to cart request failed.' );
				}

				return response.json();
			} )
			.then( function( response ) {
				if ( ! response ) {
					throw new Error( 'Invalid add to cart response.' );
				}

				if ( response.error && response.product_url ) {
					window.location = response.product_url;
					return;
				}

				window.jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, window.jQuery( button ) ] );
			} )
			.catch( function() {
				form.submit();
			} )
			.finally( function() {
				setButtonLoading( button, false );

				if ( form.classList.contains( 'variations_form' ) ) {
					window.requestAnimationFrame( function() {
						syncAddToCartState( form );
					} );
				}
			} );
	};

	forms.forEach( function( form ) {
		const isVariationForm = form.classList.contains( 'variations_form' );

		if ( isVariationForm ) {
			form.classList.add( 'has-variation-swatches' );
			syncSwatches( form );
			syncAddToCartState( form );

			form.addEventListener( 'click', function( event ) {
				const swatch = event.target.closest( '.shop-single__swatch' );

				if ( ! swatch || swatch.disabled ) {
					return;
				}

				const group = swatch.closest( '.shop-single__attribute-ui' );
				const attributeName = group ? group.getAttribute( 'data-attribute_name' ) : '';
				const select = attributeName ? form.querySelector( 'select[name="' + attributeName + '"]' ) : null;

				if ( ! select ) {
					return;
				}

				select.value = swatch.dataset.value;
				select.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			} );

			form.addEventListener( 'change', function( event ) {
				if ( event.target.matches( '.variations select' ) ) {
					syncSwatches( form );
					window.requestAnimationFrame( function() {
						syncAddToCartState( form );
					} );
				}
			} );

			if ( window.jQuery ) {
				window.jQuery( form ).on( 'reset_data woocommerce_update_variation_values found_variation hide_variation show_variation', function() {
					window.requestAnimationFrame( function() {
						syncSwatches( form );
						syncAddToCartState( form );
					} );
				} );
			}

			if ( canAjaxAddToCart ) {
				const button = form.querySelector( '.single_add_to_cart_button' );

				if ( button ) {
					button.addEventListener( 'click', function( event ) {
						const variationIdField = form.querySelector( 'input[name="variation_id"]' );
						const hasVariation = variationIdField && variationIdField.value;
						const hasErrorState =
							button.classList.contains( 'disabled' ) ||
							button.classList.contains( 'wc-variation-selection-needed' ) ||
							button.classList.contains( 'wc-variation-is-unavailable' );

						if ( ! hasVariation || hasErrorState || button.disabled ) {
							return;
						}

						event.preventDefault();
						event.stopImmediatePropagation();
						submitViaAjax( form, button );
					} );
				}
			}
		} else if ( canAjaxAddToCart ) {
			form.addEventListener( 'submit', function( event ) {
				const button = event.submitter || form.querySelector( '.single_add_to_cart_button' );

				if ( ! button || button.disabled ) {
					return;
				}

				event.preventDefault();
				submitViaAjax( form, button );
			} );
		}
	} );
}() );
