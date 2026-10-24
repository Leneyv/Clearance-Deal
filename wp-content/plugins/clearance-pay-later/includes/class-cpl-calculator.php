<?php
/**
 * Core instalment calculator (pure, side-effect-free).
 *
 * @package ClearancePayLater
 */

defined( 'ABSPATH' ) || exit;

class CPL_Calculator {

    /**
     * Calculate all plan figures for a given total.
     *
     * @param float $total     Cart/order total (products + shipping + tax).
     * @param array $overrides Optional overrides: deposit_pct, interest_rate, months.
     * @return array {
     *   total, deposit_pct, deposit, balance,
     *   interest_rate, interest_amount, balance_repayable,
     *   months, monthly_instalment, last_instalment
     * }
     */
    public static function calculate( float $total, array $overrides = [] ): array {
        $deposit_pct   = (float) ( $overrides['deposit_pct']   ?? CPL_Settings::get_float( 'deposit_pct' ) );
        $interest_rate = (float) ( $overrides['interest_rate'] ?? CPL_Settings::get_float( 'interest_rate' ) );
        $months        = (int)   ( $overrides['months']        ?? CPL_Settings::get_int( 'months' ) );

        $total   = round( $total, 2 );
        $deposit = round( $total * ( $deposit_pct / 100 ), 2 );
        $balance = round( $total - $deposit, 2 );

        $interest_amount   = round( $balance * ( $interest_rate / 100 ), 2 );
        $balance_repayable = round( $balance + $interest_amount, 2 );

        // Monthly instalment; remainder absorbed by last payment.
        $monthly = round( $balance_repayable / $months, 2 );
        $last    = round( $balance_repayable - ( $monthly * ( $months - 1 ) ), 2 );

        return compact(
            'total', 'deposit_pct', 'deposit', 'balance',
            'interest_rate', 'interest_amount', 'balance_repayable',
            'months', 'monthly', 'last'
        ) + [ 'monthly_instalment' => $monthly, 'last_instalment' => $last ];
    }

    /**
     * Generate an instalment schedule starting 30 days after delivery.
     *
     * @param array          $calc          Result from self::calculate().
     * @param \DateTime|null $delivery_date Delivery date; defaults to today.
     * @return array[] Each item: {instalment, due_date (Y-m-d), amount, paid, paid_date}.
     */
    public static function generate_schedule( array $calc, ?\DateTime $delivery_date = null ): array {
        $base     = $delivery_date ?? new \DateTime();
        $months   = (int) $calc['months'];
        $schedule = [];

        for ( $i = 1; $i <= $months; $i++ ) {
            $due = clone $base;
            $due->modify( '+' . ( $i * 30 ) . ' days' );

            $schedule[] = [
                'instalment' => $i,
                'due_date'   => $due->format( 'Y-m-d' ),
                'amount'     => ( $i === $months ) ? $calc['last_instalment'] : $calc['monthly_instalment'],
                'paid'       => false,
                'paid_date'  => null,
            ];
        }

        return $schedule;
    }

    /**
     * Format an amount as "KES 1,234.00".
     */
    public static function format_kes( float $amount ): string {
        return 'KES ' . number_format( $amount, 2, '.', ',' );
    }

    /**
     * Validate and normalise a Kenyan phone number to 2547XXXXXXXXX / 2541XXXXXXXXX.
     *
     * Accepts: 07XX, 01XX (10 digits), +254XX (13 chars), 254XX (12 digits),
     *          7XX / 1XX (9 digits).
     *
     * @return string|false Normalised 12-digit string, or false if invalid.
     */
    public static function normalise_phone( string $raw ) {
        $phone = preg_replace( '/\D/', '', $raw );

        if ( strlen( $phone ) === 10 && substr( $phone, 0, 1 ) === '0' ) {
            $phone = '254' . substr( $phone, 1 );
        } elseif ( strlen( $phone ) === 9 && ( substr( $phone, 0, 1 ) === '7' || substr( $phone, 0, 1 ) === '1' ) ) {
            $phone = '254' . $phone;
        }

        if ( strlen( $phone ) !== 12 ) {
            return false;
        }

        if ( ! preg_match( '/^254[71]\d{8}$/', $phone ) ) {
            return false;
        }

        return $phone;
    }
}
