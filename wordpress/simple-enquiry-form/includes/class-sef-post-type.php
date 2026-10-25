<?php
/**
 * Where enquiries are kept: a private post type with its own list screen.
 *
 * @package Simple_Enquiry_Form
 */

defined( 'ABSPATH' ) || exit;

class SEF_Post_Type {

	const POST_TYPE = 'sef_enquiry';

	/**
	 * Enquiries are never public: no front-end URL, no search, no REST route.
	 * Nobody can create one from the dashboard either — they only arrive
	 * through the form.
	 */
	public static function register() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => array(
					'name'          => __( 'Enquiries', 'simple-enquiry-form' ),
					'singular_name' => __( 'Enquiry', 'simple-enquiry-form' ),
					'menu_name'     => __( 'Enquiries', 'simple-enquiry-form' ),
					'all_items'     => __( 'All enquiries', 'simple-enquiry-form' ),
					'edit_item'     => __( 'Enquiry', 'simple-enquiry-form' ),
					'search_items'  => __( 'Search enquiries', 'simple-enquiry-form' ),
					'not_found'     => __( 'No enquiries yet.', 'simple-enquiry-form' ),
				),
				'public'              => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_rest'        => false,
				'menu_position'       => 26,
				'menu_icon'           => 'dashicons-email-alt',
				'supports'            => array( 'title', 'editor' ),
				'capability_type'     => 'post',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
			)
		);
	}

	public static function init_admin() {
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'column' ), 10, 2 );
		add_action( 'add_meta_boxes_' . self::POST_TYPE, array( __CLASS__, 'add_meta_box' ) );
	}

	public static function columns( $columns ) {
		return array(
			'cb'          => $columns['cb'],
			'title'       => __( 'Name', 'simple-enquiry-form' ),
			'sef_email'   => __( 'Email', 'simple-enquiry-form' ),
			'sef_phone'   => __( 'Phone', 'simple-enquiry-form' ),
			'sef_message' => __( 'Message', 'simple-enquiry-form' ),
			'date'        => __( 'Received', 'simple-enquiry-form' ),
		);
	}

	public static function column( $column, $post_id ) {
		switch ( $column ) {
			case 'sef_email':
				$email = get_post_meta( $post_id, '_sef_email', true );
				if ( $email ) {
					printf( '<a href="%s">%s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) );
				}
				break;
			case 'sef_phone':
				$phone = get_post_meta( $post_id, '_sef_phone', true );
				echo $phone ? esc_html( $phone ) : '&mdash;';
				break;
			case 'sef_message':
				echo esc_html( wp_trim_words( get_post_field( 'post_content', $post_id ), 14 ) );
				break;
		}
	}

	public static function add_meta_box() {
		add_meta_box( 'sef-details', __( 'Contact details', 'simple-enquiry-form' ), array( __CLASS__, 'render_meta_box' ), self::POST_TYPE, 'side', 'high' );
	}

	public static function render_meta_box( $post ) {
		$rows = array(
			__( 'Email', 'simple-enquiry-form' )   => get_post_meta( $post->ID, '_sef_email', true ),
			__( 'Phone', 'simple-enquiry-form' )   => get_post_meta( $post->ID, '_sef_phone', true ),
			__( 'Subject', 'simple-enquiry-form' ) => get_post_meta( $post->ID, '_sef_subject', true ),
			__( 'Sent from', 'simple-enquiry-form' ) => get_post_meta( $post->ID, '_sef_page', true ),
		);
		echo '<dl class="sef-meta">';
		foreach ( $rows as $label => $value ) {
			printf( '<dt><strong>%s</strong></dt><dd>%s</dd>', esc_html( $label ), $value ? esc_html( $value ) : '&mdash;' );
		}
		echo '</dl>';
	}
}
