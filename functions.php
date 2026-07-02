<?php
/**
 * Nycteria Store functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Nycteria_Store
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nycteria_store_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Nycteria Store, use a find and replace
		* to change 'nycteria-store' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'nycteria-store', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'nycteria-store' ),
			'footer' => esc_html__( 'Footer', 'nycteria-store' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'nycteria_store_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'nycteria_store_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function nycteria_store_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nycteria_store_content_width', 640 );
}
add_action( 'after_setup_theme', 'nycteria_store_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function nycteria_store_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'nycteria-store' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'nycteria-store' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nycteria_store_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function nycteria_store_scripts() {
	wp_enqueue_style(
		'nycteria-theme-fonts',
		'https://fonts.googleapis.com/css2?family=Grenze&family=Playfair+Display&family=Montserrat&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'nycteria-store-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'nycteria-store-style', 'rtl', 'replace' );

	wp_enqueue_script( 'nycteria-store-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nycteria_store_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Return whether the custom cart drawer should be loaded.
 *
 * @return bool
 */
function nycteria_is_cart_drawer_enabled() {
	return (bool) get_theme_mod( 'nycteria_enable_cart_drawer', true );
}

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';

	if ( nycteria_is_cart_drawer_enabled() ) {
		require get_template_directory() . '/inc/cart-drawer.php';
	}
}

/**
 * Change sorting
 * @param mixed $sortby
 */
function custom_woocommerce_catalog_orderby( $sortby ) {
    // Change "Default sorting" label
    if ( isset( $sortby['menu_order'] ) ) {
        $sortby['menu_order'] = __( 'Ordena por', 'nycteria-store' );
    }

    if ( isset( $sortby['price'] ) ) {
        $sortby['price'] = __( 'Precio', 'nycteria-store' );
    }

    if ( isset( $sortby['popularity'] ) ) {
        $sortby['popularity'] = __( 'Lo más Popular', 'nycteria-store' );
    }

    if ( isset( $sortby['date'] ) ) {
        $sortby['date'] = __( 'Recién llegados', 'nycteria-store' );
    }

    // Removed some options
    unset( $sortby['rating'] );
	unset( $sortby['price-desc'] );

    return $sortby;
}
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );

/**
 * Tracking Order
 *
 * Admin Backend
 */
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'wc_custom_tracking_fields', 10, 1 );

function wc_custom_tracking_fields( $order ) {
    // Obtener valores guardados previamente
    $tracking_number   = $order->get_meta( '_tracking_number' );
    $tracking_url      = $order->get_meta( '_tracking_url' );
	$tracking_proviswe = $order->get_meta( '_tracking_provider' );

    echo '<div class="order_data_column" style="width:100%; margin-top:20px;">';
    echo '<h3><span class="dashicons dashicons-location"></span> Información de Seguimiento</h3>';

    // Campo: Número de seguimiento
    woocommerce_wp_text_input( array(
        'id'            => '_tracking_number',
        'label'         => 'Número de Seguimiento (Guía)',
        'value'         => $tracking_number,
        'wrapper_class' => 'form-field-wide',
        'placeholder'   => 'Ej: 1Z9999999999999999'
    ) );

    // Campo: URL de seguimiento
    // woocommerce_wp_text_input( array(
    //     'id'            => '_tracking_url',
    //     'label'         => 'URL de Seguimiento del Proveedor (Dejar en blanco para usar Estafeta)',
    //     'value'         => $tracking_url,
    //     'wrapper_class' => 'form-field-wide',
    //     'placeholder'   => 'https://...'
    // ) );

	// Campo: Proveedor de seguimiento
    // woocommerce_wp_text_input( array(
    //     'id'            => '_tracking_provider',
    //     'label'         => 'Proveedor de envíos (Dejar en blanco para usar Estafeta)',
    //     'value'         => $tracking_proviswe,
    //     'wrapper_class' => 'form-field-wide',
    //     'placeholder'   => 'ESTAFETA'
    // ) );

    woocommerce_wp_select( array(
        'id'          => '_tracking_provider',
        'label'       => __( 'Proveedor de envíos', 'nycteria-store' ),
        'options'     => array(
            'estafeta' => __( 'Estafeta', 'nycteria-store' ),
            'correos_mexico' => __( 'Correos de México', 'nycteria-store' ),
        ),
        'value'       => $tracking_proviswe,
        //'desc_tip'    => true, // Permite un icono de ayuda con descripción
        //'description' => __( 'Selecciona el proveedor para el envío de este pedido.', 'nycteria-store' ),
        'wrapper_class' => 'form-field-wide',
    ) );

    echo '</div>';
}

