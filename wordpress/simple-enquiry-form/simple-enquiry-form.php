<?php
/**
 * Plugin Name:       Simple Enquiry Form
 * Description:       A lightweight enquiry form. Add [simple_enquiry_form] to any page; every message is saved privately in the dashboard and emailed to you. No third-party services, no tracking.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Ayaan Hydross Sameer
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-enquiry-form
 *
 * @package Simple_Enquiry_Form
 */

defined( 'ABSPATH' ) || exit;

define( 'SEF_VERSION', '1.0.0' );
define( 'SEF_FILE', __FILE__ );
define( 'SEF_DIR', plugin_dir_path( __FILE__ ) );

require_once SEF_DIR . 'includes/class-sef-post-type.php';
require_once SEF_DIR . 'includes/class-sef-settings.php';
require_once SEF_DIR . 'includes/class-sef-form.php';

add_action( 'init', array( 'SEF_Post_Type', 'register' ) );
SEF_Post_Type::init_admin();
SEF_Settings::init();
SEF_Form::init();
