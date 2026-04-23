<?php
/**
 * The front page template file.
 *
 * @package Nycteria_Store
 */

get_header();

$hero_image_id  = absint( get_theme_mod( 'nycteria_hero_background_image' ) );
$hero_video_url = esc_url( get_theme_mod( 'nycteria_hero_background_video', '' ) );
$hero_title     = get_theme_mod( 'nycteria_hero_title', __( 'New Winter Arrivals', 'nycteria-store' ) );
$hero_subtitle  = get_theme_mod( 'nycteria_hero_subtitle', __( 'Tailored silhouettes, sharp textures, and a darker point of view.', 'nycteria-store' ) );
$hero_cta_label = get_theme_mod( 'nycteria_hero_cta_label', __( 'Shop Collection', 'nycteria-store' ) );
$hero_cta_url   = esc_url( get_theme_mod( 'nycteria_hero_cta_url', home_url( '/shop/' ) ) );

$marketing_image_id = absint( get_theme_mod( 'nycteria_marketing_image' ) );
$marketing_kicker   = get_theme_mod( 'nycteria_marketing_kicker', __( 'Editorial Focus', 'nycteria-store' ) );
$marketing_title    = get_theme_mod( 'nycteria_marketing_title', __( 'Designed to move between statement and restraint.', 'nycteria-store' ) );
$marketing_copy     = get_theme_mod( 'nycteria_marketing_copy', __( 'Crafted essentials, refined finishes, and a monochrome palette built for modern wardrobes.', 'nycteria-store' ) );
$shop_page_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

$featured_grid_title     = get_theme_mod( 'nycteria_featured_grid_title', __( 'Featured Artifacts', 'nycteria-store' ) );
$featured_grid_link_text = get_theme_mod( 'nycteria_featured_grid_shop_link_label', __( 'View All Products', 'nycteria-store' ) );

$featured_product_ids = array_filter(
	array(
		absint( get_theme_mod( 'nycteria_featured_product_primary', 0 ) ),
		absint( get_theme_mod( 'nycteria_featured_product_secondary', 0 ) ),
		absint( get_theme_mod( 'nycteria_featured_product_tertiary', 0 ) ),
	)
);

$featured_category_id          = absint( get_theme_mod( 'nycteria_featured_category_term', 0 ) );
$featured_category_image_id    = absint( get_theme_mod( 'nycteria_featured_category_image', 0 ) );
$featured_category_headline    = get_theme_mod( 'nycteria_featured_category_headline', __( 'Category Spotlight', 'nycteria-store' ) );
$featured_category_description = get_theme_mod( 'nycteria_featured_category_description', __( 'Highlight a category with supporting copy and a dedicated call to action.', 'nycteria-store' ) );
$featured_category_button_text = get_theme_mod( 'nycteria_featured_category_button_label', __( 'Explore Category', 'nycteria-store' ) );

$featured_products       = array();
$featured_product_lookup = array();

if ( $featured_product_ids && class_exists( 'WooCommerce' ) && function_exists( 'wc_get_products' ) ) {
	$fetched_featured_products = wc_get_products(
		array(
			'status'             => 'publish',
			'catalog_visibility' => 'visible',
			'include'            => $featured_product_ids,
			'limit'              => count( $featured_product_ids ),
			'orderby'            => 'post__in',
			'return'             => 'objects',
		)
	);

	foreach ( $fetched_featured_products as $featured_product ) {
		$featured_product_lookup[ $featured_product->get_id() ] = $featured_product;
	}

	foreach ( $featured_product_ids as $featured_product_id ) {
		if ( isset( $featured_product_lookup[ $featured_product_id ] ) ) {
			$featured_products[] = $featured_product_lookup[ $featured_product_id ];
		}
	}
}

