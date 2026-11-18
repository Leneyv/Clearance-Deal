<?php
/**
 * The template for displaying the front page
 *
 * Rebuilt for high-trust conversions: dedicated Hair and Makeup categories,
 * zero appliances/refrigerators, inspection-on-delivery assurance, and BNPL.
 *
 * @package Clearance_Deal
 */

get_header();
?>

<!-- Announcement Bar -->
<div class="top-announcement-bar">
	<div class="top-announcement-track">
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Paid Doorstep Delivery Across Kenya', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Direct Warehouse Dispatch Across Kenya', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-rotate-left"></i> <?php esc_html_e( 'Easy Return & Exchange in 7 Days', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-shield-halved"></i> <?php esc_html_e( 'Lipa na M-Pesa Verified Merchant', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Paid Doorstep Delivery Across Kenya', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Direct Warehouse Dispatch Across Kenya', 'clearance-deal' ); ?></span>
	</div>
</div>

<!-- Hero Section with Background Video & Trust Signals -->
<section class="ch-hero">
	<!-- Background Video (Desktop & Mobile) -->
	<div class="ch-hero-video-bg" aria-hidden="true">
		<video
			class="ch-hero-video ch-hero-video--desktop"
			loop
			muted
			playsinline
			preload="none"
			poster="<?php echo esc_url( content_url( '/uploads/videos/hero-banner-poster.jpg' ) ); ?>">
			<source data-src="<?php echo esc_url( content_url( '/uploads/videos/hero-banner-desktop.mp4' ) ); ?>" type="video/mp4">
		</video>
		<video
			class="ch-hero-video ch-hero-video--mobile"
			loop
			muted
			playsinline
			preload="none"
			poster="<?php echo esc_url( content_url( '/uploads/videos/hero-banner-poster.jpg' ) ); ?>">
			<source data-src="<?php echo esc_url( content_url( '/uploads/videos/hero-banner-mobile.mp4' ) ); ?>" type="video/mp4">
		</video>
		<div class="ch-hero-video-overlay"></div>
<script>
(()=>{
const videos=[...document.querySelectorAll('.ch-hero-video')];
const mobile=matchMedia('(max-width: 768px)');
const reduced=matchMedia('(prefers-reduced-motion: reduce)');
let visible=true,ready=false;
function choose(){
 const net=navigator.connection;
 const skip=mobile.matches||reduced.matches||(net&&(net.saveData||/^(slow-)?2g$/.test(net.effectiveType)));
 for(const v of videos){
  if(!ready||skip||!visible||document.hidden||!v.classList.contains('ch-hero-video--desktop')){v.pause();continue;}
  const source=v.querySelector('source');
  if(!source.hasAttribute('src')){source.src=source.dataset.src;v.load();}
  v.play().catch(()=>{});
 }
}
function start(){const done=()=>{ready=true;choose();};if('requestIdleCallback' in window)requestIdleCallback(done,{timeout:2500});else setTimeout(done,300);}
if(document.readyState==='complete')start();else window.addEventListener('load',start,{once:true});
mobile.addEventListener('change',choose);reduced.addEventListener('change',choose);document.addEventListener('visibilitychange',choose);
if('IntersectionObserver' in window)new IntersectionObserver(entries=>{visible=entries[0].isIntersecting;choose();}).observe(document.querySelector('.ch-hero'));
})();
</script>
	</div>

	<div class="ch-hero-inner">


		<div class="ch-hero-text-wrap" aria-live="polite">
			<div class="ch-hero-state ch-hero-state--active">
				<h1 class="ch-hero-h1">100% Genuine Human Hair.<br><span class="ch-scroll-orange">Clearance Prices.</span></h1>
			</div>
			<div class="ch-hero-state" aria-hidden="true">
				<h1 class="ch-hero-h1">Luxury Makeup &amp; Beauty.<br><span class="ch-scroll-coral">Direct Batch Imports.</span></h1>
			</div>
		</div>

		<p class="ch-hero-sub">
			Single-donor raw Indian temple hair, glueless HD lace front wigs, and authentic beauty clearance in Kenya.
			Shipped factory-sealed directly from our Nairobi warehouse. Pay via M-Pesa or Lipa Polepole (50% payment).
		</p>

		<div class="hero-actions-container" style="display:flex; justify-content:center; gap:12px; margin-top:22px; flex-wrap:wrap;">
			<a href="#deals-section" class="cpl-btn cpl-btn-primary" style="background:#3A0F34; color:#fff; border:1.5px solid #EAAA00; padding:12px 24px; border-radius:999px; font-weight:700;" onclick="filterCategoryTab('hair')">
				<i class="fa-solid fa-scissors" style="color:#EAAA00;"></i> Shop Hair Deals
			</a>
			<a href="#deals-section" class="cpl-btn" style="background:#EAAA00; color:#3A0F34; padding:12px 24px; border-radius:999px; font-weight:700;" onclick="filterCategoryTab('makeup')">
				<i class="fa-solid fa-wand-magic-sparkles"></i> Shop Makeup Deals
			</a>
			<a href="https://wa.me/254751338975?text=Hi%2C%20can%20you%20help%20me%20choose%20a%20wig%20or%20makeup%20shade%3F" target="_blank" rel="noopener" class="cpl-btn" style="background:#16a34a; color:#fff; padding:12px 24px; border-radius:999px; font-weight:700;">
				<i class="fa-brands fa-whatsapp"></i> Chat with Hair Stylist
			</a>
		</div>

		<div class="hero-trust-bar" style="display:flex; justify-content:center; gap:24px; margin-top:24px; flex-wrap:wrap; font-size:13px; color:var(--text-muted); font-weight:600;">
			<span><i class="fa-solid fa-star" style="color:#EAAA00;"></i> 4.9/5 from 2,400+ Kenyan Clients</span>
			<span><i class="fa-solid fa-circle-check" style="color:#16a34a;"></i> Pure Human Hair Burn-Tested</span>
			<span><i class="fa-solid fa-tags" style="color:#EAAA00;"></i> Best Hair Price in Kenya</span>
			<span><i class="fa-solid fa-hand-holding-dollar" style="color:#16a34a;"></i> Pay 50% Payment Now</span>
		</div>
	</div>
</section>

