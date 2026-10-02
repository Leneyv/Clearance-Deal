<?php
/**
 * Template Name: Wigs Landing Page
 * Template for /wigs/ — hair category products only, no sold-out items.
 *
 * @package Clearance_Deal
 */

get_header();
?>

<!-- Wigs page hero -->
<section class="ch-hero ch-hero--wigs">
	<div class="ch-hero-inner">
		<div class="ch-hero-badge ch-hero-badge--kenya">
			<span class="ch-green-dot"></span>Delivered Kenya-wide
		</div>
		<h1 class="ch-hero-h1 ch-hero-h1--single">Human Hair &amp; Wigs<br><span class="ch-scroll-orange">Shop the Collection</span></h1>
		<p class="ch-hero-sub">Genuine human hair, wigs and extensions at clearance prices. Pay via M-Pesa.</p>

		<!-- Trust badges -->
		<div class="wigs-trust-bar">
			<span><i class="fa-solid fa-shield-halved"></i> Genuine products</span>
			<span><i class="fa-solid fa-rotate-left"></i> 7-day returns</span>
			<span><i class="fa-solid fa-truck-fast"></i> Kenya-wide delivery</span>
			<span>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mpesa-logo.svg' ); ?>" alt="M-Pesa" class="wigs-mpesa-logo">
				M-Pesa accepted
			</span>
		</div>
	</div>
</section>

<!-- Contact bar -->
<div class="wigs-contact-bar">
	<a href="https://wa.me/254751338975" target="_blank" rel="noopener noreferrer">
		<i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp: +254 751 338 975
	</a>
</div>

<!-- Products grid -->
<main class="wigs-main">
	<?php
	$hair_query = new WP_Query( [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'tax_query'      => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			[
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => 'hair',
			],
		],
		'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			[
				'key'   => '_stock_status',
				'value' => 'instock',
			],
		],
	] );

	if ( $hair_query->have_posts() ) :
	?>
	<div class="featured-band-grid wigs-grid">
		<?php
		while ( $hair_query->have_posts() ) :
			$hair_query->the_post();
			$product = wc_get_product( get_the_ID() );
			if ( ! $product ) { continue; }

			$discount_pct = 0;
			if ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
				$discount_pct = clearance_deal_calculate_discount( $product->get_regular_price(), $product->get_sale_price() );
			}

			$data_type_attr = '';
			$data_vars_attr = '';
			if ( $product->is_type( 'variable' ) ) {
				$data_type_attr = 'data-product-type="variable"';
				$vars_data = [];
				foreach ( $product->get_available_variations() as $var_data ) {
					$variation = wc_get_product( $var_data['variation_id'] );
					if ( ! $variation || ! $variation->is_purchasable() ) { continue; }
					$label_parts = [];
					foreach ( $variation->get_variation_attributes() as $attr_name => $attr_value ) {
						$label         = wc_attribute_label( str_replace( 'attribute_', '', $attr_name ) );
						$label_parts[] = $label . ': ' . ucfirst( $attr_value );
					}
					$v_price     = (float) ( $variation->get_sale_price() ?: $variation->get_price() );
					$vars_data[] = [
						'id'    => (int) $var_data['variation_id'],
						'label' => implode( ' / ', $label_parts ) ?: ( 'Option ' . ( count( $vars_data ) + 1 ) ),
						'price' => $v_price,
					];
				}
				if ( ! empty( $vars_data ) ) {
					$data_vars_attr = 'data-variations="' . esc_attr( wp_json_encode( $vars_data ) ) . '"';
				}
				$buy_now_url = get_permalink( $product->get_id() );
			} else {
				$data_type_attr = 'data-product-type="simple"';
				$buy_now_url    = add_query_arg( 'add-to-cart', $product->get_id(), home_url( '/' ) );
			}
		?>
		<div class="deal-card">
			<div class="deal-media-wrapper" style="position:relative;overflow:hidden;">
				<?php clearance_deal_render_featured_media( $product ); ?>
				<?php if ( $discount_pct > 0 ) : ?>
					<div class="badge-discount"><?php echo esc_html( $discount_pct ); ?>% OFF</div>
				<?php endif; ?>
			</div>

			<span class="bnpl-badge">Buy Now Pay Later</span>

			<div class="deal-details">
				<h2 class="deal-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

				<?php if ( $product->get_short_description() ) : ?>
					<p class="deal-short-description"><?php echo wp_kses_post( $product->get_short_description() ); ?></p>
				<?php endif; ?>

				<div class="deal-pricing">
					<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $discount_pct > 0 ) : ?>
						<span class="deal-savings"><?php echo esc_html( sprintf( __( 'Save %s%%', 'clearance-deal' ), $discount_pct ) ); ?></span>
					<?php endif; ?>
				</div>

				<a href="<?php echo esc_url( $buy_now_url ); ?>" class="btn-get-deal" <?php echo $data_type_attr; // phpcs:ignore ?> <?php echo $data_vars_attr; // phpcs:ignore ?>>
					<?php esc_html_e( 'Buy Now', 'clearance-deal' ); ?>
				</a>
			</div>
		</div>
		<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php else : ?>
		<p class="wigs-empty"><?php esc_html_e( 'New stock arriving soon — check back shortly or chat with us on WhatsApp.', 'clearance-deal' ); ?></p>
	<?php endif; ?>
</main>

<!-- Bottom contact bar -->
<div class="wigs-contact-bar wigs-contact-bar--bottom">
	<a href="https://wa.me/254751338975" target="_blank" rel="noopener noreferrer">
		<i class="fa-brands fa-whatsapp"></i> Questions? WhatsApp us: +254 751 338 975
	</a>
</div>

<?php get_footer(); ?>
