<?php
/**
 * Plugin Name: Clearance Pay Later
 * Plugin URI:  https://clearance.co.ke
 * Description: Buy Now Pay Later – pay 50% deposit now, rest in 12 monthly instalments.
 * Version:     1.1.0
 * Author:      Clearance Kenya
 * Author URI:  https://clearance.co.ke
 * Text Domain: clearance-pay-later
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 11.1.0
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

define( 'CPL_VERSION',     '1.1.0' );
define( 'CPL_PLUGIN_FILE', __FILE__ );
define( 'CPL_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'CPL_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );

// Declare HPOS compatibility.
add_action( 'before_woocommerce_init', static function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            CPL_PLUGIN_FILE,
            true
        );
    }
} );

// Create DB table on activation.
register_activation_hook( CPL_PLUGIN_FILE, static function () {
    require_once CPL_PLUGIN_DIR . 'includes/class-cpl-database.php';
    CPL_Database::create_tables();
} );

add_action( 'plugins_loaded', static function () {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', static function () {
            printf(
                '<div class="error"><p>%s</p></div>',
                esc_html__( 'Clearance Pay Later requires WooCommerce to be active.', 'clearance-pay-later' )
            );
        } );
        return;
    }

    load_plugin_textdomain( 'clearance-pay-later', false, dirname( plugin_basename( CPL_PLUGIN_FILE ) ) . '/languages' );

    foreach ( [
        'class-cpl-database',
        'class-cpl-settings',
        'class-cpl-calculator',
        'class-cpl-order-status',
        'class-cpl-gate',
        'class-cpl-cart-popup',
        'class-cpl-checkout',
        'class-cpl-admin',
        'class-cpl-emails',
    ] as $class ) {
        require_once CPL_PLUGIN_DIR . "includes/{$class}.php";
    }

    CPL_Database::init();
    CPL_Order_Status::init();
    CPL_Gate::init();
    CPL_Cart_Popup::init();
    CPL_Checkout::init();
    CPL_Admin::init();
    CPL_Emails::init();
} );