<script>
(function () {
	var states  = document.querySelectorAll('.ch-hero-state');
	if (!states.length) return;
	var current = 0;
	var DUR     = 600;
	setInterval(function () {
		var outgoing = current;
		current = (current + 1) % states.length;
		states[outgoing].classList.add('ch-hero-state--leaving');
		states[outgoing].classList.remove('ch-hero-state--active');
		states[outgoing].setAttribute('aria-hidden', 'true');
		states[current].classList.add('ch-hero-state--active');
		states[current].removeAttribute('aria-hidden');
		setTimeout(function () {
			states[outgoing].style.transition = 'none';
			states[outgoing].classList.remove('ch-hero-state--leaving');
			requestAnimationFrame(function () {
				requestAnimationFrame(function () {
					states[outgoing].style.transition = '';
				});
			});
		}, DUR + 50);
	}, 4000);
}());
</script>

<!-- 4 Trust Pillars Strip -->
<section class="trust-pillars-section" style="background:#ffffff; border-bottom:1px solid #DDE3E8; padding:28px 20px;">
	<div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
		<div style="display:flex; align-items:flex-start; gap:14px; padding:10px;">
			<div style="width:44px; height:44px; border-radius:12px; background:#f4eef3; color:#3A0F34; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
				<i class="fa-solid fa-truck-fast"></i>
			</div>
			<div>
				<strong style="display:block; font-size:15px; color:#3A0F34; margin-bottom:2px;">Direct Warehouse Dispatch</strong>
				<p style="font-size:13px; color:#64748b; line-height:1.4; margin:0;">Shipped factory-sealed from our warehouse with secure door-to-door tracking across Kenya.</p>
			</div>
		</div>

		<div style="display:flex; align-items:flex-start; gap:14px; padding:10px;">
			<div style="width:44px; height:44px; border-radius:12px; background:#fef8e7; color:#EAAA00; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
				<i class="fa-solid fa-certificate"></i>
			</div>
			<div>
				<strong style="display:block; font-size:15px; color:#3A0F34; margin-bottom:2px;">100% Genuine Quality</strong>
				<p style="font-size:13px; color:#64748b; line-height:1.4; margin:0;">Pure single-donor human hair burn-tested. Batch-verified imported cosmetics.</p>
			</div>
		</div>


		<div style="display:flex; align-items:flex-start; gap:14px; padding:10px;">
			<div style="width:44px; height:44px; border-radius:12px; background:#f4eef3; color:#3A0F34; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
				<i class="fa-solid fa-hand-holding-dollar"></i>
			</div>
			<div>
				<strong style="display:block; font-size:15px; color:#3A0F34; margin-bottom:2px;">Lipa Polepole (BNPL)</strong>
				<p style="font-size:13px; color:#64748b; line-height:1.4; margin:0;">Pay 50% now and buy, then 12 easy monthly instalments.</p>
			</div>
		</div>
	</div>
</section>

