<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * @package Nycteria_Store
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$current_term = is_tax( 'product_cat' ) ? get_queried_object() : null;
$categories   = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
	)
);
$archive_description = wc_format_content( term_description() );

if ( ! $archive_description && is_shop() ) {
	$archive_description = wc_format_content( get_post_field( 'post_content', wc_get_page_id( 'shop' ) ) );
}
?>

<main id="primary" class="site-main shop-archive">
	<header class="shop-archive__hero">
		<div class="homepage-shell shop-archive__hero-inner">
			<p class="homepage-kicker"><?php esc_html_e( 'Nycteria Store', 'nycteria-store' ); ?></p>
			<h1 class="shop-archive__title"><?php woocommerce_page_title(); ?></h1>
			<?php if ( $archive_description ) : ?>
				<div class="shop-archive__description"><?php echo wp_kses_post( $archive_description ); ?></div>
			<?php endif; ?>
		</div>
	</header>

	<?php if ( woocommerce_product_loop() ) : ?>
		<section class="shop-archive__filters">
			<div class="homepage-shell shop-archive__filters-inner">
				<div class="shop-archive__categories" aria-label="<?php esc_attr_e( 'Product categories', 'nycteria-store' ); ?>">
					<a class="shop-archive__category-link<?php echo ! $current_term ? ' is-active' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<?php esc_html_e( 'Todos', 'nycteria-store' ); ?>
					</a>
					<?php if ( ! is_wp_error( $categories ) ) : ?>
						<?php foreach ( $categories as $category ) : ?>
							<?php
							$is_active = $current_term && (int) $current_term->term_id === (int) $category->term_id;
							?>
							<a class="shop-archive__category-link<?php echo $is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $category ) ); ?>">
								<?php echo esc_html( $category->name ); ?>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<div class="shop-archive__ordering">
					<?php woocommerce_catalog_ordering(); ?>
				</div>
			</div>
		</section>

		<section class="shop-archive__products">
			<div class="homepage-shell">
				<?php woocommerce_product_loop_start(); ?>

				<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php woocommerce_product_loop_end(); ?>

				<div class="shop-archive__pagination">
					<?php woocommerce_pagination(); ?>
				</div>
			</div>
		</section>
	<?php else : ?>
		<section class="shop-archive__products">
			<div class="homepage-shell">
				<?php do_action( 'woocommerce_no_products_found' ); ?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer( 'shop' );
