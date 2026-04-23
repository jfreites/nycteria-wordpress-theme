<?php
/**
 * Template part for displaying products within loops.
 *
 * @package Nycteria_Store
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$is_new = false;

if ( get_the_date( 'U' ) ) {
	$is_new = ( time() - get_the_date( 'U' ) ) < ( 30 * DAY_IN_SECONDS );
}
?>

<li <?php wc_product_class( 'shop-product-card', $product ); ?>>
	<a class="shop-product-card__link" href="<?php the_permalink(); ?>">
		<figure class="shop-product-card__media">
			<?php if ( $is_new ) : ?>
				<span class="shop-product-card__badge"><?php esc_html_e( 'Nuevo', 'nycteria-store' ); ?></span>
			<?php endif; ?>

			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
			} else {
				echo wc_placeholder_img( 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</figure>

		<div class="shop-product-card__body">
			<h2 class="shop-product-card__title"><?php the_title(); ?></h2>
			<?php if ( $product->get_price_html() ) : ?>
				<span class="shop-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<?php endif; ?>
		</div>
	</a>
</li>