<!-- Main Deals Catalog Section with Category Tabs -->
<div id="deals-section" style="padding-top:40px;">
	<div class="ch-deals-header" style="margin-bottom:20px;">
		<h2>Human Hair Wigs &amp; Bundles Price in Kenya: Today's Clearance Deals</h2>
		<p>Compare human hair price in Kenya: 100% single-donor raw Indian bundles and glueless HD lace wigs at verified wholesale markdowns across Nairobi.</p>
	</div>

	<!-- Interactive Category Switcher (Horizontal scroll on mobile and desktop) -->
	<div class="category-tabs-wrapper">
		<div class="category-tabs-pill" style="background:#DDE3E8; padding:6px; border-radius:999px;">
			<button type="button" class="tab-filter-btn is-active" data-cat="all" onclick="filterCategoryTab('all', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:#3A0F34; color:#ffffff; box-shadow:0 2px 6px rgba(58,15,52,0.25); transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-border-all"></i> All Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="price-2" onclick="filterCategoryTab('price-2', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-tags"></i> From KSh 11,000 to 15,000 Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="price-1" onclick="filterCategoryTab('price-1', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-tag"></i> From KSh 3,000 to 10,000 Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="price-3" onclick="filterCategoryTab('price-3', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-fire" style="color:#ea580c;"></i> From KSh 15,000 to 20,000 Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="premium" onclick="filterCategoryTab('premium', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-crown" style="color:#9333ea;"></i> Premium Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="hair" onclick="filterCategoryTab('hair', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-scissors" style="color:#0284c7;"></i> Shop by hair Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="makeup" onclick="filterCategoryTab('makeup', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-wand-magic-sparkles" style="color:#db2777;"></i> Shop by Makup Deals
			</button>
			<button type="button" class="tab-filter-btn" data-cat="wigs" onclick="filterCategoryTab('wigs', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-feather" style="color:#d97706;"></i> Wigs
			</button>
			<button type="button" class="tab-filter-btn" data-cat="bundles" onclick="filterCategoryTab('bundles', this)" style="padding:10px 18px; border-radius:999px; font-size:13px; font-weight:700; border:none; cursor:pointer; background:transparent; color:#475569; transition:all 0.2s; white-space:nowrap;">
				<i class="fa-solid fa-box-open" style="color:#059669;"></i> Bundles
			</button>
		</div>
	</div>

	<?php
	// Query all in-stock clearance products sorted by menu_order
	$product_visibility_terms = function_exists( 'wc_get_product_visibility_term_ids' ) ? wc_get_product_visibility_term_ids() : array();
	$tax_query = array();
	if ( ! empty( $product_visibility_terms['exclude-from-catalog'] ) ) {
		$tax_query[] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'term_taxonomy_id',
			'terms'    => array( $product_visibility_terms['exclude-from-catalog'] ),
			'operator' => 'NOT IN',
		);
	}

	$products_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post__not_in'   => array( 250 ), // Hair Consultation virtual product
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'tax_query'      => $tax_query,
			'meta_query'     => array(
				array(
					'key'   => '_stock_status',
					'value' => 'instock',
				),
			),
		)
	);

	if ( $products_query->have_posts() ) :
		// Group products strictly by price tier so that each tier header is rendered exactly once
		$tier_order_map = array(
			'price-2' => 1,
			'price-1' => 2,
			'price-3' => 3,
			'premium' => 4,
		);

		usort( $products_query->posts, function ( $a, $b ) use ( $tier_order_map ) {
			$get_tier_info = function ( $post_id ) {
				$prod = wc_get_product( $post_id );
				if ( ! $prod ) return array( 'tier' => 'price-1', 'order' => 0 );
				$cat_slugs = wp_get_post_terms( $post_id, 'product_cat', array( 'fields' => 'slugs' ) );
				if ( in_array( 'deals-premium', $cat_slugs, true ) ) {
					return array( 'tier' => 'premium', 'order' => (int) $prod->get_menu_order() );
				}
				$price = (float) ( $prod->get_sale_price() ?: ( $prod->get_price() ?: 0 ) );
				if ( $price <= 10000 ) {
					$tier = 'price-1';
				} elseif ( $price <= 15000 ) {
					$tier = 'price-2';
				} elseif ( $price <= 20000 ) {
					$tier = 'price-3';
				} else {
					$tier = 'premium';
				}
				return array( 'tier' => $tier, 'order' => (int) $prod->get_menu_order() );
			};

			$a_info = $get_tier_info( $a->ID );
			$b_info = $get_tier_info( $b->ID );

			$a_rank = $tier_order_map[ $a_info['tier'] ] ?? 99;
			$b_rank = $tier_order_map[ $b_info['tier'] ] ?? 99;

			if ( $a_rank !== $b_rank ) {
				return $a_rank - $b_rank;
			}
			return $a_info['order'] - $b_info['order'];
		} );
	?>
	<section class="featured-band">
		<div class="featured-band-container">
			<div class="featured-band-grid" id="mainProductsGrid">
				<?php
				$current_tier_rendered = '';
				while ( $products_query->have_posts() ) :
					$products_query->the_post();
					$product = wc_get_product( get_the_ID() );

					if ( ! $product ) {
						continue;
					}

					$in_stock      = $product->is_in_stock();
					$discount_pct  = 0;
					$urgency_label = get_post_meta( get_the_ID(), 'deal_urgency_label', true );

					if ( $product->is_type( 'variable' ) ) {
						$reg_p = $product->get_variation_regular_price( 'min', true );
						$sal_p = $product->get_variation_sale_price( 'min', true );
						$effective_price = (float) ( $sal_p ?: ( $product->get_variation_price( 'min', true ) ?: 0 ) );
						if ( $reg_p && $sal_p && $reg_p > $sal_p ) {
							$discount_pct = clearance_deal_calculate_discount( $reg_p, $sal_p );
						}
					} elseif ( $product->is_on_sale() && $product->get_regular_price() && $product->get_sale_price() ) {
						$reg_p = $product->get_regular_price();
						$sal_p = $product->get_sale_price();
						$effective_price = (float) $sal_p;
						$discount_pct = clearance_deal_calculate_discount( $reg_p, $sal_p );
					} else {
						$effective_price = (float) ( $product->get_price() ?: 0 );
					}

					$cat_slugs = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) );

					// Determine 4 price categories (deals-premium or > 20000 = premium)
					if ( in_array( 'deals-premium', $cat_slugs, true ) || $effective_price > 20000 ) {
						$item_tier       = 'premium';
						$tier_pill_label = '💎 Premium';
						$is_premium      = true;
					} elseif ( $effective_price <= 10000 ) {
						$item_tier       = 'price-1';
						$tier_pill_label = 'KSh 3,000 – 10,000';
						$is_premium      = false;
					} elseif ( $effective_price > 10000 && $effective_price <= 15000 ) {
						$item_tier       = 'price-2';
						$tier_pill_label = 'KSh 11,000 – 15,000';
						$is_premium      = false;
					} else {
						$item_tier       = 'price-3';
						$tier_pill_label = 'KSh 15,000 – 20,000';
						$is_premium      = false;
					}

					// Category Section Divider Header
					if ( $current_tier_rendered !== $item_tier ) {
						$is_first_tier         = empty( $current_tier_rendered );
						$current_tier_rendered = $item_tier;
						$tier_margin           = $is_first_tier ? 'margin: 10px 0 10px;' : 'margin: 30px 0 10px;';
						if ( 'price-1' === $item_tier ) :
							?>
							<div class="tier-section-header" id="price-1" data-tier-header="price-1" style="grid-column: 1 / -1; <?php echo $tier_margin; ?> padding: 14px 20px; background: #fdfafc; border: 1px solid #DDE3E8; border-left: 4px solid #EAAA00; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
								<div style="font-weight: 800; font-size: 16px; color: #3A0F34; display: flex; align-items: center; gap: 8px;">
									<i class="fa-solid fa-tag" style="color: #EAAA00;"></i>
									<span>From KSh 3,000 to 10,000 Deals — Affordable Human Hair Price in Kenya</span>
								</div>
								<span style="font-size: 12px; font-weight: 700; color: #3A0F34; background: #EAAA00; padding: 4px 10px; border-radius: 999px;">Under 10K</span>
							</div>
							<?php
						elseif ( 'price-2' === $item_tier ) :
							?>
							<div class="tier-section-header" id="price-2" data-tier-header="price-2" style="grid-column: 1 / -1; <?php echo $tier_margin; ?> padding: 14px 20px; background: #fdfafc; border: 1px solid #DDE3E8; border-left: 4px solid #3A0F34; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
								<div style="font-weight: 800; font-size: 16px; color: #3A0F34; display: flex; align-items: center; gap: 8px;">
									<i class="fa-solid fa-tags" style="color: #3A0F34;"></i>
									<span>From KSh 11,000 to 15,000 Deals — Best Value Wigs Price in Kenya</span>
								</div>
								<span style="font-size: 12px; font-weight: 700; color: #ffffff; background: #3A0F34; padding: 4px 10px; border-radius: 999px;">11K – 15K</span>
							</div>
							<?php
						elseif ( 'price-3' === $item_tier ) :
							?>
							<div class="tier-section-header" id="price-3" data-tier-header="price-3" style="grid-column: 1 / -1; <?php echo $tier_margin; ?> padding: 14px 20px; background: #fdfafc; border: 1px solid #DDE3E8; border-left: 4px solid #EAAA00; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
								<div style="font-weight: 800; font-size: 16px; color: #3A0F34; display: flex; align-items: center; gap: 8px;">
									<i class="fa-solid fa-fire" style="color: #EAAA00;"></i>
									<span>From KSh 15,000 to 20,000 Deals — Luxury Wigs Price in Kenya</span>
								</div>
								<span style="font-size: 12px; font-weight: 700; color: #3A0F34; background: #EAAA00; padding: 4px 10px; border-radius: 999px;">15K – 20K</span>
							</div>
							<?php
						elseif ( 'premium' === $item_tier ) :
							?>
							<div class="tier-section-header" id="premium" data-tier-header="premium" style="grid-column: 1 / -1; <?php echo $tier_margin; ?> padding: 14px 20px; background: linear-gradient(135deg, #3A0F34 0%, #20071d 100%); border-left: 4px solid #EAAA00; border-radius: 8px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
								<div style="font-weight: 800; font-size: 16px; color: #ffffff; display: flex; align-items: center; gap: 8px;">
									<i class="fa-solid fa-crown" style="color: #EAAA00;"></i>
									<span style="color:#ffffff;">From KSh 20,000 &amp; Above — Premium Human Hair Wigs Price in Kenya</span>
								</div>
								<span style="font-size: 12px; font-weight: 800; color: #3A0F34; background: #EAAA00; padding: 4px 10px; border-radius: 999px;">Premium Tier</span>
							</div>
							<?php
						endif;
					}

					// Variable products get JS variations selector
					$data_type_attr = '';
					$data_vars_attr = '';
					if ( $product->is_type( 'variable' ) ) {
						$data_type_attr = 'data-product-type="variable"';
						$vars_data      = [];
						foreach ( $product->get_available_variations() as $var_data ) {
							$variation = wc_get_product( $var_data['variation_id'] );
							if ( ! $variation || ! $variation->is_purchasable() ) {
								continue;
							}
							$label_parts = [];
							foreach ( $variation->get_variation_attributes() as $attr_name => $attr_value ) {
								$label         = wc_attribute_label( str_replace( 'attribute_', '', $attr_name ) );
								$label_parts[] = $label . ': ' . ucfirst( $attr_value );
							}
							$v_price    = (float) ( $variation->get_sale_price() ?: $variation->get_price() );
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

					// Classify for category filtering
					$cat_slugs        = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) );
					$title_lower      = strtolower( get_the_title() );
					$has_wig_term     = in_array( 'wigs', $cat_slugs, true );
					$has_hair_term    = in_array( 'hair-bundles', $cat_slugs, true ) || in_array( 'hair', $cat_slugs, true ) || in_array( 'hairtools', $cat_slugs, true ) || in_array( 'hair-tools', $cat_slugs, true );
					$has_makeup_term  = in_array( 'makeup', $cat_slugs, true );
					$title_has_wig    = ( stripos( $title_lower, 'wig' ) !== false );
					$title_has_bundle = ( stripos( $title_lower, 'bundle' ) !== false );

					$is_makeup = $has_makeup_term || ( stripos( $title_lower, 'makeup' ) !== false ) || ( stripos( $title_lower, 'cosmetic' ) !== false );
					$is_bundle = $title_has_bundle || ( $has_hair_term && ! $title_has_wig && ! $is_makeup );
					$is_wig    = $title_has_wig || ( $has_wig_term && ! $title_has_bundle );
					$is_hair   = $has_hair_term || $has_wig_term || $is_wig || $is_bundle || ( stripos( $title_lower, 'hair' ) !== false );
					?>
					<div class="deal-card cat-all cat-<?php echo esc_attr( $item_tier ); ?><?php echo $is_premium ? ' is-premium-card' : ''; ?>"
						data-price-cat="<?php echo esc_attr( $item_tier ); ?>"
						data-is-hair="<?php echo $is_hair ? '1' : '0'; ?>"
						data-is-makeup="<?php echo $is_makeup ? '1' : '0'; ?>"
						data-is-wig="<?php echo $is_wig ? '1' : '0'; ?>"
						data-is-bundle="<?php echo $is_bundle ? '1' : '0'; ?>">
						<div class="deal-media-wrapper" style="position: relative !important; width: 100% !important; overflow: hidden !important; background: transparent !important; padding-top: 0 !important; padding-bottom: 0 !important; height: auto !important; min-height: 0 !important;">
							<?php clearance_deal_render_featured_media( $product ); ?>

							<?php if ( $discount_pct > 0 ) : ?>
								<div class="badge-discount"><?php echo esc_html( $discount_pct ); ?>% OFF</div>
							<?php endif; ?>
							<?php if ( ! $in_stock ) : ?>
								<div class="badge-clearance"><?php esc_html_e( 'Sold Out', 'clearance-deal' ); ?></div>
							<?php endif; ?>
						</div>

						<?php if ( $is_premium ) : ?>
							<span class="bnpl-badge" style="background:#f3e8ff; color:#7e22ce; border-color:#d8b4fe; font-weight:800;">
								<i class="fa-solid fa-crown" style="color:#a855f7; margin-right:3px;"></i> Premium Clearance Deal (50% Payment)
							</span>
						<?php else : ?>
							<span class="bnpl-badge">Buy Now Pay Later (50% Payment)</span>
						<?php endif; ?>

						<div class="deal-details">
							<h3 class="deal-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

							<?php if ( $urgency_label ) : ?>
								<p class="deal-urgency-label"<?php echo $is_premium ? ' style="color:#7e22ce; font-weight:700;"' : ''; ?>><?php echo esc_html( $urgency_label ); ?></p>
							<?php elseif ( $is_premium ) : ?>
								<p class="deal-urgency-label" style="color:#7e22ce; font-weight:700;">💎 VIP Premium Stock</p>
							<?php else : ?>
								<p class="deal-urgency-label" style="color:#d97706;">🔥 Verified Clearance Stock</p>
							<?php endif; ?>



							<div class="deal-pricing">
								<?php echo $product->get_price_html(); ?>
								<?php if ( $discount_pct > 0 ) : ?>
									<span class="deal-savings"><?php echo esc_html( sprintf( __( 'Save %s%%', 'clearance-deal' ), $discount_pct ) ); ?></span>
								<?php endif; ?>
							</div>

							<?php if ( $in_stock ) : ?>
								<a href="<?php echo esc_url( $buy_now_url ); ?>" class="btn-get-deal" <?php echo $data_type_attr; ?> <?php echo $data_vars_attr; ?>>
									<?php esc_html_e( 'Buy Now', 'clearance-deal' ); ?>
								</a>
							<?php else : ?>
								<span class="btn-get-deal is-disabled"><?php esc_html_e( 'Sold Out', 'clearance-deal' ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
	<?php endif; ?>
</div>

<!-- Category Switching Script -->
<script>
function filterCategoryTab(category, btn) {
	// 1. Sync pill filter buttons on homepage
	var buttons = document.querySelectorAll('.tab-filter-btn');
	buttons.forEach(function (b) {
		var active = (b.getAttribute('data-cat') === category);
		b.style.background = active ? '#3A0F34' : 'transparent';
		b.style.color = active ? '#ffffff' : '#3A0F34';
		b.style.boxShadow = active ? '0 2px 6px rgba(58,15,52,0.25)' : 'none';
		b.classList.toggle('is-active', active);
	});

	// 2. Sync header sub-bar tabs
	document.querySelectorAll('.cat-bar-item').forEach(function (t) {
		t.classList.toggle('is-active', t.getAttribute('data-cat') === category);
	});

	// 3. Sync mobile nav drawer tabs
	document.querySelectorAll('.mobile-nav-deal-tab').forEach(function (t) {
		t.classList.toggle('is-active', t.getAttribute('data-cat') === category);
	});

	// 4. Filter product cards
	var cards = document.querySelectorAll('#mainProductsGrid .deal-card');
	var headers = document.querySelectorAll('#mainProductsGrid .tier-section-header');

	cards.forEach(function (card) {
		var match = false;
		if (category === 'all') {
			match = true;
		} else if (category === 'price-1' || category === 'price-2' || category === 'price-3' || category === 'premium') {
			match = (card.getAttribute('data-price-cat') === category);
		} else if (category === 'hair') {
			match = (card.getAttribute('data-is-hair') === '1');
		} else if (category === 'makeup') {
			match = (card.getAttribute('data-is-makeup') === '1');
		} else if (category === 'wigs') {
			match = (card.getAttribute('data-is-wig') === '1');
		} else if (category === 'bundles') {
			match = (card.getAttribute('data-is-bundle') === '1');
		}
		card.style.display = match ? '' : 'none';
	});

	// 5. Filter or hide tier section headers
	headers.forEach(function (header) {
		if (category === 'all') {
			header.style.display = '';
		} else if (category === 'price-1' || category === 'price-2' || category === 'price-3' || category === 'premium') {
			header.style.display = (header.getAttribute('data-tier-header') === category) ? '' : 'none';
		} else {
			// For specific product categories (hair, makeup, wigs, bundles), hide price headers for seamless layout
			header.style.display = 'none';
		}
	});
}
window.filterCategoryTab = filterCategoryTab;

// Auto-filter when landing on URL with hash e.g. #wigs, #bundles, #price-1
function handleCategoryHash() {
	var hash = window.location.hash.replace('#', '');
	if (!hash) return;
	var validTabs = ['all', 'price-1', 'price-2', 'price-3', 'premium', 'hair', 'makeup', 'wigs', 'bundles'];
	if (validTabs.indexOf(hash) !== -1) {
		filterCategoryTab(hash);
	}
}
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', handleCategoryHash);
} else {
	handleCategoryHash();
}
window.addEventListener('hashchange', handleCategoryHash);
</script>

