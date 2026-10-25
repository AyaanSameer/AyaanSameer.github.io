<?php
/**
 * Checks the opening-hours arithmetic without WordPress: run
 *   php tests/hours-test.php
 * from the plugin folder. Prints one line per case and exits non-zero on a failure.
 *
 * @package Opening_Hours_Block
 */

// Just enough of WordPress for the plugin file to load.
define( 'ABSPATH', __DIR__ );
function add_action() {}

require __DIR__ . '/../opening-hours-block.php';

$tz = new DateTimeZone( 'Asia/Qatar' );

// Sunday first. Thursday and Friday run past midnight; Monday is closed.
$week = array(
	array( 'open' => '06:30', 'close' => '23:00' ),               // Sun
	array( 'closed' => true ),                                     // Mon
	array( 'open' => '06:30', 'close' => '23:00' ),               // Tue
	array( 'open' => '06:30', 'close' => '23:00' ),               // Wed
	array( 'open' => '06:30', 'close' => '00:30' ),               // Thu
	array( 'open' => '13:00', 'close' => '01:00' ),               // Fri
	array( 'open' => '07:00', 'close' => '23:00' ),               // Sat
);

// 2026-10-22 is a Thursday.
$cases = array(
	array( '2026-10-22 12:00', true, 'Thursday noon: open' ),
	array( '2026-10-23 00:15', true, 'Just past midnight on Thursday night: still open' ),
	array( '2026-10-23 00:45', false, 'After Thursday’s late close: closed' ),
	array( '2026-10-23 10:00', false, 'Friday morning before 13:00: closed' ),
	array( '2026-10-24 00:30', true, 'Friday night runs to 01:00' ),
	array( '2026-10-26 12:00', false, 'Monday: closed all day' ),
	array( '2026-10-25 23:00', false, 'Sunday 23:00 is the closing minute' ),
);

$failed = 0;
foreach ( $cases as $case ) {
	list( $when, $expect, $label ) = $case;
	$status = OHB_Hours::status( $week, new DateTimeImmutable( $when, $tz ) );
	$ok     = $status['open'] === $expect;
	$failed += $ok ? 0 : 1;
	printf( "%s  %s\n", $ok ? 'ok  ' : 'FAIL', $label );
}

// Monday is closed, so from Sunday night the next opening is Tuesday 06:30.
$next = OHB_Hours::status( $week, new DateTimeImmutable( '2026-10-25 23:30', $tz ) );
$ok   = 2 === $next['opens_day'] && 390 === $next['opens_at'] && 2 === $next['days_ahead'];
$failed += $ok ? 0 : 1;
printf( "%s  %s\n", $ok ? 'ok  ' : 'FAIL', 'After Sunday close, next opening is Tuesday 06:30' );

// The editor's preview sends booleans as strings: "false" must mean open.
$from_editor        = $week;
$from_editor[4]     = array( 'open' => '06:30', 'close' => '00:30', 'closed' => 'false' );
$from_editor_status = OHB_Hours::status( $from_editor, new DateTimeImmutable( '2026-10-22 12:00', $tz ) );
$ok                 = true === $from_editor_status['open'];
$failed            += $ok ? 0 : 1;
printf( "%s  %s\n", $ok ? 'ok  ' : 'FAIL', 'A "false" string from the editor preview still means open' );

exit( $failed ? 1 : 0 );
