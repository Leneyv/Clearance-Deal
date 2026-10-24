<?php
/**
 * Site gate: eligibility popup shown on every visit until customer completes the check.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Gate {

    public static function init(): void {
        // Gate popup disabled.
        add_action( 'wp_ajax_nopriv_cpl_save_phone', [ __CLASS__, 'ajax_save_phone' ] );
        add_action( 'wp_ajax_cpl_save_phone',        [ __CLASS__, 'ajax_save_phone' ] );
    }

    // -------------------------------------------------------------------------
    // Guard: decide whether to inject the gate on this request.
    // -------------------------------------------------------------------------

    public static function should_show(): bool {
        // WP-admin pages.
        if ( is_admin() ) {
            return false;
        }
        // Logged-in admin/shop-manager.
        if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ) {
            return false;
        }
        // WP REST API.
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return false;
        }
        // WP AJAX.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return false;
        }
        // WP Cron.
        if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
            return false;
        }
        // Payment callback/webhook paths (PesaPal, M-Pesa IPN, etc.).
        $uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        if ( preg_match( '/(wc-api|wc_api|ipn|notify|callback|webhook)/i', $uri ) ) {
            return false;
        }
        // Search engine bots.
        $ua = isset( $_SERVER['HTTP_USER_AGENT'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) ) : '';
        $bots = [ 'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
                  'yandexbot', 'sogou', 'exabot', 'facebot', 'ia_archiver',
                  'semrushbot', 'ahrefsbot', 'mj12bot', 'petalbot' ];
        foreach ( $bots as $bot ) {
            if ( str_contains( $ua, $bot ) ) {
                return false;
            }
        }
        return true;
    }

    public static function enqueue_assets(): void {
        if ( ! self::should_show() ) {
            return;
        }

        wp_enqueue_style(
            'cpl-frontend',
            CPL_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            CPL_VERSION
        );

        wp_enqueue_script(
            'cpl-gate',
            CPL_PLUGIN_URL . 'assets/js/gate-popup.js',
            [],
            CPL_VERSION,
            true   // footer
        );

        wp_localize_script( 'cpl-gate', 'cplGate', [
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( 'cpl_save_phone' ),
            'checkDelay'     => (int) CPL_Settings::get( 'check_delay' ),
            'popupFrequency' => CPL_Settings::get( 'popup_frequency' ),
            'homeUrl'        => esc_url( home_url( '/' ) ),
            'i18n'           => [
                'invalidPhone'    => __( 'Please enter a valid Kenyan phone number (e.g. 0712345678).', 'clearance-pay-later' ),
                'checking'        => __( 'Checking…', 'clearance-pay-later' ),
                'shopNow'         => __( 'Shop Now', 'clearance-pay-later' ),
                'checkBtn'        => __( 'Check', 'clearance-pay-later' ),
                'changeNumber'    => __( 'Change number', 'clearance-pay-later' ),
            ],
        ] );
    }

    public static function render_popup(): void {
        if ( ! self::should_show() ) {
            return;
        }

        $terms_url   = esc_url( CPL_Settings::get( 'terms_url' ) );
        $privacy_url = esc_url( CPL_Settings::get( 'privacy_url' ) );
        $title       = esc_html( CPL_Settings::get( 'gate_title' ) );
        $subtitle    = esc_html( CPL_Settings::get( 'gate_subtitle' ) );
        $eligible    = esc_html( CPL_Settings::get( 'eligible_message' ) );

        // Build notice text with optional links.
        $notice = sprintf(
            /* translators: 1: terms link 2: privacy link */
            __( 'By tapping Check you agree that we save your number and to our %1$s and %2$s.', 'clearance-pay-later' ),
            $terms_url   ? '<a href="' . $terms_url . '" target="_blank" rel="noopener">' . __( 'terms', 'clearance-pay-later' ) . '</a>' : __( 'terms', 'clearance-pay-later' ),
            $privacy_url ? '<a href="' . $privacy_url . '" target="_blank" rel="noopener">' . __( 'privacy policy', 'clearance-pay-later' ) . '</a>' : __( 'privacy policy', 'clearance-pay-later' )
        );

        include CPL_PLUGIN_DIR . 'templates/gate-popup.php';
    }

    // -------------------------------------------------------------------------
    // AJAX: save phone and return eligibility result.
    // -------------------------------------------------------------------------

    public static function ajax_save_phone(): void {
        check_ajax_referer( 'cpl_save_phone', 'nonce' );

        $raw   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $phone = CPL_Calculator::normalise_phone( $raw );

        if ( ! $phone ) {
            wp_send_json_error( [ 'message' => __( 'Invalid phone number.', 'clearance-pay-later' ) ] );
        }

        CPL_Database::save_phone( $phone );

        wp_send_json_success( [ 'phone' => $phone ] );
    }
}