<!-- Authenticity Guarantee & Burn Test Section -->
<section style="background:linear-gradient(135deg, #3A0F34 0%, #20071d 100%); color:#ffffff; padding:54px 20px; margin:40px 0;">
	<div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:40px; align-items:center;">
		<div>
			<span style="color:#EAAA00; font-weight:800; font-size:12px; letter-spacing:1px; text-transform:uppercase;">Zero Compromise Quality</span>
			<h2 style="font-size:32px; font-weight:800; line-height:1.2; margin:10px 0 16px;">
				Pure Human Hair vs. Synthetic Mix.<br><span style="color:#EAAA00;">The Burn-Test Proof.</span>
			</h2>
			<p style="color:#cbd5e1; font-size:15px; line-height:1.6; margin-bottom:20px;">
				Many vendors in Kenya blend cheap synthetic hair into human hair bundles. At Clearance Deal Kenya, every single-donor lot undergoes pure burn testing. When burned, real human hair smells like natural protein and crumbles instantly into ash &mdash; it never melts into sticky plastic beads.
			</p>
			<div style="display:flex; flex-direction:column; gap:12px;">
				<div style="display:flex; align-items:center; gap:10px; font-size:14px;">
					<i class="fa-solid fa-circle-check" style="color:#10b981; font-size:18px;"></i>
					<span><strong>Ethical Single-Donor Sourcing:</strong> Cuticles aligned root-to-tip to prevent tangling.</span>
				</div>
				<div style="display:flex; align-items:center; gap:10px; font-size:14px;">
					<i class="fa-solid fa-circle-check" style="color:#10b981; font-size:18px;"></i>
					<span><strong>Batch-Verified Imported Cosmetics:</strong> 100% genuine factory sealed batches with batch codes.</span>
				</div>
				<div style="display:flex; align-items:center; gap:10px; font-size:14px;">
					<i class="fa-solid fa-circle-check" style="color:#10b981; font-size:18px;"></i>
					<span><strong>Factory Sealed &amp; Quality Checked:</strong> Every item is verified and sealed at our warehouse before dispatch.</span>
				</div>
			</div>
		</div>

		<div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12); border-radius:18px; padding:28px;">
			<h3 style="font-size:20px; font-weight:700; margin-bottom:14px;">The Clearance Kenya Promise</h3>
			<div style="background:rgba(0,0,0,0.3); border-left:4px solid #EAAA00; padding:16px; border-radius:8px; margin-bottom:16px;">
				<h4 style="font-size:15px; color:#fff; margin-bottom:4px;"><i class="fa-solid fa-fire" style="color:#EAAA00;"></i> Pure Hair Quality Guarantee</h4>
				<p style="font-size:13px; color:#94a3b8; line-height:1.5; margin:0;">100% genuine virgin human hair with aligned cuticles. We guarantee virgin quality or your money back.</p>
			</div>
			<div style="background:rgba(0,0,0,0.3); border-left:4px solid #DDE3E8; padding:16px; border-radius:8px;">
				<h4 style="font-size:15px; color:#fff; margin-bottom:4px;"><i class="fa-solid fa-truck-ramp-box" style="color:#DDE3E8;"></i> Direct Warehouse Shipping</h4>
				<p style="font-size:13px; color:#94a3b8; line-height:1.5; margin:0;">All orders are dispatched directly from our central warehouse for guaranteed freshness and fast nationwide delivery.</p>
			</div>
		</div>
	</div>
