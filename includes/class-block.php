<?php
/**
 * Handle block
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form\Includes;

use Simple_Contact_Form\FrontEnd\Form;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Gutenberg Block
 *
 * @class Simple_Contact_Form\Includes\Block
 * @since 1.0.0
 */
class Block {

	/**
	 * Holds From class instance
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @var object
	 */
	private $form;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		$this->form = new Form();

		add_action( 'init', [ $this, 'register_block' ] );
		add_action( 'init', [ $this, 'editor_assets' ] );

		// Fallback for shortcode (classic editor).
		add_shortcode( 'simple_contact_form', [ $this, 'render_block' ] );

	}

	/**
	 * Register block for Gutenberg
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_block() {

		// Make sure Gutenberg exists.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Register block.
		register_block_type(
			'scf-block/contact-form',
			[
				'attributes'      => include SCF_PATH . 'block/attributes.php',
				'editor_script'   => 'scf-editor-js',
				'editor_style'    => 'scf-editor-css',
				'render_callback' => [ $this, 'render_block' ],
			]
		);

	}

	/**
	 * Render dynamique block on frontend
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $attributes Holds block attributes.
	 */
	public function render_block( $attributes ) {

		ob_start();
		$this->form->render( $attributes );
		return ob_get_clean();

	}

	/**
	 * Enqueue the block and editor assets for the editor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function editor_assets() {

		// Register editor stylesheet.
		wp_register_style( 'scf-editor-css', SCF_URL . 'assets/editor.build.css', [ 'wp-edit-blocks' ], SCF_VERSION );
		// Register editor JavaScript.
		wp_register_script( 'scf-editor-js', SCF_URL . 'assets/editor.build.js', [ 'wp-blocks', 'wp-element', 'wp-i18n' ], SCF_VERSION );

		// Add translations.
		$this->i18n_register();

	}

	/**
	 * Add the i18n script
	 * Internalization for Gutenberg.
	 *
	 * @since 1.0.0
	 */
	public function i18n_register() {

		if ( ! function_exists( 'gutenberg_get_jed_locale_data' ) ) {
			return;
		}

		// Get translations.
		$locale = gutenberg_get_jed_locale_data( 'simple-contact-form' );
		// Add translations to wp.i18n.setLocaleData.
		$content = 'wp.i18n.setLocaleData(' . json_encode( $locale ) . ', "simple-contact-form" );';

		wp_script_add_data( 'scf-editor-js', 'data', $content );

	}
}
