/**
 * WordPress dependencies
 */
import { useId } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import './code-field.scss';

/**
 * A code field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {string}   props.value    The field value
 * @param {Function} props.onChange Callback for when field value changes
 */
const CodeField = ( { field, value, onChange } ) => {
	// Generate a unique ID for this field
	const fieldId = useId();
	const textareaId = `wp-plugin-starter-code-${ field.id || fieldId }`;

	return (
		<div className="wp-plugin-starter-code-field">
			{ field.label && <label htmlFor={ textareaId } className="wp-plugin-starter-code-field-label">{ field.label }</label> }

			<div className="wp-plugin-starter-code-editor">
				<textarea
					id={ textareaId }
					className="wp-plugin-starter-code-textarea"
					value={ value || '' }
					onChange={ ( e ) => onChange( e.target.value ) }
					disabled={ field.disabled }
					placeholder={ field.placeholder || '' }
					rows={ field.rows || 10 }
					spellCheck="false"
				/>
			</div>

			{ field.description && <p className="wp-plugin-starter-code-field-description">{ field.description }</p> }
		</div>
	);
};

export default CodeField;
