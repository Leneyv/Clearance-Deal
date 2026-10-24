<?php
/**
 * Cart & checkout modifications: deposit fee, breakdown card, order meta.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Checkout {

    public static function init(): void {
        // Reduce cart total to deposit amount.
        add_action( 'woocommerce_cart_calculate_fees',          [ __CLASS__, 'apply_deposit_fee' ] );
        // Inline plan switcher (priority 10) + breakdown card (priority 20):
        // both use woocommerce_checkout_before_order_review_heading which fires
        // ONCE on the initial page load and is NOT part of the AJAX-updated
        // review-order table, so the card never duplicates.
        add_action( 'woocommerce_checkout_before_order_review_heading', [ __CLASS__, 'render_checkout_plan_switcher' ], 10 );
        add_action( 'woocommerce_checkout_before_order_review_heading', [ __CLASS__, 'render_checkout_breakdown' ],     20 );
        // AJAX: update plan cookie from checkout switcher.
        add_action( 'wp_ajax_cpl_checkout_set_plan',        [ __CLASS__, 'ajax_checkout_set_plan' ] );
        add_action( 'wp_ajax_nopriv_cpl_checkout_set_plan', [ __CLASS__, 'ajax_checkout_set_plan' ] );
        // Breakdown card on cart page only.
        add_action( 'woocommerce_before_cart_totals', [ __CLASS__, 'render_cart_breakdown' ] );
        // "Change number" link in cart.
        add_action( 'woocommerce_before_cart',                  [ __CLASS__, 'change_number_link' ] );
        // Delivery notice.
        add_action( 'woocommerce_review_order_after_payment',   [ __CLASS__, 'delivery_notice' ] );
        // Required instalment checkbox.
        add_action( 'woocommerce_review_order_before_submit',   [ __CLASS__, 'render_terms_checkbox' ] );
        add_action( 'woocommerce_checkout_process',             [ __CLASS__, 'validate_terms_checkbox' ] );
        // Save order meta.
        add_action( 'woocommerce_checkout_order_created',       [ __CLASS__, 'save_order_meta' ] );
        // Set "Awaiting Agreement Signing" status after deposit payment.
        add_action( 'woocommerce_payment_complete',             [ __CLASS__, 'set_awaiting_sign_status' ] );
        // My Account: show plan details on order page.
        add_action( 'woocommerce_order_details_after_order_table', [ __CLASS__, 'my_account_plan_details' ] );
    }

    // -------------------------------------------------------------------------
    // Checkout plan switcher: shown above the order review, lets the customer
    // pick or confirm their plan.  Changing it sets the cookie via AJAX and
    // triggers WooCommerce's live checkout update (update_checkout).
    // -------------------------------------------------------------------------

    /**
     * Check if a product is eligible for Buy Now Pay Later (Lipa Polepole).
     * Hair Consultation (ID: 250), virtual products, and service appointments require 100% full upfront payment.
     */
    public static function is_product_eligible_for_bnpl( int $product_id ): bool {
        if ( 250 === $product_id ) {
            return false;
        }

        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return false;
        }

        if ( $product->is_virtual() || $product->is_downloadable() ) {
            return false;
        }

        if ( stripos( $product->get_name(), 'consultation' ) !== false ) {
            return false;
        }

        return true;
    }

    /**
     * Compute cart BNPL eligibility and separate eligible vs non-eligible totals.
     */
    public static function get_cart_bnpl_eligibility(): array {
        if ( ! function_exists( 'WC' ) || ! WC()->cart || WC()->cart->is_empty() ) {
            return [
                'can_bnpl'        => true,
                'has_ineligible'  => false,
                'eligible_base'   => 0.0,
                'ineligible_base' => 0.0,
            ];
        }

        $has_eligible    = false;
        $has_ineligible  = false;
        $eligible_base   = 0.0;
        $ineligible_base = 0.0;

        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product_id = (int) ( ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'] );
            $parent_id  = (int) $cart_item['product_id'];
            $line_total = (float) $cart_item['line_total'] + (float) $cart_item['line_tax'];

            $is_eligible = self::is_product_eligible_for_bnpl( $parent_id ) && self::is_product_eligible_for_bnpl( $product_id );

            if ( $is_eligible ) {
                $has_eligible   = true;
                $eligible_base += $line_total;
            } else {
                $has_ineligible   = true;
                $ineligible_base += $line_total;
            }
        }

        $shipping = (float) WC()->cart->get_shipping_total() + (float) WC()->cart->get_shipping_tax();
        if ( $has_eligible ) {
            $eligible_base += $shipping;
        } else {
            $ineligible_base += $shipping;
        }

        return [
            'can_bnpl'        => $has_eligible,
            'has_ineligible'  => $has_ineligible,
            'eligible_base'   => round( $eligible_base, 2 ),
            'ineligible_base' => round( $ineligible_base, 2 ),
        ];
    }

    public static function render_checkout_plan_switcher(): void {
        if ( ! is_checkout() ) {
            return;
        }

        $eligibility = self::get_cart_bnpl_eligibility();

        // If the cart only contains consultation/ineligible products, BNPL cannot be used.
        if ( ! $eligibility['can_bnpl'] ) {
            ?>
            <div class="cpl-checkout-switcher cpl-checkout-switcher--full-only" id="cpl-checkout-switcher">
                <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:14px;">
                    <div style="width:38px; height:38px; border-radius:50%; background:#e11d48; color:#fff; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0;">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div>
                        <strong style="color:#9f1239; font-size:14px; display:block; margin-bottom:2px;">100% Upfront Booking Payment Required</strong>
                        <span style="font-size:13px; color:#475569; line-height:1.4;">
                            Certified Hair Consultation sessions require full payment (KSh 1,000) upon booking. Lipa Polepole (50% deposit) is available on hair bundles and wigs.
                        </span>
                    </div>
                </div>
            </div>
            <script>
            (function(){
                document.cookie = 'cpl_plan_choice=full; path=/; SameSite=Lax';
            }());
            </script>
            <?php
            return;
        }

        $current     = CPL_Cart_Popup::get_choice();
        $months      = (int) CPL_Settings::get( 'months' );
        $rate        = (float) CPL_Settings::get( 'interest_rate' );
        $deposit_pct = (int) CPL_Settings::get( 'deposit_pct' );
        $nonce       = wp_create_nonce( 'cpl_checkout_set_plan' );
        ?>
        <div class="cpl-checkout-switcher" id="cpl-checkout-switcher">
            <p class="cpl-checkout-switcher__label">
                <?php esc_html_e( 'Choose your payment plan:', 'clearance-pay-later' ); ?>
            </p>
            <div class="cpl-checkout-switcher__options">

                <label class="cpl-checkout-option <?php echo 'full' === $current ? 'cpl-checkout-option--active' : ''; ?>">
                    <input type="radio"
                           name="cpl_checkout_plan"
                           value="full"
                           <?php checked( $current, 'full' ); ?>
                           data-nonce="<?php echo esc_attr( $nonce ); ?>">
                    <span class="cpl-checkout-option__title">
                        <?php esc_html_e( 'Pay full amount', 'clearance-pay-later' ); ?>
                    </span>
                    <span class="cpl-checkout-option__sub">
                        <?php esc_html_e( 'Pay everything now', 'clearance-pay-later' ); ?>
                    </span>
                </label>

                <label class="cpl-checkout-option cpl-checkout-option--bnpl <?php echo 'deposit' === $current ? 'cpl-checkout-option--active' : ''; ?>">
                    <input type="radio"
                           name="cpl_checkout_plan"
                           value="deposit"
                           <?php checked( $current, 'deposit' ); ?>
                           data-nonce="<?php echo esc_attr( $nonce ); ?>">
                    <span class="cpl-checkout-option__badge">
                        <?php esc_html_e( 'Buy Now Pay Later', 'clearance-pay-later' ); ?>
                    </span>
                    <span class="cpl-checkout-option__title">
                        <?php printf(
                            /* translators: deposit % */
                            esc_html__( 'Pay %d%% now – Pay Later', 'clearance-pay-later' ),
                            $deposit_pct
                        ); ?>
                    </span>
                    <span class="cpl-checkout-option__sub">
                        <?php printf(
                            /* translators: 1: months 2: rate */
                            esc_html__( '%1$d monthly instalments · %2$s%% interest on balance', 'clearance-pay-later' ),
                            $months,
                            $rate
                        ); ?>
                    </span>
                </label>

            </div><!-- /.cpl-checkout-switcher__options -->
        </div><!-- /#cpl-checkout-switcher -->

        <script>
        (function(){
            var radios = document.querySelectorAll('input[name="cpl_checkout_plan"]');
            radios.forEach(function(r){
                r.addEventListener('change', function(){
                    var choice = this.value;
                    var nonce  = this.dataset.nonce;

                    // 1. Update cookie immediately so PHP gets it on the next request.
                    document.cookie = 'cpl_plan_choice=' + encodeURIComponent(choice) +
                                      '; path=/; SameSite=Lax';

                    // 2. Update card active state.
                    document.querySelectorAll('.cpl-checkout-option').forEach(function(el){
                        el.classList.remove('cpl-checkout-option--active');
                    });
                    this.closest('.cpl-checkout-option').classList.add('cpl-checkout-option--active');

                    // 3. Persist in WC session too via AJAX.
                    var fd = new FormData();
                    fd.append('action', 'cpl_checkout_set_plan');
                    fd.append('nonce',  nonce);
                    fd.append('choice', choice);
                    fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
                        method: 'POST', body: fd, credentials: 'same-origin'
                    }).catch(function(){});

                    // 4. Tell WooCommerce to recalculate totals (fires woocommerce_cart_calculate_fees).
                    if (window.jQuery) {
                        jQuery(document.body).trigger('update_checkout');
                    }
                });
            });
        }());
        </script>
        <?php
    }

    /**
     * AJAX: set plan choice from the checkout page switcher.
     * Sets both WC session and cookie header.
     */
    public static function ajax_checkout_set_plan(): void {
        check_ajax_referer( 'cpl_checkout_set_plan', 'nonce' );

        $choice = isset( $_POST['choice'] ) ? sanitize_key( $_POST['choice'] ) : 'full';
        $choice = in_array( $choice, [ 'full', 'deposit' ], true ) ? $choice : 'full';

        // WC session.
        if ( function_exists( 'WC' ) && WC()->session ) {
            WC()->session->set( 'cpl_plan_choice', $choice );
        }

        // Cookie – ensures PHP reads the right value even if WC session is stale.
        wc_setcookie( 'cpl_plan_choice', $choice );

        wp_send_json_success( [ 'choice' => $choice ] );
    }

    // -------------------------------------------------------------------------
    // Deposit fee: reduces order total to deposit % of the base amount.
    // -------------------------------------------------------------------------

    public static function apply_deposit_fee(): void {
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] ) {
            return;
        }

        if ( 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }

        $eligible_base = $eligibility['eligible_base'];
        if ( $eligible_base <= 0 ) {
            return;
        }

        $deposit_pct = (float) CPL_Settings::get( 'deposit_pct' ) / 100;
        $deposit     = round( $eligible_base * $deposit_pct, 2 );
        $balance     = round( $eligible_base - $deposit, 2 );

        if ( $balance <= 0 ) {
            return;
        }

        // Non-taxable negative fee reduces what gateways (M-Pesa) charge.
        WC()->cart->add_fee(
            sprintf(
                /* translators: balance amount */
                __( 'Pay Later Balance – KES %s (due at completion)', 'clearance-pay-later' ),
                number_format( $balance, 2 )
            ),
            -$balance,
            false
        );
    }

    // -------------------------------------------------------------------------
    // Breakdown card.
    // -------------------------------------------------------------------------

    public static function render_cart_breakdown(): void {
        if ( ! is_cart() ) {
            return;
        }
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] || 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }

        $total = self::get_cart_base_total();
        if ( $total <= 0 ) {
            return;
        }

        $calc = CPL_Calculator::calculate( $total );
        include CPL_PLUGIN_DIR . 'templates/cart-breakdown.php';
    }

    public static function render_checkout_breakdown(): void {
        if ( ! is_checkout() ) {
            return;
        }
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] || 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }

        $total = self::get_cart_base_total();
        if ( $total <= 0 ) {
            return;
        }

        $calc = CPL_Calculator::calculate( $total );
        $context = 'checkout';
        include CPL_PLUGIN_DIR . 'templates/cart-breakdown.php';
    }

    // -------------------------------------------------------------------------

    public static function change_number_link(): void {
        $phone = isset( $_COOKIE['cpl_phone'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['cpl_phone'] ) ) : '';
        if ( ! $phone ) {
            return;
        }
        echo '<div class="cpl-change-number-notice">'
            . esc_html__( 'Buy Now Pay Later active for ', 'clearance-pay-later' )
            . '<strong>' . esc_html( substr( $phone, 0, 4 ) . '****' . substr( $phone, -4 ) ) . '</strong> &nbsp;'
            . '<a href="#" id="cpl-change-number-link">' . esc_html__( 'Change number', 'clearance-pay-later' ) . '</a>'
            . '</div>';
    }

    public static function delivery_notice(): void {
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] || 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }
        $notice = CPL_Settings::get( 'delivery_notice' );
        echo '<div class="cpl-delivery-notice"><span class="cpl-notice-icon">&#9432;</span> ' . esc_html( $notice ) . '</div>';
    }

    public static function render_terms_checkbox(): void {
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] || 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }

        $months = (int) CPL_Settings::get( 'months' );
        $rate   = (float) CPL_Settings::get( 'interest_rate' );

        woocommerce_form_field( 'cpl_terms_agreed', [
            'type'     => 'checkbox',
            'class'    => [ 'cpl-terms-check form-row-wide' ],
            'label'    => sprintf(
                /* translators: 1: months, 2: rate */
                __( 'I have read and agree to the instalment terms (%1$d monthly instalments, %2$s%% interest on the balance).', 'clearance-pay-later' ),
                $months,
                $rate
            ),
            'required' => true,
        ], WC()->checkout->get_value( 'cpl_terms_agreed' ) );
    }

    public static function validate_terms_checkbox(): void {
        $eligibility = self::get_cart_bnpl_eligibility();
        if ( ! $eligibility['can_bnpl'] || 'deposit' !== CPL_Cart_Popup::get_choice() ) {
            return;
        }

        if ( empty( $_POST['cpl_terms_agreed'] ) ) {
            wc_add_notice(
                __( 'You must agree to the Pay Later instalment terms to complete your order.', 'clearance-pay-later' ),
                'error'
            );
        }
    }

    // -------------------------------------------------------------------------
    // Order meta.
    // -------------------------------------------------------------------------

    public static function save_order_meta( \WC_Order $order ): void {
        $eligibility = self::get_cart_bnpl_eligibility();
        $choice      = $eligibility['can_bnpl'] ? CPL_Cart_Popup::get_choice() : 'full';
        $order->update_meta_data( '_cpl_plan_type', $choice );

        if ( 'deposit' !== $choice || ! $eligibility['can_bnpl'] ) {
            $order->save();
            return;
        }

        $total = self::get_cart_base_total();
        $calc  = CPL_Calculator::calculate( $total );
        $sched = CPL_Calculator::generate_schedule( $calc );

        $phone = isset( $_COOKIE['cpl_phone'] )
            ? sanitize_text_field( wp_unslash( $_COOKIE['cpl_phone'] ) )
            : '';
        if ( ! $phone && method_exists( $order, 'get_billing_phone' ) ) {
            $phone = $order->get_billing_phone();
        }

        $order->update_meta_data( '_cpl_total_price',         $calc['total'] );
        $order->update_meta_data( '_cpl_deposit_pct',         $calc['deposit_pct'] );
        $order->update_meta_data( '_cpl_deposit_amount',      $calc['deposit'] );
        $order->update_meta_data( '_cpl_balance',             $calc['balance'] );
        $order->update_meta_data( '_cpl_interest_rate',       $calc['interest_rate'] );
        $order->update_meta_data( '_cpl_interest_amount',     $calc['interest_amount'] );
        $order->update_meta_data( '_cpl_balance_repayable',   $calc['balance_repayable'] );
        $order->update_meta_data( '_cpl_monthly_instalment',  $calc['monthly_instalment'] );
        $order->update_meta_data( '_cpl_last_instalment',     $calc['last_instalment'] );
        $order->update_meta_data( '_cpl_months',              $calc['months'] );
        $order->update_meta_data( '_cpl_schedule',            wp_json_encode( $sched ) );
        $order->update_meta_data( '_cpl_phone',               $phone );
        $order->update_meta_data( '_cpl_agreement_status',    'pending' );
        $order->update_meta_data( '_cpl_terms_agreed',        ! empty( $_POST['cpl_terms_agreed'] ) ? 1 : 0 );

        $order->save();

        // Clear the plan choice so the next visit starts fresh.
        if ( WC()->session ) {
            WC()->session->set( 'cpl_plan_choice', 'full' );
        }
        // Expire the cookie too.
        wc_setcookie( 'cpl_plan_choice', '', time() - HOUR_IN_SECONDS );
    }

    public static function set_awaiting_sign_status( int $order_id ): void {
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }

        if ( 'deposit' === $order->get_meta( '_cpl_plan_type' ) ) {
            $order->update_status(
                CPL_Order_Status::STATUS,
                __( 'Deposit payment received. Awaiting agreement signing at delivery.', 'clearance-pay-later' )
            );
        }
    }

    // -------------------------------------------------------------------------
    // My Account: plan details on order view.
    // -------------------------------------------------------------------------

    public static function my_account_plan_details( \WC_Order $order ): void {
        if ( 'deposit' !== $order->get_meta( '_cpl_plan_type' ) ) {
            return;
        }

        $calc = [
            'total'              => (float) $order->get_meta( '_cpl_total_price' ),
            'deposit'            => (float) $order->get_meta( '_cpl_deposit_amount' ),
            'balance'            => (float) $order->get_meta( '_cpl_balance' ),
            'interest_rate'      => (float) $order->get_meta( '_cpl_interest_rate' ),
            'interest_amount'    => (float) $order->get_meta( '_cpl_interest_amount' ),
            'balance_repayable'  => (float) $order->get_meta( '_cpl_balance_repayable' ),
            'monthly_instalment' => (float) $order->get_meta( '_cpl_monthly_instalment' ),
            'months'             => (int)   $order->get_meta( '_cpl_months' ),
        ];

        $schedule_raw = $order->get_meta( '_cpl_schedule' );
        $schedule     = $schedule_raw ? json_decode( $schedule_raw, true ) : [];

        include CPL_PLUGIN_DIR . 'templates/my-account-plan.php';
    }

    // -------------------------------------------------------------------------

    public static function get_cart_base_total(): float {
        $eligibility = self::get_cart_bnpl_eligibility();
        return $eligibility['can_bnpl'] ? $eligibility['eligible_base'] : 0.0;
    }
}
