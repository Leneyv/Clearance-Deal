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
				<h2 style="font-size:16px;margin-bottom:16px">Company &amp; Guides</h2>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="color:#EAAA00; font-weight:700;"><i class="fa-solid fa-book-open"></i> Hair &amp; Wigs Blog (All Guides)</a></li>
					<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> About Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Contact Us</a></li>
					<li><a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Careers &amp; Jobs</a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Terms &amp; Conditions</a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Privacy Policy</a></li>
					<li><a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>"><i class="fa-solid fa-angle-right"></i> Refund Policy</a></li>
				</ul>
			</div>

			<div class="footer-newsletter">
				<h2 style="font-size:16px;margin-bottom:16px">Alert Sign-up</h2>
				<p>Subscribe to our newsletter to receive real-time alerts on the deepest clearance price cuts!</p>
				<form class="newsletter-form" action="#" method="post" onsubmit="event.preventDefault(); alert('Subscribed to deal alerts!');">
					<input type="email" placeholder="Your email address..." required>
					<button type="submit" aria-label="Subscribe to deal alerts"><i class="fa-solid fa-paper-plane"></i></button>
				</form>
			</div>
		</div>

		<div class="footer-bottom" style="justify-content: center; text-align: center;">
			<div class="site-info">
				&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>. All rights reserved. &bull; <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="color:#EAAA00; font-weight:700; text-decoration:none;"><i class="fa-solid fa-book-open" style="font-size:12px;"></i> Hair &amp; Wigs Blog Hub (All Guides)</a>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php
// Do not show BNPL floating widget on the Jobs page (https://clearance.co.ke/jobs/)
$is_jobs_page = is_page( 'jobs' ) || is_page( 251 ) || is_page_template( 'page-jobs.php' );
if ( ! $is_jobs_page && ! empty( $_SERVER['REQUEST_URI'] ) ) {
	$request_path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( 'jobs' === $request_path || 0 === strpos( $request_path, 'jobs/' ) ) {
		$is_jobs_page = true;
	}
}

if ( ! $is_jobs_page && file_exists( get_template_directory() . '/inc/bnpl-floating-widget.php' ) ) {
	require_once get_template_directory() . '/inc/bnpl-floating-widget.php';
}
?>

<?php wp_footer(); ?>

</body>
</html>
