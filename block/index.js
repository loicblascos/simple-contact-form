/**
 * Internal Dependencies
 */
import Style from './style.scss';
import Editor from './editor.scss';
import icon from './icon';
import edit from './edit';

/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Register block.
 */
export default registerBlockType( 'scf-block/contact-form', {
	title : __( 'Contact Form', 'simple-contact-form' ),
	description : __( 'Add a contact form.', 'simple-contact-form' ),
	keyword : [
		__( 'contact', 'simple-contact-form' ),
		__( 'form', 'simple-contact-form' ),
	],
	icon,
	category : 'common',
	supports : {
		multiple : false,
	},
	getEditWrapperProps( { align } ) {

		const isAligned = [ 'center', 'wide', 'full' ].includes( align );
		return isAligned && { 'data-align': align };

	},
	edit,
	save : () => {},
} )
