<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Nycteria_Store
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
function nycteria_store_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 150,
			'single_image_width'    => 300,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'nycteria_store_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function nycteria_store_woocommerce_scripts() {
	wp_enqueue_style( 'nycteria-store-woocommerce-style', get_template_directory_uri() . '/woocommerce.css', array(), _S_VERSION );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'nycteria-store-woocommerce-style', $inline_font );

	if ( is_product() ) {
		wp_enqueue_script(
			'nycteria-store-single-product',
			get_template_directory_uri() . '/js/single-product.js',
			array( 'wc-add-to-cart', 'wc-add-to-cart-variation' ),
			_S_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'nycteria_store_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function nycteria_store_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'nycteria_store_woocommerce_active_body_class' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function nycteria_store_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'nycteria_store_woocommerce_related_products_args' );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'nycteria_store_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function nycteria_store_woocommerce_wrapper_before() {
		?>
			<main id="primary" class="site-main">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'nycteria_store_woocommerce_wrapper_before' );

if ( ! function_exists( 'nycteria_store_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function nycteria_store_woocommerce_wrapper_after() {
		?>
			</main><!-- #main -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'nycteria_store_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'nycteria_store_woocommerce_header_cart' ) ) {
			nycteria_store_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'nycteria_store_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function nycteria_store_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		nycteria_store_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'nycteria_store_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'nycteria_store_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function nycteria_store_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'nycteria-store' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'nycteria-store' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'nycteria_store_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function nycteria_store_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php nycteria_store_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}

if ( ! function_exists( 'nycteria_store_get_cart_count' ) ) {
	/**
	 * Return the current WooCommerce cart item count.
	 *
	 * @return int
	 */
	function nycteria_store_get_cart_count() {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
			return 0;
		}

		return (int) WC()->cart->get_cart_contents_count();
	}
}

/**
 * Refresh the custom header cart count via AJAX fragments.
 *
 * @param array $fragments Fragments to refresh.
 * @return array
 */
function nycteria_cart_count_fragment( $fragments ) {
	ob_start();
	?>
	<span class="header-cart-count"><?php echo esc_html( nycteria_store_get_cart_count() ); ?></span>
	<?php
	$fragments['.header-cart-count'] = ob_get_clean();

	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'nycteria_cart_count_fragment' );

if ( ! function_exists( 'nycteria_store_product_breadcrumb' ) ) {
	/**
	 * Render breadcrumbs for the single-product template.
	 *
	 * @return void
	 */
	function nycteria_store_product_breadcrumb() {
		?>
		<nav class="page-breadcrumbs shop-single__breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'nycteria-store' ); ?>">
			<?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
				<?php yoast_breadcrumb( '<p>', '</p>' ); ?>
			<?php elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) : ?>
				<?php rank_math_the_breadcrumbs(); ?>
			<?php else : ?>
				<?php
				woocommerce_breadcrumb(
					array(
						'wrap_before' => '<p class="woocommerce-breadcrumb">',
						'wrap_after'  => '</p>',
					)
				);
				?>
			<?php endif; ?>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'nycteria_store_get_attribute_swatch_style' ) ) {
	/**
	 * Return an inline style for color-based swatches.
	 *
	 * @param string $attribute Attribute slug.
	 * @param string $value     Option value.
	 * @return string
	 */
	function nycteria_store_get_attribute_swatch_style( $attribute, $value ) {
		$attribute = wc_attribute_label( $attribute );
		$is_color  = false !== stripos( $attribute, 'color' ) || false !== stripos( $attribute, 'colour' );

		if ( ! $is_color ) {
			return '';
		}

		$normalized = sanitize_title( (string) $value );
		$map        = array(
			'black'      => '#111111',
			'white'      => '#f5f5f5',
			'ivory'      => '#f4f1de',
			'cream'      => '#efe6d3',
			'beige'      => '#d6c2a1',
			'nude'       => '#c8a27c',
			'brown'      => '#6f4e37',
			'tan'        => '#b08968',
			'red'        => '#a32020',
			'burgundy'   => '#6d071a',
			'maroon'     => '#5f1020',
			'pink'       => '#d88ca0',
			'orange'     => '#c96b2c',
			'yellow'     => '#d6ad32',
			'gold'       => '#b89635',
			'green'      => '#567a56',
			'olive'      => '#6b6d3f',
			'blue'       => '#445f8f',
			'navy'       => '#1d2f4f',
			'purple'     => '#6a2c70',
			'lilac'      => '#9f8bb3',
			'grey'       => '#8b8b8b',
			'gray'       => '#8b8b8b',
			'silver'     => '#b8b8b8',
			'charcoal'   => '#36454f',
		);
		$color_code = '';

		if ( preg_match( '/^#(?:[0-9a-f]{3}){1,2}$/i', (string) $value ) ) {
			$color_code = $value;
		} elseif ( isset( $map[ $normalized ] ) ) {
			$color_code = $map[ $normalized ];
		} else {
			$parts = explode( '-', $normalized );

			foreach ( $parts as $part ) {
				if ( isset( $map[ $part ] ) ) {
					$color_code = $map[ $part ];
					break;
				}
			}
		}

		if ( ! $color_code ) {
			return '';
		}

		return '--swatch-color:' . esc_attr( $color_code ) . ';';
	}
}

if ( ! function_exists( 'nycteria_store_variation_swatches' ) ) {
	/**
	 * Append square variation swatches after the native attribute select.
	 *
	 * @param string $html Original dropdown HTML.
	 * @param array  $args Dropdown arguments.
	 * @return string
	 */
	function nycteria_store_variation_swatches( $html, $args ) {
		if ( ! is_product() || empty( $args['options'] ) || empty( $args['product'] ) || empty( $args['attribute'] ) ) {
			return $html;
		}

		$product   = $args['product'];
		$attribute = $args['attribute'];
		$options   = $args['options'];
		$input_name = ! empty( $args['name'] ) ? $args['name'] : 'attribute_' . sanitize_title( $attribute );

		if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( empty( $options ) ) {
			return $html;
		}

		$attribute_label = wc_attribute_label( $attribute );
		$current_value   = isset( $args['selected'] ) ? (string) $args['selected'] : '';
		$items_markup    = '';

		foreach ( $options as $option ) {
			$option_slug = (string) $option;
			$label       = $option_slug;

			if ( taxonomy_exists( $attribute ) ) {
				$term = get_term_by( 'slug', $option_slug, $attribute );

				if ( $term && ! is_wp_error( $term ) ) {
					$label = $term->name;
				}
			}

			$is_selected  = $current_value === $option_slug;
			$swatch_style = nycteria_store_get_attribute_swatch_style( $attribute, $option_slug );

			$items_markup .= sprintf(
				'<button type="button" class="shop-single__swatch%1$s" data-value="%2$s"%3$s aria-pressed="%4$s" aria-label="%5$s"><span class="shop-single__swatch-label">%6$s</span></button>',
				$is_selected ? ' is-selected' : '',
				esc_attr( $option_slug ),
				$swatch_style ? ' style="' . esc_attr( $swatch_style ) . '"' : '',
				$is_selected ? 'true' : 'false',
				esc_attr( sprintf( __( '%1$s: %2$s', 'nycteria-store' ), $attribute_label, $label ) ),
				esc_html( $label )
			);
		}

		$html .= sprintf(
			'<div class="shop-single__attribute-ui" data-attribute_name="%1$s"><div class="shop-single__attribute-label">%2$s</div><div class="shop-single__swatches" role="list">%3$s</div></div>',
			esc_attr( $input_name ),
			esc_html( $attribute_label ),
			$items_markup
		);

		return $html;
	}
}
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'nycteria_store_variation_swatches', 10, 2 );
