<?php
/**
 * Manual email sending from the order meta box.
 * No automatic emails are sent by this plugin.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Emails {

    public static function init(): void {
        add_action( 'wp_ajax_cpl_send_email',         [ __CLASS__, 'ajax_send_email' ] );
        add_action( 'wp_ajax_cpl_get_email_template', [ __CLASS__, 'ajax_get_template' ] );
    }

    // -------------------------------------------------------------------------
    // AJAX: return template subject + body for a given key.
    // -------------------------------------------------------------------------

    public static function ajax_get_template(): void {
        check_ajax_referer( 'cpl_update_agreement', 'nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error();
        }

        $order_id = absint( $_POST['order_id'] ?? 0 );
        $key      = sanitize_key( $_POST['template'] ?? 'plan_summary' );

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wp_send_json_error();
        }

        wp_send_json_success( self::get_template( $key, $order ) );
    }

    // -------------------------------------------------------------------------
    // AJAX: send email and log to order notes.
    // -------------------------------------------------------------------------

    public static function ajax_send_email(): void {
        check_ajax_referer( 'cpl_send_email', 'nonce' );

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Unauthorised.', 'clearance-pay-later' ) ] );
        }

        $order_id = absint( $_POST['order_id'] ?? 0 );
        $subject  = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
        $body     = wp_kses_post( wp_unslash( $_POST['body'] ?? '' ) );

        if ( ! $order_id || ! $subject || ! $body ) {
            wp_send_json_error( [ 'message' => __( 'Missing required fields.', 'clearance-pay-later' ) ] );
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            wp_send_json_error( [ 'message' => __( 'Order not found.', 'clearance-pay-later' ) ] );
        }

        $to = $order->get_billing_email();
        if ( ! $to ) {
            wp_send_json_error( [ 'message' => __( 'No email address for this customer.', 'clearance-pay-later' ) ] );
        }

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo( 'name' ) . ' <' . get_option( 'admin_email' ) . '>',
        ];

        $sent = wp_mail( $to, $subject, nl2br( $body ), $headers );

        if ( $sent ) {
            $order->add_order_note(
                sprintf(
                    /* translators: 1: email 2: subject 3: admin */
                    __( 'Pay Later email sent to %1$s. Subject: "%2$s". Sent by: %3$s.', 'clearance-pay-later' ),
                    $to,
                    $subject,
                    wp_get_current_user()->display_name
                )
            );
            $order->save();
            wp_send_json_success( [ 'message' => __( 'Email sent successfully.', 'clearance-pay-later' ) ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Email failed to send. Check your mail settings.', 'clearance-pay-later' ) ] );
        }
    }

    // -------------------------------------------------------------------------
    // Template helpers.
    // -------------------------------------------------------------------------

    /**
     * Return default template body for a given template key.
     */
    public static function get_template( string $key, \WC_Order $order ): array {
        $site  = get_bloginfo( 'name' );
        $first = $order->get_billing_first_name();

        $calc = [
            'total'              => (float) $order->get_meta( '_cpl_total_price' ),
            'deposit'            => (float) $order->get_meta( '_cpl_deposit_amount' ),
            'balance'            => (float) $order->get_meta( '_cpl_balance' ),
            'interest_amount'    => (float) $order->get_meta( '_cpl_interest_amount' ),
            'balance_repayable'  => (float) $order->get_meta( '_cpl_balance_repayable' ),
            'monthly_instalment' => (float) $order->get_meta( '_cpl_monthly_instalment' ),
            'months'             => (int)   $order->get_meta( '_cpl_months' ),
        ];

        switch ( $key ) {
            case 'plan_summary':
                return [
                    'subject' => sprintf( __( 'Your Pay Later Plan – Order #%s', 'clearance-pay-later' ), $order->get_order_number() ),
                    'body'    => sprintf(
                        __( "Dear %s,\n\nThank you for your order with %s.\n\nYour Pay Later plan summary:\n\nTotal price: %s\nDeposit paid today: %s\nBalance: %s\nInterest: %s\nBalance repayable: %s\nMonthly instalment: %s x %d months\n\nOur delivery person will bring your agreement documents. Please have your original ID ready to sign.\n\nThank you,\n%s Team", 'clearance-pay-later' ),
                        $first, $site,
                        CPL_Calculator::format_kes( $calc['total'] ),
                        CPL_Calculator::format_kes( $calc['deposit'] ),
                        CPL_Calculator::format_kes( $calc['balance'] ),
                        CPL_Calculator::format_kes( $calc['interest_amount'] ),
                        CPL_Calculator::format_kes( $calc['balance_repayable'] ),
                        CPL_Calculator::format_kes( $calc['monthly_instalment'] ),
                        $calc['months'],
                        $site
                    ),
                ];

            case 'instalment_reminder':
                return [
                    'subject' => sprintf( __( 'Instalment Payment Reminder – Order #%s', 'clearance-pay-later' ), $order->get_order_number() ),
                    'body'    => sprintf(
                        __( "Dear %s,\n\nThis is a friendly reminder that your instalment payment of %s is due soon for Order #%s.\n\nPlease make your payment at your earliest convenience.\n\nThank you,\n%s Team", 'clearance-pay-later' ),
                        $first,
                        CPL_Calculator::format_kes( $calc['monthly_instalment'] ),
                        $order->get_order_number(),
                        $site
                    ),
                ];

            default:
                return [
                    'subject' => sprintf( __( 'Message from %s – Order #%s', 'clearance-pay-later' ), $site, $order->get_order_number() ),
                    'body'    => sprintf( __( "Dear %s,\n\n", 'clearance-pay-later' ), $first ),
                ];
        }
    }
}