$featured_category     = $featured_category_id ? get_term( $featured_category_id, 'product_cat' ) : null;
$featured_category_url = $featured_category && ! is_wp_error( $featured_category ) ? get_term_link( $featured_category ) : '';
$featured_category_url = ! is_wp_error( $featured_category_url ) ? $featured_category_url : '';

$has_featured_products = ! empty( $featured_products );
$has_featured_category = $featured_category && ! is_wp_error( $featured_category ) && $featured_category_url;
$show_featured_grid    = $has_featured_products || $has_featured_category;

$hero_image_url = '';
$hero_poster    = '';

if ( $hero_image_id ) {
	$hero_image_url = wp_get_attachment_image_url( $hero_image_id, 'full' );
	$hero_poster    = $hero_image_url;
}

$hero_style = '';

if ( $hero_image_url ) {
	$hero_style = sprintf(
		'background-image: linear-gradient(180deg, rgba(10, 10, 10, 0.16), rgba(10, 10, 10, 0.78)), url(%s);',
		esc_url( $hero_image_url )
	);
}

$latest_products = array();

if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_products' ) ) {
	$latest_products = wc_get_products(
		array(
			'status'             => 'publish',
			'catalog_visibility' => 'visible',
			'limit'              => 3,
			'orderby'            => 'date',
			'order'              => 'DESC',
			'return'             => 'objects',
		)
	);
}
?>