/**
 * Save tracking data
 */
add_action( 'woocommerce_process_shop_order_meta', 'wc_save_custom_tracking_fields', 10, 2 );

function wc_save_custom_tracking_fields( $order_id, $post ) {
    $order = wc_get_order( $order_id );

    if ( isset( $_POST['_tracking_number'] ) ) {
        $order->update_meta_data( '_tracking_number', sanitize_text_field( $_POST['_tracking_number'] ) );
    }

    /*
    if ( isset( $_POST['_tracking_url'] ) ) {
        // esc_url_raw asegura que el formato del link sea seguro
        $order->update_meta_data( '_tracking_url', esc_url_raw( $_POST['_tracking_url'] ) );
    }
    */

	if ( isset( $_POST['_tracking_provider'] ) ) {
        $order->update_meta_data( '_tracking_provider', sanitize_text_field( $_POST['_tracking_provider'] ) );

        if ( $_POST['_tracking_provider'] === 'estafeta' ) {
            $tracking_url = 'https://cs.estafeta.com/es/Tracking/searchByGet?wayBillType=1&wayBill=' . urlencode( $tracking_number );
        } else {
            $tracking_url = 'https://www.correosdemexico.gob.mx/sslservicios/seguimientoenvio/seguimiento.aspx';
        }

        $order->update_meta_data( '_tracking_url', esc_url_raw( $tracking_url ) );
    }

    $order->save();
}

/**
 * Tracking form shortcode
 */
add_shortcode( 'rastreo_pedido', 'wc_public_tracking_form_shortcode' );

function wc_public_tracking_form_shortcode() {
    ob_start();

    if ( isset( $_POST['submit_tracking'] ) && wp_verify_nonce( $_POST['tracking_nonce'], 'verify_tracking_action' ) ) {

        $order_id    = absint( $_POST['order_id'] );
        $order_email = sanitize_email( $_POST['order_email'] );

        $order = wc_get_order( $order_id );

        if ( $order && strtolower($order->get_billing_email()) === strtolower($order_email) ) {
            $status            = wc_get_order_status_name( $order->get_status() );
            $tracking_number   = $order->get_meta( '_tracking_number' );
            $tracking_url      = $order->get_meta( '_tracking_url' );
			$tracking_provider = $order->get_meta( '_tracking_provider' );

            echo '<div class="tracking-result" style="background: var(--bg-elevated); border-left: 4px solid var(--accent); padding: 20px; margin-bottom: 30px; border-radius: 4px;">';
            echo '<h3 style="margin-top:0; font-size: 1.4rem; margin-bottom: 1rem;">Estado de tu pedido: <strong>' . esc_html( $status ) . '</strong></h3>';

            if ( ! empty( $tracking_number ) ) {
                echo '<p style="margin-bottom: 0rem;"><strong>Número de guía:</strong> ' . esc_html( $tracking_number ) . '</p>';
				echo '<p><strong>Proveedor:</strong> ' . esc_html( strtoupper( $tracking_provider ) ) . '</p>';

                if ( ! empty( $tracking_url ) ) {
                    echo '<a href="' . esc_url( $tracking_url ) . '" target="_blank" class="button alt" style="text-decoration:none; display:inline-block; margin-top:10px;">Rastrear paquete &rarr;</a>';
                }
            } else {
                echo '<p><em>Tu pedido está siendo procesado y aún no tiene un número de guía asignado. Por favor, revisa nuevamente más tarde.</em></p>';
            }

            echo '</div>';
        } else {
            echo '<div style="color: #d63638; background: #fcf0f1; border-left: 4px solid #d63638; padding: 15px; margin-bottom: 20px;">Los datos ingresados no coinciden con ningún pedido registrado. Revisa tu número de pedido y correo.</div>';
        }
    }

    // Formulario HTML
    ?>
    <form method="POST" action="" class="wc-tracking-form" style="max-width: 400px; margin: 0 auto;">
        <?php wp_nonce_field( 'verify_tracking_action', 'tracking_nonce' ); ?>
        <p class="form-row form-row-wide" style="display: flex; flex-direction: column;">
            <label for="order_id">Número de Pedido <span class="required">*</span></label>
            <input type="number" name="order_id" id="order_id" required class="input-text" placeholder="Ej: 1045" value="<?php echo isset($_POST['order_id']) ? esc_attr($_POST['order_id']) : ''; ?>">
        </p>
        <p class="form-row form-row-wide" style="display: flex; flex-direction: column;">
            <label for="order_email">Correo electrónico de facturación <span class="required">*</span></label>
            <input type="email" name="order_email" id="order_email" required class="input-text" placeholder="tu@correo.com" value="<?php echo isset($_POST['order_email']) ? esc_attr($_POST['order_email']) : ''; ?>">
        </p>
        <p class="form-row">
            <button type="submit" name="submit_tracking" class="button wp-element-button">Consultar Estado</button>
        </p>
    </form>
    <?php

    return ob_get_clean();
}

