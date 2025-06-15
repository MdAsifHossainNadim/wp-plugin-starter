/**
 * WordPress dependencies
 */
import { TextControl } from '@wordpress/components';

/**
 * A number field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {number}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const NumberField = ( { field, value, onChange } ) => {
	// Get min, max, and step values
	const min = field.min !== undefined ? field.min : field.minimum;
	const max = field.max !== undefined ? field.max : field.maximum;
	const step = field.step || 1;

	// Convert value to string for the input
	const stringValue = value !== undefined && value !== null ? String( value ) : '';

	// Handle value change
	const handleChange = ( newValue ) => {
		// If empty, allow it (will be handled by validation if required)
		if ( newValue === '' ) {
			onChange( '' );
			return;
		}

		// Convert to number
		const numValue = parseFloat( newValue );

		// Check if it's a valid number
		if ( isNaN( numValue ) ) {
			return;
		}

		// Apply min/max constraints
		let constrainedValue = numValue;
		if ( min !== undefined && numValue < min ) {
			constrainedValue = min;
		}
		if ( max !== undefined && numValue > max ) {
			constrainedValue = max;
		}

		onChange( constrainedValue );
	};

	return (
		<TextControl
			type="number"
			label={ field.label }
			help={ field.description }
			value={ stringValue }
			onChange={ handleChange }
			min={ min }
			max={ max }
			step={ step }
			disabled={ field.disabled }
			readOnly={ field.readonly }
			placeholder={ field.placeholder }
			className={ field.hasError ? 'dk-has-error' : '' }
		/>
	);
};

export default NumberField;
