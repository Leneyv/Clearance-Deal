<?php
/**
 * The template for displaying all pages
 *
 * @package Clearance_Deal
 */

get_header();
?>

<main class="main-content page-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-header">
				<h1 class="section-title"><?php the_title(); ?></h1>
			</header>

			<div class="page-body">
				<?php
				if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url() ) {
					clearance_deal_checkout_quantity_editor();
				}
				the_content();
				?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
