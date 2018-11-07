<?php
/**
 * Block Attributes
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'align' => [
		'type'    => 'string',
		'default' => '',
	],
	'className' => [
		'type'    => 'string',
		'default' => '',
	],
	'name_label' => [
		'type'    => 'string',
		'default' => __( 'Name', 'simple-contact-form' ),
	],
	'name_placeholder' => [
		'type'    => 'string',
		'default' => __( 'Enter your name', 'simple-contact-form' ),
	],
	'email_label' => [
		'type'    => 'string',
		'default' => __( 'Email', 'simple-contact-form' ),
	],
	'email_placeholder' => [
		'type'    => 'string',
		'default' => __( 'address@email.com', 'simple-contact-form' ),
	],
	'subject_label' => [
		'type'    => 'string',
		'default' => __( 'Subject', 'simple-contact-form' ),
	],
	'subject_placeholder' => [
		'type'    => 'string',
		'default' => __( 'Enter a subject', 'simple-contact-form' ),
	],
	'message_label' => [
		'type'    => 'string',
		'default' => __( 'Message', 'simple-contact-form' ),
	],
	'message_placeholder' => [
		'type'    => 'string',
		'default' => __( 'Enter your message', 'simple-contact-form' ),
	],
	'captcha_label' => [
		'type'    => 'string',
		'default' => __( 'Enter Captcha', 'simple-contact-form' ),
	],
	'attachment_label' => [
		'type'    => 'string',
		'default' => __( 'Attachment', 'simple-contact-form' ),
	],
	'upload_text' => [
		'type'    => 'string',
		'default' => __( 'Select a file', 'simple-contact-form' ),
	],
	'register_text' => [
		'type'    => 'string',
		'default' => __( 'Subscribing you accept the privacy informative.', 'simple-contact-form' ),
	],
	'submit_text' => [
		'type'    => 'string',
		'default' => __( 'Send Your Inquiry', 'simple-contact-form' ),
	],
	'attachment' => [
		'type'    => 'boolean',
		'default' => 0,
	],
	'register' => [
		'type'    => 'boolean',
		'default' => 0,
	],
];
