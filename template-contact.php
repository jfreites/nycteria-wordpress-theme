<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * @package Nycteria_Store
 */

get_header();

$hero_title = get_theme_mod('nycteria_contact_hero_title', __('Contacto', 'nycteria-store'));
$hero_subtitle = get_theme_mod('nycteria_contact_hero_subtitle', __('Escrbenos y te responderemos con la misma atencion con la que seleccionamos cada pieza.', 'nycteria-store'));
$form_shortcode = trim((string) get_theme_mod('nycteria_contact_form_shortcode', ''));
$opening_hours = trim((string) get_theme_mod('nycteria_contact_hours', ''));
$map_embed_url = esc_url(get_theme_mod('nycteria_contact_map_embed_url', ''));
$contact_phone = trim((string) get_theme_mod('nycteria_contact_phone', ''));
$contact_email = trim((string) get_theme_mod('nycteria_contact_email', ''));
//$contact_email   = get_option( 'woocommerce_email_from_address', get_option( 'admin_email' ) );
$store_address_1 = get_option('woocommerce_store_address', '');
$store_address_2 = get_option('woocommerce_store_address_2', '');
$store_city = get_option('woocommerce_store_city', '');
$store_postcode = get_option('woocommerce_store_postcode', '');
$store_phone = get_option('woocommerce_store_phone', '');
$store_location = get_option('woocommerce_default_country', '');

$location_parts = function_exists('wc_format_country_state_string') ? wc_format_country_state_string($store_location) : array();
$store_country = isset($location_parts['country']) ? $location_parts['country'] : '';
$store_state = isset($location_parts['state']) ? $location_parts['state'] : '';

$address_lines = array_filter(
	array(
		$store_address_1,
		$store_address_2,
		trim(implode(', ', array_filter(array($store_city, $store_state, $store_postcode)))),
		$store_country,
	)
);

$display_phone = $store_phone ? $store_phone : $contact_phone;
?>

<main id="primary" class="site-main contact-page-main">
	<section class="contact-hero" style="position: relative; min-height: 360px; height: 50vh; overflow: hidden;">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/nycteria-store-front.jpg" alt="Ubicación fisica de la tienda de Nycteria" width="1920" height="800" style="position: absolute; top: 0; left: 0; right: 0; object-fit: cover; width: 100%;">
		<div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-background/30" style="position: absolute; top:0; left:0; right:0; bottom:0; background-image: linear-gradient(to top, hsl(0 0% 4%), hsl(0 0% 4% / .6), hsl(0 0% 4% / .3));"></div>
		<div class="page-shell contact-hero__inner" style="position: relative;">
			<p class="homepage-kicker"><?php esc_html_e('Nycteria Store', 'nycteria-store'); ?></p>
			<h1 class="contact-hero__title"><?php echo esc_html($hero_title); ?></h1>
			<?php if ($hero_subtitle): ?>
				<p class="contact-hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php while (have_posts()): ?>
		<?php the_post(); ?>

		<section class="contact-section">
			<div class="page-shell">
				<?php if (get_the_content()): ?>
					<div class="contact-editor-content entry-content">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<div class="contact-grid">
					<div class="contact-form-panel">
						<div class="contact-decoration-border"></div>
						<h2 class="contact-info-card__title">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
								style="color: hsl(280 50% 60%);" viewBox="0 0 16 16">
								<path
									d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
							</svg>
							<?php esc_html_e('Envíanos un mensaje', 'nycteria-store'); ?>
						</h2>
						<?php if ($form_shortcode): ?>
							<?php echo do_shortcode($form_shortcode); ?>
						<?php else: ?>
							<p class="contact-empty-state">
								<?php esc_html_e('Agrega un shortcode de formulario en el Customizer para mostrar el formulario de contacto.', 'nycteria-store'); ?>
							</p>
						<?php endif; ?>
					</div>

					<div class="contact-info-stack">
						<section class="contact-info-card">
							<div class="contact-decoration-border"></div>
							<h2 class="contact-info-card__title">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
									style="color: hsl(280 50% 60%);" viewBox="0 0 16 16">
									<path
										d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
									<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
								</svg>
								<?php esc_html_e('Horarios de atencion', 'nycteria-store'); ?>
							</h2>
							<?php if ($opening_hours): ?>
								<div class="contact-info-card__content"><?php echo wp_kses_post(wpautop($opening_hours)); ?>
								</div>
							<?php endif; ?>
						</section>

						<section class="contact-info-card">
							<div class="contact-decoration-border"></div>
							<h2 class="contact-info-card__title">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
									style="color: hsl(280 50% 60%);" viewBox="0 0 16 16">
									<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
									<path
										d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
								</svg>
								<?php esc_html_e('Informacion de Contacto', 'nycteria-store'); ?>
							</h2>
							<div class="contact-info-card__content">
								<?php if ($address_lines): ?>
									<p><?php echo esc_html(implode(', ', $address_lines)); ?></p>
								<?php endif; ?>
								<?php if ($display_phone): ?>
									<p><a
											href="<?php echo esc_url('tel:' . preg_replace('/[^0-9+]/', '', $display_phone)); ?>"><?php echo esc_html($display_phone); ?></a>
									</p>
								<?php endif; ?>
								<?php if ($contact_email): ?>
									<p><a
											href="<?php echo esc_url('mailto:' . antispambot($contact_email)); ?>"><?php echo esc_html(antispambot($contact_email)); ?></a>
									</p>
								<?php endif; ?>
							</div>
						</section>
					</div>
				</div>
			</div>
		</section>
	<?php endwhile; ?>

	<?php if ($map_embed_url): ?>
		<section class="contact-map-section" aria-labelledby="contact-map-title">
			<div class="page-shell contact-map-section__header">
				<h2 id="contact-map-title" class="contact-map-section__title">

					<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="16" height="16"
						fill="currentColor" viewBox="0 0 16 16" style="color: hsl(280 50% 60%);">
						<path
							d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
						<path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
					</svg>
					<?php esc_html_e('Nuestra ubicacion', 'nycteria-store'); ?>
				</h2>
			</div>
			<div class="contact-map-embed">
				<iframe title="Ubicación de Nycteria" src="<?php echo esc_url($map_embed_url); ?>" width="600" height="450"
					style="border:0; filter: invert(90%) hue-rotate(180deg) contrast(0.9);" allowfullscreen=""
					loading="lazy" referrerpolicy="no-referrer-when-downgrade"
					title="<?php esc_attr_e('Nuestra ubicacion', 'nycteria-store'); ?>"></iframe>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
