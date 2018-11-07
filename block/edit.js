/**
 * Internal Dependencies
 */
import Form from './form';
import Controls from "./controls"
import Inspector from "./inspector"

/**
 * WordPress Dependencies
 */
const { Fragment } = wp.element;

/**
 * Block edit component
 *
 * @param {object} props
 */
export default props => {

	return (
		<Fragment>
			<Controls { ...props } />
			<Inspector { ...props } />
			<Form { ...props } />
		</Fragment>
	);

}
