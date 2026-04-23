<?php
/**
 * Cart Drawer
 *
 * Slide-in sidebar cart panel activated by the header cart icon.
 * Updates dynamically via WooCommerce cart fragments + custom AJAX handlers.
 *
 * @package Nycteria_Store
 */

defined( 'ABSPATH' ) || exit;

// ─── Enqueue ─────────────────────────────────────────────────────────────────

/**
 * Enqueue the cart drawer script and pass config to JS.
 */
function nycteria_cart_drawer_enqueue() {
	wp_enqueue_script(
		'nycteria-cart-drawer',
		get_template_directory_uri() . '/js/cart-drawer.js',
		array( 'jquery' ),
		_S_VERSION,
		true
	);

	wp_localize_script(
		'nycteria-cart-drawer',
		'nycteriaCart',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'nycteria_cart_nonce' ),
			'updateAction' => 'nycteria_update_cart_item',
			'removeAction' => 'nycteria_remove_cart_item',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'nycteria_cart_drawer_enqueue' );

// ─── Markup ───────────────────────────────────────────────────────────────────

/**
 * Output the cart drawer shell in wp_footer.
 * The inner content is rendered separately so it can be used as a fragment.
 */
function nycteria_cart_drawer_markup() {
	?>
	<div id="cart-drawer" class="cart-drawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Carrito de compras', 'nycteria-store' ); ?>">
		<div class="cart-drawer__overlay" aria-hidden="true"></div>
		<div class="cart-drawer__panel">
			<header class="cart-drawer__header">
				<h2 class="cart-drawer__title"><?php esc_html_e( 'Tu Carrito', 'nycteria-store' ); ?></h2>
				<button class="cart-drawer__close" aria-label="<?php esc_attr_e( 'Cerrar carrito', 'nycteria-store' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="18" height="18">
						<path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"/>
					</svg>
				</button>
			</header>

			<?php echo nycteria_cart_drawer_inner_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'nycteria_cart_drawer_markup' );

// ─── Inner HTML ───────────────────────────────────────────────────────────────

/**
 * Build and return the full <div class="cart-drawer__inner"> HTML.
 * Used both for initial render and for WC cart fragments.
 *
 * @return string
 */
function nycteria_cart_drawer_inner_html() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return '';
	}

	$cart_items = WC()->cart->get_cart();

	ob_start();
	?>
	<div class="cart-drawer__inner">
		<?php if ( $cart_items ) : ?>
			<div class="cart-drawer__body">
				<ul class="cart-drawer__items">
					<?php foreach ( $cart_items as $cart_item_key => $cart_item ) : ?>
						<?php
						/** @var WC_Product $product */
						$product = $cart_item['data'];

						if ( ! $product || ! $product->exists() || ! $product->is_purchasable() ) {
							continue;
						}

						$product_id  = absint( $cart_item['product_id'] );
						$quantity    = absint( $cart_item['quantity'] );
						$product_url = esc_url( get_permalink( $product_id ) );
						$thumbnail   = $product->get_image(
							'thumbnail',
							array(
								'loading' => 'lazy',
								'class'   => 'cart-drawer__item-thumb',
							)
						);
						$price_html = WC()->cart->get_product_subtotal( $product, $quantity );
						?>
						<li class="cart-drawer__item" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
							<a class="cart-drawer__item-image" href="<?php echo esc_url( $product_url ); ?>" tabindex="-1" aria-hidden="true">
								<?php echo wp_kses_post( $thumbnail ); ?>
							</a>

							<div class="cart-drawer__item-details">
								<a class="cart-drawer__item-name" href="<?php echo esc_url( $product_url ); ?>">
									<?php echo esc_html( $product->get_name() ); ?>
								</a>
								<span class="cart-drawer__item-price">
									<?php echo wp_kses_post( $price_html ); ?>
								</span>

								<div class="cart-drawer__item-controls">
									<div class="cart-drawer__qty" role="group" aria-label="<?php esc_attr_e( 'Cantidad', 'nycteria-store' ); ?>">
										<button
											class="cart-drawer__qty-btn cart-drawer__qty-btn--minus"
											aria-label="<?php esc_attr_e( 'Disminuir cantidad', 'nycteria-store' ); ?>"
											data-action="decrease"
										>
											<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="14" height="14">
												<path d="M5 12h14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
											</svg>
										</button>
										<span class="cart-drawer__qty-value" aria-live="polite"><?php echo esc_html( $quantity ); ?></span>
										<button
											class="cart-drawer__qty-btn cart-drawer__qty-btn--plus"
											aria-label="<?php esc_attr_e( 'Aumentar cantidad', 'nycteria-store' ); ?>"
											data-action="increase"
										>
											<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="14" height="14">
												<path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
											</svg>
										</button>
									</div>

									<button
										class="cart-drawer__remove"
										aria-label="<?php esc_attr_e( 'Eliminar producto', 'nycteria-store' ); ?>"
										data-key="<?php echo esc_attr( $cart_item_key ); ?>"
									>
										<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="12" height="12">
											<path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
										</svg>
										<?php esc_html_e( 'Eliminar', 'nycteria-store' ); ?>
									</button>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php
			// snippet for the store free shipping plugin
			if ( function_exists( 'wc_free_shipping_bar' ) ) {
				wc_free_shipping_bar(
					array(
						'context' => 'drawer',
						'class'   => 'cart-drawer-free-shipping',
					)
				);
			}
			?>

			<footer class="cart-drawer__footer">
				<div class="cart-drawer__subtotal">
					<span class="cart-drawer__subtotal-label"><?php esc_html_e( 'Subtotal', 'nycteria-store' ); ?></span>
					<span class="cart-drawer__subtotal-amount"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
				</div>
				<a class="cart-drawer__checkout homepage-button" href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
					<?php esc_html_e( 'Ir al Checkout', 'nycteria-store' ); ?>
				</a>
				<a class="cart-drawer__cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<?php esc_html_e( 'Ver carrito completo', 'nycteria-store' ); ?>
				</a>
			</footer>

		<?php else : ?>
			<div class="cart-drawer__body cart-drawer__body--empty">
				<svg class="cart-drawer__empty-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="40" height="40">
					<path d="M4.5 6h2l1.6 8.2a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L20.5 8H8.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"/>
					<circle cx="10" cy="19" r="1.25" fill="currentColor"/>
					<circle cx="17" cy="19" r="1.25" fill="currentColor"/>
				</svg>
				<p class="cart-drawer__empty-text"><?php esc_html_e( 'Tu carrito está vacío.', 'nycteria-store' ); ?></p>
				<a class="homepage-button cart-drawer__shop-btn" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'Ir a la tienda', 'nycteria-store' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
	<?php

	return ob_get_clean();
}

