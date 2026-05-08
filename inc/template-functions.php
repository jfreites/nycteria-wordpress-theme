<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Nycteria_Store
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function nycteria_store_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'nycteria_store_body_classes' );

/**
 * Get random content for no results search page.
 *
 * @return array Array with 'message' and 'image_url'.
 */
function nycteria_store_get_random_no_results_content() {
	$content_pool = array(
		array(
			'message' => __( 'Lo que buscas se ha desvanecido en las sombras.', 'nycteria-store' ),
			'image'   => get_template_directory_uri() . '/assets/images/nycteria-store.jpg',
		),
		array(
			'message' => __( 'El abismo no ha devuelto resultados para esta búsqueda.', 'nycteria-store' ),
			'image'   => get_template_directory_uri() . '/assets/images/flor-morada.png',
		),
		array(
			'message' => __( 'Incluso en la oscuridad, algunas piezas son difíciles de encontrar.', 'nycteria-store' ),
			'image'   => get_template_directory_uri() . '/assets/images/nycteria-store-front.jpg',
		),
		array(
			'message' => __( 'Los ecos de tu búsqueda no han encontrado respuesta en nuestro catálogo.', 'nycteria-store' ),
			'image'   => get_template_directory_uri() . '/assets/images/hero-contact-C7zUOIEP.jpg',
		),
	);

	return $content_pool[ array_rand( $content_pool ) ];
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function nycteria_store_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'nycteria_store_pingback_header' );