<main id="primary" class="site-main homepage-main">
	<section class="homepage-hero<?php echo $hero_video_url ? ' homepage-hero--has-video' : ''; ?>"<?php echo $hero_style ? ' style="' . esc_attr( $hero_style ) . '"' : ''; ?>>
		<?php if ( $hero_video_url ) : ?>
			<video class="homepage-hero__media" autoplay muted loop playsinline preload="metadata" <?php echo $hero_poster ? 'poster="' . esc_url( $hero_poster ) . '"' : ''; ?>>
				<source src="<?php echo esc_url( $hero_video_url ); ?>">
			</video>
		<?php endif; ?>

		<div class="homepage-shell homepage-hero__inner">
			<div class="homepage-hero__content homepage-hero__content-alignment-center">
				<p class="homepage-kicker"><?php esc_html_e( 'Nycteria Gothic Boutique', 'nycteria-store' ); ?></p>
				<h1 class="homepage-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
				<p class="homepage-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
				<?php if ( $hero_cta_label && $hero_cta_url ) : ?>
					<a class="homepage-button" href="<?php echo esc_url( $hero_cta_url ); ?>"><?php echo esc_html( $hero_cta_label ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php /*
	<section class="homepage-section homepage-products" aria-labelledby="latest-products-title">
		<div class="homepage-shell">
			<div class="homepage-section__header">
				<p class="homepage-kicker"><?php esc_html_e( 'Latest Products', 'nycteria-store' ); ?></p>
				<h2 id="latest-products-title" class="homepage-section__title"><?php esc_html_e( 'Recent arrivals from the shop.', 'nycteria-store' ); ?></h2>
			</div>

			<?php if ( $latest_products ) : ?>
				<div class="homepage-products__grid">
					<?php foreach ( $latest_products as $product ) : ?>
						<?php
						$product_id    = $product->get_id();
						$product_image = $product->get_image_id()
							? wp_get_attachment_image( $product->get_image_id(), 'large', false, array( 'loading' => 'lazy' ) )
							: wc_placeholder_img( 'woocommerce_thumbnail' );
						?>
						<article class="homepage-product-card">
							<a class="homepage-product-card__link" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<figure class="homepage-product-card__media">
									<?php echo wp_kses_post( $product_image ); ?>
								</figure>
								<div class="homepage-product-card__body">
									<h3 class="homepage-product-card__title"><?php echo esc_html( $product->get_name() ); ?></h3>
									<span class="homepage-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
								</div>
							</a>
						</article>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="homepage-empty-state"><?php esc_html_e( 'Latest products will appear here once WooCommerce products are published.', 'nycteria-store' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	*/ ?>

	<?php if ( $show_featured_grid ) : ?>
		<section class="homepage-section homepage-featured-grid" aria-labelledby="homepage-featured-grid-title">
			<div class="homepage-shell" style="max-width: 76rem;">
				<div class="homepage-section__header homepage-section__header--split">
					<h2 id="homepage-featured-grid-title" class="homepage-section__title"><?php echo esc_html( $featured_grid_title ); ?></h2>
					<?php if ( $featured_grid_link_text && $shop_page_url ) : ?>
						<a class="homepage-featured-grid__shop-link" href="<?php echo esc_url( $shop_page_url ); ?>"><?php echo esc_html( $featured_grid_link_text ); ?></a>
					<?php endif; ?>
				</div>

				<div class="homepage-featured-grid__layout">
					<?php foreach ( $featured_products as $index => $product ) : ?>
						<?php
						$product_id    = $product->get_id();
						$product_image = $product->get_image_id()
							? wp_get_attachment_image( $product->get_image_id(), 'large', false, array( 'loading' => 'lazy' ) )
							: wc_placeholder_img( 'woocommerce_thumbnail' );
						$card_classes  = 'homepage-featured-product-card';

						if ( 0 === $index ) {
							$card_classes .= ' homepage-featured-product-card--hero';
						}
						?>
						<article class="<?php echo esc_attr( $card_classes ); ?>">
							<a class="homepage-featured-product-card__link" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
								<figure class="homepage-featured-product-card__media">
									<?php echo wp_kses_post( $product_image ); ?>
								</figure>
								<div class="homepage-featured-product-card__body">
									<h3 class="homepage-featured-product-card__title"><?php echo esc_html( $product->get_name() ); ?></h3>
									<?php if ( $product->get_price_html() ) : ?>
										<span class="homepage-featured-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
									<?php endif; ?>
								</div>
							</a>
						</article>
					<?php endforeach; ?>

					<?php if ( $has_featured_category ) : ?>
						<article class="homepage-featured-category-card">
							<a class="homepage-featured-category-card__link" href="<?php echo esc_url( $featured_category_url ); ?>">
								<div class="homepage-featured-category-card__content">
									<p class="homepage-kicker"><?php echo esc_html( $featured_category->name ); ?></p>
									<h3 class="homepage-featured-category-card__title"><?php echo esc_html( $featured_category_headline ); ?></h3>
									<?php if ( $featured_category_description ) : ?>
										<p class="homepage-featured-category-card__description"><?php echo esc_html( $featured_category_description ); ?></p>
									<?php endif; ?>
									<?php if ( $featured_category_button_text ) : ?>
										<span class="homepage-button homepage-featured-category-card__button"><?php echo esc_html( $featured_category_button_text ); ?></span>
									<?php endif; ?>
								</div>
								<figure class="homepage-featured-category-card__media">
									<?php
									if ( $featured_category_image_id ) {
										echo wp_get_attachment_image( $featured_category_image_id, 'large', false, array( 'loading' => 'lazy' ) );
									} else {
										echo '<div class="homepage-featured-category-card__media-placeholder"></div>';
									}
									?>
								</figure>
							</a>
						</article>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="homepage-section homepage-marketing" aria-labelledby="marketing-title">
		<div class="homepage-shell homepage-marketing__grid">
			<?php if ( $marketing_image_id ) : ?>
				<div class="homepage-marketing__media">
					<?php echo wp_get_attachment_image( $marketing_image_id, 'large', false, array( 'loading' => 'lazy' ) ); ?>
				</div>
			<?php endif; ?>
			<div class="homepage-marketing__content">
				<p class="homepage-kicker"><?php echo esc_html( $marketing_kicker ); ?></p>
				<h2 id="marketing-title" class="homepage-section__title"><?php echo esc_html( $marketing_title ); ?></h2>
				<p class="homepage-marketing__copy"><?php echo esc_html( $marketing_copy ); ?></p>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