/**
 * Add tracking information to the "Completed Order" email.
 */
add_action( 'woocommerce_email_after_order_table', 'nycteria_store_add_tracking_to_email', 10, 4 );

function nycteria_store_add_tracking_to_email( $order, $sent_to_admin, $plain_text, $email ) {
    // Only show in the "Completed Order" email for the customer
    if ( 'customer_completed_order' !== $email->id ) {
        return;
    }

    $tracking_number   = $order->get_meta( '_tracking_number' );
    //$tracking_url      = $order->get_meta( '_tracking_url' );
    $tracking_provider = $order->get_meta( '_tracking_provider' );

    if ( empty( $tracking_number ) ) {
        return;
    }

    if ( $tracking_provider === 'estafeta' ) {
        $tracking_url = 'https://cs.estafeta.com/es/Tracking/searchByGet?wayBillType=1&wayBill=' . urlencode( $tracking_number );
    } else {
        $tracking_url = 'https://www.correosdemexico.gob.mx/sslservicios/seguimientoenvio/seguimiento.aspx';
    }

    $tracking_provider_display_name = $tracking_provider == 'estafeta' ? 'ESTAFETA' : 'CORREOS DE MÉXICO';

    // Output tracking info
    ?>
    <div style="margin-bottom: 40px; padding: 20px; background-color: #121212; border-left: 4px solid #c52020; color: #ffffff; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
        <h2 style="color: #c52020; margin-top: 0; font-size: 18px; text-transform: uppercase;"><?php esc_html_e( 'Información de Envío', 'nycteria-store' ); ?></h2>
        <p style="margin: 10px 0;">
            <strong><?php esc_html_e( 'Número de guía:', 'nycteria-store' ); ?></strong> <?php echo esc_html( $tracking_number ); ?><br>
            <strong><?php esc_html_e( 'Proveedor:', 'nycteria-store' ); ?></strong> <?php echo esc_html( $tracking_provider_display_name ); ?>
        </p>
        <p style="margin: 20px 0 0;">
            <a href="<?php echo esc_url( $tracking_url ); ?>" target="_blank" style="background-color: #c52020; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 2px; font-weight: bold; display: inline-block;">
                <?php esc_html_e( 'Rastrear paquete &rarr;', 'nycteria-store' ); ?>
            </a>
        </p>
    </div>
    <?php
}