</section>

<!-- Interactive Lipa Polepole (BNPL) Calculator -->
<section style="max-width:1000px; margin:40px auto; padding:0 20px;">
	<div style="background:#ffffff; border:1px solid #DDE3E8; border-radius:18px; padding:32px 28px; box-shadow:0 4px 14px rgba(0,0,0,0.06); display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:32px; align-items:center;">
		<div>
			<span style="color:#3A0F34; font-weight:800; font-size:12px; text-transform:uppercase;">Buy Now Pay Later</span>
			<h3 style="font-size:24px; font-weight:800; color:#3A0F34; margin:6px 0 10px;">Lipa 50% Polepole Calculator</h3>
			<p style="font-size:14px; color:#64748b; line-height:1.5; margin-bottom:20px;">
				Get your dream hair bundles or beauty kit today. Pay 50% now and buy, and clear the remainder in flexible monthly instalments via M-Pesa.
			</p>
			<div>
				<div style="display:flex; justify-content:space-between; font-size:13px; font-weight:700; margin-bottom:8px;">
					<span>Selected Item Value:</span>
					<span id="sliderDisplayPrice" style="color:#3A0F34; font-size:16px; font-weight:800;">KSh 8,000</span>
				</div>
				<input type="range" id="bnplSlider" min="3000" max="30000" step="500" value="8000" oninput="calculateBnpl(this.value)" style="width:100%; height:8px; border-radius:4px; accent-color:#3A0F34; cursor:pointer;">
			</div>
		</div>

		<div style="background:#fdfafc; border:1.5px dashed #3A0F34; border-radius:14px; padding:24px; text-align:center;">
			<div style="font-size:12px; font-weight:800; color:#3A0F34; text-transform:uppercase;">Due Today (50% Payment)</div>
			<div id="sliderDepositText" style="font-size:32px; font-weight:800; color:#3A0F34; margin:6px 0;">KSh 4,000</div>
			<p style="font-size:13px; color:#64748b; margin-bottom:14px;">
				Remaining balance spread over <strong>12 monthly instalments</strong> of approximately <strong id="sliderMonthlyText" style="color:#3A0F34;">KSh 373/mo</strong>.
			</p>
			<a href="#deals-section" class="cpl-btn cpl-btn-primary" style="display:inline-block; padding:10px 24px; border-radius:999px; background:#3A0F34; color:#fff; border:1px solid #EAAA00;">
				Browse Clearance Deals
			</a>
		</div>
	</div>
