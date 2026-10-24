<?php
/**
 * Custom order status: Awaiting Agreement Signing.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Order_Status {

    const STATUS = 'wc-awaiting-sign';
    const SLUG   = 'awaiting-sign';

    public static function init(): void {
        add_action( 'init',                        [ __CLASS__, 'register_post_status' ] );
        add_filter( 'wc_order_statuses',           [ __CLASS__, 'add_to_order_statuses' ] );
        add_filter( 'woocommerce_reports_order_statuses', [ __CLASS__, 'add_to_reports' ] );
    }

    public static function register_post_status(): void {
        register_post_status(
            self::STATUS,
            [
                'label'                     => _x( 'Awaiting Agreement Signing', 'Order status', 'clearance-pay-later' ),
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                /* translators: %s: count */
                'label_count'               => _n_noop(
                    'Awaiting Agreement Signing <span class="count">(%s)</span>',
                    'Awaiting Agreement Signing <span class="count">(%s)</span>',
                    'clearance-pay-later'
                ),
            ]
        );
    }

    public static function add_to_order_statuses( array $statuses ): array {
        $statuses[ self::STATUS ] = _x( 'Awaiting Agreement Signing', 'Order status', 'clearance-pay-later' );
        return $statuses;
    }

    public static function add_to_reports( array $statuses ): array {
        $statuses[] = self::SLUG;
        return $statuses;
    }
}
