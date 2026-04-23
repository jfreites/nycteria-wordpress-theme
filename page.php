<?php
/**
 * The template for displaying all pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nycteria_Store
 */

get_header();
?>

<main id="primary" class="site-main page-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-layout' ); ?>>
			<div class="page-shell">
				<?php if ( function_exists( 'yoast_breadcrumb' ) ) : ?>
					<nav class="page-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'nycteria-store' ); ?>">
						<?php yoast_breadcrumb( '<p>', '</p>' ); ?>
					</nav>
				<?php elseif ( function_exists( 'rank_math_the_breadcrumbs' ) ) : ?>
					<nav class="page-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'nycteria-store' ); ?>">
						<?php rank_math_the_breadcrumbs(); ?>
					</nav>
				<?php else : ?>
					<nav class="page-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'nycteria-store' ); ?>">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'nycteria-store' ); ?></a>
						<span aria-hidden="true">/</span>
						<span><?php the_title(); ?></span>
					</nav>
				<?php endif; ?>

				<header class="page-header">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="page-featured-media">
						<?php the_post_thumbnail( 'full', array( 'loading' => 'eager' ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="page-content entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'nycteria-store' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<?php if ( get_edit_post_link() ) : ?>
					<footer class="page-footer">
						<?php
						edit_post_link(
							sprintf(
								wp_kses(
									__( 'Edit <span class="screen-reader-text">%s</span>', 'nycteria-store' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								wp_kses_post( get_the_title() )
							),
							'<span class="edit-link">',
							'</span>'
						);
						?>
					</footer>
				<?php endif; ?>
			</div>
		</article>

		<?php
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
		?>
	<?php endwhile; ?>
</main>

<?php
get_footer();