</section>

<script>
function calculateBnpl(val) {
	var price = parseInt(val, 10);
	document.getElementById('sliderDisplayPrice').textContent = 'KSh ' + price.toLocaleString();
	var deposit = Math.round(price * 0.5);
	var monthly = Math.round((price * 0.5 * 1.12) / 12);
	document.getElementById('sliderDepositText').textContent = 'KSh ' + deposit.toLocaleString();
	document.getElementById('sliderMonthlyText').textContent = 'KSh ' + monthly.toLocaleString() + '/mo';
}
</script>

<!-- Kenya Human Hair & Wigs Buyer's Guide & Verification Section -->
<section style="max-width:1200px; margin:50px auto 40px; padding:0 20px;">
	<div class="ch-deals-header" style="margin-bottom:30px; text-align:center;">
		<span style="display:inline-block; font-size:12px; font-weight:800; color:#EAAA00; background:#3A0F34; padding:5px 14px; border-radius:999px; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:10px;">
			Kenya Hair &amp; Wig Buyer's Guide
		</span>
		<h2 style="font-size:clamp(1.5rem, 3.5vw, 2.2rem); font-weight:800; color:#3A0F34; margin-bottom:8px;">
			Kenya Human Hair &amp; Wigs Guide: How to Choose, Verify &amp; Find the Best Price in Kenya
		</h2>
		<p style="font-size:15px; color:#64748b; max-width:760px; margin:0 auto; line-height:1.6;">
			Your trusted guide to authentic 100% raw human hair, glueless HD lace front wigs, single-donor bundles, and wholesale clearance prices across Nairobi and Kenya.
		</p>
	</div>

	<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(270px, 1fr)); gap:22px;">
		<!-- Guide Card 1 -->
		<div style="background:#ffffff; border:1px solid #DDE3E8; border-top:4px solid #3A0F34; border-radius:14px; padding:24px 20px; box-shadow:0 3px 10px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="width:44px; height:44px; border-radius:10px; background:#f4eef3; color:#3A0F34; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:14px;">
				<i class="fa-solid fa-crown"></i>
			</div>
			<h3 style="font-size:17px; font-weight:800; color:#3A0F34; margin-bottom:8px;">
				Glueless HD Lace Wigs &amp; Frontals
			</h3>
			<p style="font-size:13.5px; color:#475569; line-height:1.6; margin:0;">
				Featuring real ultra-thin Swiss HD transparent lace that melts effortlessly into African and melanin skin tones. Designed with adjustable elastic bands and inner combs for beginner-friendly wear without toxic glues or edge damage.
			</p>
		</div>

		<!-- Guide Card 2 -->
		<div style="background:#ffffff; border:1px solid #DDE3E8; border-top:4px solid #EAAA00; border-radius:14px; padding:24px 20px; box-shadow:0 3px 10px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="width:44px; height:44px; border-radius:10px; background:#fef8e7; color:#EAAA00; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:14px;">
				<i class="fa-solid fa-fire"></i>
			</div>
			<h3 style="font-size:17px; font-weight:800; color:#3A0F34; margin-bottom:8px;">
				100% Raw Indian Virgin Hair
			</h3>
			<p style="font-size:13.5px; color:#475569; line-height:1.6; margin:0;">
				Single-donor unprocessed temple hair with unidirectionally aligned cuticles. Tangle-free, zero chemical processing, and pure protein burn-tested. Bleaches safely to #613 blonde, flat-irons silky, and lasts 3 to 5+ years.
			</p>
		</div>

		<!-- Guide Card 3 -->
		<div style="background:#ffffff; border:1px solid #DDE3E8; border-top:4px solid #3A0F34; border-radius:14px; padding:24px 20px; box-shadow:0 3px 10px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="width:44px; height:44px; border-radius:10px; background:#f4eef3; color:#3A0F34; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:14px;">
				<i class="fa-solid fa-tags"></i>
			</div>
			<h3 style="font-size:17px; font-weight:800; color:#3A0F34; margin-bottom:8px;">
				Human Hair Price in Kenya (KSh 3,500 – 25,000+)
			</h3>
			<p style="font-size:13.5px; color:#475569; line-height:1.6; margin:0;">
				Direct warehouse clearance eliminates high salon markups. Starter bundles and crochet curls from KSh 3,500; 18-inch HD lace closure wigs and 3-bundle sets from KSh 10,999; and luxury blonde balayage from KSh 15,000+. Compare our human hair wigs price in Kenya for guaranteed savings.
			</p>
		</div>

		<!-- Guide Card 4 -->
		<div style="background:#ffffff; border:1px solid #DDE3E8; border-top:4px solid #EAAA00; border-radius:14px; padding:24px 20px; box-shadow:0 3px 10px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="width:44px; height:44px; border-radius:10px; background:#fef8e7; color:#EAAA00; display:flex; align-items:center; justify-content:center; font-size:20px; margin-bottom:14px;">
				<i class="fa-solid fa-hand-holding-dollar"></i>
			</div>
			<h3 style="font-size:17px; font-weight:800; color:#3A0F34; margin-bottom:8px;">
				Lipa Polepole via M-Pesa
			</h3>
			<p style="font-size:13.5px; color:#475569; line-height:1.6; margin:0;">
				Enjoy flexible Buy Now Pay Later. Pay a 50% payment only after your order arrives at your door and you inspect the hair. Clear the remaining balance in flexible, stress-free monthly instalments via Safaricom M-Pesa.
			</p>
		</div>
	</div>
