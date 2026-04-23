<?php
/**
 * Nycteria Store Theme Customizer
 *
 * @package Nycteria_Store
 */

/**
 * Return published WooCommerce products as Customizer choices.
 *
 * @return array<string, string>
 */
function nycteria_store_get_product_choices() {
	$choices = array(
		'' => __( 'Select a product', 'nycteria-store' ),
	);

	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_products' ) ) {
		return $choices;
	}

	$products = wc_get_products(
		array(
			'status'             => 'publish',
			'catalog_visibility' => 'visible',
			'limit'              => -1,
			'orderby'            => 'title',
			'order'              => 'ASC',
			'return'             => 'objects',
		)
	);

	foreach ( $products as $product ) {
		$choices[ (string) $product->get_id() ] = $product->get_name();
	}

	return $choices;
}

/**
 * Return product category choices for the Customizer.
 *
 * @return array<string, string>
 */
function nycteria_store_get_product_category_choices() {
	$choices = array(
		'' => __( 'Select a category', 'nycteria-store' ),
	);

	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return $choices;
	}

	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	if ( is_wp_error( $terms ) ) {
		return $choices;
	}

	foreach ( $terms as $term ) {
		$choices[ (string) $term->term_id ] = $term->name;
	}

	return $choices;
}

/**
 * Sanitize a Customizer select value against its declared choices.
 *
 * @param string                $input   Raw setting value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function nycteria_store_sanitize_select_choice( $input, $setting ) {
	$input   = (string) $input;
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return array_key_exists( $input, $choices ) ? $input : '';
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function nycteria_store_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'nycteria_store_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'nycteria_store_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_section('theme_options', [
    	'title' => 'Theme Options',
	]);

	// Toggle search icon
	$wp_customize->add_setting('show_search', [
		'default' => true,
	]);

	$wp_customize->add_control('show_search', [
		'label' => 'Show Search Icon',
		'section' => 'theme_options',
		'type' => 'checkbox',
	]);

	$wp_customize->add_setting(
		'nycteria_header_announcement',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_header_announcement',
		array(
			'label'       => __( 'Header Announcement', 'nycteria-store' ),
			'description' => __( 'Optional short announcement shown below the navbar.', 'nycteria-store' ),
			'section'     => 'theme_options',
			'type'        => 'text',
		)
	);

	$wp_customize->add_section(
		'nycteria_homepage',
		array(
			'title' => __( 'Homepage', 'nycteria-store' ),
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_background_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'nycteria_hero_background_image',
			array(
				'label'     => __( 'Hero Background Image', 'nycteria-store' ),
				'section'   => 'nycteria_homepage',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_background_video',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'nycteria_hero_background_video',
		array(
			'label'       => __( 'Hero Background Video URL', 'nycteria-store' ),
			'description' => __( 'Optional MP4/WebM URL. When set, video is shown above the hero image.', 'nycteria-store' ),
			'section'     => 'nycteria_homepage',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_title',
		array(
			'default'           => __( 'New Winter Arrivals', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_hero_title',
		array(
			'label'   => __( 'Hero Title', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_subtitle',
		array(
			'default'           => __( 'Tailored silhouettes, sharp textures, and a darker point of view.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_hero_subtitle',
		array(
			'label'   => __( 'Hero Subtitle', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_cta_label',
		array(
			'default'           => __( 'Shop Collection', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_hero_cta_label',
		array(
			'label'   => __( 'Hero CTA Label', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_hero_cta_url',
		array(
			'default'           => home_url( '/shop/' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'nycteria_hero_cta_url',
		array(
			'label'   => __( 'Hero CTA URL', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'nycteria_marketing_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'nycteria_marketing_image',
			array(
				'label'     => __( 'Marketing Image', 'nycteria-store' ),
				'section'   => 'nycteria_homepage',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'nycteria_marketing_kicker',
		array(
			'default'           => __( 'Editorial Focus', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_marketing_kicker',
		array(
			'label'   => __( 'Marketing Kicker', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_marketing_title',
		array(
			'default'           => __( 'Designed to move between statement and restraint.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_marketing_title',
		array(
			'label'   => __( 'Marketing Title', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_marketing_copy',
		array(
			'default'           => __( 'Crafted essentials, refined finishes, and a monochrome palette built for modern wardrobes.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_marketing_copy',
		array(
			'label'   => __( 'Marketing Copy', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'textarea',
		)
	);

	$product_choices  = nycteria_store_get_product_choices();
	$category_choices = nycteria_store_get_product_category_choices();

	$wp_customize->add_setting(
		'nycteria_featured_grid_title',
		array(
			'default'           => __( 'Featured Artifacts', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_grid_title',
		array(
			'label'       => __( 'Featured Grid Title', 'nycteria-store' ),
			'description' => __( 'Headline displayed above the featured products section.', 'nycteria-store' ),
			'section'     => 'nycteria_homepage',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_grid_shop_link_label',
		array(
			'default'           => __( 'View All Products', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_grid_shop_link_label',
		array(
			'label'   => __( 'Featured Grid Shop Link Label', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_product_primary',
		array(
			'default'           => '',
			'sanitize_callback' => 'nycteria_store_sanitize_select_choice',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_product_primary',
		array(
			'label'       => __( 'Featured Product 1', 'nycteria-store' ),
			'description' => __( 'Large product card shown first in the grid.', 'nycteria-store' ),
			'section'     => 'nycteria_homepage',
			'type'        => 'select',
			'choices'     => $product_choices,
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_product_secondary',
		array(
			'default'           => '',
			'sanitize_callback' => 'nycteria_store_sanitize_select_choice',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_product_secondary',
		array(
			'label'   => __( 'Featured Product 2', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'select',
			'choices' => $product_choices,
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_product_tertiary',
		array(
			'default'           => '',
			'sanitize_callback' => 'nycteria_store_sanitize_select_choice',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_product_tertiary',
		array(
			'label'   => __( 'Featured Product 3', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'select',
			'choices' => $product_choices,
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_category_term',
		array(
			'default'           => '',
			'sanitize_callback' => 'nycteria_store_sanitize_select_choice',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_category_term',
		array(
			'label'       => __( 'Featured Category', 'nycteria-store' ),
			'description' => __( 'The button will automatically link to the selected category archive.', 'nycteria-store' ),
			'section'     => 'nycteria_homepage',
			'type'        => 'select',
			'choices'     => $category_choices,
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_category_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'nycteria_featured_category_image',
			array(
				'label'     => __( 'Featured Category Image', 'nycteria-store' ),
				'section'   => 'nycteria_homepage',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_category_headline',
		array(
			'default'           => __( 'Category Spotlight', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_category_headline',
		array(
			'label'   => __( 'Featured Category Headline', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_category_description',
		array(
			'default'           => __( 'Highlight a category with supporting copy and a dedicated call to action.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_category_description',
		array(
			'label'   => __( 'Featured Category Description', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'nycteria_featured_category_button_label',
		array(
			'default'           => __( 'Explore Category', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_featured_category_button_label',
		array(
			'label'   => __( 'Featured Category Button Label', 'nycteria-store' ),
			'section' => 'nycteria_homepage',
			'type'    => 'text',
		)
	);

	$wp_customize->add_section(
		'nycteria_contact_page',
		array(
			'title' => __( 'Contact Page', 'nycteria-store' ),
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_hero_title',
		array(
			'default'           => __( 'Contacto', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_hero_title',
		array(
			'label'   => __( 'Contact Hero Title', 'nycteria-store' ),
			'section' => 'nycteria_contact_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_hero_subtitle',
		array(
			'default'           => __( 'Escrbenos y te responderemos con la misma atencion con la que seleccionamos cada pieza.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_hero_subtitle',
		array(
			'label'   => __( 'Contact Hero Subtitle', 'nycteria-store' ),
			'section' => 'nycteria_contact_page',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_form_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_form_shortcode',
		array(
			'label'       => __( 'Contact Form Shortcode', 'nycteria-store' ),
			'description' => __( 'Paste a shortcode from a form plugin such as Contact Form 7.', 'nycteria-store' ),
			'section'     => 'nycteria_contact_page',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_hours',
		array(
			'default'           => "Lunes a Viernes: 10:00 - 19:00\nSabado: 11:00 - 17:00",
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_hours',
		array(
			'label'   => __( 'Opening Hours', 'nycteria-store' ),
			'section' => 'nycteria_contact_page',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_phone',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_phone',
		array(
			'label'       => __( 'Contact Phone', 'nycteria-store' ),
			'description' => __( 'Used if no store phone is available from WooCommerce.', 'nycteria-store' ),
			'section'     => 'nycteria_contact_page',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_contact_map_embed_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'nycteria_contact_map_embed_url',
		array(
			'label'       => __( 'Google Maps Embed URL', 'nycteria-store' ),
			'description' => __( 'Paste the Google Maps embed URL for the iframe.', 'nycteria-store' ),
			'section'     => 'nycteria_contact_page',
			'type'        => 'url',
		)
	);

	$wp_customize->add_section(
		'nycteria_footer',
		array(
			'title' => __( 'Footer', 'nycteria-store' ),
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_brand_image',
		array(
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'nycteria_footer_brand_image',
			array(
				'label'     => __( 'Footer Brand Image', 'nycteria-store' ),
				'description' => __( 'Optional image to replace the footer brand title.', 'nycteria-store' ),
				'section'   => 'nycteria_footer',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_brand_description',
		array(
			'default'           => __( 'Moda oscura para quienes caminan entre mundos. Elegancia gotica curada desde 2024.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_footer_brand_description',
		array(
			'label'   => __( 'Footer Brand Description', 'nycteria-store' ),
			'section' => 'nycteria_footer',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_instagram_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'nycteria_footer_instagram_url',
		array(
			'label'   => __( 'Instagram URL', 'nycteria-store' ),
			'section' => 'nycteria_footer',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_pinterest_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'nycteria_footer_pinterest_url',
		array(
			'label'   => __( 'Pinterest URL', 'nycteria-store' ),
			'section' => 'nycteria_footer',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_newsletter_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_footer_newsletter_shortcode',
		array(
			'label'       => __( 'Newsletter Shortcode', 'nycteria-store' ),
			'description' => __( 'Paste a shortcode for your newsletter signup form.', 'nycteria-store' ),
			'section'     => 'nycteria_footer',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'nycteria_footer_copyright_text',
		array(
			'default'           => __( 'NYCTERIA Gothic Boutique. Todos los derechos reservados.', 'nycteria-store' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'nycteria_footer_copyright_text',
		array(
			'label'   => __( 'Copyright Text', 'nycteria-store' ),
			'section' => 'nycteria_footer',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'nycteria_store_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function nycteria_store_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function nycteria_store_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function nycteria_store_customize_preview_js() {
	wp_enqueue_script( 'nycteria-store-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'nycteria_store_customize_preview_js' );
