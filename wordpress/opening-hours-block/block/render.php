<?php
/**
 * Server render for ayaan/opening-hours.
 *
 * WordPress provides $attributes, $content and $block. Everything printed is
 * escaped here; the times come from the site's own format and time zone.
 *
 * @package Opening_Hours_Block
 */

defined( 'ABSPATH' ) || exit;

$ohb_hours = isset( $attributes['hours'] ) && is_array( $attributes['hours'] ) ? array_values( $attributes['hours'] ) : array();
$ohb_hours = array_pad( array_slice( $ohb_hours, 0, 7 ), 7, array( 'closed' => true ) );

$ohb_now    = new DateTimeImmutable( 'now', wp_timezone() );
$ohb_today  = (int) $ohb_now->format( 'w' );
$ohb_start  = (int) get_option( 'start_of_week', 0 );
$ohb_status = OHB_Hours::status( $ohb_hours, $ohb_now );

/* translators: Used between opening and closing times, e.g. "6:30 am – 11:00 pm". */
$ohb_dash = _x( '–', 'time range', 'opening-hours-block' );

// The badge's second half: when it closes, or when it next opens.
$ohb_when = '';
if ( $ohb_status['open'] && isset( $ohb_status['closes_at'] ) ) {
	/* translators: %s: a time, e.g. "11:00 pm". */
	$ohb_when = sprintf( __( 'closes at %s', 'opening-hours-block' ), OHB_Hours::format( $ohb_status['closes_at'] ) );
} elseif ( ! $ohb_status['open'] && isset( $ohb_status['opens_at'] ) ) {
	$ohb_time = OHB_Hours::format( $ohb_status['opens_at'] );
	if ( 0 === $ohb_status['days_ahead'] ) {
		/* translators: %s: a time. */
		$ohb_when = sprintf( __( 'opens today at %s', 'opening-hours-block' ), $ohb_time );
	} elseif ( 1 === $ohb_status['days_ahead'] ) {
		/* translators: %s: a time. */
		$ohb_when = sprintf( __( 'opens tomorrow at %s', 'opening-hours-block' ), $ohb_time );
	} else {
		/* translators: 1: a day of the week, 2: a time. */
		$ohb_when = sprintf( __( 'opens %1$s at %2$s', 'opening-hours-block' ), $GLOBALS['wp_locale']->get_weekday( $ohb_status['opens_day'] ), $ohb_time );
	}
}
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'ohb' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core escapes it. ?>>
	<?php if ( ! empty( $attributes['showStatus'] ) ) : ?>
		<p class="ohb-status <?php echo $ohb_status['open'] ? 'ohb-status--open' : 'ohb-status--closed'; ?>">
			<span class="ohb-dot" aria-hidden="true"></span>
			<strong><?php echo $ohb_status['open'] ? esc_html__( 'Open now', 'opening-hours-block' ) : esc_html__( 'Closed now', 'opening-hours-block' ); ?></strong>
			<?php if ( $ohb_when ) : ?>
				<span class="ohb-when">· <?php echo esc_html( $ohb_when ); ?></span>
			<?php endif; ?>
		</p>
	<?php endif; ?>

	<dl class="ohb-list">
		<?php
		for ( $ohb_i = 0; $ohb_i < 7; $ohb_i++ ) :
			$ohb_day      = ( $ohb_start + $ohb_i ) % 7;
			$ohb_span     = OHB_Hours::span( $ohb_hours[ $ohb_day ] );
			$ohb_is_today = ! empty( $attributes['highlightToday'] ) && $ohb_day === $ohb_today;
			?>
			<div class="ohb-row<?php echo $ohb_is_today ? ' is-today' : ''; ?>"<?php echo $ohb_is_today ? ' aria-current="date"' : ''; ?>>
				<dt>
					<?php echo esc_html( $GLOBALS['wp_locale']->get_weekday( $ohb_day ) ); ?>
					<?php if ( $ohb_is_today ) : ?>
						<span class="ohb-today"><?php esc_html_e( 'Today', 'opening-hours-block' ); ?></span>
					<?php endif; ?>
				</dt>
				<dd>
					<?php
					if ( $ohb_span ) {
						echo esc_html( OHB_Hours::format( $ohb_span[0] ) . ' ' . $ohb_dash . ' ' . OHB_Hours::format( $ohb_span[1] ) );
					} else {
						esc_html_e( 'Closed', 'opening-hours-block' );
					}
					?>
				</dd>
			</div>
		<?php endfor; ?>
	</dl>

	<?php if ( ! empty( $attributes['note'] ) ) : ?>
		<p class="ohb-note"><?php echo esc_html( $attributes['note'] ); ?></p>
	<?php endif; ?>
</div>
