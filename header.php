<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nycteria_Store
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'nycteria-store' ); ?></a>

	<?php
	$show_search         = get_theme_mod( 'show_search', true );
	$header_announcement = trim( (string) get_theme_mod( 'nycteria_header_announcement', '' ) );
	$cart_count          = 0;
	$cart_url            = '#';

	if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) ) {
		$cart_url = wc_get_cart_url();

		if ( WC()->cart ) {
			$cart_count = (int) WC()->cart->get_cart_contents_count();
		}
	}
	?>

	<header id="masthead" class="site-header">
		<div class="header-inner">
			<div class="header-left site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php elseif ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="main-navigation header-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'nycteria-store' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'nycteria-store' ); ?></span>
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"/>
					</svg>
				</button>
				<div class="main-navigation__container">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'nav-menu',
							'container'      => false,
						)
					);
					?>
				</div>
			</nav>

			<div class="header-actions">
				<?php if ( $show_search ) : ?>
					<a class="header-icon search-toggle" href="<?php echo esc_url( add_query_arg( 's', '', home_url( '/' ) ) ); ?>" aria-label="<?php esc_attr_e( 'Search', 'nycteria-store' ); ?>">
						<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="1.5"/>
							<path d="M16 16l4.5 4.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"/>
						</svg>
					</a>
				<?php endif; ?>

				<a class="header-cart-link" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'View cart', 'nycteria-store' ); ?>">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
						<path d="M4.5 6h2l1.6 8.2a1 1 0 0 0 1 .8h8.7a1 1 0 0 0 1-.8L20.5 8H8.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
						<circle cx="10" cy="19" r="1.25" fill="currentColor"/>
						<circle cx="17" cy="19" r="1.25" fill="currentColor"/>
					</svg>
					<span class="header-cart-count"><?php echo esc_html( $cart_count ); ?></span>
				</a>
			</div>
		</div>
	</header>

	<?php if ( $header_announcement ) : ?>
	<div class="header-announcement">
		<p class="header-announcement__text"><?php echo esc_html( $header_announcement ); ?></p>
	</div>
	<?php endif; ?>

	<div id="content" class="site-content">
