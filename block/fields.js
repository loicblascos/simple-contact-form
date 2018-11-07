/**
 * WordPress Dependency
 */
const { __ } = wp.i18n;

/**
 * fields (label/input) definitions
 */
export default {
	name_label : {
		className   : 'scf-label',
		fieldName   : 'name_label',
		placeholder : __( 'Name', 'simple-contact-form' ),
	},
	name_placeholder : {
		className   : 'scf-input',
		fieldName   : 'name_placeholder',
		placeholder : __( 'Enter your name', 'simple-contact-form' ),
	},
	email_label : {
		className   : 'scf-label',
		fieldName   : 'email_label',
		placeholder : __( 'Email', 'simple-contact-form' ),
	},
	email_placeholder : {
		className   : 'scf-input',
		fieldName   : 'email_placeholder',
		placeholder : __( 'address@email.com', 'simple-contact-form' ),
	},
	subject_label : {
		className   : 'scf-label',
		fieldName   : 'subject_label',
		placeholder : __( 'Subject', 'simple-contact-form' ),
	},
	subject_placeholder : {
		className   : 'scf-input',
		fieldName   : 'subject_placeholder',
		placeholder : __( 'Enter a subject', 'simple-contact-form' ),
	},
	message_label : {
		className   : 'scf-label',
		fieldName   : 'message_label',
		placeholder : __( 'Message', 'simple-contact-form' ),
	},
	message_placeholder : {
		className   : 'scf-textarea',
		fieldName   : 'message_placeholder',
		placeholder : __( 'Enter your message', 'simple-contact-form' ),
	},
	captcha_label : {
		className   : 'scf-label',
		fieldName   : 'captcha_label',
		placeholder : __( 'Enter your message', 'simple-contact-form' ),
	},
	attachment_label : {
		className   : 'scf-label',
		fieldName   : 'attachment_label',
		placeholder : __( 'Attachment', 'simple-contact-form' ),
	},
	upload_text : {
		className   : 'scf-button',
		fieldName   : 'upload_text',
		placeholder : __( 'Select a File', 'simple-contact-form' ),
	},
	register_text : {
		className   : '',
		fieldName   : 'register_text',
		placeholder : __( 'Subscribing you accept the privacy informative.', 'simple-contact-form' ),
	},
	submit_text : {
		className   : 'scf-submit',
		fieldName   : 'submit_text',
		placeholder : __( 'Send Your Inquiry', 'simple-contact-form' ),
	},
};
