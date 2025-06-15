/**
 * WordPress dependencies
 */
import { CustomSelectControlV2 } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './multiselect.scss';

/**
 * A multi-select field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {Array}    props.value    The field value (array of selected values)
 * @param {Function} props.onChange Callback for when field value changes
 */
const MultiSelectField = ( { field, value, onChange } ) => {
	// Format options for CustomSelectControlV2
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

	// Ensure value is always an array
	let selectedValues = [];
	if ( Array.isArray( value ) ) {
		selectedValues = value;
	} else if ( value ) {
		selectedValues = [ value ];
	}

	// Find the selected options objects based on their values
	const selectedOptions = selectedValues.map( ( selectedValue ) =>
		formattedOptions.find( ( option ) => option.value === selectedValue ) || { value: selectedValue, label: selectedValue }
	);

	return (
		<CustomSelectControlV2
			label={ field.label }
			help={ field.description }
			options={ formattedOptions }
			onChange={ ( { selectedItems } ) => onChange( selectedItems.map( ( item ) => item.value ) ) }
			value={ selectedOptions }
			isMultiple={ true }
			__next40pxDefaultSize
			className="wp-plugin-starter-multiselect-field"
			disabled={ field.disabled }
		/>
	);
};

export default MultiSelectField;
