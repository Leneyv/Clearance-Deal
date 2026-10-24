<?php
/**
 * Add-to-cart payment-choice modal, header mini-cart, and checkout remove.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Cart_Popup {

    public static function init(): void {
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
        add_action( 'wp_footer',          [ __CLASS__, 'render_footer_html' ] );

        // Plan choice AJAX.
        add_action( 'wp_ajax_cpl_save_plan_choice',        [ __CLASS__, 'ajax_save_choice' ] );
        add_action( 'wp_ajax_nopriv_cpl_save_plan_choice', [ __CLASS__, 'ajax_save_choice' ] );

        // Mini-cart AJAX.
        add_action( 'wp_ajax_cpl_get_mini_cart',        [ __CLASS__, 'ajax_get_mini_cart' ] );
        add_action( 'wp_ajax_nopriv_cpl_get_mini_cart', [ __CLASS__, 'ajax_get_mini_cart' ] );
        add_action( 'wp_ajax_cpl_remove_cart_item',        [ __CLASS__, 'ajax_remove_cart_item' ] );
        add_action( 'wp_ajax_nopriv_cpl_remove_cart_item', [ __CLASS__, 'ajax_remove_cart_item' ] );
        add_action( 'wp_ajax_cpl_update_cart_qty',        [ __CLASS__, 'ajax_update_cart_qty' ] );
        add_action( 'wp_ajax_nopriv_cpl_update_cart_qty', [ __CLASS__, 'ajax_update_cart_qty' ] );

        // Checkout: add remove button next to each item quantity.
        add_filter( 'woocommerce_checkout_cart_item_quantity', [ __CLASS__, 'add_checkout_remove_btn' ], 10, 3 );
    }

    // -------------------------------------------------------------------------
    // Assets
    // -------------------------------------------------------------------------

    public static function enqueue_assets(): void {
        if ( is_admin() ) {
            return;
        }

        wp_enqueue_script(
            'cpl-cart-popup',
            CPL_PLUGIN_URL . 'assets/js/cart-popup.js',
            [],
            CPL_VERSION,
            true
        );

        wp_localize_script( 'cpl-cart-popup', 'cplCart', [
            'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
            'nonce'        => wp_create_nonce( 'cpl_save_plan_choice' ),
            'cartNonce'    => wp_create_nonce( 'cpl_cart_action' ),
            'cartUrl'      => wc_get_cart_url(),
            'checkoutUrl'  => wc_get_checkout_url(),
            'isCheckout'   => is_checkout() ? 1 : 0,
            'siteUrl'      => home_url( '/' ),
            'depositPct'   => (float) CPL_Settings::get( 'deposit_pct' ),
            'interestRate' => (float) CPL_Settings::get( 'interest_rate' ),
            'months'       => (int) CPL_Settings::get( 'months' ),
            'currency'     => get_woocommerce_currency_symbol(),
            'i18n'         => [
                'fullTitle'    => __( 'Pay full amount', 'clearance-pay-later' ),
                'depositTitle' => __( 'Pay 50% now – Pay Later', 'clearance-pay-later' ),
                'depositDesc'  => sprintf(
                    __( '%1$d monthly instalments · %2$s%% interest on balance', 'clearance-pay-later' ),
                    (int) CPL_Settings::get( 'months' ),
                    (float) CPL_Settings::get( 'interest_rate' )
                ),
                'continueBtn' => __( 'Continue', 'clearance-pay-later' ),
                'title'       => __( 'Choose your payment plan', 'clearance-pay-later' ),
                'today'       => __( 'due today', 'clearance-pay-later' ),
                'monthly'     => __( '/month', 'clearance-pay-later' ),
                'yourCart'    => __( 'Your Cart', 'clearance-pay-later' ),
                'emptyCart'   => __( 'Your cart is empty.', 'clearance-pay-later' ),
                'checkout'    => __( 'Proceed to Checkout', 'clearance-pay-later' ),
                'removing'    => __( 'Removing…', 'clearance-pay-later' ),
            ],
        ] );
    }

    // -------------------------------------------------------------------------
    // Footer HTML: plan-choice modal + mini-cart panel + cart icon
    // -------------------------------------------------------------------------

    public static function render_footer_html(): void {
        if ( is_admin() ) {
            return;
        }
        include CPL_PLUGIN_DIR . 'templates/cart-popup.php';
        include CPL_PLUGIN_DIR . 'templates/mini-cart.php';
        // Variation selector modal for home-page Buy Now on variable products.
        ?>
        <div id="cpl-var-overlay" class="cpl-var-overlay" hidden role="dialog" aria-modal="true" aria-labelledby="cpl-var-title">
            <div class="cpl-var-modal">
                <button type="button" id="cpl-var-close" class="cpl-var-close" aria-label="<?php esc_attr_e( 'Close', 'clearance-pay-later' ); ?>">&times;</button>
                <h3 id="cpl-var-title" class="cpl-var-title"><?php esc_html_e( 'Choose your option', 'clearance-pay-later' ); ?></h3>
                <div id="cpl-var-list" class="cpl-var-list" role="group" aria-labelledby="cpl-var-title"></div>
                <div class="cpl-var-footer">
                    <button type="button" id="cpl-var-confirm" class="cpl-btn cpl-btn-primary"><?php esc_html_e( 'Continue', 'clearance-pay-later' ); ?></button>
                </div>
            </div>
        </div>
        <?php
    }

    // -------------------------------------------------------------------------
    // Checkout: append remove (×) button to item quantity cell
    // -------------------------------------------------------------------------

    public static function add_checkout_remove_btn( string $html, array $cart_item, string $cart_item_key ): string {
        $nonce = wp_create_nonce( 'cpl_cart_action' );
        $label = esc_attr__( 'Remove item', 'clearance-pay-later' );
        $btn   = '<button type="button" class="cpl-checkout-remove"'
               . ' data-key="' . esc_attr( $cart_item_key ) . '"'
               . ' data-nonce="' . esc_attr( $nonce ) . '"'
               . ' aria-label="' . $label . '"'
               . ' title="' . $label . '">&#x2715;</button>';
        return $html . $btn;
    }

    // -------------------------------------------------------------------------
    // AJAX: plan choice
    // -------------------------------------------------------------------------

    public static function ajax_save_choice(): void {
        check_ajax_referer( 'cpl_save_plan_choice', 'nonce' );

        $choice = isset( $_POST['choice'] ) ? sanitize_key( $_POST['choice'] ) : 'full';
        $choice = in_array( $choice, [ 'full', 'deposit' ], true ) ? $choice : 'full';

        if ( function_exists( 'WC' ) && WC()->session ) {
            WC()->session->set( 'cpl_plan_choice', $choice );
        }

        wp_send_json_success( [ 'choice' => $choice ] );
    }

    // -------------------------------------------------------------------------
    // AJAX: mini-cart HTML
    // -------------------------------------------------------------------------

    public static function ajax_get_mini_cart(): void {
        check_ajax_referer( 'cpl_cart_action', 'nonce' );

        wp_send_json_success( [
            'html'  => self::build_mini_cart_html(),
            'count' => WC()->cart->get_cart_contents_count(),
        ] );
    }

    // -------------------------------------------------------------------------
    // AJAX: remove cart item
    // -------------------------------------------------------------------------

    public static function ajax_remove_cart_item(): void {
        check_ajax_referer( 'cpl_cart_action', 'nonce' );

        $key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
        if ( ! $key ) {
            wp_send_json_error( [ 'message' => __( 'Invalid item.', 'clearance-pay-later' ) ] );
        }

        WC()->cart->remove_cart_item( $key );
        WC()->cart->calculate_totals();

        wp_send_json_success( [
            'html'  => self::build_mini_cart_html(),
            'count' => WC()->cart->get_cart_contents_count(),
        ] );
    }

    // -------------------------------------------------------------------------
    // AJAX: update cart item quantity
    // -------------------------------------------------------------------------

    public static function ajax_update_cart_qty(): void {
        check_ajax_referer( 'cpl_cart_action', 'nonce' );

        $key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
        $qty = isset( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 0;

        if ( ! $key ) {
            wp_send_json_error();
        }

        if ( $qty < 1 ) {
            WC()->cart->remove_cart_item( $key );
        } else {
            WC()->cart->set_quantity( $key, $qty, true );
        }
        WC()->cart->calculate_totals();

        wp_send_json_success( [
            'html'  => self::build_mini_cart_html(),
            'count' => WC()->cart->get_cart_contents_count(),
        ] );
    }

    // -------------------------------------------------------------------------
    // Build mini-cart items HTML (server-rendered, returned via AJAX)
    // -------------------------------------------------------------------------

    public static function build_mini_cart_html(): string {
        $items = WC()->cart->get_cart();

        if ( empty( $items ) ) {
            return '<p class="cpl-mc-empty">' . esc_html__( 'Your cart is empty.', 'clearance-pay-later' ) . '</p>';
        }

        $nonce = wp_create_nonce( 'cpl_cart_action' );
        $html  = '';

        foreach ( $items as $key => $item ) {
            /** @var WC_Product $product */
            $product = $item['data'];
            if ( ! $product || ! $product->exists() ) {
                continue;
            }

            $img      = $product->get_image( [ 60, 60 ], [ 'class' => 'cpl-mc-img' ] );
            $name     = $product->get_name();
            $qty      = (int) $item['quantity'];
            $subtotal = WC()->cart->get_product_subtotal( $product, $qty );

            $html .= '<div class="cpl-mc-item" data-key="' . esc_attr( $key ) . '">'
                   . '<div class="cpl-mc-img-wrap">' . $img . '</div>'
                   . '<div class="cpl-mc-info">'
                   .   '<p class="cpl-mc-name">' . esc_html( $name ) . '</p>'
                   .   '<div class="cpl-mc-row">'
                   .     '<div class="cpl-mc-qty-wrap">'
                   .       '<button class="cpl-mc-minus" data-key="' . esc_attr( $key ) . '" data-nonce="' . esc_attr( $nonce ) . '" aria-label="' . esc_attr__( 'Decrease', 'clearance-pay-later' ) . '">&#8722;</button>'
                   .       '<span class="cpl-mc-qty">' . esc_html( $qty ) . '</span>'
                   .       '<button class="cpl-mc-plus"  data-key="' . esc_attr( $key ) . '" data-nonce="' . esc_attr( $nonce ) . '" aria-label="' . esc_attr__( 'Increase', 'clearance-pay-later' ) . '">&#43;</button>'
                   .     '</div>'
                   .     '<span class="cpl-mc-subtotal">' . $subtotal . '</span>'
                   .   '</div>'
                   . '</div>'
                   . '<button class="cpl-mc-remove" data-key="' . esc_attr( $key ) . '" data-nonce="' . esc_attr( $nonce ) . '" aria-label="' . esc_attr__( 'Remove', 'clearance-pay-later' ) . '">&#x2715;</button>'
                   . '</div>';
        }

        $total = WC()->cart->get_cart_total();
        $html .= '<div class="cpl-mc-total-row"><span>' . esc_html__( 'Total', 'clearance-pay-later' ) . '</span><strong>' . $total . '</strong></div>';

        return $html;
    }

    // -------------------------------------------------------------------------
    // Plan choice helper
    // -------------------------------------------------------------------------

    public static function get_choice(): string {
        if ( class_exists( 'CPL_Checkout' ) && method_exists( 'CPL_Checkout', 'get_cart_bnpl_eligibility' ) ) {
            $eligibility = CPL_Checkout::get_cart_bnpl_eligibility();
            if ( ! $eligibility['can_bnpl'] ) {
                return 'full';
            }
        }

        if ( isset( $_COOKIE['cpl_plan_choice'] ) ) {
            $v = sanitize_key( wp_unslash( $_COOKIE['cpl_plan_choice'] ) );
            if ( in_array( $v, [ 'full', 'deposit' ], true ) ) {
                return $v;
            }
        }
        if ( function_exists( 'WC' ) && WC()->session ) {
            $v = WC()->session->get( 'cpl_plan_choice' );
            if ( $v && in_array( $v, [ 'full', 'deposit' ], true ) ) {
                return $v;
            }
        }
        return 'full';
    }
}
