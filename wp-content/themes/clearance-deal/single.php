<?php
/**
 * The template for displaying all single posts (Hair & Wigs Blog)
 *
 * @package Clearance_Deal
 */

get_header();
?>

<style>
.cd-blog-container {
	max-width: 900px;
	margin: 0 auto;
	padding: 24px 20px 60px;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
	color: #27212b;
	line-height: 1.7;
}
.cd-breadcrumbs {
	font-size: 13px;
	color: #64748b;
	margin-bottom: 16px;
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 6px;
}
.cd-breadcrumbs a {
	color: #3A0F34;
	font-weight: 600;
	text-decoration: none;
}
.cd-breadcrumbs a:hover {
	color: #EAAA00;
}
.cd-post-header {
	margin-bottom: 24px;
	border-bottom: 1px solid #DDE3E8;
	padding-bottom: 20px;
}
.cd-post-category-badge {
	display: inline-block;
	font-size: 11.5px;
	font-weight: 800;
	text-transform: uppercase;
	letter-spacing: 0.8px;
	background: #3A0F34;
	color: #EAAA00;
	padding: 4px 12px;
	border-radius: 999px;
	margin-bottom: 12px;
}
.cd-post-title {
	font-size: clamp(1.8rem, 4vw, 2.6rem);
	font-weight: 800;
	color: #3A0F34;
	line-height: 1.25;
	margin: 0 0 14px;
}
.cd-post-meta {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 16px;
	font-size: 13.5px;
	color: #64748b;
}
.cd-post-meta span {
	display: inline-flex;
	align-items: center;
	gap: 6px;
}
.cd-featured-media {
	margin: 20px 0 30px;
	border-radius: 14px;
	overflow: hidden;
	box-shadow: 0 4px 16px rgba(0,0,0,0.06);
	background: #f8fafc;
	text-align: center;
}
.cd-featured-media img {
	width: 100%;
	max-height: 480px;
	object-fit: cover;
	display: block;
}
.cd-post-content {
	font-size: 16px;
	color: #334155;
	line-height: 1.8;
}
.cd-post-content h2 {
	font-size: clamp(1.4rem, 3vw, 1.9rem);
	font-weight: 800;
	color: #3A0F34;
	margin: 36px 0 14px;
	padding-bottom: 8px;
	border-bottom: 2px solid #f1f5f9;
}
.cd-post-content h3 {
	font-size: clamp(1.15rem, 2.5vw, 1.45rem);
	font-weight: 700;
	color: #3A0F34;
	margin: 26px 0 10px;
}
.cd-post-content p {
	margin-bottom: 18px;
}
.cd-post-content ul, .cd-post-content ol {
	margin: 0 0 20px 24px;
	padding: 0;
}
.cd-post-content li {
	margin-bottom: 8px;
}
.cd-ai-summary-box {
	background: #fdfafc;
	border: 1px solid #DDE3E8;
	border-left: 5px solid #3A0F34;
	border-radius: 12px;
	padding: 20px 22px;
	margin: 24px 0 30px;
	box-shadow: 0 2px 8px rgba(58,15,52,0.04);
}
.cd-ai-summary-box h4 {
	font-size: 15px;
	font-weight: 800;
	color: #3A0F34;
	text-transform: uppercase;
	letter-spacing: 0.6px;
	margin: 0 0 8px;
	display: flex;
	align-items: center;
	gap: 8px;
}
.cd-table-wrap {
	overflow-x: auto;
	margin: 26px 0 30px;
	border-radius: 12px;
	border: 1px solid #DDE3E8;
	box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.cd-pricing-table {
	width: 100%;
	border-collapse: collapse;
	font-size: 14.5px;
	text-align: left;
	background: #fff;
}
.cd-pricing-table th {
	background: #3A0F34;
	color: #ffffff;
	font-weight: 700;
	padding: 12px 16px;
	white-space: nowrap;
}
.cd-pricing-table td {
	padding: 12px 16px;
	border-bottom: 1px solid #f1f5f9;
	color: #334155;
}
.cd-pricing-table tr:last-child td {
	border-bottom: none;
}
.cd-pricing-table tr:hover td {
	background: #faf8fb;
}
.cd-highlight-price {
	font-weight: 800;
	color: #3A0F34;
}
.cd-bnpl-callout {
	background: linear-gradient(135deg, #3A0F34, #260a22);
	color: #ffffff;
	border-radius: 14px;
	padding: 24px 26px;
	margin: 36px 0;
	border: 1.5px solid #EAAA00;
	box-shadow: 0 6px 20px rgba(58,15,52,0.25);
	display: flex;
	flex-direction: column;
	gap: 14px;
}
.cd-bnpl-callout h3 {
	color: #EAAA00 !important;
	margin: 0 !important;
	font-size: 20px !important;
	font-weight: 800 !important;
	display: flex;
	align-items: center;
	gap: 10px;
}
.cd-bnpl-callout p {
	color: #e2e8f0 !important;
	margin: 0 !important;
	font-size: 14.5px !important;
	line-height: 1.6 !important;
}
.cd-bnpl-btn-group {
	display: flex;
	flex-wrap: wrap;
	gap: 12px;
	margin-top: 6px;
}
.cd-btn-primary {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	background: #EAAA00;
	color: #3A0F34 !important;
	font-weight: 800;
	font-size: 14px;
	padding: 12px 22px;
	border-radius: 8px;
	text-decoration: none !important;
	transition: all 0.2s ease;
	box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}
.cd-btn-primary:hover {
	background: #ffd043;
	transform: translateY(-1px);
}
.cd-btn-whatsapp {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	background: #16a34a;
	color: #ffffff !important;
	font-weight: 700;
	font-size: 14px;
	padding: 12px 22px;
	border-radius: 8px;
	text-decoration: none !important;
	transition: all 0.2s ease;
}
.cd-btn-whatsapp:hover {
	background: #15803d;
	transform: translateY(-1px);
}
.cd-faq-block {
	margin: 36px 0 20px;
}
.cd-faq-item {
	background: #ffffff;
	border: 1px solid #DDE3E8;
	border-radius: 10px;
	padding: 16px 20px;
	margin-bottom: 12px;
	cursor: pointer;
}
.cd-faq-item summary {
	font-weight: 700;
	color: #3A0F34;
	font-size: 15.5px;
	outline: none;
	display: flex;
	align-items: center;
	justify-content: space-between;
}
.cd-faq-item p {
	font-size: 14px;
	color: #475569;
	line-height: 1.65;
	margin: 12px 0 0;
}
.cd-author-box {
	background: #ffffff;
	border: 1px solid #DDE3E8;
	border-radius: 14px;
	padding: 22px;
	margin: 40px 0;
	display: flex;
	align-items: center;
	gap: 18px;
}
.cd-author-avatar {
	width: 64px;
	height: 64px;
	border-radius: 50%;
	background: #3A0F34;
	color: #EAAA00;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 26px;
	flex-shrink: 0;
}
.cd-author-info h4 {
	font-size: 16px;
	font-weight: 800;
	color: #3A0F34;
	margin: 0 0 4px;
}
.cd-author-info p {
	font-size: 13px;
	color: #64748b;
	line-height: 1.5;
	margin: 0;
}
.cd-related-deals {
	margin-top: 50px;
	border-top: 2px solid #DDE3E8;
	padding-top: 36px;
}
.cd-related-header {
	display: flex;
	justify-content: space-between;
	align-items: flex-end;
	margin-bottom: 22px;
}
.cd-related-header h3 {
	font-size: 20px;
	font-weight: 800;
	color: #3A0F34;
	margin: 0;
}
.cd-related-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
	gap: 18px;
}
.cd-mini-card {
	background: #ffffff;
	border: 1px solid #DDE3E8;
	border-radius: 12px;
	overflow: hidden;
	display: flex;
	flex-direction: column;
	transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.cd-mini-card:hover {
	transform: translateY(-3px);
	box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.cd-mini-thumb {
	position: relative;
	width: 100%;
	height: 170px;
	background: #f1f5f9;
	overflow: hidden;
}
.cd-mini-thumb img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.cd-mini-body {
	padding: 14px;
	display: flex;
	flex-direction: column;
	flex-grow: 1;
}
.cd-mini-title {
	font-size: 14px;
	font-weight: 700;
	color: #3A0F34;
	line-height: 1.35;
	margin: 0 0 8px;
}
.cd-mini-price {
	font-size: 15px;
	font-weight: 800;
	color: #3A0F34;
	margin-top: auto;
	margin-bottom: 10px;
}
.cd-mini-btn {
	display: block;
	text-align: center;
	background: #3A0F34;
	color: #ffffff !important;
	font-size: 12.5px;
	font-weight: 700;
	padding: 8px 12px;
	border-radius: 6px;
	text-decoration: none !important;
}
.cd-mini-btn:hover {
	background: #260a22;
}
</style>

<div class="cd-blog-container">
	<?php
	while ( have_posts() ) :
		the_post();
		$categories = get_the_category();
		$cat_name   = ! empty( $categories ) ? $categories[0]->name : 'Hair & Wigs Guide';
		$cat_link   = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : home_url( '/blog/' );
		?>

		<nav class="cd-breadcrumbs" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
			<span><i class="fa-solid fa-angle-right" style="font-size:10px;"></i></span>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
			<span><i class="fa-solid fa-angle-right" style="font-size:10px;"></i></span>
			<a href="<?php echo esc_url( $cat_link ); ?>"><?php echo esc_html( $cat_name ); ?></a>
			<span><i class="fa-solid fa-angle-right" style="font-size:10px;"></i></span>
			<span style="color:#334155; font-weight:600;"><?php echo esc_html( wp_trim_words( get_the_title(), 5, '...' ) ); ?></span>
		</nav>

		<header class="cd-post-header">
			<a href="<?php echo esc_url( $cat_link ); ?>" class="cd-post-category-badge">
				<?php echo esc_html( $cat_name ); ?>
			</a>
			<h1 class="cd-post-title"><?php the_title(); ?></h1>
			<div class="cd-post-meta">
				<span><i class="fa-regular fa-clock" style="color:#EAAA00;"></i> 5 min read</span>
				<span><i class="fa-solid fa-shield-halved" style="color:#16a34a;"></i> Verified by Clearance Deal Kenya</span>
				<span><i class="fa-solid fa-location-dot" style="color:#3A0F34;"></i> Nairobi, Kenya</span>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="cd-featured-media">
				<?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
			</div>
		<?php endif; ?>

		<div class="cd-post-content">
			<?php the_content(); ?>
		</div>

		<!-- Lipa Polepole Callout Banner -->
		<div class="cd-bnpl-callout">
			<h3><i class="fa-solid fa-hand-holding-dollar"></i> Buy with Lipa Polepole: Pay 50% Now &amp; Buy</h3>
			<p>
				Don't postpone looking your absolute best. Clearance Deal offers flexible <strong>Buy Now Pay Later</strong> across Nairobi and all 47 counties of Kenya. Pay 50% now and buy, and comfortably clear the remaining balance in easy monthly instalments via Safaricom M-Pesa.
			</p>
			<div class="cd-bnpl-btn-group">
				<a href="<?php echo esc_url( home_url( '/#deals-section' ) ); ?>" class="cd-btn-primary">
					<i class="fa-solid fa-bag-shopping"></i> Shop Clearance Hair Deals
				</a>
				<a href="https://wa.me/254751338975?text=<?php echo rawurlencode( 'Hello Clearance Deal, I am interested in: ' . get_the_title() ); ?>" target="_blank" rel="noopener" class="cd-btn-whatsapp">
					<i class="fa-brands fa-whatsapp"></i> WhatsApp Stylist (+254 751 338 975)
				</a>
			</div>
		</div>

		<!-- Author Box -->
		<div class="cd-author-box">
			<div class="cd-author-avatar">
				<i class="fa-solid fa-crown"></i>
			</div>
			<div class="cd-author-info">
				<h4>Clearance Deal Kenya Hair Specialists</h4>
				<p>
					Written &amp; verified by the certified beauty and hair care editorial team at Clearance Deal Kenya. Dispatched directly from Highlight Business Park, 4th Parklands, Nairobi. We specialize in 100% single-donor raw Indian hair, glueless HD lace front wigs, and liquidation clearance bargains across Kenya.
				</p>
			</div>
		</div>

		<!-- Related Featured Products -->
		<?php
		if ( function_exists( 'wc_get_products' ) ) {
			$related_products = wc_get_products( array(
				'status'  => 'publish',
				'limit'   => 4,
				'orderby' => 'rand',
			) );

			if ( ! empty( $related_products ) ) :
				?>
				<section class="cd-related-deals">
					<div class="cd-related-header">
						<div>
							<span style="font-size:11px; font-weight:800; color:#EAAA00; text-transform:uppercase; letter-spacing:0.8px; display:block; margin-bottom:4px;">In Stock in Nairobi</span>
							<h3>Featured Human Hair Deals &amp; Wigs</h3>
						</div>
						<a href="<?php echo esc_url( home_url( '/#deals-section' ) ); ?>" style="font-size:13px; font-weight:700; color:#3A0F34; text-decoration:none;">
							View All Deals <i class="fa-solid fa-arrow-right" style="color:#EAAA00;"></i>
						</a>
					</div>

					<div class="cd-related-grid">
						<?php
						foreach ( $related_products as $p ) :
							$p_img_id  = $p->get_image_id();
							$p_img_url = $p_img_id ? wp_get_attachment_image_url( $p_img_id, 'medium' ) : wc_placeholder_img_src();
							?>
							<div class="cd-mini-card">
								<a href="<?php echo esc_url( $p->get_permalink() ); ?>" class="cd-mini-thumb">
									<img src="<?php echo esc_url( $p_img_url ); ?>" alt="<?php echo esc_attr( $p->get_name() ); ?>" loading="lazy">
								</a>
								<div class="cd-mini-body">
									<h4 class="cd-mini-title">
										<a href="<?php echo esc_url( $p->get_permalink() ); ?>" style="color:#3A0F34; text-decoration:none;">
											<?php echo esc_html( wp_trim_words( $p->get_name(), 7, '...' ) ); ?>
										</a>
									</h4>
									<div class="cd-mini-price">
										KSh <?php echo number_format( (float) $p->get_price() ); ?>
									</div>
									<a href="<?php echo esc_url( $p->get_permalink() ); ?>" class="cd-mini-btn">
										View Deal <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>
				<?php
			endif;
		}
		?>

	<?php endwhile; ?>
</div>

<?php
get_footer();
