<?php
/**
 * My Account > Order: instalment plan details.
 * Variables: $calc (array), $schedule (array), $order (WC_Order)
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="cpl-myaccount-plan" aria-label="<?php esc_attr_e( 'Pay Later plan', 'clearance-pay-later' ); ?>">

    <h2 class="cpl-myaccount-title">
        <?php esc_html_e( 'Your Pay Later Plan', 'clearance-pay-later' ); ?>
    </h2>

    <dl class="cpl-breakdown-rows">
        <div class="cpl-breakdown-row">
            <dt><?php esc_html_e( 'Total price', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['total'] ) ); ?></dd>
        </div>
        <div class="cpl-breakdown-row cpl-breakdown-row--highlight">
            <dt><?php esc_html_e( 'Deposit paid', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['deposit'] ) ); ?></dd>
        </div>
        <div class="cpl-breakdown-row">
            <dt><?php esc_html_e( 'Balance', 'clearance-pay-later' ); ?></dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['balance'] ) ); ?></dd>
        </div>
        <div class="cpl-breakdown-row">
            <dt>
                <?php printf(
                    /* translators: interest rate */
                    esc_html__( 'Interest (%s%%)', 'clearance-pay-later' ),
                    esc_html( number_format( $calc['interest_rate'], 1 ) )
                ); ?>
            </dt>
            <dd><?php echo esc_html( CPL_Calculator::format_kes( $calc['interest_amount'] ) ); ?></dd>
        </div>
        <div class="cpl-breakdown-row cpl-breakdown-row--instalment">
            <dt>
                <strong>
                <?php printf(
                    /* translators: months */
                    esc_html__( 'Monthly instalment × %d', 'clearance-pay-later' ),
                    (int) $calc['months']
                ); ?>
                </strong>
            </dt>
            <dd>
                <strong><?php echo esc_html( CPL_Calculator::format_kes( $calc['monthly_instalment'] ) ); ?></strong>
            </dd>
        </div>
    </dl>

    <?php if ( ! empty( $schedule ) ) : ?>
    <h3 class="cpl-schedule-title">
        <?php esc_html_e( 'Instalment Schedule', 'clearance-pay-later' ); ?>
    </h3>
    <table class="cpl-schedule-table shop_table">
        <thead>
            <tr>
                <th><?php esc_html_e( '#', 'clearance-pay-later' ); ?></th>
                <th><?php esc_html_e( 'Due Date', 'clearance-pay-later' ); ?></th>
                <th><?php esc_html_e( 'Amount', 'clearance-pay-later' ); ?></th>
                <th><?php esc_html_e( 'Status', 'clearance-pay-later' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $schedule as $item ) : ?>
            <tr class="<?php echo $item['paid'] ? 'cpl-row-paid' : ''; ?>">
                <td><?php echo esc_html( $item['instalment'] ); ?></td>
                <td><?php echo esc_html( $item['due_date'] ); ?></td>
                <td><?php echo esc_html( CPL_Calculator::format_kes( $item['amount'] ) ); ?></td>
                <td>
                    <?php if ( $item['paid'] ) : ?>
                        <span class="cpl-status-paid">&#10003; <?php esc_html_e( 'Paid', 'clearance-pay-later' ); ?></span>
                    <?php else : ?>
                        <span class="cpl-status-pending"><?php esc_html_e( 'Pending', 'clearance-pay-later' ); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</section>
