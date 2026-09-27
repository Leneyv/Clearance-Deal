<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Clearance_Deal
 */

get_header();
?>

<!-- Hero Section -->
<section class="ch-hero">
	<div class="ch-hero-inner">

		<!-- Crossfading text block: badge + heading swap every 4 s -->
		<div class="ch-hero-text-wrap" aria-live="polite">
			<!-- State 1: theclearancehouse style (active by default) -->
			<div class="ch-hero-state ch-hero-state--active">
				<div class="ch-hero-badge">Updated daily · 200+ brands</div>
				<h1 class="ch-hero-h1">Every clearance sale.<br><span>One place.</span></h1>
			</div>
			<!-- State 2: Kenya trust style -->
			<div class="ch-hero-state" aria-hidden="true">
				<div class="ch-hero-badge ch-hero-badge--kenya">
					<span class="ch-green-dot"></span>Kenya's Trusted Clearance Store
				</div>
				<h1 class="ch-hero-h1">Genuine Products.<br><span class="ch-hero-h1--coral">Unbeatable Prices.</span></h1>
			</div>
		</div>

		<p class="ch-hero-sub">30,000+ products from 200+ top brands — all on sale, up to 80% off. Stop tab-switching. Start saving.</p>
		<div class="ch-hero-btns">
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="ch-btn-primary">Browse Deals</a>
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="ch-btn-outline">Get Deal Alerts</a>
		</div>
		<div class="ch-hero-stats">
			<div class="ch-stat">
				<div class="ch-stat-num">30,000+</div>
				<div class="ch-stat-label">Products on sale</div>
			</div>
			<div class="ch-stat">
				<div class="ch-stat-num">100+</div>
				<div class="ch-stat-label">Top brands</div>
			</div>
			<div class="ch-stat">
				<div class="ch-stat-num">80%</div>
				<div class="ch-stat-label">Max discount</div>
			</div>
			<div class="ch-stat">
				<div class="ch-stat-num">Daily</div>
				<div class="ch-stat-label">Update frequency</div>
			</div>
		</div>
	</div>
</section>
<script>
(function () {
	var states = document.querySelectorAll('.ch-hero-state');
	if (!states.length) return;
	var current = 0;
	var FADE_MS = 700;
	setInterval(function () {
		var outgoing = current;
		current = (current + 1) % states.length;
		var incoming = current;
		states[outgoing].classList.remove('ch-hero-state--active');
		states[outgoing].setAttribute('aria-hidden', 'true');
		setTimeout(function () {
			states[incoming].classList.add('ch-hero-state--active');
			states[incoming].removeAttribute('aria-hidden');
		}, FADE_MS + 80);
	}, 4000);
}());
</script>

