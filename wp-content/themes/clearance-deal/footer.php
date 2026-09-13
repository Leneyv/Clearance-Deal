<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Clearance_Deal
 */

?>
	<footer id="colophon" class="site-footer">
		<div class="footer-container">
			<div class="footer-brand">
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-white.svg' ); ?>"
					alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
					class="footer-logo"
					width="340"
					height="64">
				<p>Your ultimate destination for finding the absolute best deals, discount coupons, clearance sales, and price drops from around the web. Save big, every day.</p>
			</div>

			<div class="footer-links">
				<h4>Popular Stores</h4>
				<ul>
					<li><a href="#store-amazon"><i class="fa-solid fa-angle-right"></i> Amazon Deals</a></li>
					<li><a href="#store-walmart"><i class="fa-solid fa-angle-right"></i> Walmart Clearance</a></li>
					<li><a href="#store-bestbuy"><i class="fa-solid fa-angle-right"></i> Best Buy Price Drops</a></li>
					<li><a href="#store-target"><i class="fa-solid fa-angle-right"></i> Target Redcard Specials</a></li>
				</ul>
			</div>

			<div class="footer-links">
				<h4>Quick Navigation</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Home Feed</a></li>
					<li><a href="#featured-deals"><i class="fa-solid fa-angle-right"></i> Featured Deals</a></li>
					<li><a href="#expired-deals"><i class="fa-solid fa-angle-right"></i> Expired Archive</a></li>
					<li><a href="#submit-deal"><i class="fa-solid fa-angle-right"></i> Submit a Deal</a></li>
				</ul>
			</div>

			<div class="footer-newsletter">
				<h4>Alert Sign-up</h4>
				<p>Subscribe to our newsletter to receive real-time alerts on the deepest clearance price cuts!</p>
				<form class="newsletter-form" action="#" method="post" onsubmit="event.preventDefault(); alert('Subscribed to deal alerts!');">
					<input type="email" placeholder="Your email address..." required>
					<button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
				</form>
			</div>
		</div>

		<div class="footer-bottom">
			<div class="site-info">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>. All rights reserved.
			</div>
			<div class="footer-social">
				<a href="https://www.facebook.com/clearance.deal.kenya" target="_blank" rel="noopener noreferrer" style="margin-left: 1rem;"><i class="fa-brands fa-facebook-f"></i></a>
				<a href="#" style="margin-left: 1rem;"><i class="fa-brands fa-twitter"></i></a>
				<a href="#" style="margin-left: 1rem;"><i class="fa-brands fa-instagram"></i></a>
				<a href="#" style="margin-left: 1rem;"><i class="fa-brands fa-telegram"></i></a>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
