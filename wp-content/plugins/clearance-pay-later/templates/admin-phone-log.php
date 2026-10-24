<?php
/**
 * Phone log admin page template.
 * Variables: $rows, $total, $page, $pages, $export_url
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php esc_html_e( 'Pay Later Phone Log', 'clearance-pay-later' ); ?>
    </h1>
    <a href="<?php echo esc_url( $export_url ); ?>" class="page-title-action">
        <?php esc_html_e( 'Export CSV', 'clearance-pay-later' ); ?>
    </a>
    <hr class="wp-header-end">

    <p>
        <?php printf(
            /* translators: total */
            esc_html__( 'Total entries: %d', 'clearance-pay-later' ),
            (int) $total
        ); ?>
    </p>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width:5%"><?php esc_html_e( 'ID', 'clearance-pay-later' ); ?></th>
                <th style="width:16%"><?php esc_html_e( 'Phone', 'clearance-pay-later' ); ?></th>
                <th style="width:14%"><?php esc_html_e( 'IP Address', 'clearance-pay-later' ); ?></th>
                <th><?php esc_html_e( 'User Agent', 'clearance-pay-later' ); ?></th>
                <th style="width:8%"><?php esc_html_e( 'User ID', 'clearance-pay-later' ); ?></th>
                <th style="width:16%"><?php esc_html_e( 'Date', 'clearance-pay-later' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if ( $rows ) : ?>
            <?php foreach ( $rows as $row ) : ?>
            <tr>
                <td><?php echo esc_html( $row['id'] ); ?></td>
                <td><?php echo esc_html( $row['phone'] ); ?></td>
                <td><?php echo esc_html( $row['ip'] ); ?></td>
                <td style="word-break:break-all;font-size:11px">
                    <?php echo esc_html( $row['user_agent'] ); ?>
                </td>
                <td>
                    <?php if ( $row['user_id'] ) :
                        $user = get_userdata( $row['user_id'] );
                        echo $user ? esc_html( $user->user_login ) : esc_html( $row['user_id'] );
                    else :
                        esc_html_e( 'Guest', 'clearance-pay-later' );
                    endif; ?>
                </td>
                <td><?php echo esc_html( $row['created_at'] ); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6"><?php esc_html_e( 'No entries yet.', 'clearance-pay-later' ); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if ( $pages > 1 ) : ?>
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <?php
            echo paginate_links( [
                'base'      => add_query_arg( 'paged', '%#%' ),
                'format'    => '',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'total'     => $pages,
                'current'   => $page,
            ] );
            ?>
        </div>
    </div>
    <?php endif; ?>

</div>
