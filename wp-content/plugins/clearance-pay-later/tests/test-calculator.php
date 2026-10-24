<?php
/**
 * PHPUnit tests for CPL_Calculator.
 *
 * Run from plugin root:
 *   vendor/bin/phpunit tests/test-calculator.php
 *
 * @package ClearancePayLater
 */

// Minimal bootstrap without WordPress (pure-class test).
define( 'ABSPATH', dirname( __DIR__ ) . '/' );
define( 'CPL_PLUGIN_DIR', dirname( __DIR__ ) . '/' );

// Stub CPL_Settings so calculator can run standalone.
if ( ! class_exists( 'CPL_Settings' ) ) {
    class CPL_Settings {
        private static $data = [
            'deposit_pct'   => 50,
            'interest_rate' => 12,
            'months'        => 12,
        ];
        public static function get_float( string $key ): float { return (float) ( self::$data[ $key ] ?? 0 ); }
        public static function get_int( string $key ): int     { return (int)   ( self::$data[ $key ] ?? 0 ); }
    }
}

require_once dirname( __DIR__ ) . '/includes/class-cpl-calculator.php';

use PHPUnit\Framework\TestCase;

class Test_CPL_Calculator extends TestCase {

    // ------------------------------------------------------------------
    // Basic calculation
    // ------------------------------------------------------------------

    public function test_basic_calculation(): void {
        $c = CPL_Calculator::calculate( 1000 );

        $this->assertSame( 1000.0, $c['total'] );
        $this->assertSame( 50.0,   $c['deposit_pct'] );
        $this->assertSame( 500.0,  $c['deposit'] );
        $this->assertSame( 500.0,  $c['balance'] );
        $this->assertSame( 12.0,   $c['interest_rate'] );
        $this->assertSame( 60.0,   $c['interest_amount'] );     // 500 × 12%
        $this->assertSame( 560.0,  $c['balance_repayable'] );   // 500 + 60
        $this->assertSame( 12,     $c['months'] );
        $this->assertSame( 46.67,  $c['monthly_instalment'] );  // 560 / 12 = 46.666…
        $this->assertSame( 46.63,  $c['last_instalment'] );     // 560 - 46.67×11
    }

    public function test_last_instalment_absorbs_rounding(): void {
        $c    = CPL_Calculator::calculate( 1000 );
        $sum  = round( $c['monthly_instalment'] * ( $c['months'] - 1 ) + $c['last_instalment'], 2 );
        $this->assertSame( $c['balance_repayable'], $sum );
    }

    public function test_zero_interest(): void {
        $c = CPL_Calculator::calculate( 2000, [ 'interest_rate' => 0 ] );
        $this->assertSame( 0.0,    $c['interest_amount'] );
        $this->assertSame( 1000.0, $c['balance_repayable'] );
    }

    public function test_custom_deposit_pct(): void {
        $c = CPL_Calculator::calculate( 1000, [ 'deposit_pct' => 30 ] );
        $this->assertSame( 300.0, $c['deposit'] );
        $this->assertSame( 700.0, $c['balance'] );
    }

    public function test_custom_months(): void {
        $c = CPL_Calculator::calculate( 600, [ 'months' => 6, 'interest_rate' => 0 ] );
        // balance = 300, repayable = 300, monthly = 50 × 6 = 300
        $this->assertSame( 50.0, $c['monthly_instalment'] );
        $this->assertSame( 50.0, $c['last_instalment'] );
    }

    // ------------------------------------------------------------------
    // Phone normalisation
    // ------------------------------------------------------------------

    /** @dataProvider validPhoneProvider */
    public function test_valid_phone( string $input, string $expected ): void {
        $this->assertSame( $expected, CPL_Calculator::normalise_phone( $input ) );
    }

    public function validPhoneProvider(): array {
        return [
            'ten-digit 07xx'      => [ '0712345678',    '254712345678' ],
            'ten-digit 01xx'      => [ '0112345678',    '254112345678' ],
            'nine-digit 7xx'      => [ '712345678',     '254712345678' ],
            'nine-digit 1xx'      => [ '112345678',     '254112345678' ],
            'plus-254'            => [ '+254712345678', '254712345678' ],
            'full-254-no-plus'    => [ '254712345678',  '254712345678' ],
            'spaces stripped'     => [ '0712 345 678',  '254712345678' ],
        ];
    }

    /** @dataProvider invalidPhoneProvider */
    public function test_invalid_phone( string $input ): void {
        $this->assertFalse( CPL_Calculator::normalise_phone( $input ) );
    }

    public function invalidPhoneProvider(): array {
        return [
            'too short'   => [ '071234' ],
            'wrong prefix'=> [ '254512345678' ],
            'letters'     => [ 'abcdefghij' ],
            'empty'       => [ '' ],
        ];
    }

    // ------------------------------------------------------------------
    // Schedule generation
    // ------------------------------------------------------------------

    public function test_schedule_has_correct_count(): void {
        $c    = CPL_Calculator::calculate( 1200 );
        $sched = CPL_Calculator::generate_schedule( $c );
        $this->assertCount( 12, $sched );
    }

    public function test_schedule_first_due_30_days_after_delivery(): void {
        $c        = CPL_Calculator::calculate( 1200 );
        $delivery = new DateTime( '2025-01-01' );
        $sched    = CPL_Calculator::generate_schedule( $c, $delivery );

        $this->assertSame( '2025-01-31', $sched[0]['due_date'] );
    }

    public function test_schedule_amounts_sum_to_balance_repayable(): void {
        $c    = CPL_Calculator::calculate( 999 );
        $sched = CPL_Calculator::generate_schedule( $c );
        $sum  = array_sum( array_column( $sched, 'amount' ) );
        $this->assertSame( $c['balance_repayable'], round( $sum, 2 ) );
    }

    // ------------------------------------------------------------------
    // Format
    // ------------------------------------------------------------------

    public function test_format_kes(): void {
        $this->assertSame( 'KES 1,234.56', CPL_Calculator::format_kes( 1234.56 ) );
        $this->assertSame( 'KES 0.00',     CPL_Calculator::format_kes( 0 ) );
    }
}