</section>

<!-- Verified Kenyan Customer Reviews -->
<section style="max-width:1200px; margin:60px auto; padding:0 20px;">
	<div class="ch-deals-header" style="margin-bottom:30px;">
		<h2>Verified Kenyan Customer Reviews</h2>
		<p>Real feedback from ladies across Nairobi, Mombasa, and beyond</p>
	</div>

	<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(290px, 1fr)); gap:20px;">
		<div style="background:#fff; border:1px solid #DDE3E8; border-radius:14px; padding:22px; box-shadow:0 2px 8px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="color:#EAAA00; font-size:14px; margin-bottom:10px;">
				<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
			</div>
			<p style="font-size:14px; color:#334155; line-height:1.6; margin-bottom:16px; font-style:italic;">
				&ldquo;I ordered the Raw Indian curls to Kilimani. The package arrived in perfect, factory-sealed condition. Real single donor human hair &mdash; zero synthetic mix! Truly trustworthy service.&rdquo;
			</p>
			<div style="margin-top:auto; font-size:13px;">
				<strong style="display:block; color:#3A0F34;">Wanjiku N.</strong>
				<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Verified Buyer &bull; Kilimani, Nairobi</span>
			</div>
		</div>

		<div style="background:#fff; border:1px solid #DDE3E8; border-radius:14px; padding:22px; box-shadow:0 2px 8px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="color:#EAAA00; font-size:14px; margin-bottom:10px;">
				<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
			</div>
			<p style="font-size:14px; color:#334155; line-height:1.6; margin-bottom:16px; font-style:italic;">
				&ldquo;The Raquel Welch Classic Cut wig arrived safely in Nyali, Mombasa via courier. The monofilament crown looks exactly like my real scalp. Snatched it at 72% off clearance &mdash; unbelievable bargain!&rdquo;
			</p>
			<div style="margin-top:auto; font-size:13px;">
				<strong style="display:block; color:#3A0F34;">Amina M.</strong>
				<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Verified Buyer &bull; Nyali, Mombasa</span>
			</div>
		</div>

		<div style="background:#fff; border:1px solid #DDE3E8; border-radius:14px; padding:22px; box-shadow:0 2px 8px rgba(0,0,0,0.04); display:flex; flex-direction:column;">
			<div style="color:#EAAA00; font-size:14px; margin-bottom:10px;">
				<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
			</div>
			<p style="font-size:14px; color:#334155; line-height:1.6; margin-bottom:16px; font-style:italic;">
				&ldquo;Got the complete makeup artist brush set and cosmetics kit. 100% authentic imported tools. Paying 50% payment and clearing the rest polepole makes salon shopping so stress-free.&rdquo;
			</p>
			<div style="margin-top:auto; font-size:13px;">
				<strong style="display:block; color:#3A0F34;">Sharon O.</strong>
				<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Verified Buyer &bull; Westlands, Nairobi</span>
			</div>
		</div>
	</div>
</section>

