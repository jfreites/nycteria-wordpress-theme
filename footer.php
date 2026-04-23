<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Nycteria_Store
 */

$footer_brand_image_id    = absint( get_theme_mod( 'nycteria_footer_brand_image' ) );
$footer_brand_description = trim( (string) get_theme_mod( 'nycteria_footer_brand_description', __( 'Moda oscura para quienes caminan entre mundos. Elegancia gotica curada desde 2024.', 'nycteria-store' ) ) );
$footer_instagram_url     = esc_url( get_theme_mod( 'nycteria_footer_instagram_url', '' ) );
$footer_pinterest_url     = esc_url( get_theme_mod( 'nycteria_footer_pinterest_url', '' ) );
$footer_newsletter_code   = trim( (string) get_theme_mod( 'nycteria_footer_newsletter_shortcode', '' ) );
$footer_copyright_text    = trim( (string) get_theme_mod( 'nycteria_footer_copyright_text', __( 'NYCTERIA Gothic Boutique. Todos los derechos reservados.', 'nycteria-store' ) ) );

$footer_brand_image = '';

if ( $footer_brand_image_id ) {
	$footer_brand_image = wp_get_attachment_image(
		$footer_brand_image_id,
		'medium',
		false,
		array(
			'loading' => 'lazy',
			'class'   => 'footer-brand__image',
		)
	);
}
?>

	<footer id="colophon" class="site-footer">
		<div class="site-footer__main">
			<div class="homepage-shell site-footer__grid">
				<div class="site-footer__brand">
					<?php if ( $footer_brand_image ) : ?>
						<div class="footer-brand__media">
							<?php echo $footer_brand_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php else : ?>
						<p class="site-footer__heading site-footer__heading--brand"><?php bloginfo( 'name' ); ?></p>
					<?php endif; ?>

					<?php if ( $footer_brand_description ) : ?>
						<p class="site-footer__description"><?php echo esc_html( $footer_brand_description ); ?></p>
					<?php endif; ?>
				</div>

				<div class="site-footer__nav">
					<p class="site-footer__heading"><?php esc_html_e( 'Navegacion', 'nycteria-store' ); ?></p>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_id'        => 'footer-menu',
							'menu_class'     => 'site-footer__menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
					?>
				</div>

				<div class="site-footer__connect">
					<p class="site-footer__heading"><?php esc_html_e( 'Conectar', 'nycteria-store' ); ?></p>

					<div class="site-footer__socials">
						<?php if ( $footer_instagram_url ) : ?>
							<a class="site-footer__social-link" href="<?php echo esc_url( $footer_instagram_url ); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php esc_attr_e( 'Instagram', 'nycteria-store' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
									<path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
								</svg>
							</a>
						<?php endif; ?>

						<?php if ( $footer_pinterest_url ) : ?>
							<a class="site-footer__social-link" href="<?php echo esc_url( $footer_pinterest_url ); ?>" target="_blank" rel="noreferrer noopener" aria-label="<?php esc_attr_e( 'Pinterest', 'nycteria-store' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
									<path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0"/>
								</svg>
							</a>
						<?php endif; ?>

						<a class="site-footer__social-link" href="#" target="_blank" rel="noreferrer noopener" aria-label="<?php esc_attr_e( 'Facebook', 'nycteria-store' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
							</svg>
						</a>

						<a class="site-footer__social-link" href="#" target="_blank" rel="noreferrer noopener" aria-label="<?php esc_attr_e( 'TikTok', 'nycteria-store' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3z"/>
							</svg>
						</a>
					</div>

					<?php if ( $footer_newsletter_code ) : ?>
						<div class="site-footer__newsletter">
							<?php echo do_shortcode( $footer_newsletter_code ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="site-footer__bottom">
			<div class="homepage-shell site-footer__bottom-inner">
				<p class="site-footer__copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $footer_copyright_text ); ?></p>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
