<?php
/**
 * Site gate eligibility popup template.
 * Variables: $title, $subtitle, $eligible, $notice (HTML)
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

$logo_url = function_exists( 'get_custom_logo' )
    ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )
    : false;
$logo_src = $logo_url ? $logo_url[0] : '';
?>

<div id="cpl-gate-overlay"
     class="cpl-gate-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="cpl-gate-title"
     aria-describedby="cpl-gate-subtitle">

    <div class="cpl-gate-modal" role="document">

        <button type="button" id="cpl-gate-close" class="cpl-gate-close" aria-label="<?php esc_attr_e( 'Close and continue browsing', 'clearance-pay-later' ); ?>">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if ( $logo_src ) : ?>
        <div class="cpl-gate-logo" aria-hidden="true">
            <img src="<?php echo esc_url( $logo_src ); ?>"
                 alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                 width="160" height="auto">
        </div>
        <?php endif; ?>

        <?php /* ---- SCREEN 1: Phone entry ---- */ ?>
        <div class="cpl-gate-screen" id="cpl-screen-phone" aria-live="polite">

            <h2 id="cpl-gate-title" class="cpl-gate-title">
                <?php echo esc_html( $title ); ?>
            </h2>
            <p id="cpl-gate-subtitle" class="cpl-gate-subtitle">
                <?php echo esc_html( $subtitle ); ?>
            </p>

            <div class="cpl-field-group">
                <label for="cpl-phone-input" class="cpl-sr-only">
                    <?php esc_html_e( 'Kenyan phone number', 'clearance-pay-later' ); ?>
                </label>
                <div class="cpl-phone-wrap">
                    <span class="cpl-phone-prefix" aria-hidden="true">+254</span>
                    <input
                        type="tel"
                        id="cpl-phone-input"
                        name="cpl_phone"
                        class="cpl-phone-input"
                        inputmode="numeric"
                        autocomplete="tel"
                        placeholder="<?php esc_attr_e( '7XXXXXXXX', 'clearance-pay-later' ); ?>"
                        maxlength="13"
                        aria-required="true"
                        aria-describedby="cpl-phone-error cpl-phone-notice"
                    >
                </div>
                <p class="cpl-field-error" id="cpl-phone-error" role="alert" aria-live="assertive" hidden></p>
                <p class="cpl-field-notice" id="cpl-phone-notice">
                    <?php echo wp_kses( $notice, [ 'a' => [ 'href' => [], 'target' => [], 'rel' => [] ] ] ); ?>
                </p>
            </div>

            <button type="button" id="cpl-check-btn" class="cpl-btn cpl-btn-primary">
                <?php esc_html_e( 'Check', 'clearance-pay-later' ); ?>
            </button>

        </div><!-- /#cpl-screen-phone -->

        <?php /* ---- SCREEN 2: Checking ---- */ ?>
        <div class="cpl-gate-screen" id="cpl-screen-checking" hidden aria-live="polite" aria-label="<?php esc_attr_e( 'Checking eligibility', 'clearance-pay-later' ); ?>">
            <div class="cpl-checking-wrap" aria-hidden="true">
                <div class="cpl-spinner"></div>
            </div>
            <p class="cpl-checking-text">
                <?php esc_html_e( 'Checking eligibility…', 'clearance-pay-later' ); ?>
            </p>
            <div class="cpl-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div class="cpl-progress-fill" id="cpl-progress-fill"></div>
            </div>
        </div><!-- /#cpl-screen-checking -->

        <?php /* ---- SCREEN 3: Eligible result ---- */ ?>
        <div class="cpl-gate-screen" id="cpl-screen-result" hidden aria-live="polite">
            <div class="cpl-result-icon" aria-hidden="true">✓</div>
            <h2 class="cpl-result-title">
                <?php esc_html_e( 'You\'re eligible!', 'clearance-pay-later' ); ?>
            </h2>
            <p class="cpl-result-message">
                <?php echo esc_html( $eligible ); ?>
            </p>
            <button type="button" id="cpl-shop-btn" class="cpl-btn cpl-btn-primary cpl-btn-success">
                <?php esc_html_e( 'Shop Now', 'clearance-pay-later' ); ?>
            </button>
        </div><!-- /#cpl-screen-result -->

    </div><!-- /.cpl-gate-modal -->
</div><!-- /#cpl-gate-overlay -->

<noscript>
    <style>
        #cpl-gate-overlay { display: none !important; }
    </style>
</noscript>
