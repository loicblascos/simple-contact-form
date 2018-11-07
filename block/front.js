/**
 * Submit form
 *
 * @package Simple Contact Form
 * @author  Loïc Blascos
 */

 /* global simple_contact_form */

import Style from './style.scss';

/**
 * Generate random number between a range (min/max)
 *
 * @return {Integer}
 */
const randomize = ( min, max ) => Math.floor( Math.random() * ( max - min + 1 ) + min );

/**
 * Form class to handle submission with fetch
 *
 */
class Form {

	/**
	 * Constructor
	 *
	 * @param {object} form
	 */
	constructor( form ) {

		this.form = form;

		this.getFields();
		this.bindEvents();

	}

	/**
	 * Get all fields
	 */
	getFields() {

		this.fields = new Map();

		[...this.form.querySelectorAll( 'input, textarea' )].forEach(
			field => field.name && this.fields.set( field.name, field )
		);

	}

	/**
	 * Get field
	 *
	 * @param {String} name
	 */
	field( name ) {

		return this.fields.get( name ) || {};

	}

	/**
	 * Bind form events
	 */
	bindEvents() {

		this.form.addEventListener( 'submit', this );
		this.form.addEventListener( 'change', this );
		this.form.addEventListener( 'reset', this );
		this.form.addEventListener( 'click', this );

	}

	/**
	 * Trigger handler methods for events
     *
	 * @param {Object} event
	 */
	handleEvent( event ) {

		const { type } = event;

		if ( this[ type ] ) {
			this[ type ]( event );
		}

	}

	/**
	 * Handle upload input click
	 * Check field validities
	 *
	 * @param {Object} event
	 */
	click( event ) {

		const { target } = event;

		if ( target === this.field( 'upload' ) ) {
			this.field( 'attachment' ).click();
		}

		if ( target === this.field( 'submit' ) ) {
			this.fields.forEach( field => this.check( field ) );
		}

	}

	/**
	 * Handle file input change event
	 *
	 * @param {Object} event
	 */
	change( event ) {

		const { target } = event;
		const { files } = target;

		this.check( target );

		if ( target !== this.field( 'attachment' ) ) {
			return;
		}

		if ( ! this.uploadText ) {
			this.uploadText = this.field( 'upload' ).value;
		}

		this.field( 'upload' ).value = files.length && files[0]['name'] || this.uploadText;

	}

	/**
	 * Handle form reset event
	 * Reset upload field value
	 */
	reset() {

		const { uploadText } = this;

		if ( uploadText ) {
			this.field( 'upload' ).value = uploadText;
		}

		this.fields.forEach( field => field.removeAttribute( 'aria-invalid' ) );

	}

	/**
	 * Check field validity
	 *
	 * @param {Object} field
	 */
	check( field ) {

		if ( ! field.required ) {
			return;
		}

		// Reset message before to check.
		field.setCustomValidity( '' );

		let validity = field.checkValidity();
		let message  = simple_contact_form.messages[ field.name ];

		field.setAttribute( 'aria-invalid', ! validity )
		field.setCustomValidity( ! validity && message ? message : '' );

	}

	/**
	 * Submit form asynchronously
	 *
	 * @param {Object} event
	 */
	submit( event ) {

		// Prevent default form submission.
		event.preventDefault();

		// If currently sending email.
		if ( this.fetching ) {
			return;
		}

		this.fetching = true;

		this.removeResponse();
		this.sendEmail();

	}

	/**
	 * Send email
	 */
	sendEmail() {

		// Fetch from admin-ajax.php url.
		fetch( simple_contact_form.ajaxUrl, {
			method : 'post',
			body   : new FormData( this.form ),
		} ).then( response => {

			if ( ! response.ok ) {
				throw Error( response.statusText );
			}

			return response.json();

		} ).then( data => {
			this.handle( data );
		} ).catch( error =>  {
			this.handle( error );
		} );

	}

	/**
	 * Handle asynchronous response.
	 *
	 * @param {Object} data
	 */
	handle( data ) {

		const { success, message } = data;

		// Clear form fields if succeed.
		if ( success ) {
			this.form.reset();
		}

		this.resetCaptcha();

		if ( ! success ) {
			this.fields.forEach( field => this.check( field ) );
		}

		this.addResponse( success, message );
		// Unset fetching status to allows new request.
		delete this.fetching;

	}

	/**
	 * Display response (success/error) under the form.
	 *
	 * @param {Boolean} success
	 * @param {String} message
	 */
	addResponse( success, message ) {

		const className = success ? 'success' : 'error';

		// Message was already escaped (see templates/form.php).
		this.response = document.createElement( 'div' );
		this.response.className = 'scf-response scf-' + className;
		this.response.innerHTML = message;

		this.form.classList.remove( 'scf-loading' );
		this.form.appendChild( this.response );

	}

	/**
	 * Remove response (success/error) from previous request.
	 */
	removeResponse() {

		if ( this.response ) {
			this.response.remove();
		}

		this.form.classList.add( 'scf-loading' );

	}

	/**
	 * Reset captcha code
	 */
	resetCaptcha() {

		this.field( 'result' ).value = '';
		this.field( 'captcha' ).value = `${randomize( 1, 9 )} + ${randomize( 1, 9 )}`;

	}

}

const form = document.querySelector( 'form#simple-contact-form' );

// If fetch supported.
if ( form && self.fetch ) {
	new Form( form );
}
