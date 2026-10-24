<?php
/**
 * Add-to-cart payment-choice modal template.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="cpl-cart-overlay"
     class="cpl-cart-overlay"
     role="dialog"
     aria-modal="true"
     aria-labelledby="cpl-cart-modal-title"
     hidden>

    <div class="cpl-cart-modal" role="document">

        <h2 id="cpl-cart-modal-title" class="cpl-cart-modal-title">
            <?php esc_html_e( 'Choose your payment plan', 'clearance-pay-later' ); ?>
        </h2>

        <fieldset class="cpl-plan-fieldset">
            <legend class="cpl-sr-only">
                <?php esc_html_e( 'Payment plan options', 'clearance-pay-later' ); ?>
            </legend>

            <!-- Option 1: Full payment -->
            <label class="cpl-plan-option" id="cpl-plan-option-full">
                <input type="radio"
                       name="cpl_plan"
                       value="full"
                       class="cpl-plan-radio"
                       checked
                       aria-describedby="cpl-full-desc">
                <span class="cpl-plan-content">
                    <span class="cpl-plan-label">
                        <?php esc_html_e( 'Pay full amount', 'clearance-pay-later' ); ?>
                    </span>
                    <span class="cpl-plan-price" id="cpl-full-price">KES –</span>
                    <span class="cpl-plan-sub" id="cpl-full-desc">
                        <?php esc_html_e( 'Pay everything now', 'clearance-pay-later' ); ?>
                    </span>
                </span>
            </label>

            <!-- Option 2: Deposit (Pay Later) -->
            <label class="cpl-plan-option cpl-plan-option--bnpl" id="cpl-plan-option-deposit">
                <input type="radio"
                       name="cpl_plan"
                       value="deposit"
                       class="cpl-plan-radio"
                       aria-describedby="cpl-deposit-desc">
                <span class="cpl-plan-content">
                    <span class="cpl-plan-badge"><?php esc_html_e( 'Buy Now Pay Later', 'clearance-pay-later' ); ?></span>
                    <span class="cpl-plan-label">
                        <?php esc_html_e( 'Pay 50% now – Pay Later', 'clearance-pay-later' ); ?>
                    </span>
                    <span class="cpl-plan-price" id="cpl-deposit-price">KES –</span>
                    <span class="cpl-plan-sub" id="cpl-deposit-desc">
                        <?php esc_html_e( 'then 12 monthly instalments', 'clearance-pay-later' ); ?>
                    </span>
                    <span class="cpl-plan-instalment" id="cpl-instalment-line"></span>
                </span>
            </label>

        </fieldset>

        <button type="button" id="cpl-cart-continue-btn" class="cpl-btn cpl-btn-primary cpl-btn-full">
            <?php esc_html_e( 'Continue', 'clearance-pay-later' ); ?>
        </button>

    </div><!-- /.cpl-cart-modal -->
</div><!-- /#cpl-cart-overlay -->
