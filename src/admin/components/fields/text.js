/**
 * WordPress dependencies
 */
import { TextControl as WPTextControl } from '@wordpress/components';

/**
 * A text field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const TextField = ( { field, value, onChange } ) => {
	// Handle different text input types
	const inputType = field.inputType || 'text';

	// Ensure value is a string
	const stringValue = value !== undefined && value !== null ? String( value ) : '';

	return (
		<WPTextControl
			label={ field.label }
			help={ field.description }
			value={ stringValue }
			onChange={ onChange }
			disabled={ field.disabled }
			readOnly={ field.readonly }
			placeholder={ field.placeholder || '' }
			type={ inputType }
			required={ field.required }
			size={ field.size }
		/>
	);
};

export default TextField;
