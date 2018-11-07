<?php
/**
 * Handle privacy to be RGPD compliant
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form\Includes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook int WordPress Privacy to export/delete subscriber data.
 *
 * @class Simple_Contact_Form\Includes\Privacy
 * @since 1.0.0
 */
class Privacy {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_filter( 'wp_privacy_personal_data_erasers', [ $this, 'register_eraser' ], 10 );
		add_filter( 'wp_privacy_personal_data_exporters', [ $this, 'register_exporter' ], 10 );

	}

	/**
	 * Register privacy eraser callback
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $erasers An array of callable exporters of personal data.
	 * @return array
	 */
	public function register_eraser( $erasers ) {

		$erasers['simple-contact-form'] = [
			'eraser_friendly_name' => __( 'Simple Contact Form Plugin', 'simple-contact-form' ),
			'callback'             => [ $this, 'erase_subscriber' ],
		];

		return $erasers;

	}

	/**
	 * Register privacy exporter callback
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $exporters An array of callable exporters of personal data.
	 * @return array
	 */
	public function register_exporter( $exporters ) {

		$exporters['simple-contact-form'] = [
			'exporter_friendly_name' => __( 'Simple Contact Form Plugin', 'simple-contact-form' ),
			'callback'               => [ $this, 'export_subscriber' ],
		];

		return $exporters;

	}

	/**
	 * Erase subscriber (delete post)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $email_address Email adress to match in order to delete post.
	 * @param integer $page          Page number of the privacy exporter.
	 * @return array
	 */
	public function erase_subscriber( $email_address, $page = 1 ) {

		$removed = false;
		$subscriber = $this->get_subscriber( $email_address );

		if ( ! empty( $subscriber->ID ) ) {
			// Set to true to bypass trash and force deletion.
			$removed = wp_delete_post( $subscriber->ID, true );
		}

		return [
			'items_removed'  => (bool) $removed,
			'items_retained' => false,
			'messages'       => [],
			'done'           => true,
		];

	}

	/**
	 * Export subscriber (delete post)
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string  $email_address Email adress to match in order to export post.
	 * @param integer $page          Page number of the privacy exporter.
	 * @return array
	 */
	public function export_subscriber( $email_address, $page = 1 ) {

		$data = [];
		$subscriber = $this->get_subscriber( $email_address );

		if ( ! empty( $subscriber->ID ) ) {

			$subscriber_name = get_post_meta( $subscriber->ID, '_scf_name', true );

			$data[] = [
				'group_id'    => 'posts',
				'group_label' => 'Simple Contact Form',
				'item_id'     => 'simple-contact-form-' . $subscriber->ID,
				'data'        => [
					[
						'name'  => __( 'Email Adress', 'simple-contact-form' ),
						'value' => sanitize_email( $subscriber->post_title ),
					],
					[
						'name' => __( 'Subscriber', 'simple-contact-form' ),
						'value' => sanitize_text_field( $subscriber_name ),
					],
				],
			];
		}

		return [
			'data' => $data,
			'done' => true,
		];

	}

	/**
	 * Get subscriber from email
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $email_address Email adress to match.
	 * @return object
	 */
	public function get_subscriber( $email_address ) {

		return get_page_by_title( $email_address, '', 'simple-contact-form' );

	}
}
