/**
 * WordPress dependencies
 */
import { ColorPicker } from '@wordpress/components';
import { useId } from '@wordpress/element';

/**
 * A color field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const ColorField = ( { field, value, onChange } ) => {
	// Generate a unique ID for this field
	const fieldId = useId();
	const colorPickerId = `wp-plugin-starter-color-${ field.id || fieldId }`;

	return (
		<div className="wp-plugin-starter-color-field">
			<div className="wp-plugin-starter-color-field-label">
				{ field.label && <label htmlFor={ colorPickerId }>{ field.label }</label> }
			</div>
			<ColorPicker
				id={ colorPickerId }
				color={ value || field.default || '#000000' }
				onChangeComplete={ ( color ) => {
					let colorValue;

					if ( typeof color.hex === 'string' ) {
						colorValue = color.hex;
					} else {
						colorValue = `rgba(${ color.rgb.r }, ${ color.rgb.g }, ${ color.rgb.b }, ${ color.rgb.a })`;
					}

					onChange( colorValue );
				} }
				disableAlpha={ field.disableAlpha === true }
			/>
			{ field.description && <p className="wp-plugin-starter-color-field-description">{ field.description }</p> }
		</div>
	);
};

export default ColorField;
