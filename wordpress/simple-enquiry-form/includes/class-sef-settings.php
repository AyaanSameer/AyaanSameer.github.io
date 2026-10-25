<?php
/**
 * Settings → Enquiry form: who gets the email, and what the visitor reads
 * after sending.
 *
 * @package Simple_Enquiry_Form
 */

defined( 'ABSPATH' ) || exit;

class SEF_Settings {

	const OPTION = 'sef_settings';

	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register' ) );
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( SEF_FILE ), array( __CLASS__, 'action_link' ) );
	}

	/** Settings with their defaults filled in, so callers never check for gaps. */
	public static function get() {
		$saved = get_option( self::OPTION, array() );
		return wp_parse_args(
			is_array( $saved ) ? $saved : array(),
			array(
				'notify_email'    => get_option( 'admin_email' ),
				'success_message' => __( 'Thank you — your message is on its way. We usually reply within one working day.', 'simple-enquiry-form' ),
			)
		);
	}

	public static function register() {
		register_setting(
			'sef_settings_group',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => array(),
			)
		);

		add_settings_section( 'sef_main', '', '__return_false', 'sef-settings' );

		add_settings_field(
			'notify_email',
			__( 'Send enquiries to', 'simple-enquiry-form' ),
			array( __CLASS__, 'field_email' ),
			'sef-settings',
			'sef_main',
			array( 'label_for' => 'sef-notify-email' )
		);
		add_settings_field(
			'success_message',
			__( 'Thank-you message', 'simple-enquiry-form' ),
			array( __CLASS__, 'field_message' ),
			'sef-settings',
			'sef_main',
			array( 'label_for' => 'sef-success-message' )
		);
	}

	public static function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		$email = isset( $input['notify_email'] ) ? sanitize_email( $input['notify_email'] ) : '';

		if ( ! is_email( $email ) ) {
			add_settings_error( self::OPTION, 'sef-email', __( 'That email address does not look right, so the site admin email is used instead.', 'simple-enquiry-form' ) );
			$email = get_option( 'admin_email' );
		}

		return array(
			'notify_email'    => $email,
			'success_message' => isset( $input['success_message'] ) ? sanitize_textarea_field( $input['success_message'] ) : '',
		);
	}

	public static function menu() {
		add_options_page(
			__( 'Enquiry form', 'simple-enquiry-form' ),
			__( 'Enquiry form', 'simple-enquiry-form' ),
			'manage_options',
			'sef-settings',
			array( __CLASS__, 'render_page' )
		);
	}

	public static function action_link( $links ) {
		$url = admin_url( 'options-general.php?page=sef-settings' );
		array_unshift( $links, sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html__( 'Settings', 'simple-enquiry-form' ) ) );
		return $links;
	}

	public static function field_email() {
		$settings = self::get();
		printf(
			'<input type="email" id="sef-notify-email" class="regular-text" name="%s[notify_email]" value="%s" />',
			esc_attr( self::OPTION ),
			esc_attr( $settings['notify_email'] )
		);
	}

	public static function field_message() {
		$settings = self::get();
		printf(
			'<textarea id="sef-success-message" class="large-text" rows="3" name="%s[success_message]">%s</textarea>',
			esc_attr( self::OPTION ),
			esc_textarea( $settings['success_message'] )
		);
	}

	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Enquiry form', 'simple-enquiry-form' ); ?></h1>
			<p><?php echo wp_kses_post( __( 'Add the form to any page with the shortcode <code>[simple_enquiry_form]</code>. Every message is saved under <strong>Enquiries</strong> and emailed to the address below.', 'simple-enquiry-form' ) ); ?></p>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'sef_settings_group' );
				do_settings_sections( 'sef-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
