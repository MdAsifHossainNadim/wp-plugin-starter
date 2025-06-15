/**
 * WordPress dependencies
 */
import { useEffect, useMemo, useRef, useState } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */

/**
 * Internal dependencies
 */
import FieldRenderer from '@admin/components/fields';
import { isFieldVisible } from '@admin/utils/structure-helpers';

import './section.scss';

/**
 * Map variant types from structure to field component types
 * Using an object map for better performance and maintainability
 */
const VARIANT_TYPE_MAP = {
	text: 'text',
	textarea: 'textarea',
	select: 'select',
	number: 'number',
	checkbox: 'checkbox',
	radio: 'radio',
	color: 'color',
	media: 'media',
	code: 'code',
	toggle: 'toggle',
};

/**
 * Map variant types from structure to field component types
 *
 * @param  {string} variant The variant from structure
 * @return {string}         The field component type
 */
const mapVariantToType = ( variant ) => {
	return VARIANT_TYPE_MAP[ variant ] || 'text';
};

/**
 * Renders a features section with its fields
 *
 * @param {Object}   props                  Component props
 * @param {string}   props.tabId            The parent tab ID
 * @param {string}   props.sectionId        The section ID
 * @param {Object}   props.fields           The fields to render (as an object)
 * @param {Object}   props.settings         The settings values
 * @param {Object}   props.validationErrors Validation errors object
 * @param {Function} props.onSettingChange  Callback when settings are changed
 */
const SettingsSection = ( { tabId, sectionId, fields, settings, validationErrors = {}, onSettingChange } ) => {
	const [ fieldAnimations, setFieldAnimations ] = useState( {} );
	const prevVisibleFieldsRef = useRef( {} );
	const prevValidationErrorsRef = useRef( {} );

	// Create a field map with visibility status, memoized to prevent unnecessary re-renders
	const visibleFields = useMemo( () => {
		return Object.values( fields ).map( ( field ) => {
			// Use dependency_key if available, otherwise construct a key
			const settingKey = field.dependency_key || `${ tabId }.${ sectionId }.${ field.id }`;
			const value = settings[ settingKey ];

			// Check visibility for each field
			const isVisible = isFieldVisible( field, settings );

			return {
				field,
				settingKey,
				value,
				isVisible,
				hasError: validationErrors[ settingKey ] !== undefined,
				errorMessage: validationErrors[ settingKey ],
			};
		} );
	}, [ fields, settings, tabId, sectionId, validationErrors ] );

	// Update animation states when visibility changes
	useEffect( () => {
		const newAnimationStates = {};
		const currentVisibleFieldIds = {};

		// Track currently visible fields
		visibleFields.forEach( ( { field, isVisible } ) => {
			if ( isVisible ) {
				currentVisibleFieldIds[ field.id ] = true;

				// If field wasn't visible before but is now, add entry animation
				if ( ! prevVisibleFieldsRef.current[ field.id ] ) {
					newAnimationStates[ field.id ] = 'animate-field-enter';
				}
			}
		} );

		// Update animation states if we have new ones
		if ( Object.keys( newAnimationStates ).length > 0 ) {
			setFieldAnimations( ( prev ) => ( {
				...prev,
				...newAnimationStates,
			} ) );

			// Clear animations after they've played
			const animatedFieldIds = Object.keys( newAnimationStates );
			setTimeout( () => {
				setFieldAnimations( ( prev ) => {
					const updated = { ...prev };
					animatedFieldIds.forEach( ( id ) => {
						delete updated[ id ];
					} );
					return updated;
				} );
			}, 500 ); // Slightly longer than the animation duration
		}

		// Update ref for next comparison
		prevVisibleFieldsRef.current = currentVisibleFieldIds;
	}, [ visibleFields ] );

	// Add shake animation when validation errors appear
	useEffect( () => {
		const newErrorAnimations = {};

		// Check for newly added errors
		visibleFields.forEach( ( { field, settingKey, hasError } ) => {
			if ( hasError && ! prevValidationErrorsRef.current[ settingKey ] ) {
				newErrorAnimations[ field.id ] = 'dk-shake';
			}
		} );

		// Update error animations if we have new ones
		if ( Object.keys( newErrorAnimations ).length > 0 ) {
			setFieldAnimations( ( prev ) => ( {
				...prev,
				...newErrorAnimations,
			} ) );

			// Clear animations after they've played
			const animatedFieldIds = Object.keys( newErrorAnimations );
			setTimeout( () => {
				setFieldAnimations( ( prev ) => {
					const updated = { ...prev };
					animatedFieldIds.forEach( ( id ) => {
						delete updated[ id ];
					} );
					return updated;
				} );
			}, 800 ); // Match shake animation duration
		}

		// Update ref for next comparison
		const currentErrors = {};
		Object.entries( validationErrors ).forEach( ( [ key, value ] ) => {
			currentErrors[ key ] = value;
		} );
		prevValidationErrorsRef.current = currentErrors;
	}, [ validationErrors, visibleFields ] );

	// Handle field change and update settings
	const handleFieldChange = ( key, newValue ) => {
		onSettingChange( key, newValue );
	};

	if ( ! fields || Object.keys( fields ).length === 0 ) {
		return <p>{ __( 'No fields in this section.', 'wp-plugin-starter' ) }</p>;
	}

	return (
		<div className="wp-plugin-starter-settings-fields">
			{ visibleFields.map( ( { field, settingKey, value, isVisible, hasError, errorMessage } ) => {
				// Skip rendering if field should not be visible
				if ( ! isVisible ) {
					return null;
				}

				// Map variant to proper field type
				const fieldType = mapVariantToType( field.variant );

				// Get animation class if any
				const animationClass = fieldAnimations[ field.id ] || '';

				return (
					<div key={ field.id } className={ `wp-plugin-starter-field-container ${ hasError ? 'dk-has-error' : '' } ${ animationClass }` }>
						<FieldRenderer
							field={ {
								...field,
								type: fieldType, // Set the type based on variant mapping
								label: decodeEntities( field.title ),
								description: decodeEntities( field.description ),
								hasError,
								errorMessage,
							} }
							value={ value }
							onChange={ ( newValue ) => {
								handleFieldChange( settingKey, newValue );
							} }
						/>
						{ hasError && <div className="dk-text-red-500 dk-text-sm dk-mt-1">{ errorMessage }</div> }
					</div>
				);
			} ) }
		</div>
	);
};

export default SettingsSection;
