/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
/**
 * External dependencies
 */
import ButtonField from '@admin/components/fields/button';
import CheckboxField from '@admin/components/fields/checkbox';
import CodeField from '@admin/components/fields/code';
import ColorField from '@admin/components/fields/color';
import MediaField from '@admin/components/fields/media';
import MultiSelectField from '@admin/components/fields/multiselect';
import NumberField from '@admin/components/fields/number';
import RadioField from '@admin/components/fields/radio';
import RangeField from '@admin/components/fields/range';
import SelectField from '@admin/components/fields/select';
import TextField from '@admin/components/fields/text';
import TextareaField from '@admin/components/fields/textarea';
import ToggleField from '@admin/components/fields/toggle';

import './field.scss';

/**
 * Maps field types to their components
 */
const FIELD_COMPONENTS = {
	toggle: ToggleField,
	text: TextField,
	select: SelectField,
	multiselect: MultiSelectField,
	number: NumberField,
	range: RangeField,
	color: ColorField,
	textarea: TextareaField,
	radio: RadioField,
	checkbox: CheckboxField,
	media: MediaField,
	code: CodeField,
	button: ButtonField,
};

/**
 * Maps variant types from structure to field component types
 * This helps translate between backend 'variant' and frontend 'type'
 */
const VARIANT_TO_TYPE_MAP = {
	text: 'text',
	textarea: 'textarea',
	select: 'select',
	multiselect: 'multiselect',
	number: 'number', // Will be overridden to 'range' if display === 'range'
	checkbox: 'checkbox',
	radio: 'radio',
	color: 'color',
	media: 'media',
	code: 'code',
	toggle: 'toggle',
	button: 'button',
};

/**
 * Renders a field based on its type
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {*}        props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const FieldRenderer = ( { field, value, onChange } ) => {
	// Determine the field type to use from our structure
	let fieldType = field.type;

	// Map variant from the structure to our component types
	if ( field.variant ) {
		// Get the type from our mapping, defaulting to 'text' if not found
		fieldType = VARIANT_TO_TYPE_MAP[ field.variant ] || 'text';

		// Special case for number fields with range display
		if ( field.variant === 'number' && field.display === 'range' ) {
			fieldType = 'range';
		}
	}

	// Get the component for this field type
	const FieldComponent = FIELD_COMPONENTS[ fieldType ];

	// If no component is found for this field type
	if ( ! FieldComponent ) {
		return (
			<div className="wp-plugin-starter-field-error">
				<p>
					{ __( 'Unknown field type:', 'wp-plugin-starter' ) }
					<code>{ fieldType }</code>
				</p>
			</div>
		);
	}

	// Prepare field props based on our structure
	const fieldProps = {
		...field,
		// Ensure label is present (from title if not)
		label: field.label || field.title || '',
		// Include options if present
		options: field.options || [],
		// Include any other relevant field properties
		placeholder: field.placeholder || '',
		readonly: field.readonly || false,
		disabled: field.disabled || false,
		// For number fields
		min: field.minimum,
		max: field.maximum,
		step: field.step,
	};

	// Render the field component
	return <FieldComponent field={ fieldProps } value={ value } onChange={ onChange } />;
};

export default FieldRenderer;
