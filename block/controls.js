/**
 * WordPress Dependencies
 */
const {
	BlockControls,
	BlockAlignmentToolbar,
} = wp.editor;

/**
 * Block controls component
 *
 * @param {object} props
 */
export default props => {

	const {
		attributes,
		setAttributes,
	} = props;

	const { align } = attributes;

	return (
		<BlockControls key="controls">
			<BlockAlignmentToolbar
				value={ align }
				onChange={ align => setAttributes( { align } ) }
				controls={ [ 'center', 'wide', 'full' ] }
			/>
		</BlockControls>
	);

}