<!-- Brand Ticker -->
<div class="ch-ticker-bar">
	<div class="ch-ticker-track">
		<span>Nike<span class="ch-ticker-dot">·</span></span>
		<span>adidas<span class="ch-ticker-dot">·</span></span>
		<span>Lululemon<span class="ch-ticker-dot">·</span></span>
		<span>The North Face<span class="ch-ticker-dot">·</span></span>
		<span>Michael Kors<span class="ch-ticker-dot">·</span></span>
		<span>Patagonia<span class="ch-ticker-dot">·</span></span>
		<span>PUMA<span class="ch-ticker-dot">·</span></span>
		<span>Vans<span class="ch-ticker-dot">·</span></span>
		<span>Timberland<span class="ch-ticker-dot">·</span></span>
		<span>Under Armour<span class="ch-ticker-dot">·</span></span>
		<span>Zara<span class="ch-ticker-dot">·</span></span>
		<span>Calvin Klein<span class="ch-ticker-dot">·</span></span>
		<span>Gymshark<span class="ch-ticker-dot">·</span></span>
		<span>Alo Yoga<span class="ch-ticker-dot">·</span></span>
		<span>Reebok<span class="ch-ticker-dot">·</span></span>
		<span>Steve Madden<span class="ch-ticker-dot">·</span></span>
		<span>Aldo<span class="ch-ticker-dot">·</span></span>
		<span>Ted Baker<span class="ch-ticker-dot">·</span></span>
		<span>Club Monaco<span class="ch-ticker-dot">·</span></span>
		<span>Forever 21<span class="ch-ticker-dot">·</span></span>
		<span>BCBG<span class="ch-ticker-dot">·</span></span>
		<span>RW&amp;CO<span class="ch-ticker-dot">·</span></span>
		<span>La Senza<span class="ch-ticker-dot">·</span></span>
		<span>Ardene<span class="ch-ticker-dot">·</span></span>
		<span>Psycho Bunny<span class="ch-ticker-dot">·</span></span>
		<span>SAXX<span class="ch-ticker-dot">·</span></span>
		<span>Tentree<span class="ch-ticker-dot">·</span></span>
		<!-- duplicate for seamless loop -->
		<span>Nike<span class="ch-ticker-dot">·</span></span>
		<span>adidas<span class="ch-ticker-dot">·</span></span>
		<span>Lululemon<span class="ch-ticker-dot">·</span></span>
		<span>The North Face<span class="ch-ticker-dot">·</span></span>
		<span>Michael Kors<span class="ch-ticker-dot">·</span></span>
		<span>Patagonia<span class="ch-ticker-dot">·</span></span>
		<span>PUMA<span class="ch-ticker-dot">·</span></span>
		<span>Vans<span class="ch-ticker-dot">·</span></span>
		<span>Timberland<span class="ch-ticker-dot">·</span></span>
		<span>Under Armour<span class="ch-ticker-dot">·</span></span>
		<span>Zara<span class="ch-ticker-dot">·</span></span>
		<span>Calvin Klein<span class="ch-ticker-dot">·</span></span>
		<span>Gymshark<span class="ch-ticker-dot">·</span></span>
		<span>Alo Yoga<span class="ch-ticker-dot">·</span></span>
		<span>Reebok<span class="ch-ticker-dot">·</span></span>
		<span>Steve Madden<span class="ch-ticker-dot">·</span></span>
		<span>Aldo<span class="ch-ticker-dot">·</span></span>
		<span>Ted Baker<span class="ch-ticker-dot">·</span></span>
		<span>Club Monaco<span class="ch-ticker-dot">·</span></span>
		<span>Forever 21<span class="ch-ticker-dot">·</span></span>
		<span>BCBG<span class="ch-ticker-dot">·</span></span>
		<span>RW&amp;CO<span class="ch-ticker-dot">·</span></span>
		<span>La Senza<span class="ch-ticker-dot">·</span></span>
		<span>Ardene<span class="ch-ticker-dot">·</span></span>
		<span>Psycho Bunny<span class="ch-ticker-dot">·</span></span>
		<span>SAXX<span class="ch-ticker-dot">·</span></span>
		<span>Tentree<span class="ch-ticker-dot">·</span></span>
	</div>
</div>

