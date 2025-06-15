/**
 * WordPress dependencies
 */
import { useCallback, useEffect, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { populateDefaultValues, mergeApiSettings } from '../utils/structure-helpers';

/**
 * Custom hook to use structure from global WP_Plugin_Starter variable but save via API
 *
 * @param  {Object}   apiSettingsData      Optional API settings data to use instead of context
 * @param  {Function} updateContextSetting Function to update settings in parent component
 * @return {Object}                        The structure and features data
 */
export const useStructure = ( apiSettingsData = null, updateContextSetting = () => {} ) => {
	const [ structure, setStructure ] = useState( {} );
	const [ settings, setSettings ] = useState( {} );
	const [ isLoading, setIsLoading ] = useState( true );

	/**
	 * Load structure from global variable
	 */
	const loadStructure = () => {
		if ( typeof window.WP_Plugin_Starter === 'undefined' ) {
			setIsLoading( false );
			return;
		}

		// Extract structure from WP_Plugin_Starter.features
		const featuresData = window.WP_Plugin_Starter.features || {};

		// Parse the structure
		const parsedStructure = parseStructure( featuresData );
		setStructure( parsedStructure );

		// Use populateDefaultValues to ensure all fields have proper values
		const populatedSettings = populateDefaultValues( parsedStructure );

		// Merge with API settings if available - only during initial load
		// Priority: 1. Passed apiSettingsData, 2. Default values
		if ( apiSettingsData && Object.keys( apiSettingsData ).length > 0 ) {
			const mergedSettings = mergeApiSettings( parsedStructure, { data: apiSettingsData } );
			setSettings( mergedSettings );
		} else {
			setSettings( populatedSettings );
		}

		setIsLoading( false );
	};

	/**
	 * Parse the structure from the WP_Plugin_Starter.features data
	 *
	 * @param  {Object} data The features data
	 * @return {Object}      The parsed structure
	 */
	const parseStructure = ( data ) => {
		// Start with an empty object for tabs
		const parsedStructure = {};

		// Check if we have a valid features object with children
		if ( ! data?.children?.length ) {
			return parsedStructure;
		}

		// Process each top-level section as a tab
		data.children.forEach( ( tab ) => {
			if ( ! tab.id || tab.type !== 'section' || ! tab.display ) {
				return;
			}

			// Create a new tab entry
			parsedStructure[ tab.id ] = {
				id: tab.id,
				title: tab.title || '',
				icon: tab.icon || '',
				description: tab.description || '',
				sections: {},
			};

			// Process each subsection
			if ( ! tab.children?.length ) {
				return;
			}

			tab.children.forEach( ( section ) => {
				if ( ! section.id || section.type !== 'subsection' || ! section.display ) {
					return;
				}

				// Create a new section entry
				parsedStructure[ tab.id ].sections[ section.id ] = {
					id: section.id,
					title: section.title || '',
					description: section.description || '',
					badge: section.badge || null,
					fields: {},
				};

				// Process each field
				if ( ! section.children?.length ) {
					return;
				}

				section.children.forEach( ( field ) => {
					if ( ! field.id || field.type !== 'field' || ! field.display ) {
						return;
					}

					// Get the initial value (value or default or empty)
					let fieldValue =
						// eslint-disable-next-line no-nested-ternary
						field.value !== undefined ? field.value : field.default !== undefined ? field.default : '';

					// Handle specific variant types
					if ( [ 'toggle', 'checkbox' ].includes( field.variant ) ) {
						// Convert to boolean for toggle/checkbox fields
						fieldValue = Boolean( fieldValue === true || fieldValue === 'true' || fieldValue === '1' || fieldValue === 1 );
					} else if ( field.variant === 'number' ) {
						// For number fields, ensure it's a proper number or empty string
						if ( fieldValue === '' || fieldValue === null || fieldValue === undefined ) {
							fieldValue = '';
						} else {
							// Convert to number if it's not already
							const num = parseFloat( fieldValue );
							if ( ! isNaN( num ) ) {
								fieldValue = num;
							} else {
								// If it can't be parsed as a number, use default or empty string
								fieldValue = field.default !== undefined ? parseFloat( field.default ) : '';
								if ( isNaN( fieldValue ) ) {
									fieldValue = '';
								}
							}
						}
					}

					// Process dependencies to ensure they're in the correct format
					const dependencies = [];
					if ( field.dependencies && Array.isArray( field.dependencies ) ) {
						field.dependencies.forEach( ( dependency ) => {
							// Ensure dependency has all required properties
							if ( dependency && dependency.key ) {
								// Normalize comparison operator if not provided
								if ( ! dependency.comparison ) {
									dependency.comparison = '=';
								}

								// Handle boolean values in string format
								if ( typeof dependency.value === 'string' ) {
									if ( dependency.value === 'true' ) {
										dependency.value = true;
									} else if ( dependency.value === 'false' ) {
										dependency.value = false;
									} else if ( ! isNaN( Number( dependency.value ) ) ) {
										// Convert numeric strings to numbers if comparison is numeric
										if ( [ '>', '<', '>=', '<=' ].includes( dependency.comparison ) ) {
											dependency.value = Number( dependency.value );
										}
									}
								}

								dependencies.push( dependency );
							}
						} );
					}

					// Create a new field entry
					parsedStructure[ tab.id ].sections[ section.id ].fields[ field.id ] = {
						id: field.id,
						title: field.title || '',
						description: field.description || '',
						variant: field.variant || 'text',
						value: fieldValue,
						default: field.default || '',
						placeholder: field.placeholder || '',
						readonly: field.readonly || false,
						disabled: field.disabled || false,
						dependencies,
						dependency_key: field.dependency_key || '',
						required: field.required || false,
						// Include additional properties needed for specific field types
						options: field.options || [],
						minimum: field.minimum,
						maximum: field.maximum,
						step: field.step,
						size: field.size,
					};
				} );
			} );
		} );

		return parsedStructure;
	};

	/**
	 * Update a setting value
	 *
	 * @param {string} key   The setting key
	 * @param {*}      value The new value
	 */
	const updateSetting = useCallback( ( key, value ) => {
		// Update the settings state
		setSettings( ( prevSettings ) => ( {
			...prevSettings,
			[ key ]: value,
		} ) );

		// Update the context settings as well if callback is provided
		if ( typeof updateContextSetting === 'function' ) {
			updateContextSetting( key, value );
		}

		// Also update the value in the structure
		setStructure( ( prevStructure ) => {
			const newStructure = { ...prevStructure };

			// Find and update the field in the structure
			for ( const tabId in newStructure ) {
				const tab = newStructure[ tabId ];

				for ( const sectionId in tab.sections ) {
					const section = tab.sections[ sectionId ];

					for ( const fieldId in section.fields ) {
						const field = section.fields[ fieldId ];
						const fieldKey = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

						if ( fieldKey === key ) {
							newStructure[ tabId ].sections[ sectionId ].fields[ fieldId ].value = value;
							return newStructure;
						}
					}
				}
			}

			return newStructure;
		} );
	}, [ updateContextSetting ] );

	/**
	 * Update multiple settings at once
	 *
	 * @param {Object} updates An object with key-value pairs to update
	 */
	const updateSettings = useCallback( ( updates ) => {
		if ( ! updates || typeof updates !== 'object' ) {
			return;
		}

		// Update the settings state
		setSettings( ( prevSettings ) => ( {
			...prevSettings,
			...updates,
		} ) );

		// Update context settings for each key if callback is provided
		if ( typeof updateContextSetting === 'function' ) {
			Object.entries( updates ).forEach( ( [ key, value ] ) => {
				updateContextSetting( key, value );
			} );
		}

		// Also update the values in the structure
		setStructure( ( prevStructure ) => {
			const newStructure = JSON.parse( JSON.stringify( prevStructure ) );

			// Find and update each field in the structure
			Object.entries( updates ).forEach( ( [ key, value ] ) => {
				for ( const tabId in newStructure ) {
					const tab = newStructure[ tabId ];

					for ( const sectionId in tab.sections ) {
						const section = tab.sections[ sectionId ];

						for ( const fieldId in section.fields ) {
							const field = section.fields[ fieldId ];
							const fieldKey = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

							if ( fieldKey === key ) {
								newStructure[ tabId ].sections[ sectionId ].fields[ fieldId ].value = value;
								break; // Exit inner loop once found
							}
						}
					}
				}
			} );

			return newStructure;
		} );
	}, [ updateContextSetting ] );

	// Load structure from global variable on mount
	useEffect( () => {
		loadStructure();
	}, [ apiSettingsData ] );

	return {
		structure,
		settings,
		isLoading,
		updateSetting,
		updateSettings,
	};
};

export default useStructure;
