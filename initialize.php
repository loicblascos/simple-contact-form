<?php
/**
 * Initialize plugin
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

namespace Simple_Contact_Form;

use Simple_Contact_Form\Includes\Post_Type;
use Simple_Contact_Form\Includes\Privacy;
use Simple_Contact_Form\Includes\Block;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include autoloader.
require_once SCF_PATH . 'includes/class-autoload.php';

/**
 * Init plugin.
 *
 * @since 1.0.0
 */
function init() {

	new Post_Type();
	new Privacy();
	new Block();

}

add_action( 'plugins_loaded', 'Simple_Contact_Form\init' );
