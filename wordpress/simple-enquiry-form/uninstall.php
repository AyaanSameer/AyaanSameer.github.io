<?php
/**
 * Runs when the plugin is deleted from the Plugins screen (not when it is
 * only deactivated): removes its settings and every saved enquiry, so no
 * visitor's details are left behind in the database.
 *
 * @package Simple_Enquiry_Form
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'sef_settings' );

$sef_ids = get_posts(
	array(
		'post_type'   => 'sef_enquiry',
		'post_status' => 'any',
		'numberposts' => -1,
		'fields'      => 'ids',
	)
);

foreach ( $sef_ids as $sef_id ) {
	wp_delete_post( $sef_id, true );
}
