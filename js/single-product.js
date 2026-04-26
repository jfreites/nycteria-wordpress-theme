( function() {
	const forms = document.querySelectorAll( '.variations_form' );

	if ( ! forms.length ) {
		return;
	}

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

	forms.forEach( function( form ) {
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
	} );
}() );
