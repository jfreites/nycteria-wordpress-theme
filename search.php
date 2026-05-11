<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Nycteria_Store
 */

get_header();
?>

	<main id="primary" class="site-main search-results-page">
		<div class="homepage-shell">

			<?php if ( have_posts() ) : ?>

				<header class="homepage-section__header">
					<p class="homepage-kicker"><?php esc_html_e( 'Resultados de búsqueda', 'nycteria-store' ); ?></p>
					<h1 class="homepage-section__title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Explorando: %s', 'nycteria-store' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header><!-- .homepage-section__header -->

				<div class="shop-archive__grid">
					<ul class="products columns-4">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );

						endwhile;
						?>
					</ul>
				</div>

				<?php
				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div><!-- .homepage-shell -->
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
