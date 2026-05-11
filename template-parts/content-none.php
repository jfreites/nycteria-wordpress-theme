<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nycteria_Store
 */

?>

<section class="no-results not-found">
	<div class="homepage-shell">
		<?php if ( is_search() ) : 
			$random_content = nycteria_store_get_random_no_results_content();
		?>
			<div class="no-results-random">
				<div class="no-results-random__media">
					<img src="<?php echo esc_url( $random_content['image'] ); ?>" alt="<?php esc_attr_e( 'No results found', 'nycteria-store' ); ?>" class="no-results-random__image">
				</div>
				<div class="no-results-random__content">
					<header class="homepage-section__header">
						<p class="homepage-kicker"><?php esc_html_e( 'Sin rastro', 'nycteria-store' ); ?></p>
						<h1 class="homepage-section__title"><?php echo esc_html( $random_content['message'] ); ?></h1>
					</header>

					<div class="page-content">
						<p><?php esc_html_e( 'Quizás otra búsqueda pueda revelar lo que el abismo oculta.', 'nycteria-store' ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'nycteria-store' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<?php
				if ( is_home() && current_user_can( 'publish_posts' ) ) :

					printf(
						'<p>' . wp_kses(
							/* translators: 1: link to WP admin new post page. */
							__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'nycteria-store' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						) . '</p>',
						esc_url( admin_url( 'post-new.php' ) )
					);

				else :
					?>

					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'nycteria-store' ); ?></p>
					<?php
					get_search_form();

				endif;
				?>
			</div><!-- .page-content -->
		<?php endif; ?>
	</div>
</section><!-- .no-results -->
