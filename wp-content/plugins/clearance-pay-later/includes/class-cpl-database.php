<?php
/**
 * Database operations: phone log table.
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Database {

    const TABLE_VERSION_KEY = 'cpl_db_version';
    const TABLE_VERSION     = '1';

    public static function init(): void {
        // Upgrade table on version bump.
        if ( get_option( self::TABLE_VERSION_KEY ) !== self::TABLE_VERSION ) {
            self::create_tables();
        }
    }

    public static function create_tables(): void {
        global $wpdb;

        $table      = $wpdb->prefix . 'cpl_phones';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id          bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            phone       varchar(20)         NOT NULL,
            ip          varchar(45)         NOT NULL DEFAULT '',
            user_agent  text                NOT NULL,
            user_id     bigint(20) unsigned NOT NULL DEFAULT 0,
            created_at  datetime            NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY phone (phone),
            KEY created_at (created_at)
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        update_option( self::TABLE_VERSION_KEY, self::TABLE_VERSION );
    }

    /**
     * Save a phone entry.
     *
     * @param string $phone Normalised phone (2547XXXXXXXXX).
     * @return int|false Inserted row ID or false on failure.
     */
    public static function save_phone( string $phone ) {
        global $wpdb;

        $data = [
            'phone'      => $phone,
            'ip'         => self::get_ip(),
            'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 500 ) : '',
            'user_id'    => get_current_user_id(),
            'created_at' => current_time( 'mysql' ),
        ];

        $result = $wpdb->insert( $wpdb->prefix . 'cpl_phones', $data, [ '%s', '%s', '%s', '%d', '%s' ] );

        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Get paginated phone log entries.
     *
     * @param int $per_page Rows per page.
     * @param int $page     Current page (1-based).
     * @return array{rows: array, total: int}
     */
    public static function get_phones( int $per_page = 50, int $page = 1 ): array {
        global $wpdb;

        $table  = $wpdb->prefix . 'cpl_phones';
        $offset = ( $page - 1 ) * $per_page;

        $rows  = $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset ),
            ARRAY_A
        );
        $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );

        return [ 'rows' => $rows ?: [], 'total' => $total ];
    }

    /**
     * Get all rows for CSV export (no pagination).
     */
    public static function get_all_phones(): array {
        global $wpdb;
        $rows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cpl_phones ORDER BY created_at DESC", ARRAY_A );
        return $rows ?: [];
    }

    // -------------------------------------------------------------------------

    private static function get_ip(): string {
        foreach ( [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ] as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ip = trim( explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) ) )[0] );
                if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
                    return $ip;
                }
            }
        }
        return '';
    }
}
