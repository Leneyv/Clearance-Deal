<?php
/**
 * Admin: settings tab, order meta box, orders list column, phone log page.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Admin {

    public static function init(): void {
        // Settings tab.
        add_filter( 'woocommerce_settings_tabs_array',  [ __CLASS__, 'add_settings_tab' ], 60 );
        add_action( 'woocommerce_settings_tabs_cpl',    [ __CLASS__, 'render_settings' ] );
        add_action( 'woocommerce_update_options_cpl',   [ __CLASS__, 'save_settings' ] );

        // Admin assets.
        add_action( 'admin_enqueue_scripts',            [ __CLASS__, 'enqueue_assets' ] );

        // Orders list column.
        add_filter( 'manage_woocommerce_page_wc-orders_columns', [ __CLASS__, 'add_plan_column' ] );
        add_action( 'manage_woocommerce_page_wc-orders_custom_column', [ __CLASS__, 'render_plan_column' ], 10, 2 );
        // Legacy orders page.
        add_filter( 'manage_edit-shop_order_columns',   [ __CLASS__, 'add_plan_column' ] );
        add_action( 'manage_shop_order_posts_custom_column', [ __CLASS__, 'render_plan_column_legacy' ], 10, 2 );

        // Order meta box.
        add_action( 'add_meta_boxes',                   [ __CLASS__, 'add_order_meta_box' ] );
        add_action( 'wp_ajax_cpl_update_agreement',     [ __CLASS__, 'ajax_update_agreement' ] );
        add_action( 'wp_ajax_cpl_mark_instalment_paid', [ __CLASS__, 'ajax_mark_instalment_paid' ] );

        // Phone log menu.
        add_action( 'admin_menu',                       [ __CLASS__, 'add_phone_log_menu' ] );
        add_action( 'wp_ajax_cpl_export_phones_csv',    [ __CLASS__, 'export_phones_csv' ] );
    }

    // -------------------------------------------------------------------------
    // Settings tab.
    // -------------------------------------------------------------------------

    public static function add_settings_tab( array $tabs ): array {
        $tabs['cpl'] = __( 'Pay Later', 'clearance-pay-later' );
        return $tabs;
    }

    public static function render_settings(): void {
        woocommerce_admin_fields( self::get_settings_fields() );
    }

    public static function save_settings(): void {
        woocommerce_update_options( self::get_settings_fields() );
    }

    private static function get_settings_fields(): array {
        return [
            [
                'title' => __( 'Clearance Pay Later', 'clearance-pay-later' ),
                'type'  => 'title',
                'id'    => 'cpl_section_general',
            ],
            [
                'title'   => __( 'Deposit percentage', 'clearance-pay-later' ),
                'desc'    => __( '% of cart total charged today (default: 50)', 'clearance-pay-later' ),
                'id'      => 'cpl_deposit_pct',
                'type'    => 'number',
                'default' => '50',
                'custom_attributes' => [ 'min' => '1', 'max' => '99', 'step' => '1' ],
            ],
            [
                'title'   => __( 'Interest rate (%)', 'clearance-pay-later' ),
                'desc'    => __( 'Flat interest on the balance (default: 12)', 'clearance-pay-later' ),
                'id'      => 'cpl_interest_rate',
                'type'    => 'number',
                'default' => '12',
                'custom_attributes' => [ 'min' => '0', 'max' => '100', 'step' => '0.1' ],
            ],
            [
                'title'   => __( 'Number of instalments', 'clearance-pay-later' ),
                'id'      => 'cpl_months',
                'type'    => 'number',
                'default' => '12',
                'custom_attributes' => [ 'min' => '1', 'max' => '60', 'step' => '1' ],
            ],
            [
                'title'   => __( 'Eligibility check delay (seconds)', 'clearance-pay-later' ),
                'desc'    => __( 'How long the "Checking…" animation plays (default: 8)', 'clearance-pay-later' ),
                'id'      => 'cpl_check_delay',
                'type'    => 'number',
                'default' => '8',
                'custom_attributes' => [ 'min' => '1', 'max' => '30', 'step' => '1' ],
            ],
            [
                'title'   => __( 'Show popup', 'clearance-pay-later' ),
                'id'      => 'cpl_popup_frequency',
                'type'    => 'select',
                'default' => 'once_per_visit',
                'options' => [
                    'once_per_visit' => __( 'Once per browser session (closes popup until browser restart)', 'clearance-pay-later' ),
                    'every_page'     => __( 'On every page load', 'clearance-pay-later' ),
                ],
            ],
            [
                'type' => 'sectionend',
                'id'   => 'cpl_section_general',
            ],

            // --- Popup text ---
            [
                'title' => __( 'Popup Text', 'clearance-pay-later' ),
                'type'  => 'title',
                'id'    => 'cpl_section_text',
            ],
            [
                'title'   => __( 'Gate popup title', 'clearance-pay-later' ),
                'id'      => 'cpl_gate_title',
                'type'    => 'text',
                'default' => 'Check your Buy Now Pay Later eligibility',
            ],
            [
                'title'   => __( 'Gate popup subtitle', 'clearance-pay-later' ),
                'id'      => 'cpl_gate_subtitle',
                'type'    => 'text',
                'default' => 'Enter your Kenyan phone number to check instantly.',
            ],
            [
                'title'   => __( 'Eligibility message', 'clearance-pay-later' ),
                'id'      => 'cpl_eligible_message',
                'type'    => 'textarea',
                'default' => 'Congratulations! You are eligible. Pay only 50% now and the rest in 12 easy monthly instalments (12% interest). Calculated automatically at checkout.',
            ],
            [
                'title'   => __( 'Delivery notice', 'clearance-pay-later' ),
                'desc'    => __( 'Shown at checkout when customer selects Pay Later.', 'clearance-pay-later' ),
                'id'      => 'cpl_delivery_notice',
                'type'    => 'textarea',
                'default' => 'Our delivery person will bring the instalment agreement documents at delivery. Please have your original ID ready to sign.',
            ],
            [
                'type' => 'sectionend',
                'id'   => 'cpl_section_text',
            ],

            // --- URLs ---
            [
                'title' => __( 'Legal URLs', 'clearance-pay-later' ),
                'type'  => 'title',
                'id'    => 'cpl_section_urls',
            ],
            [
                'title'   => __( 'Terms page URL', 'clearance-pay-later' ),
                'id'      => 'cpl_terms_url',
                'type'    => 'url',
                'default' => '',
            ],
            [
                'title'   => __( 'Privacy page URL', 'clearance-pay-later' ),
                'id'      => 'cpl_privacy_url',
                'type'    => 'url',
                'default' => '',
            ],
            [
                'type' => 'sectionend',
                'id'   => 'cpl_section_urls',
            ],
        ];
    }

    // -------------------------------------------------------------------------
    // Admin assets.
    // -------------------------------------------------------------------------

    public static function enqueue_assets( string $hook ): void {
        $is_order = in_array( $hook, [ 'woocommerce_page_wc-orders', 'post.php', 'post-new.php' ], true );
        $is_log   = str_contains( $hook, 'cpl_phone_log' );

        if ( ! $is_order && ! $is_log ) {
            return;
        }

        wp_enqueue_style(  'cpl-admin', CPL_PLUGIN_URL . 'assets/css/admin.css', [], CPL_VERSION );
        wp_enqueue_script( 'cpl-admin', CPL_PLUGIN_URL . 'assets/js/admin.js', [ 'jquery' ], CPL_VERSION, true );
        wp_localize_script( 'cpl-admin', 'cplAdmin', [
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonceAgreement' => wp_create_nonce( 'cpl_update_agreement' ),
            'nonceInstalment'=> wp_create_nonce( 'cpl_mark_instalment_paid' ),
            'i18n'           => [
                'saving'    => __( 'Saving…', 'clearance-pay-later' ),
                'saved'     => __( 'Saved!', 'clearance-pay-later' ),
                'markPaid'  => __( 'Mark Paid', 'clearance-pay-later' ),
                'paid'      => __( 'Paid ✓', 'clearance-pay-later' ),
                'error'     => __( 'Error. Please try again.', 'clearance-pay-later' ),
            ],
        ] );
    }

    // -------------------------------------------------------------------------
    // Orders list "Plan" column.
    // -------------------------------------------------------------------------

    public static function add_plan_column( array $columns ): array {
        $columns['cpl_plan'] = __( 'Plan', 'clearance-pay-later' );
        return $columns;
    }

    public static function render_plan_column( string $column, $order ): void {
        if ( 'cpl_plan' !== $column ) {
            return;
        }
        if ( is_numeric( $order ) ) {
            $order = wc_get_order( $order );
        }
        self::print_plan_badge( $order );
    }

    public static function render_plan_column_legacy( string $column, int $post_id ): void {
        if ( 'cpl_plan' !== $column ) {
            return;
        }
        self::print_plan_badge( wc_get_order( $post_id ) );
    }

    private static function print_plan_badge( $order ): void {
        if ( ! $order ) {
            return;
        }
        $type = $order->get_meta( '_cpl_plan_type' );
        if ( 'deposit' === $type ) {
            echo '<span class="cpl-badge cpl-badge--deposit">' . esc_html__( '50% + 12 inst.', 'clearance-pay-later' ) . '</span>';
        } else {
            echo '<span class="cpl-badge cpl-badge--full">' . esc_html__( 'Full', 'clearance-pay-later' ) . '</span>';
        }
    }

    // -------------------------------------------------------------------------
    // Order meta box.
    // -------------------------------------------------------------------------

    public static function add_order_meta_box(): void {
        $screens = [ 'woocommerce_page_wc-orders', 'shop_order' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'cpl_order_meta_box',
                __( 'Pay Later Plan', 'clearance-pay-later' ),
                [ __CLASS__, 'render_order_meta_box' ],
                $screen,
                'normal',
                'default'
            );
        }
    }

    public static function render_order_meta_box( $post_or_order ): void {
        $order = $post_or_order instanceof \WC_Order
            ? $post_or_order
            : wc_get_order( $post_or_order->ID );

        if ( ! $order ) {
            return;
        }

        $plan_type = $order->get_meta( '_cpl_plan_type' );

        if ( 'deposit' !== $plan_type ) {
            echo '<p class="cpl-meta-none">' . esc_html__( 'Full payment – no instalment plan for this order.', 'clearance-pay-later' ) . '</p>';
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

        $agreement_status = $order->get_meta( '_cpl_agreement_status' ) ?: 'pending';
        $phone            = $order->get_meta( '_cpl_phone' );
        $schedule_raw     = $order->get_meta( '_cpl_schedule' );
        $schedule         = $schedule_raw ? json_decode( $schedule_raw, true ) : [];

        include CPL_PLUGIN_DIR . 'templates/admin-meta-box.php';
    }

    // -------------------------------------------------------------------------
    // AJAX: update agreement status.
    // -------------------------------------------------------------------------

    public static function ajax_update_agreement(): void {
        check_ajax_referer( 'cpl_update_agreement', 'nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorised.', 'clearance-pay-later' ) ] );
        }

        $order_id = absint( $_POST['order_id'] ?? 0 );
        $status   = sanitize_key( $_POST['agreement_status'] ?? '' );

        if ( ! $order_id || ! in_array( $status, [ 'pending', 'signed', 'defaulted' ], true ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid data.', 'clearance-pay-later' ) ] );
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wp_send_json_error( [ 'message' => __( 'Order not found.', 'clearance-pay-later' ) ] );
        }

        $order->update_meta_data( '_cpl_agreement_status', $status );
        $order->add_order_note(
            sprintf(
                /* translators: 1: status 2: admin user */
                __( 'Pay Later agreement status changed to "%1$s" by %2$s.', 'clearance-pay-later' ),
                $status,
                wp_get_current_user()->display_name
            )
        );
        $order->save();

        wp_send_json_success();
    }

    // -------------------------------------------------------------------------
    // AJAX: mark an instalment as paid.
    // -------------------------------------------------------------------------

    public static function ajax_mark_instalment_paid(): void {
        check_ajax_referer( 'cpl_mark_instalment_paid', 'nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorised.', 'clearance-pay-later' ) ] );
        }

        $order_id    = absint( $_POST['order_id'] ?? 0 );
        $instalment  = absint( $_POST['instalment'] ?? 0 );

        $order = wc_get_order( $order_id );
        if ( ! $order || ! $instalment ) {
            wp_send_json_error( [ 'message' => __( 'Invalid data.', 'clearance-pay-later' ) ] );
        }

        $schedule_raw = $order->get_meta( '_cpl_schedule' );
        $schedule     = $schedule_raw ? json_decode( $schedule_raw, true ) : [];

        $updated = false;
        foreach ( $schedule as &$item ) {
            if ( (int) $item['instalment'] === $instalment ) {
                $item['paid']      = true;
                $item['paid_date'] = current_time( 'Y-m-d' );
                $updated           = true;
                break;
            }
        }
        unset( $item );

        if ( ! $updated ) {
            wp_send_json_error( [ 'message' => __( 'Instalment not found.', 'clearance-pay-later' ) ] );
        }

        $order->update_meta_data( '_cpl_schedule', wp_json_encode( $schedule ) );
        $order->add_order_note(
            sprintf(
                /* translators: 1: instalment number 2: admin user */
                __( 'Instalment #%1$s marked as paid by %2$s.', 'clearance-pay-later' ),
                $instalment,
                wp_get_current_user()->display_name
            )
        );
        $order->save();

        wp_send_json_success( [ 'paid_date' => current_time( 'Y-m-d' ) ] );
    }

    // -------------------------------------------------------------------------
    // Phone log menu & page.
    // -------------------------------------------------------------------------

    public static function add_phone_log_menu(): void {
        add_submenu_page(
            'woocommerce',
            __( 'Pay Later Phone Log', 'clearance-pay-later' ),
            __( 'Phone Log', 'clearance-pay-later' ),
            'manage_woocommerce',
            'cpl_phone_log',
            [ __CLASS__, 'render_phone_log_page' ]
        );
    }

    public static function render_phone_log_page(): void {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( esc_html__( 'Not allowed.', 'clearance-pay-later' ) );
        }

        $per_page = 50;
        $page     = max( 1, absint( $_GET['paged'] ?? 1 ) );
        $data     = CPL_Database::get_phones( $per_page, $page );
        $rows     = $data['rows'];
        $total    = $data['total'];
        $pages    = (int) ceil( $total / $per_page );

        $export_url = wp_nonce_url(
            admin_url( 'admin-ajax.php?action=cpl_export_phones_csv' ),
            'cpl_export_csv'
        );

        include CPL_PLUGIN_DIR . 'templates/admin-phone-log.php';
    }

    public static function export_phones_csv(): void {
        check_ajax_referer( 'cpl_export_csv' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_die( esc_html__( 'Not allowed.', 'clearance-pay-later' ) );
        }

        $rows = CPL_Database::get_all_phones();

        header( 'Content-Type: text/csv; charset=UTF-8' );
        header( 'Content-Disposition: attachment; filename="cpl-phone-log-' . gmdate( 'Y-m-d' ) . '.csv"' );
        header( 'Pragma: no-cache' );

        $out = fopen( 'php://output', 'w' );
        fputcsv( $out, [ 'ID', 'Phone', 'IP', 'User Agent', 'User ID', 'Created At' ] );

        foreach ( $rows as $row ) {
            fputcsv( $out, [
                $row['id'],
                $row['phone'],
                $row['ip'],
                $row['user_agent'],
                $row['user_id'],
                $row['created_at'],
            ] );
        }

        fclose( $out );
        exit;
    }
}
