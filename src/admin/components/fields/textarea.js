/**
 * WordPress dependencies
 */
import { TextareaControl } from '@wordpress/components';

/**
 * A textarea field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const TextareaField = ( { field, value, onChange } ) => {
	return (
		<TextareaControl label={ field.label } help={ field.description } value={ value || '' } onChange={ onChange } disabled={ field.disabled } placeholder={ field.placeholder || '' } rows={ field.rows || 4 } />
	);
};

export default TextareaField;
