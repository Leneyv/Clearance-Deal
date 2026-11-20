<?php
/**
 * The template for displaying Category and Archive pages
 *
 * @package Clearance_Deal
 */

get_header();
$current_term = get_queried_object();
$term_name    = $current_term && ! is_wp_error( $current_term ) ? $current_term->name : 'Hair & Wigs Guides';
$term_desc    = $current_term && ! is_wp_error( $current_term ) ? $current_term->description : '';
?>

<style>
.cd-archive-container {
	max-width: 1200px;
	margin: 0 auto;
	padding: 40px 20px 80px;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.cd-archive-header {
	text-align: center;
	margin-bottom: 36px;
}
.cd-archive-badge {
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
.cd-archive-title {
	font-size: clamp(1.8rem, 4vw, 2.6rem);
	font-weight: 800;
	color: #3A0F34;
	margin: 0 0 12px;
}
.cd-archive-sub {
	font-size: 15px;
	color: #64748b;
	max-width: 720px;
	margin: 0 auto;
	line-height: 1.6;
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

<div class="cd-archive-container">
	<header class="cd-archive-header">
		<span class="cd-archive-badge">Topic Guides</span>
		<h1 class="cd-archive-title"><?php echo esc_html( $term_name ); ?></h1>
		<?php if ( ! empty( $term_desc ) ) : ?>
			<p class="cd-archive-sub"><?php echo esc_html( $term_desc ); ?></p>
		<?php else : ?>
			<p class="cd-archive-sub">Explore the latest articles, price breakdowns, and styling tips for <?php echo esc_html( $term_name ); ?> in Kenya.</p>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="cd-blog-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$categories = get_the_category();
				$cat_name   = ! empty( $categories ) ? $categories[0]->name : $term_name;
				$thumb_url  = has_post_thumbnail() ? ( get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) ?: get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) : 'https://clearance.co.ke/wp-content/uploads/2026/10/model-front-view-straight-raw-indian-hair-bundles-768x1024.jpg';
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
				'prev_text' => '<i class="fa-solid fa-arrow-left"></i> Previous',
				'next_text' => 'Next <i class="fa-solid fa-arrow-right"></i>',
				'type'      => 'plain',
			) );
			?>
		</div>
	<?php else : ?>
		<p style="text-align:center; color:#64748b; font-size:16px;">No articles found in this category.</p>
	<?php endif; ?>
</div>

<?php
get_footer();
