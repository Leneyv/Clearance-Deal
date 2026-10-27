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
				<p>Genuine human hair, wigs and extensions at clearance prices. Delivered across Kenya.</p>
				<div class="footer-mpesa">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mpesa-logo.svg' ); ?>" alt="M-Pesa accepted">
					<span><?php esc_html_e( 'M-Pesa accepted', 'clearance-deal' ); ?></span>
				</div>
			</div>

			<div class="footer-links">
				<h4>Company</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> About Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Contact Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Careers &amp; Jobs</a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Terms &amp; Conditions</a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Privacy Policy</a></li>
					<li><a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Refund Policy</a></li>
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

		<div class="footer-bottom" style="justify-content: center; text-align: center;">
			<div class="site-info">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>. All rights reserved.
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php
if ( file_exists( get_template_directory() . '/inc/bnpl-floating-widget.php' ) ) {
	require_once get_template_directory() . '/inc/bnpl-floating-widget.php';
}
?>

<?php wp_footer(); ?>

</body>
</html>
