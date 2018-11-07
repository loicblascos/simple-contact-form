/**
 * Default fields attributes
 */
import fields from './fields.js';

/**
 * WordPress Dependencies
 */
const { RichText } = wp.editor;
const { Component, Fragment } = wp.element;

/**
 * Form component
 *
 * @param {object} props
 */
export default class Form extends Component {

	constructor( props ) {

		super( ...arguments );

	}

	richText( type ) {

		if ( ! fields[ type ] ) {
			return;
		}

		const {
			setAttributes,
			attributes,
		} = this.props;

		const  {
			className,
			fieldName,
			placeholder,
		} = fields[ type ];

		return (
			<RichText
				tagName="div"
				className={ className }
				value={ attributes[ fieldName ] }
				onChange={ value => setAttributes( { [ fieldName ]: value } ) }
				placeholder={ placeholder }
				formattingControls={ [] }
				format="string"
				multiline="false"
			/>
		);

	}

	render() {

		const {
			setAttributes,
		} = this.props;
		const {
			attributes : {
				register,
				attachment,
			}
		} = this.props;

		return (
			<div id="simple-contact-form">

				<fieldset>

					{ this.richText( 'name_label' ) }
					{ this.richText( 'name_placeholder' ) }

					{ this.richText( 'email_label' ) }
					{ this.richText( 'email_placeholder' ) }

					{ this.richText( 'subject_label' ) }
					{ this.richText( 'subject_placeholder' ) }

					{ this.richText( 'message_label' ) }
					{ this.richText( 'message_placeholder' ) }

					{ !! attachment &&
						<Fragment>
							{ this.richText( 'attachment_label' ) }
							<div class="scf-attachment-holder">
								{ this.richText( 'upload_text' ) }
							</div>
						</Fragment>
					}

					{ this.richText( 'captcha_label' ) }
					<div class="scf-captcha-holder">
						<input type="text" id="scf-result" name="captcha" value="2 + 3" tabindex="-1" readonly/>
						<span> = </span>
						<input type="number" id="scf-captcha" name="result" min="0" autocomplete="off" disabled/>
					</div>

					{ !! register &&
						<label class="scf-checkbox-holder" for="scf-register">
							<input type="checkbox" id="scf-register" name="register" disabled/>
							<span class="scf-checkbox-control"></span>
							<span class="scf-checkbox-label">{ this.richText( 'register_text' ) }</span>
						</label>
					}

				</fieldset>

				{ this.richText( 'submit_text' ) }

			</div>
		);

	}
}
