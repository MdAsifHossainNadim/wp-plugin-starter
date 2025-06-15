/**
 * WordPress dependencies
 */
import { RangeControl } from '@wordpress/components';

/**
 * A range field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {number}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const RangeField = ( { field, value, onChange } ) => {
	// Get min, max, and step values
	const min = field.min !== undefined ? field.min : field.minimum;
	const max = field.max !== undefined ? field.max : field.maximum;
	const step = field.step || 1;

	// Convert value to number for the input
	const numValue = value !== undefined && value !== null ? Number( value ) : '';

	return (
		<RangeControl
			label={ field.label }
			help={ field.description }
			value={ numValue }
			onChange={ onChange }
			min={ min }
			max={ max }
			step={ step }
			allowReset={ field.allowReset || false }
			resetFallbackValue={ field.default || min }
			disabled={ field.disabled }
			withInputField={ true }
			showTooltip={ true }
			railColor={ field.railColor || undefined }
			trackColor={ field.trackColor || undefined }
			marks={ field.marks || false }
			className={ field.hasError ? 'dk-has-error' : '' }
		/>
	);
};

export default RangeField;
