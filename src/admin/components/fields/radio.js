/**
 * WordPress dependencies
 */
import { RadioControl } from '@wordpress/components';

/**
 * A radio field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const RadioField = ( { field, value, onChange } ) => {
	return <RadioControl label={ field.label } help={ field.description } selected={ value || field.default || '' } options={ field.options || [] } onChange={ onChange } disabled={ field.disabled } />;
};

export default RadioField;
