<?php
/**
 * The template to display the reviewers meta data (name, verified owner - no date)
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $comment;

if ( '0' === $comment->comment_approved ) { ?>

	<p class="meta">
		<em class="woocommerce-review__awaiting-approval">
			<?php esc_html_e( 'Your review is awaiting approval', 'woocommerce' ); ?>
		</em>
	</p>

<?php } else { ?>

	<p class="meta" style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
		<strong class="woocommerce-review__author" style="font-size:15px; color:#0f172a;"><?php comment_author(); ?></strong>
		<span class="woocommerce-review__verified" style="display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:700; color:#059669; background:#ecfdf5; padding:2px 8px; border-radius:999px;">
			<i class="fa-solid fa-circle-check" style="font-size:11px;"></i> Verified Buyer
		</span>
	</p>

	<?php
}
