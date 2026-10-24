<?php
/**
 * Admin order meta box template.
 * Variables: $order, $calc, $agreement_status, $phone, $schedule
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

$order_id = $order->get_id();
?>

<div class="cpl-metabox" data-order-id="<?php echo esc_attr( $order_id ); ?>">

    <?php /* ---- Plan summary ---- */ ?>
    <div class="cpl-metabox-section">
        <h4 class="cpl-metabox-section-title">
            <?php esc_html_e( 'Plan Summary', 'clearance-pay-later' ); ?>
        </h4>
        <table class="cpl-meta-table">
            <tr>
                <th><?php esc_html_e( 'Plan type', 'clearance-pay-later' ); ?></th>
                <td><?php esc_html_e( '50% Deposit + 12 Instalments', 'clearance-pay-later' ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Phone', 'clearance-pay-later' ); ?></th>
                <td><?php echo $phone ? esc_html( $phone ) : '—'; ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Total price', 'clearance-pay-later' ); ?></th>
                <td><?php echo esc_html( CPL_Calculator::format_kes( $calc['total'] ) ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Deposit paid today', 'clearance-pay-later' ); ?></th>
                <td><strong><?php echo esc_html( CPL_Calculator::format_kes( $calc['deposit'] ) ); ?></strong></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Balance', 'clearance-pay-later' ); ?></th>
                <td><?php echo esc_html( CPL_Calculator::format_kes( $calc['balance'] ) ); ?></td>
            </tr>
            <tr>
                <th>
                    <?php printf(
                        /* translators: rate */
                        esc_html__( 'Interest (%s%%)', 'clearance-pay-later' ),
                        esc_html( $calc['interest_rate'] )
                    ); ?>
                </th>
                <td><?php echo esc_html( CPL_Calculator::format_kes( $calc['interest_amount'] ) ); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Balance repayable', 'clearance-pay-later' ); ?></th>
                <td><?php echo esc_html( CPL_Calculator::format_kes( $calc['balance_repayable'] ) ); ?></td>
            </tr>
            <tr>
                <th>
                    <?php printf(
                        /* translators: months */
                        esc_html__( 'Monthly instalment × %d', 'clearance-pay-later' ),
                        (int) $calc['months']
                    ); ?>
                </th>
                <td><strong><?php echo esc_html( CPL_Calculator::format_kes( $calc['monthly_instalment'] ) ); ?></strong></td>
            </tr>
        </table>
    </div>

    <?php /* ---- Agreement status ---- */ ?>
    <div class="cpl-metabox-section">
        <h4 class="cpl-metabox-section-title">
            <?php esc_html_e( 'Agreement Status', 'clearance-pay-later' ); ?>
        </h4>
        <div class="cpl-agreement-row">
            <label for="cpl-agreement-status" class="cpl-sr-only">
                <?php esc_html_e( 'Agreement status', 'clearance-pay-later' ); ?>
            </label>
            <select id="cpl-agreement-status" class="cpl-select" data-order-id="<?php echo esc_attr( $order_id ); ?>">
                <option value="pending"   <?php selected( $agreement_status, 'pending' ); ?>>
                    <?php esc_html_e( 'Pending', 'clearance-pay-later' ); ?>
                </option>
                <option value="signed"    <?php selected( $agreement_status, 'signed' ); ?>>
                    <?php esc_html_e( 'Signed', 'clearance-pay-later' ); ?>
                </option>
                <option value="defaulted" <?php selected( $agreement_status, 'defaulted' ); ?>>
                    <?php esc_html_e( 'Defaulted', 'clearance-pay-later' ); ?>
                </option>
            </select>
            <button type="button" class="button cpl-save-agreement-btn" data-order-id="<?php echo esc_attr( $order_id ); ?>">
                <?php esc_html_e( 'Save', 'clearance-pay-later' ); ?>
            </button>
            <span class="cpl-agreement-saved" aria-live="polite" hidden>&#10003;</span>
        </div>
    </div>

    <?php /* ---- Instalment schedule ---- */ ?>
    <?php if ( ! empty( $schedule ) ) : ?>
    <div class="cpl-metabox-section">
        <h4 class="cpl-metabox-section-title">
            <?php esc_html_e( 'Instalment Schedule', 'clearance-pay-later' ); ?>
        </h4>
        <table class="cpl-schedule-table widefat striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php esc_html_e( 'Due Date', 'clearance-pay-later' ); ?></th>
                    <th><?php esc_html_e( 'Amount', 'clearance-pay-later' ); ?></th>
                    <th><?php esc_html_e( 'Status', 'clearance-pay-later' ); ?></th>
                    <th><?php esc_html_e( 'Action', 'clearance-pay-later' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ( $schedule as $item ) : ?>
                <tr id="cpl-row-<?php echo esc_attr( $item['instalment'] ); ?>"
                    class="<?php echo $item['paid'] ? 'cpl-row-paid' : ''; ?>">
                    <td><?php echo esc_html( $item['instalment'] ); ?></td>
                    <td>
                        <span class="cpl-due-date" data-instalment="<?php echo esc_attr( $item['instalment'] ); ?>">
                            <?php echo esc_html( $item['due_date'] ); ?>
                        </span>
                    </td>
                    <td><?php echo esc_html( CPL_Calculator::format_kes( $item['amount'] ) ); ?></td>
                    <td>
                        <?php if ( $item['paid'] ) : ?>
                            <span class="cpl-status-paid">
                                &#10003; <?php esc_html_e( 'Paid', 'clearance-pay-later' ); ?>
                                <?php if ( $item['paid_date'] ) : ?>
                                    <small>(<?php echo esc_html( $item['paid_date'] ); ?>)</small>
                                <?php endif; ?>
                            </span>
                        <?php else : ?>
                            <span class="cpl-status-pending">
                                <?php esc_html_e( 'Pending', 'clearance-pay-later' ); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ( ! $item['paid'] ) : ?>
                            <button type="button"
                                    class="button button-small cpl-mark-paid-btn"
                                    data-order-id="<?php echo esc_attr( $order_id ); ?>"
                                    data-instalment="<?php echo esc_attr( $item['instalment'] ); ?>">
                                <?php esc_html_e( 'Mark Paid', 'clearance-pay-later' ); ?>
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php /* ---- Send email ---- */ ?>
    <div class="cpl-metabox-section">
        <h4 class="cpl-metabox-section-title">
            <?php esc_html_e( 'Send Email to Customer', 'clearance-pay-later' ); ?>
        </h4>

        <div class="cpl-email-wrap">
            <label for="cpl-email-template"><?php esc_html_e( 'Template', 'clearance-pay-later' ); ?></label>
            <select id="cpl-email-template" class="cpl-select" data-order-id="<?php echo esc_attr( $order_id ); ?>">
                <option value="plan_summary">
                    <?php esc_html_e( 'Plan Summary', 'clearance-pay-later' ); ?>
                </option>
                <option value="instalment_reminder">
                    <?php esc_html_e( 'Instalment Reminder', 'clearance-pay-later' ); ?>
                </option>
                <option value="custom">
                    <?php esc_html_e( 'Custom Message', 'clearance-pay-later' ); ?>
                </option>
            </select>

            <label for="cpl-email-subject"><?php esc_html_e( 'Subject', 'clearance-pay-later' ); ?></label>
            <input type="text" id="cpl-email-subject" class="widefat cpl-email-subject">

            <label for="cpl-email-body"><?php esc_html_e( 'Message', 'clearance-pay-later' ); ?></label>
            <textarea id="cpl-email-body" rows="8" class="widefat cpl-email-body"></textarea>

            <button type="button"
                    class="button button-primary cpl-send-email-btn"
                    data-order-id="<?php echo esc_attr( $order_id ); ?>">
                <?php esc_html_e( 'Send Email', 'clearance-pay-later' ); ?>
            </button>
            <span class="cpl-email-status" aria-live="polite"></span>
        </div>
    </div>

</div><!-- /.cpl-metabox -->
