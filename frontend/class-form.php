<?php
/**
 * Form Class
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form\FrontEnd;

use Simple_Contact_Form\Includes\Attachment;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Form
 *
 * @class Simple_Contact_Form\Frontend\Form
 * @since 1.0.0
 */
class Form extends Request {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		parent::__construct();

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_style' ] );

	}

	/**
	 * Submit form
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function submit() {

		$this->attachment = new Attachment();

		$this->validate();
		$this->normalize();
		$this->sanitize();

		$sent = $this->send_email();
		$this->attachment->delete();

		// If an error occured when sending mail.
		if ( ! $sent ) {
			$this->handle( 'error' );
		}

		$this->register_email();
		$this->handle( 'success' );

	}

	/**
	 * Validate required fields
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function validate() {

		$is_valid = isset(
			$this->fields['name'],
			$this->fields['email'],
			$this->fields['subject'],
			$this->fields['message']
		);

		if ( ! $is_valid ) {
			$this->handle( 'error' );
		}

	}

	/**
	 * Normalize optional fields
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function normalize() {

		$this->fields = wp_parse_args(
			$this->fields,
			[
				'register'   => 0,
				'attachment' => [],
			]
		);

	}

	/**
	 * Sanitize fields
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function sanitize() {

		$this->fields = [
			'date'     => date_i18n( 'Y-m-d H:i:s' ),
			'name'     => sanitize_text_field( $this->fields['name'] ),
			'email'    => sanitize_email( $this->fields['email'] ),
			'subject'  => sanitize_text_field( $this->fields['subject'] ),
			'message'  => wp_filter_post_kses( $this->fields['message'] ),
			'register' => (bool) $this->fields['register'],
		];

	}

	/**
	 * Send mail
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean
	 */
	public function send_email() {

		ob_start();
		include_once SCF_PATH . 'templates/email.php';

		$subject = __( 'New contact from enquiry:', 'simple-contact-form' );

		return wp_mail(
			get_option( 'admin_email' ),
			$subject . ' ' . $this->fields['name'],
			ob_get_clean(),
			$this->set_header(),
			$this->attachment->get()
		);

	}

	/**
	 * Set mail header
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function set_header() {

		return [
			'From: ' . $this->fields['email'],
			'Content-Type: text/html',
			'X-SCF-Content-Type: text/html',
		];

	}

	/**
	 * Register subscriber email and name.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_email() {

		if ( ! $this->fields['register'] ) {
			return;
		}

		$exists = get_page_by_title( $this->fields['email'], '', 'simple-contact-form' );

		if ( $exists ) {
			return;
		}

		wp_insert_post(
			[
				'post_type'   => 'simple-contact-form',
				'post_title'  => $this->fields['email'],
				'post_name'   => $this->fields['name'],
				'post_status' => 'publish',
				'meta_input'  => [
					'_scf_name' => $this->fields['name'],
				],
			]
		);

	}

	/**
	 * Render form
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds form attributes.
	 */
	public function render( $attributes ) {

		$defaults = include SCF_PATH . 'block/attributes.php';
		$defaults = array_map(
			function( $args ) {
				return $args['default'];
			},
			$defaults
		);

		$attributes = array_filter( (array) $attributes );
		$attributes = wp_parse_args( $attributes, $defaults );

		include_once SCF_PATH . 'templates/form.php';

		$this->enqueue_script();

	}

	/**
	 * Enqueue frontend style
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_style() {

		wp_enqueue_style( 'scf-front-css', SCF_URL . 'assets/front.build.css', [], SCF_VERSION );

	}

	/**
	 * Enqueue frontend script
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_script() {

		// Do not enqueue in Gutenberg.
		if ( is_admin() ) {
			return;
		}

		// Enqueue form script.
		wp_enqueue_script( 'simple-contact-form', SCF_URL . 'assets/front.build.js', [], SCF_VERSION );
		// Localize script var.
		wp_localize_script( 'simple-contact-form', 'simple_contact_form', $this->localize() );

	}

	/**
	 * Localize ajax url and field custom messages
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function localize() {

		$ajax_url = admin_url( 'admin-ajax.php' );

		return [
			'ajaxUrl'  => esc_url( $ajax_url ),
			'messages' => [
				'name'    => esc_html__( 'Please enter your name.', 'simple-contact-form' ),
				'email'   => esc_html__( 'Please enter your email.', 'simple-contact-form' ),
				'subject' => esc_html__( 'Please enter a subject.', 'simple-contact-form' ),
				'message' => esc_html__( 'Please enter your message.', 'simple-contact-form' ),
				'result'  => esc_html__( 'Please enter the captcha result.', 'simple-contact-form' ),
			],
		];

	}
}