<div class="top-announcement-bar">
	<div class="top-announcement-track">
		<span><i class="fa-solid fa-rotate-left"></i> <?php esc_html_e( 'Easy Return in 7 Days', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Free Delivery', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-shield-halved"></i> <?php esc_html_e( 'Secure Payment', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-rotate-left"></i> <?php esc_html_e( 'Easy Return in 7 Days', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-truck-fast"></i> <?php esc_html_e( 'Free Delivery', 'clearance-deal' ); ?></span>
		<span><i class="fa-solid fa-shield-halved"></i> <?php esc_html_e( 'Secure Payment', 'clearance-deal' ); ?></span>
	</div>
</div>

<div class="ch-deals-header">
	<h2>Today's top deals</h2>
	<p>Sorted by biggest discount</p>
</div>

<?php clearance_deal_featured_products_section(); ?>

<?php if ( have_posts() ) : ?>
<main class="main-content">
	<div class="ch-deals-header">
		<h2>More deals</h2>
		<p>Fresh clearance finds</p>
	</div>

	<div class="deals-grid" id="deals-grid">
		<?php
			while ( have_posts() ) :
				the_post();

				// Get deal specific custom fields (supporting both standard WP meta or smart fallback)
				$original_price = get_post_meta( get_the_ID(), 'original_price', true );
				$deal_price     = get_post_meta( get_the_ID(), 'deal_price', true );
				$deal_store     = get_post_meta( get_the_ID(), 'deal_store', true ) ?: 'Online Retailer';
				$deal_rating    = get_post_meta( get_the_ID(), 'deal_rating', true ) ?: '4.5';
				$deal_link      = get_post_meta( get_the_ID(), 'deal_link', true ) ?: get_permalink();
				$category_names = wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) );
				$primary_cat    = ! empty( $category_names ) ? strtolower( $category_names[0] ) : 'all';

				// Smart fallback for mock-matching category filters
				$filter_class = 'all';
				if ( stripos( $primary_cat, 'tech' ) !== false || stripos( get_the_title(), 'headphone' ) !== false || stripos( get_the_title(), 'laptop' ) !== false ) {
					$filter_class .= ' tech';
				} elseif ( stripos( $primary_cat, 'home' ) !== false || stripos( get_the_title(), 'smart' ) !== false || stripos( get_the_title(), 'cook' ) !== false ) {
					$filter_class .= ' home';
				} elseif ( stripos( $primary_cat, 'fashion' ) !== false || stripos( get_the_title(), 'shoe' ) !== false || stripos( get_the_title(), 'shirt' ) !== false ) {
					$filter_class .= ' fashion';
				} elseif ( stripos( $primary_cat, 'gaming' ) !== false || stripos( get_the_title(), 'game' ) !== false || stripos( get_the_title(), 'console' ) !== false ) {
					$filter_class .= ' gaming';
				}

				// Default prices if empty
				if ( ! $original_price ) {
					$original_price = '$99.99';
				}
				if ( ! $deal_price ) {
					$deal_price = '$59.99';
				}

				$discount_pct = clearance_deal_calculate_discount( $original_price, $deal_price );
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'deal-card ' . esc_attr( $filter_class ) ); ?>>
					<div class="deal-media-wrapper" style="position: relative; overflow: hidden;">
						<?php clearance_deal_render_media( get_the_ID() ); ?>

						<?php if ( $discount_pct > 0 ) : ?>
							<div class="badge-discount"><?php echo esc_html( $discount_pct ); ?>% OFF</div>
						<?php endif; ?>
						<div class="badge-clearance">Deal of Day</div>
					</div>

					<div class="deal-details">
						<div class="deal-store"><?php echo esc_html( $deal_store ); ?></div>
						<h3 class="deal-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h3>

						<div class="deal-meta">
							<span class="deal-rating">
								<i class="fa-solid fa-star"></i> <?php echo esc_html( $deal_rating ); ?>
							</span>
							<span class="deal-separator">•</span>
							<span class="deal-posted-time"><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ago' ); ?></span>
						</div>

						<div class="deal-pricing">
							<span class="deal-current-price"><?php echo esc_html( $deal_price ); ?></span>
							<span class="deal-original-price"><?php echo esc_html( $original_price ); ?></span>
							<?php if ( $discount_pct > 0 ) : ?>
								<span class="deal-savings">Save <?php echo esc_html( $discount_pct ); ?>%</span>
							<?php endif; ?>
						</div>

						<a href="<?php echo esc_url( $deal_link ); ?>" class="btn-get-deal" target="_blank" rel="nofollow">
							Get Deal <i class="fa-solid fa-arrow-right"></i>
						</a>
					</div>
				</article>
				<?php
			endwhile;
		?>
	</div>
</main>
<?php endif; ?>

<?php
get_footer();
