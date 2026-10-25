<?php
/**
 * The form itself: the shortcode that draws it and the handler that receives it.
 *
 * The form posts to admin-post.php rather than to the page, so the page can be
 * cached and the handler always runs. After saving, the visitor is sent back
 * to the page they came from (post/redirect/get), so a refresh never sends the
 * message twice.
 *
 * @package Simple_Enquiry_Form
 */

defined( 'ABSPATH' ) || exit;

class SEF_Form {

	const ACTION = 'sef_submit';

	/** Seconds a visitor must wait between two messages. */
	const RATE_LIMIT = 30;

	/** A form sent faster than this after loading was not typed by a person. */
	const MIN_SECONDS = 3;

	public static function init() {
		add_shortcode( 'simple_enquiry_form', array( __CLASS__, 'render' ) );
		add_action( 'admin_post_nopriv_' . self::ACTION, array( __CLASS__, 'handle' ) );
		add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'handle' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	/** Registered everywhere, loaded only on pages that show the form. */
	public static function register_assets() {
		wp_register_style( 'sef-form', plugins_url( 'assets/form.css', SEF_FILE ), array(), SEF_VERSION );
	}

	/**
	 * [simple_enquiry_form subject="Catering" button="Send"]
	 */
	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'subject' => __( 'Website enquiry', 'simple-enquiry-form' ),
				'button'  => __( 'Send enquiry', 'simple-enquiry-form' ),
			),
			$atts,
			'simple_enquiry_form'
		);

		wp_enqueue_style( 'sef-form' );

		$status  = isset( $_GET['sef'] ) ? sanitize_key( wp_unslash( $_GET['sef'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
		$token   = isset( $_GET['sef_t'] ) ? sanitize_key( wp_unslash( $_GET['sef_t'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
		$stored  = $token ? get_transient( 'sef_retry_' . $token ) : false;
		$values  = is_array( $stored ) ? $stored['values'] : array();
		$errors  = is_array( $stored ) ? $stored['errors'] : array();
		$here    = get_permalink() ? get_permalink() : home_url( '/' );
		$value   = static function ( $key ) use ( $values ) {
			return isset( $values[ $key ] ) ? $values[ $key ] : '';
		};

		ob_start();
		?>
		<div class="sef" id="sef-form">
			<?php if ( 'sent' === $status ) : ?>
				<div class="sef-notice sef-notice--success" role="status">
					<?php echo esc_html( SEF_Settings::get()['success_message'] ); ?>
				</div>
			<?php elseif ( 'wait' === $status ) : ?>
				<div class="sef-notice sef-notice--error" role="alert">
					<?php esc_html_e( 'You have just sent a message. Please wait a moment before sending another.', 'simple-enquiry-form' ); ?>
				</div>
			<?php elseif ( 'expired' === $status ) : ?>
				<div class="sef-notice sef-notice--error" role="alert">
					<?php esc_html_e( 'The form expired before it was sent. Please try again.', 'simple-enquiry-form' ); ?>
				</div>
			<?php elseif ( $errors ) : ?>
				<div class="sef-notice sef-notice--error" role="alert">
					<?php esc_html_e( 'Please check the highlighted fields.', 'simple-enquiry-form' ); ?>
				</div>
			<?php endif; ?>

			<form class="sef-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
				<input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION ); ?>" />
				<input type="hidden" name="sef_redirect" value="<?php echo esc_url( $here ); ?>" />
				<input type="hidden" name="sef_subject" value="<?php echo esc_attr( $atts['subject'] ); ?>" />
				<input type="hidden" name="sef_ts" value="<?php echo esc_attr( (string) time() ); ?>" />
				<?php wp_nonce_field( self::ACTION, 'sef_nonce' ); ?>

				<?php
				self::field( 'name', __( 'Your name', 'simple-enquiry-form' ), 'text', true, $value( 'name' ), $errors, 'name' );
				self::field( 'email', __( 'Email', 'simple-enquiry-form' ), 'email', true, $value( 'email' ), $errors, 'email' );
				self::field( 'phone', __( 'Phone (optional)', 'simple-enquiry-form' ), 'tel', false, $value( 'phone' ), $errors, 'tel' );
				self::field( 'message', __( 'How can we help?', 'simple-enquiry-form' ), 'textarea', true, $value( 'message' ), $errors );
				?>

				<?php // A field people never see. Bots fill in every field they find. ?>
				<div class="sef-hp" aria-hidden="true">
					<label for="sef-website"><?php esc_html_e( 'Leave this empty', 'simple-enquiry-form' ); ?></label>
					<input type="text" id="sef-website" name="sef_website" tabindex="-1" autocomplete="off" />
				</div>

				<p class="sef-actions">
					<button type="submit" class="sef-button wp-element-button"><?php echo esc_html( $atts['button'] ); ?></button>
				</p>
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	/** One labelled field, with its error message tied to it for screen readers. */
	private static function field( $key, $label, $type, $required, $current, $errors, $autocomplete = '' ) {
		$id        = 'sef-' . $key;
		$has_error = isset( $errors[ $key ] );
		$described = $has_error ? ' aria-describedby="' . esc_attr( $id . '-error' ) . '" aria-invalid="true"' : '';
		$req       = $required ? ' required aria-required="true"' : '';
		$auto      = $autocomplete ? ' autocomplete="' . esc_attr( $autocomplete ) . '"' : '';
		?>
		<p class="sef-field<?php echo $has_error ? ' sef-field--error' : ''; ?>">
			<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
			<?php if ( 'textarea' === $type ) : ?>
				<textarea id="<?php echo esc_attr( $id ); ?>" name="sef_<?php echo esc_attr( $key ); ?>" rows="5"<?php echo $req . $described; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above. ?>><?php echo esc_textarea( $current ); ?></textarea>
			<?php else : ?>
				<input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $id ); ?>" name="sef_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $current ); ?>"<?php echo $req . $described . $auto; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above. ?> />
			<?php endif; ?>
			<?php if ( $has_error ) : ?>
				<span class="sef-error" id="<?php echo esc_attr( $id . '-error' ); ?>"><?php echo esc_html( $errors[ $key ] ); ?></span>
			<?php endif; ?>
		</p>
		<?php
	}

	/** Receive the form, check it, save it, email it, send the visitor back. */
	public static function handle() {
		$back = isset( $_POST['sef_redirect'] ) ? esc_url_raw( wp_unslash( $_POST['sef_redirect'] ) ) : '';
		$back = wp_validate_redirect( $back, home_url( '/' ) );

		$nonce = isset( $_POST['sef_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sef_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
			self::go( $back, array( 'sef' => 'expired' ) );
		}

		// Spam is told it succeeded, so it has no reason to try again.
		$honeypot = isset( $_POST['sef_website'] ) ? sanitize_text_field( wp_unslash( $_POST['sef_website'] ) ) : '';
		$loaded   = isset( $_POST['sef_ts'] ) ? absint( $_POST['sef_ts'] ) : 0;
		if ( '' !== $honeypot || ( time() - $loaded ) < self::MIN_SECONDS ) {
			self::go( $back, array( 'sef' => 'sent' ) );
		}

		$limit_key = 'sef_rl_' . md5( self::visitor_ip() );
		if ( get_transient( $limit_key ) ) {
			self::go( $back, array( 'sef' => 'wait' ) );
		}

		// What they typed, kept so a mistyped address can be shown back and corrected.
		$typed_email = isset( $_POST['sef_email'] ) ? sanitize_text_field( wp_unslash( $_POST['sef_email'] ) ) : '';

		$values = array(
			'name'    => isset( $_POST['sef_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sef_name'] ) ) : '',
			'email'   => sanitize_email( $typed_email ),
			'phone'   => isset( $_POST['sef_phone'] ) ? preg_replace( '/[^0-9+\-\s()]/', '', sanitize_text_field( wp_unslash( $_POST['sef_phone'] ) ) ) : '',
			'message' => isset( $_POST['sef_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sef_message'] ) ) : '',
		);
		$subject = isset( $_POST['sef_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['sef_subject'] ) ) : '';

		$errors = self::validate( $values );
		if ( $errors ) {
			// Keep what they typed for ten minutes so nothing has to be retyped.
			$token           = strtolower( wp_generate_password( 12, false ) );
			$values['email'] = $typed_email;
			set_transient( 'sef_retry_' . $token, compact( 'values', 'errors' ), 10 * MINUTE_IN_SECONDS );
			self::go( $back, array( 'sef' => 'invalid', 'sef_t' => $token ) );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => SEF_Post_Type::POST_TYPE,
				'post_status'  => 'publish',
				'post_title'   => $values['name'],
				'post_content' => $values['message'],
				'meta_input'   => array(
					'_sef_email'   => $values['email'],
					'_sef_phone'   => $values['phone'],
					'_sef_subject' => $subject,
					'_sef_page'    => $back,
				),
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			self::go( $back, array( 'sef' => 'expired' ) );
		}

		self::notify( $values, $subject, $post_id );
		set_transient( $limit_key, 1, self::RATE_LIMIT );

		self::go( $back, array( 'sef' => 'sent' ) );
	}

	/** @return array<string,string> field => message; empty when all is well. */
	public static function validate( array $values ) {
		$errors = array();
		if ( '' === $values['name'] ) {
			$errors['name'] = __( 'Please tell us your name.', 'simple-enquiry-form' );
		} elseif ( mb_strlen( $values['name'] ) > 100 ) {
			$errors['name'] = __( 'Please keep your name under 100 characters.', 'simple-enquiry-form' );
		}
		if ( ! is_email( $values['email'] ) ) {
			$errors['email'] = __( 'Please enter an email address we can reply to.', 'simple-enquiry-form' );
		}
		if ( '' === trim( $values['message'] ) ) {
			$errors['message'] = __( 'Please write a short message.', 'simple-enquiry-form' );
		} elseif ( mb_strlen( $values['message'] ) > 5000 ) {
			$errors['message'] = __( 'Please keep your message under 5,000 characters.', 'simple-enquiry-form' );
		}
		return $errors;
	}

	private static function notify( array $values, $subject, $post_id ) {
		$settings = SEF_Settings::get();
		$site     = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

		// Header values must never carry a line break, or a visitor could add headers of their own.
		$reply_name = str_replace( array( "\r", "\n", '"' ), '', $values['name'] );

		$body  = sprintf( "%s\n\n", $values['message'] );
		$body .= sprintf( "%s: %s\n", __( 'Name', 'simple-enquiry-form' ), $values['name'] );
		$body .= sprintf( "%s: %s\n", __( 'Email', 'simple-enquiry-form' ), $values['email'] );
		$body .= sprintf( "%s: %s\n", __( 'Phone', 'simple-enquiry-form' ), $values['phone'] ? $values['phone'] : '-' );
		$body .= sprintf( "\n%s: %s\n", __( 'View in the dashboard', 'simple-enquiry-form' ), admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' ) );

		wp_mail(
			$settings['notify_email'],
			sprintf( '[%s] %s — %s', $site, $subject, $reply_name ),
			$body,
			array( sprintf( 'Reply-To: "%s" <%s>', $reply_name, $values['email'] ) )
		);
	}

	private static function visitor_ip() {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : 'unknown';
	}

	private static function go( $url, array $args ) {
		wp_safe_redirect( add_query_arg( $args, $url ) . '#sef-form', 303 );
		exit;
	}
}
