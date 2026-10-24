<?php
/**
 * Header mini-cart panel + cart icon button.
 * The JS injects the icon into .header-actions; the panel sits in the footer.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

$count       = function_exists( 'WC' ) ? WC()->cart->get_cart_contents_count() : 0;
$checkout_url = wc_get_checkout_url();
?>

<!-- Cart icon button (JS moves this into .header-actions) -->
<button id="cpl-cart-icon-btn"
        type="button"
        class="cpl-cart-icon-btn"
        aria-label="<?php esc_attr_e( 'Open cart', 'clearance-pay-later' ); ?>"
        aria-expanded="false"
        aria-controls="cpl-mini-cart-panel">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
         stroke-linejoin="round" aria-hidden="true" focusable="false">
        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
    </svg>
    <span class="cpl-cart-count" id="cpl-cart-count" aria-live="polite">
        <?php echo esc_html( $count ); ?>
    </span>
</button>

<!-- Mini-cart slide-in panel -->
<div id="cpl-mini-cart-panel"
     class="cpl-mini-cart-panel"
     role="dialog"
     aria-modal="true"
     aria-labelledby="cpl-mini-cart-title">

    <div class="cpl-mini-cart-inner">

        <div class="cpl-mini-cart-header">
            <h2 id="cpl-mini-cart-title" class="cpl-mini-cart-title">
                <?php esc_html_e( 'Your Cart', 'clearance-pay-later' ); ?>
            </h2>
            <button type="button"
                    class="cpl-mini-cart-close"
                    id="cpl-mini-cart-close"
                    aria-label="<?php esc_attr_e( 'Close cart', 'clearance-pay-later' ); ?>">
                &#x2715;
            </button>
        </div>

        <div class="cpl-mini-cart-body" id="cpl-mini-cart-body" aria-live="polite">
            <div class="cpl-mc-spinner" aria-hidden="true"></div>
        </div>

        <div class="cpl-mini-cart-footer" id="cpl-mini-cart-footer">
            <a href="<?php echo esc_url( $checkout_url ); ?>"
               class="cpl-btn cpl-btn-primary cpl-btn-full"
               id="cpl-mini-checkout-btn">
                <?php esc_html_e( 'Proceed to Checkout', 'clearance-pay-later' ); ?>
            </a>
        </div>

    </div><!-- /.cpl-mini-cart-inner -->
</div><!-- /#cpl-mini-cart-panel -->

<!-- Backdrop -->
<div id="cpl-mini-cart-backdrop" class="cpl-mini-cart-backdrop" aria-hidden="true"></div>
