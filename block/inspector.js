/**
 * WordPress Dependencies
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { InspectorControls } = wp.editor;
const {
  PanelBody,
  TextControl,
  ToggleControl,
} = wp.components;


/**
 * Block inspector control.
 *
 * @param {object} props component props
 * @returns {object} Component
 */
export default props => {

	const {
		setAttributes,
		attributes,
	} = props;

	const {
		name_label,
		name_placeholder,
		email_label,
		email_placeholder,
		subject_label,
		subject_placeholder,
		message_label,
		message_placeholder,
		attachment_label,
		upload_text,
		captcha_label,
		register_text,
		submit_text,
		attachment,
		register,
	} = attributes;

	return (
		<InspectorControls>

			<PanelBody title={ __( 'Field Labels', 'simple-contact-form' ) }>
				<TextControl
					label={ __( 'Name Label', 'simple-contact-form' ) }
					value={ name_label }
					onChange={ name_label => setAttributes( { name_label } ) }
				/>
				<TextControl
					label={ __( 'Email Label', 'simple-contact-form' ) }
					value={ email_label }
					onChange={ email_label => setAttributes( { email_label } ) }
				/>
				<TextControl
					label={ __( 'Subject Label', 'simple-contact-form' ) }
					value={ subject_label }
					onChange={ subject_label => setAttributes( { subject_label } ) }
				/>
				<TextControl
					label={ __( 'Message Label', 'simple-contact-form' ) }
					value={ message_label }
					onChange={ message_label => setAttributes( { message_label } ) }
				/>
				<TextControl
					label={ __( 'Captcha Label', 'simple-contact-form' ) }
					value={ captcha_label }
					onChange={ captcha_label => setAttributes( { captcha_label } ) }
				/>
				<TextControl
					label={ __( 'Submit Text', 'simple-contact-form' ) }
					value={ submit_text }
					onChange={ submit_text => setAttributes( { submit_text } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Field Placeholders', 'simple-contact-form' ) } initialOpen={ false }>
				<TextControl
					label={ __( 'Name Placeholder', 'simple-contact-form' ) }
					value={ name_placeholder }
					onChange={ name_placeholder => setAttributes( { name_placeholder } ) }
				/>
				<TextControl
					label={ __( 'Email Placeholder', 'simple-contact-form' ) }
					value={ email_placeholder }
					onChange={ email_placeholder => setAttributes( { email_placeholder } ) }
				/>
				<TextControl
					label={ __( 'Subject Placeholder', 'simple-contact-form' ) }
					value={ subject_placeholder }
					onChange={ subject_placeholder => setAttributes( { subject_placeholder } ) }
				/>
				<TextControl
					label={ __( 'Message Placeholder', 'simple-contact-form' ) }
					value={ message_placeholder }
					onChange={ message_placeholder => setAttributes( { message_placeholder } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Attachement Settings', 'simple-contact-form' ) } initialOpen={ false }>
				<ToggleControl
					label={ __( 'Enable Attachment', 'simple-contact-form' ) }
					checked={ attachment }
					onChange={ attachment => { setAttributes( { attachment } ) } }
				/>
				{ !! attachment &&
					<Fragment>
						<TextControl
							label={ __( 'Attachment Label', 'simple-contact-form' ) }
							value={ attachment_label }
							onChange={ attachment_label => setAttributes( { attachment_label } ) }
						/>
						<TextControl
							label={ __( 'Upload Text', 'simple-contact-form' ) }
							value={ upload_text }
							onChange={ upload_text => setAttributes( { upload_text } ) }
						/>
					</Fragment>
				}
			</PanelBody>

			<PanelBody title={ __( 'Subscribe Settings', 'simple-contact-form' ) } initialOpen={ false }>
				<ToggleControl
					label={ __( 'Register Email', 'simple-contact-form' ) }
					checked={ register }
					onChange={ register => { setAttributes( { register } ) } }
				/>
				{ !! register &&
					<TextControl
						label={ __( 'Register Text', 'simple-contact-form' ) }
						value={ register_text }
						onChange={ register_text => setAttributes( { register_text } ) }
					/>
				}
			</PanelBody>

		</InspectorControls>
	);

}
