<?php
/**
 * Plugin Name:       Opening Hours Block
 * Description:       A block for your weekly opening hours, with today highlighted and a live "Open now" or "Closed" badge in your site's time zone. Handles late nights that run past midnight.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Ayaan Hydross Sameer
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       opening-hours-block
 *
 * @package Opening_Hours_Block
 */

defined( 'ABSPATH' ) || exit;

/**
 * The block is described by block/block.json; WordPress loads its editor
 * script, stylesheet and server render from there.
 */
function ohb_register_block() {
	register_block_type( __DIR__ . '/block' );
}
add_action( 'init', 'ohb_register_block' );

/**
 * Tell the editor which day the site's week starts on (Settings → General),
 * so the sidebar lists days in the same order as the page.
 */
function ohb_editor_settings() {
	$handle = generate_block_asset_handle( 'ayaan/opening-hours', 'editorScript' );
	wp_add_inline_script(
		$handle,
		'window.ohbSettings = ' . wp_json_encode( array( 'startOfWeek' => (int) get_option( 'start_of_week', 0 ) ) ) . ';',
		'before'
	);
}
add_action( 'enqueue_block_editor_assets', 'ohb_editor_settings' );

/**
 * The opening-hours arithmetic, kept apart from the markup so it can be
 * reasoned about (and tested) on its own.
 */
class OHB_Hours {

	/**
	 * Minutes past midnight for "HH:MM", or null when the value is not a time.
	 */
	public static function minutes( $time ) {
		if ( ! is_string( $time ) || ! preg_match( '/^([01]\d|2[0-3]):([0-5]\d)$/', $time, $m ) ) {
			return null;
		}
		return (int) $m[1] * 60 + (int) $m[2];
	}

	/**
	 * A day's opening as [open, close] in minutes, where close may run past
	 * 1440 when the day ends after midnight (07:00–01:00 is [420, 1500]).
	 * Null when the day is closed or its times are missing.
	 */
	public static function span( $day ) {
		// The editor's live preview can send booleans as the strings "true" and
		// "false"; ! empty( "false" ) is true, so read the flag strictly.
		$closed = isset( $day['closed'] ) && filter_var( $day['closed'], FILTER_VALIDATE_BOOLEAN );
		if ( ! is_array( $day ) || $closed ) {
			return null;
		}
		$open  = self::minutes( isset( $day['open'] ) ? $day['open'] : '' );
		$close = self::minutes( isset( $day['close'] ) ? $day['close'] : '' );
		if ( null === $open || null === $close ) {
			return null;
		}
		if ( $close <= $open ) {
			$close += 1440; // Runs past midnight; 00:00 means "until midnight".
		}
		return array( $open, $close );
	}

	/**
	 * Whether the place is open at $now, and when that changes.
	 *
	 * @param array             $hours Seven days, Sunday first (PHP's "w").
	 * @param DateTimeImmutable $now   The current time in the site's zone.
	 * @return array{open:bool, closes_at?:int, opens_day?:int, opens_at?:int, days_ahead?:int}
	 */
	public static function status( array $hours, DateTimeImmutable $now ) {
		$today  = (int) $now->format( 'w' );
		$minute = (int) $now->format( 'G' ) * 60 + (int) $now->format( 'i' );

		// Still inside last night's late opening?
		$yesterday = self::span( isset( $hours[ ( $today + 6 ) % 7 ] ) ? $hours[ ( $today + 6 ) % 7 ] : null );
		if ( $yesterday && $yesterday[1] > 1440 && $minute < $yesterday[1] - 1440 ) {
			return array( 'open' => true, 'closes_at' => $yesterday[1] - 1440 );
		}

		$span = self::span( isset( $hours[ $today ] ) ? $hours[ $today ] : null );
		if ( $span && $minute >= $span[0] && $minute < $span[1] ) {
			return array( 'open' => true, 'closes_at' => $span[1] % 1440 );
		}

		// Closed: find the next opening, today included if it is still ahead.
		for ( $ahead = 0; $ahead <= 7; $ahead++ ) {
			$day  = ( $today + $ahead ) % 7;
			$next = self::span( isset( $hours[ $day ] ) ? $hours[ $day ] : null );
			if ( $next && ( $ahead > 0 || $next[0] > $minute ) ) {
				return array( 'open' => false, 'opens_day' => $day, 'opens_at' => $next[0], 'days_ahead' => $ahead );
			}
		}
		return array( 'open' => false );
	}

	/** Minutes past midnight in the site's own time format (Settings → General). */
	public static function format( $minutes ) {
		$time = ( new DateTimeImmutable( 'today', wp_timezone() ) )->setTime( intdiv( $minutes, 60 ) % 24, $minutes % 60 );
		return wp_date( get_option( 'time_format', 'g:i a' ), $time->getTimestamp() );
	}
}
