<?php
/**
 * The Template for displaying all single products.
 *
 * @package Nycteria_Store
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<main id="primary" class="site-main shop-single">
	<div class="homepage-shell shop-single__shell">
		<?php if ( function_exists( 'nycteria_store_product_breadcrumb' ) ) : ?>
			<?php nycteria_store_product_breadcrumb(); ?>
		<?php endif; ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<?php wc_get_template_part( 'content', 'single-product' ); ?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer( 'shop' );
