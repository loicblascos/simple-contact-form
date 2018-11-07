<?php
/**
 * Request abstract Class
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form\FrontEnd;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Form Request
 *
 * @class Simple_Contact_Form\Frontend\Request
 * @since 1.0.0
 */
abstract class Request {

	/**
	 * Holds unslashed $_POST fields
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Handle ajax submission.
		add_action( 'wp_ajax_nopriv_scf_submit', [ $this, 'maybe_handle' ] );
		add_action( 'wp_ajax_scf_submit', [ $this, 'maybe_handle' ] );

		// Fallback if JavaScript disabled.
		add_action( 'admin_post_nopriv_scf_submit', [ $this, 'maybe_handle' ] );
		add_action( 'admin_post_scf_submit', [ $this, 'maybe_handle' ] );

	}

	/**
	 * Handle request
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function maybe_handle() {

		$this->capability();
		$this->referer();
		$this->captcha();
		$this->submit();

	}

	/**
	 * Handle response (redirect or send json for Ajax)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $message Holds message type.
	 */
	public function handle( $message ) {

		$success = 'success' === $message;

		// If ajax request.
		if ( wp_doing_ajax() ) {

			$message = $this->response( $message );

			wp_send_json(
				[
					'success' => $success,
					'message' => wpautop( esc_html( $message ) ),
				]
			);

		}

		$referer  = wp_get_referer();
		$redirect = add_query_arg( 'scf_response', $message, $referer );

		// Redirect to form page with response type as query string in url.
		wp_safe_redirect( esc_url_raw( $redirect ), 302, 'Simple Contact Form' );
		exit;

	}

	/**
	 * Check capability
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function capability() {

		if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
			$this->handle( 'error' );
		}

	}

	/**
	 * Check referer
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function referer() {

		$nonce = isset( $_POST['scf_submit_nonce'] ) ? sanitize_key( $_POST['scf_submit_nonce'] ) : false;

		if ( is_user_logged_in() && ! wp_verify_nonce( $nonce, 'scf_submit' ) ) {
			$this->handle( 'error' );
		}

		$this->fields = wp_unslash( $_POST );

	}

	/**
	 * Check captcha
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function captcha() {

		if ( empty( $this->fields['captcha'] ) || empty( $this->fields['result'] ) ) {
			$this->handle( 'captcha' );
		}

		$result  = (int) $this->fields['result'];
		$captcha = explode( ' + ', $this->fields['captcha'] ?: '' );
		$captcha = array_map( 'intval', $captcha );

		if ( ! isset( $captcha[0], $captcha[1] ) || $result !== $captcha[0] + $captcha[1] ) {
			$this->handle( 'captcha' );
		}

	}

	/**
	 * Get success/error response message
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @param string $type Message type.
	 * @return string
	 */
	public function response( $type ) {

		$response = '';

		switch ( $type ) {
			case 'success':
				$response = __( "Thank you for contacting us!\nWe received your inquiry and will get back to you soon.", 'simple-contact-form' );
				break;
			case 'error':
				$response = __( "Sorry, an error occured...\nPlease try to refresh the page and submit inquiry again.", 'simple-contact-form' );
				break;
			case 'captcha':
				$response = __( "Sorry, captcha verification failed.\nPlease re-enter result in the captcha field and to submit again.", 'simple-contact-form' );
				break;
		}

		return $response;

	}

	/**
	 * Submit
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	abstract protected function submit();
}
