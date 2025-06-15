/**
 * WordPress dependencies
 */
import { CheckboxControl } from '@wordpress/components';

/**
 * A checkbox field component
 *
 * @param {Object}         props          Component props
 * @param {Object}         props.field    The field definition
 * @param {boolean|string} props.value    The field value
 * @param {Function}       props.onChange Callback for when field value changes
 */
const CheckboxField = ( { field, value, onChange } ) => {
	// Convert value to boolean
	const isChecked = value === true || value === 'true' || value === '1' || value === 1;

	return (
		<CheckboxControl
			label={ field.label }
			help={ field.description }
			checked={ isChecked }
			onChange={ ( newValue ) => {
				// Return boolean value
				onChange( newValue );
			} }
			disabled={ field.disabled || field.readonly }
		/>
	);
};

export default CheckboxField;
