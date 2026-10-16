<?php
/**
 * Instalment breakdown card – used on both cart and checkout.
 * Variables available: $calc (array), $context ('cart' | 'checkout')
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

$context = $context ?? 'cart';
?>

<div class="cpl-breakdown-card" role="region" aria-label="<?php esc_attr_e( 'Pay Later breakdown', 'clearance-pay-later' ); ?>">

    <div class="cpl-breakdown-header">
        <span class="cpl-breakdown-icon" aria-hidden="true">📋</span>
        <h3 class="cpl-breakdown-title">
            <?php esc_html_e( 'Your Pay Later Plan', 'clearance-pay-later' ); ?>
        </h3>
    </div>

    <dl class="cpl-breakdown-rows">

        <div class="cpl-breakdown-row">
            <dt><?php esc_html_e( 'Total price', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['total'] ) ); ?></dd>
        </div>

        <div class="cpl-breakdown-row cpl-breakdown-row--highlight">
            <dt><?php esc_html_e( 'Payment due today', 'clearance-pay-later' ); ?></dt>
            <dd class="cpl-breakdown-deposit">
                <?php echo esc_html( CPL_Calculator::format_kes( $calc['deposit'] ) ); ?>
            </dd>
        </div>

        <div class="cpl-breakdown-row">
            <dt><?php esc_html_e( 'Balance', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['balance'] ) ); ?></dd>
        </div>

        <div class="cpl-breakdown-row">
            <dt>
                <?php printf(
                    /* translators: interest rate % */
                    esc_html__( 'Interest (%s%%)', 'clearance-pay-later' ),
                    esc_html( number_format( $calc['interest_rate'], 1 ) )
                ); ?>
            </dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['interest_amount'] ) ); ?></dd>
        </div>

        <div class="cpl-breakdown-row cpl-breakdown-row--sub">
            <dt><?php esc_html_e( 'Balance repayable', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['balance_repayable'] ) ); ?></dd>
        </div>

        <div class="cpl-breakdown-row cpl-breakdown-row--instalment">
            <dt>
                <strong>
                <?php printf(
                    /* translators: number of months */
                    esc_html__( 'Monthly instalment × %d', 'clearance-pay-later' ),
                    (int) $calc['months']
                ); ?>
                </strong>
            </dt>
            <dd>
                <strong>
                <?php echo esc_html( CPL_Calculator::format_kes( $calc['monthly_instalment'] ) ); ?>
                <span class="cpl-per-month"><?php esc_html_e( '/month', 'clearance-pay-later' ); ?></span>
                </strong>
            </dd>
        </div>

        <div class="cpl-breakdown-row cpl-breakdown-row--total">
            <dt><?php esc_html_e( 'Total repayable', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['total'] + $calc['interest_amount'] ) ); ?></dd>
        </div>

        <div class="cpl-breakdown-row cpl-breakdown-row--highlight cpl-breakdown-row--due-today-bottom">
            <dt><?php esc_html_e( 'Payment due today', 'clearance-pay-later' ); ?></dt>
            <dd class="cpl-breakdown-deposit">
                <?php echo esc_html( CPL_Calculator::format_kes( $calc['deposit'] ) ); ?>
            </dd>
        </div>

    </dl>

    <p class="cpl-breakdown-note cpl-breakdown-note--green">
        <?php esc_html_e( 'You are charged only 50% of the total amount. The balance is paid over 12 Months, our delivery personal will come with forms to fill please keep your ID ready.', 'clearance-pay-later' ); ?>
    </p>

</div>
