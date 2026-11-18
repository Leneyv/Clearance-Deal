<?php
/**
 * Template Name: Hair & Wigs Blog
 *
 * @package Clearance_Deal
 */

get_header();
?>

<style>
.cd-blog-hub {
	max-width: 1200px;
	margin: 0 auto;
	padding: 40px 20px 80px;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.cd-hub-header {
	text-align: center;
	margin-bottom: 36px;
}
.cd-hub-badge {
	display: inline-block;
	font-size: 12px;
	font-weight: 800;
	color: #EAAA00;
	background: #3A0F34;
	padding: 5px 14px;
	border-radius: 999px;
	text-transform: uppercase;
	letter-spacing: 0.8px;
	margin-bottom: 12px;
}
.cd-hub-title {
	font-size: clamp(1.8rem, 4vw, 2.8rem);
	font-weight: 800;
	color: #3A0F34;
	margin: 0 0 12px;
}
.cd-hub-sub {
	font-size: 15px;
	color: #64748b;
	max-width: 720px;
	margin: 0 auto;
	line-height: 1.6;
}
.cd-hub-categories {
	display: flex;
	justify-content: center;
	flex-wrap: wrap;
	gap: 10px;
	margin: 28px 0 36px;
}
.cd-hub-cat-link {
	font-size: 13px;
	font-weight: 700;
	color: #3A0F34;
	background: #ffffff;
	border: 1px solid #DDE3E8;
	padding: 8px 16px;
	border-radius: 999px;
	text-decoration: none;
	transition: all 0.2s ease;
}
.cd-hub-cat-link:hover, .cd-hub-cat-link.active {
	background: #3A0F34;
	color: #EAAA00;
	border-color: #3A0F34;
}
.cd-blog-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: 26px;
}
.cd-blog-card {
	background: #ffffff;
	border: 1px solid #DDE3E8;
	border-radius: 14px;
	overflow: hidden;
	display: flex;
	flex-direction: column;
	transition: transform 0.2s ease, box-shadow 0.2s ease;
	box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.cd-blog-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 10px 24px rgba(58,15,52,0.1);
	border-color: #cbd5e1;
}
.cd-card-thumb {
	position: relative;
	width: 100%;
	height: 200px;
	background: #f1f5f9;
	overflow: hidden;
}
.cd-card-thumb img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s ease;
}
.cd-blog-card:hover .cd-card-thumb img {
	transform: scale(1.04);
}
.cd-card-cat {
	position: absolute;
	top: 12px;
	left: 12px;
	background: rgba(58, 15, 52, 0.9);
	color: #EAAA00;
	font-size: 11px;
	font-weight: 800;
	padding: 4px 10px;
	border-radius: 999px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	backdrop-filter: blur(4px);
}
.cd-card-body {
	padding: 20px;
	display: flex;
	flex-direction: column;
	flex-grow: 1;
}
.cd-card-title {
	font-size: 16px;
	font-weight: 800;
	color: #3A0F34;
	line-height: 1.4;
	margin: 0 0 10px;
}
.cd-card-title a {
	color: #3A0F34;
	text-decoration: none;
}
.cd-card-title a:hover {
	color: #EAAA00;
}
.cd-card-excerpt {
	font-size: 13.5px;
	color: #64748b;
	line-height: 1.6;
	margin: 0 0 16px;
	display: -webkit-box;
	-webkit-line-clamp: 3;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
.cd-card-footer {
	margin-top: auto;
	display: flex;
	align-items: center;
	justify-content: space-between;
	font-size: 12.5px;
	color: #94a3b8;
	border-top: 1px solid #f1f5f9;
	padding-top: 12px;
}
.cd-card-btn {
	color: #3A0F34;
	font-weight: 700;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	gap: 4px;
}
.cd-card-btn:hover {
	color: #EAAA00;
}
.cd-pagination {
	margin-top: 48px;
	display: flex;
	justify-content: center;
	gap: 8px;
}
.cd-pagination .page-numbers {
	padding: 8px 16px;
	background: #ffffff;
	border: 1px solid #DDE3E8;
	color: #3A0F34;
	font-weight: 700;
	font-size: 14px;
	border-radius: 8px;
	text-decoration: none;
	transition: all 0.2s ease;
}
.cd-pagination .page-numbers.current, .cd-pagination .page-numbers:hover {
	background: #3A0F34;
	color: #EAAA00;
	border-color: #3A0F34;
}
</style>

<div class="cd-blog-hub">
	<header class="cd-hub-header">
		<span class="cd-hub-badge">Kenya Beauty &amp; Wigs Resource</span>
		<h1 class="cd-hub-title">Human Hair &amp; Wigs Guide: Prices, Styles &amp; Care in Kenya</h1>
		<p class="cd-hub-sub">
			Explore expert guides on 100% raw Indian virgin hair prices in Kenya, HD lace front wigs, glueless installation, Lipa Polepole (50% payment) via M-Pesa, and authentic hair verification.
		</p>

		<?php
		$cats = get_categories( array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'exclude'    => 1, // Exclude Uncategorized
		) );
		if ( ! empty( $cats ) ) :
			?>
			<div class="cd-hub-categories">
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="cd-hub-cat-link active">All Guides</a>
				<?php foreach ( $cats as $c ) : ?>
					<a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>" class="cd-hub-cat-link">
						<?php echo esc_html( $c->name ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</header>

	<?php
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$blog_query = new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 18,
		'paged'          => $paged,
	) );

	if ( $blog_query->have_posts() ) :
		?>
		<div class="cd-blog-grid">
			<?php
			while ( $blog_query->have_posts() ) :
				$blog_query->the_post();
				$categories = get_the_category();
				$cat_name   = ! empty( $categories ) ? $categories[0]->name : 'Hair Guide';
				$thumb_url  = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : 'https://clearance.co.ke/wp-content/uploads/2026/10/model-front-view-straight-raw-indian-hair-bundles-768x1024.jpg';
				?>
				<article class="cd-blog-card">
					<a href="<?php echo esc_url( get_permalink() ); ?>" class="cd-card-thumb">
						<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
						<span class="cd-card-cat"><?php echo esc_html( $cat_name ); ?></span>
					</a>
					<div class="cd-card-body">
						<h2 class="cd-card-title">
							<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
						</h2>
						<p class="cd-card-excerpt">
							<?php echo esc_html( get_the_excerpt() ); ?>
						</p>
						<div class="cd-card-footer">
							<span style="color:#16a34a; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Verified Guide</span>
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="cd-card-btn">
								Read Guide <i class="fa-solid fa-arrow-right"></i>
							</a>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="cd-pagination">
			<?php
			echo paginate_links( array(
				'total'        => $blog_query->max_num_pages,
				'current'      => $paged,
				'prev_text'    => '<i class="fa-solid fa-arrow-left"></i> Previous',
				'next_text'    => 'Next <i class="fa-solid fa-arrow-right"></i>',
				'type'         => 'plain',
			) );
			?>
		</div>
		<?php
		wp_reset_postdata();
	else :
		?>
		<p style="text-align:center; color:#64748b; font-size:16px;">Articles are being published. Check back shortly!</p>
	<?php endif; ?>
</div>

<?php
get_footer();