// ─── Cart Fragments ───────────────────────────────────────────────────────────

/**
 * Add the cart drawer inner HTML as a WooCommerce cart fragment.
 * WooCommerce will automatically replace the matching DOM element
 * whenever the cart changes (add, remove, update).
 *
 * @param array $fragments
 * @return array
 */
function nycteria_cart_drawer_fragment( $fragments ) {
	$fragments['div.cart-drawer__inner'] = nycteria_cart_drawer_inner_html();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'nycteria_cart_drawer_fragment' );

// ─── AJAX: Update quantity ────────────────────────────────────────────────────

/**
 * AJAX handler — update cart item quantity.
 * Accepts cart_item_key + quantity. Setting quantity to 0 removes the item.
 */
function nycteria_ajax_update_cart_item() {
	check_ajax_referer( 'nycteria_cart_nonce', 'nonce' );

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';
	$quantity      = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

	if ( ! $cart_item_key ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Clave de producto no válida.', 'nycteria-store' ) ) );
	}

	if ( ! WC()->cart->get_cart_item( $cart_item_key ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Producto no encontrado en el carrito.', 'nycteria-store' ) ) );
	}

	if ( 0 === $quantity ) {
		WC()->cart->remove_cart_item( $cart_item_key );
	} else {
		WC()->cart->set_quantity( $cart_item_key, $quantity, true );
	}

	WC()->cart->calculate_totals();

	wp_send_json_success(
		array(
			'drawer_html' => nycteria_cart_drawer_inner_html(),
			'cart_count'  => (int) WC()->cart->get_cart_contents_count(),
		)
	);
}
add_action( 'wp_ajax_nycteria_update_cart_item', 'nycteria_ajax_update_cart_item' );
add_action( 'wp_ajax_nopriv_nycteria_update_cart_item', 'nycteria_ajax_update_cart_item' );

// ─── AJAX: Remove item ────────────────────────────────────────────────────────

/**
 * AJAX handler — remove a single cart item.
 */
function nycteria_ajax_remove_cart_item() {
	check_ajax_referer( 'nycteria_cart_nonce', 'nonce' );

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) ) : '';

	if ( ! $cart_item_key ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Clave de producto no válida.', 'nycteria-store' ) ) );
	}

	WC()->cart->remove_cart_item( $cart_item_key );
	WC()->cart->calculate_totals();

	wp_send_json_success(
		array(
			'drawer_html' => nycteria_cart_drawer_inner_html(),
			'cart_count'  => (int) WC()->cart->get_cart_contents_count(),
		)
	);
}
add_action( 'wp_ajax_nycteria_remove_cart_item', 'nycteria_ajax_remove_cart_item' );
add_action( 'wp_ajax_nopriv_nycteria_remove_cart_item', 'nycteria_ajax_remove_cart_item' );
