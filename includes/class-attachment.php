<?php
/**
 * Uplaod/delete attachment from email
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
 * Handle email attachment
 *
 * @class Simple_Contact_Form\Includes\Attachment
 * @since 1.0.0
 */
class Attachment {

	/**
	 * Holds file properties
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $file = [];

	/**
	 * Allowed mime types for attachments
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $mimes = [
		// Image mime types.
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tiff|tif'     => 'image/tiff',
		'ico'          => 'image/x-icon',
		// PDF mime type.
		'pdf'          => 'application/pdf',
	];

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		$this->upload();

	}

	/**
	 * Get WP file system instance
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return object
	 */
	public function filesystem() {

		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
			}

			WP_Filesystem();

		}

		return $wp_filesystem;

	}

	/**
	 * Get attachment
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get() {

		return $this->file;

	}

	/**
	 * Delete attachment from WP upload dir.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function delete() {

		if ( empty( $this->file ) ) {
			return;
		}

		$this->filesystem()->delete( $this->file, false, 'f' );

	}

	/**
	 * Check and get global $_FILES properties.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return boolean|array
	 */
	public function check() {

		// Check if all needed properties are correctly set.
		$is_valid = isset(
			$_FILES['attachment']['tmp_name'],
			$_FILES['attachment']['name'],
			$_FILES['attachment']['type'],
			$_FILES['attachment']['size']
		);

		if ( ! $is_valid ) {
			return false;
		}

		// Allows 1MB file size at maximum (1 048 576 octets).
		if ( $_FILES['attachment']['size'] > 1048576 ) {
			return false;
		}

		return $_FILES;

	}

	/**
	 * Temporarly Upload attachment in WordPress
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function upload() {

		$file = $this->check();

		// Check if a file need to be uploaded and if it is valid.
		if ( ! $file ) {
			return;
		}

		// Override the sideload defaults.
		$overrides = [
			'action'      => 'scf_submit',
			'test_form'   => false,
			'test_size'   => true,
			'test_upload' => true,
			'mimes'       => $this->mimes,
		];

		// Upload file in WordPress upload dir.
		$file = wp_handle_sideload( $file['attachment'], $overrides );

		if ( ! empty( $file['error'] ) ) {
			return;
		}

		$this->file = $file['file'];

	}
}
