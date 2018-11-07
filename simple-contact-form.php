<?php
/**
 * Simple Contact Form Plugin
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 *
 * @wordpress-plugin
 * Plugin Name:  Simple Contact Form
 * Description:  A simple contact form
 * Version:      1.0.0
 * Author:       Loïc Blascos
 * Text Domain:  simple-contact-form
 * Domain Path:  /languages
 * License:      GPL2+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCF_VERSION', '1.0.0' );
define( 'SCF_NAME', 'Simple Contact Form' );
define( 'SCF_BASE', plugin_basename( __FILE__ ) );
define( 'SCF_PATH', plugin_dir_path( __FILE__ ) );
define( 'SCF_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin text domain for translations.
 *
 * @since 1.0.0
 */
function simple_contact_form_textdomain() {

	load_plugin_textdomain(
		'simple-contact-form',
		false,
		basename( dirname( __FILE__ ) ) . '/languages'
	);

	// Translate Plugin Description.
	__( 'A simple contact form', 'simple-contact-form' );

}
add_action( 'plugins_loaded', 'simple_contact_form_textdomain' );

// Compatibility class.
$compat = require_once SCF_PATH . 'compatibility.php';

// If compatibility issue.
if ( ! $compat->check() ) {
	return;
}

// Initialize plugin.
require_once SCF_PATH . 'initialize.php';
