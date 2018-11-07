<?php
/**
 * Uninstall plugin
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

// Exit, if uninstall.php is not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( ! current_user_can( 'activate_plugins' ) ) {
	exit;
}

global $wpdb;

$wpdb->query(
	"DELETE post, meta
	FROM {$wpdb->posts} post
	LEFT JOIN {$wpdb->postmeta} meta ON meta.post_id = post.id
	WHERE post.post_type = 'simple-contact-form'"
);
