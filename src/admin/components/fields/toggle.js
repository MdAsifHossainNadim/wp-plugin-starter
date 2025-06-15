/**
 * WordPress dependencies
 */
import { ToggleControl } from '@wordpress/components';

/**
 * A toggle field component
 *
 * @param {Object}         props          Component props
 * @param {Object}         props.field    The field definition
 * @param {boolean|string} props.value    The field value
 * @param {Function}       props.onChange Callback for when field value changes
 */
const ToggleField = ( { field, value, onChange } ) => {
	// Convert value to boolean
	const isChecked = value === true || value === 'true' || value === '1' || value === 1;

	return (
		<ToggleControl
			label={ field.label }
			help={ field.description }
			checked={ isChecked }
			onChange={ ( newValue ) => {
				onChange( newValue );
			} }
			disabled={ field.disabled || field.readonly }
		/>
	);
};

export default ToggleField;
