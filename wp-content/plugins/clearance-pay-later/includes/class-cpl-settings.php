<?php
/**
 * Centralised settings helper.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Settings {

    // Default values.
    const DEFAULTS = [
        'deposit_pct'        => 50,
        'interest_rate'      => 12,
        'months'             => 12,
        'check_delay'        => 8,
        'popup_frequency'    => 'once_per_visit',   // once_per_visit | every_page
        'terms_url'          => '',
        'privacy_url'        => '',
        'gate_title'         => 'Check your Buy Now Pay Later eligibility',
        'gate_subtitle'      => 'Enter your Kenyan phone number to check instantly.',
        'eligible_message'   => 'Congratulations! You are eligible. Pay only 50% now and the rest in 12 easy monthly instalments (12% interest). Calculated automatically at checkout.',
        'notice_text'        => 'By tapping Check you agree that we save your number and to our terms.',
        'delivery_notice'    => 'Our delivery person will bring the instalment agreement documents at delivery. Please have your original ID ready to sign.',
    ];

    public static function get( string $key, $fallback = null ) {
        $default = $fallback ?? ( self::DEFAULTS[ $key ] ?? '' );
        return get_option( 'cpl_' . $key, $default );
    }

    public static function get_int( string $key ): int {
        return (int) self::get( $key );
    }

    public static function get_float( string $key ): float {
        return (float) self::get( $key );
    }
}
