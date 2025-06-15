/**
 * WordPress dependencies
 */
import { SelectControl } from '@wordpress/components';

/**
 * A select field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const SelectField = ( { field, value, onChange } ) => {
	// Format options for SelectControl
	const options = field.options || [];

	// Ensure we have proper options
	const formattedOptions = Array.isArray( options )
		? options.map( ( option ) => {
			if ( typeof option === 'object' && option.value !== undefined ) {
				return option;
			}
			return {
				value: option,
				label: option,
			};
		} )
		: Object.entries( options ).map( ( [ valueData, label ] ) => ( {
			value: valueData,
			label,
		} ) );

	return <SelectControl label={ field.label } help={ field.description } value={ value || '' } options={ formattedOptions } onChange={ onChange } disabled={ field.disabled } multiple={ field.multiple } />;
};

export default SelectField;