<!-- Frequently Asked Questions (Comprehensive Kenya Hair & Wigs FAQ) -->
<section style="max-width:960px; margin:40px auto 60px; padding:0 20px;">
	<div class="ch-deals-header" style="margin-bottom:24px; text-align:center;">
		<span style="display:inline-block; font-size:12px; font-weight:800; color:#EAAA00; background:#3A0F34; padding:5px 14px; border-radius:999px; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:10px;">
			Clearance Kenya Help Center
		</span>
		<h2 style="font-size:clamp(1.5rem, 3.5vw, 2.2rem); font-weight:800; color:#3A0F34; margin-bottom:8px;">
			Frequently Asked Questions: Human Hair Wigs &amp; Bundles Price in Kenya
		</h2>
		<p style="font-size:15px; color:#64748b; max-width:700px; margin:0 auto; line-height:1.6;">
			Everything you need to know about purchasing authentic raw human hair, HD lace wigs, best price in Kenya, Lipa Polepole payment, and fast delivery across Kenya.
		</p>
	</div>

	<div style="display:flex; flex-direction:column; gap:12px;">
		<!-- FAQ 1 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;" open>
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>What is the human hair wigs price in Kenya?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				The human hair wigs price in Kenya at Clearance Deal ranges from <strong>KSh 7,400 to KSh 14,995</strong> for 18-inch glueless HD lace closure and lace front wigs, and from <strong>KSh 15,000 to KSh 25,000+</strong> for luxury virgin blonde balayage, honey ombre, and long bone straight styles. Starter crochet hair and single closures are available from KSh 3,500.
			</p>
		</details>

		<!-- FAQ 2 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>What is the raw Indian hair bundles price in Kenya?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				The raw Indian hair bundles price in Kenya starts from <strong>KSh 4,499</strong> for single unprocessed bundles, and from <strong>KSh 7,990 to KSh 14,995</strong> for complete 3-bundle sets with 5x5 HD lace closures in Body Wave, Deep Wave, and Natural Curly textures.
			</p>
		</details>

		<!-- FAQ 3 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>Where can I find the best human hair price in Kenya online?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				You can find the best authentic human hair price in Kenya at <strong>Clearance Deal Kenya (clearance.co.ke)</strong>. Because we liquidate directly from central warehouse overstock in Highlight Business Park, 4th Parklands, Nairobi, our prices are 40% to 70% below regular retail salon prices, complete with <strong>Lipa Polepole (50% payment)</strong> via M-Pesa.
			</p>
		</details>

		<!-- FAQ 4 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>Why is the human hair wigs price in Kenya lower at Clearance Deal?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				Our human hair wigs price in Kenya is substantially lower because we operate as a direct liquidation and warehouse clearance hub. We bypass expensive salon rent, middleman importers, and retail markups, passing wholesale discounts directly to Kenyan consumers.
			</p>
		</details>

		<!-- FAQ 5 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>What is a glueless HD lace front wig and its price in Kenya?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				A glueless HD lace front wig uses invisible Swiss HD lace that melts seamlessly into African and melanin-rich skin tones without glue or adhesives. In Kenya, our glueless HD lace front wig price ranges from <strong>KSh 10,999 to KSh 14,995</strong> for 18-inch virgin human hair, featuring adjustable elastic bands for 3-minute installation.
			</p>
		</details>

		<!-- FAQ 6 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>Can I buy human hair in Kenya on Lipa Polepole (Buy Now Pay Later)?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				Yes! Clearance Deal Kenya offers Lipa Polepole (BNPL) across Kenya. You only pay a <strong>50% payment now and buy</strong>. The remaining 50% balance is comfortably cleared over easy monthly instalments via Safaricom M-Pesa.
			</p>
		</details>

		<!-- FAQ 7 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>How can I verify if human hair in Kenya is 100% genuine before paying?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				Perform the protein burn test on a strand: genuine 100% human hair burns slowly with white smoke, smells like burnt natural protein (feathers), and crumbles into fine ash. Synthetic mixes burn quickly with toxic black smoke and melt into hard plastic beads. All Clearance Deal Kenya hair is <strong>100% burn-test verified</strong>.
			</p>
		</details>

		<!-- FAQ 8 -->
		<details style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:18px 22px; cursor:pointer;">
			<summary style="font-weight:700; color:#3A0F34; font-size:15.5px; outline:none; display:flex; align-items:center; justify-content:space-between;">
				<span>How long does delivery take across Kenya?</span>
				<i class="fa-solid fa-chevron-down" style="font-size:13px; color:#EAAA00;"></i>
			</summary>
			<p style="font-size:14px; color:#475569; line-height:1.65; margin-top:12px; margin-bottom:0;">
				Orders within Nairobi County are delivered same-day or within 24 hours via dedicated warehouse dispatch riders. Deliveries to Mombasa, Kisumu, Nakuru, Eldoret, Thika, and upcountry locations arrive within 24 to 48 hours via Wells Fargo or Fargo Courier with secure door-to-door tracking.
			</p>
		</details>
	</div>
</section>

<!-- Showroom Location Card -->
<section style="max-width:1100px; margin:0 auto 50px; padding:0 20px;">
	<div style="background:#fff; border:1px solid #DDE3E8; border-radius:16px; padding:28px; display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:24px; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
		<div>
			<h4 style="font-size:16px; font-weight:700; color:#3A0F34; margin-bottom:6px;"><i class="fa-solid fa-location-dot" style="color:#3A0F34; margin-right:8px;"></i> Nairobi Office</h4>
			<p style="font-size:13px; color:#64748b; line-height:1.5; margin:0;">Office 303, 1st Floor, Highlight Business Park, South Zone, 4th Parklands, Nairobi.</p>
		</div>
		<div style="background:#fdfafc; border:1px solid #DDE3E8; border-radius:12px; padding:18px;">
			<h4 style="font-size:16px; font-weight:700; color:#3A0F34; margin-bottom:6px;"><i class="fa-solid fa-clock" style="color:#EAAA00; margin-right:8px;"></i> Hours &amp; Styling Support</h4>
			<p style="font-size:13px; color:#475569; line-height:1.5; margin:0 0 10px 0;">Mon &ndash; Sat: 8:30 AM &ndash; 6:30 PM. Certified hair consultants on stand-by via WhatsApp for shade and texture matching.</p>
			<div style="background:#fff; border:1px solid #DDE3E8; border-radius:10px; padding:14px; margin-top:8px;">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
					<span style="font-size:13px; font-weight:700; color:#3A0F34;"><i class="fa-solid fa-user-tie" style="color:#3A0F34; margin-right:6px;"></i> 1-on-1 Stylist Consultation</span>
					<span style="font-size:15px; font-weight:800; color:#3A0F34;">KSh 1,000</span>
				</div>
				<a href="<?php echo esc_url( add_query_arg( 'add-to-cart', 250, wc_get_checkout_url() ) ); ?>" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; background:linear-gradient(135deg, #3A0F34, #260a22); color:#fff; font-weight:700; font-size:13px; padding:10px 16px; border-radius:8px; text-decoration:none; text-transform:uppercase; letter-spacing:0.5px; border:1px solid #EAAA00; box-shadow:0 3px 10px rgba(58,15,52,0.3); transition:all 0.2s ease;">
					<i class="fa-solid fa-calendar-check" style="color:#EAAA00;"></i> Book Consultation &bull; KSh 1,000
				</a>
			</div>
		</div>
		<div>
			<h4 style="font-size:16px; font-weight:700; color:#3A0F34; margin-bottom:6px;"><i class="fa-solid fa-phone" style="color:#16a34a; margin-right:8px;"></i> Official Contacts</h4>
			<p style="font-size:13px; color:#64748b; line-height:1.5; margin:0;">Call / WhatsApp: +254 751 338 975<br>Email: info@clearance.co.ke</p>
		</div>
	</div>
</section>

<?php get_footer(); ?>
