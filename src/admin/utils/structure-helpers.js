/**
 * Structure helper utility functions
 */

/**
 * Get a field from the structure by its key
 *
 * @param  {Object}      structure The structure object
 * @param  {string}      key       The field key (either dependency_key or tab.section.field format)
 * @return {Object|null}           The field object or null if not found
 */
export const getFieldByKey = ( structure, key ) => {
	if ( ! structure || ! key ) {
		return null;
	}

	// Try to find by dependency_key first (faster)
	for ( const tabId in structure ) {
		const tab = structure[ tabId ];
		for ( const sectionId in tab.sections ) {
			const section = tab.sections[ sectionId ];
			for ( const fieldId in section.fields ) {
				const field = section.fields[ fieldId ];
				if ( field.dependency_key === key ) {
					return {
						field,
						path: { tabId, sectionId, fieldId },
					};
				}
			}
		}
	}

	// If not found by dependency_key, try by path format (tab.section.field)
	const parts = key.split( '.' );
	if ( parts.length === 3 ) {
		const [ tabId, sectionId, fieldId ] = parts;
		if ( structure[ tabId ] && structure[ tabId ].sections[ sectionId ] && structure[ tabId ].sections[ sectionId ].fields[ fieldId ] ) {
			return {
				field: structure[ tabId ].sections[ sectionId ].fields[ fieldId ],
				path: { tabId, sectionId, fieldId },
			};
		}
	}

	return null;
};

/**
 * Compare two values with appropriate type coercion
 *
 * @param  {*}       value1   The first value
 * @param  {*}       value2   The second value
 * @param  {string}  operator The comparison operator
 * @return {boolean}          Whether the comparison is true
 */
export const compareValues = ( value1, value2, operator = '=' ) => {
	// Handle boolean values represented as strings
	if ( typeof value2 === 'string' && ( value2 === 'true' || value2 === 'false' ) ) {
		value2 = value2 === 'true';
	}

	// Handle numeric values represented as strings
	if ( typeof value2 === 'string' && ! isNaN( Number( value2 ) ) ) {
		// Only convert if value1 is also a number or can be converted to one
		if ( typeof value1 === 'number' || ( typeof value1 === 'string' && ! isNaN( Number( value1 ) ) ) ) {
			value1 = Number( value1 );
			value2 = Number( value2 );
		}
	}

	// Handle comparison
	switch ( operator ) {
		case '=':
			return value1 === value2;
		case '!=':
			return value1 !== value2;
		case '>':
			return value1 > value2;
		case '<':
			return value1 < value2;
		case '>=':
			return value1 >= value2;
		case '<=':
			return value1 <= value2;
		case 'contains':
			return String( value1 ).includes( String( value2 ) );
		case 'not_contains':
			return ! String( value1 ).includes( String( value2 ) );
		case 'is_empty':
			return value1 === '' || value1 === null || value1 === undefined;
		case 'is_not_empty':
			return value1 !== '' && value1 !== null && value1 !== undefined;
		default:
			return value1 === value2;
	}
};

/**
 * Check if a field should be visible based on its dependencies
 *
 * @param  {Object}  field    The field object
 * @param  {Object}  settings The current settings values
 * @return {boolean}          Whether the field should be visible
 */
export const isFieldVisible = ( field, settings ) => {
	if ( ! field?.dependencies?.length ) {
		return true; // No dependencies, always visible
	}

	// Check if all dependencies are met
	for ( const dependency of field.dependencies ) {
		// Sometimes dependency key might be missing in settings
		// due to the field not being registered yet
		if ( dependency.key && settings[ dependency.key ] === undefined ) {
			// Field is hidden if dependency is missing
			return false;
		}

		const dependencyValue = settings[ dependency.key ];
		const isVisible = compareValues( dependencyValue, dependency.value, dependency.comparison );

		if ( ! isVisible ) {
			return false; // If any dependency fails, hide the field
		}
	}

	return true; // All dependencies passed
};

/**
 * Get all visible fields based on current settings
 *
 * @param  {Object} structure The structure object
 * @param  {Object} settings  The current settings values
 * @return {Array}            Array of visible field objects with their paths
 */
export const getVisibleFields = ( structure, settings ) => {
	const visibleFields = [];

	for ( const tabId in structure ) {
		const tab = structure[ tabId ];
		for ( const sectionId in tab.sections ) {
			const section = tab.sections[ sectionId ];
			for ( const fieldId in section.fields ) {
				const field = section.fields[ fieldId ];

				// Set the id property in the field object if it's not already set
				if ( ! field.id ) {
					field.id = fieldId;
				}

				const key = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

				// Always include the field in the settings object to ensure dependencies can be checked
				if ( settings[ key ] === undefined && field.value !== undefined ) {
					settings[ key ] = field.value;
				}

				// Include visible fields only
				if ( isFieldVisible( field, settings ) ) {
					visibleFields.push( {
						field,
						path: { tabId, sectionId, fieldId },
						key,
					} );
				}
			}
		}
	}

	return visibleFields;
};

/**
 * Validate a field value based on its type and constraints
 *
 * @param  {Object} field The field object
 * @param  {*}      value The value to validate
 * @return {Object}       Object with isValid and message properties
 */
export const validateFieldValue = ( field, value ) => {
	if ( ! field ) {
		return { isValid: false, message: 'Field not found' };
	}

	// Required field validation
	if ( field.required && ( value === '' || value === null || value === undefined ) ) {
		return {
			isValid: false,
			message: field.title + ' is required',
		};
	}

	// Type-specific validations
	switch ( field.variant ) {
		case 'number':
			// Skip validation if the field is empty and not required
			if ( value === '' && ! field.required ) {
				return { isValid: true, message: '' };
			}

			// Check if it's a valid number
			const numValue = parseFloat( value );
			if ( isNaN( numValue ) ) {
				return {
					isValid: false,
					message: field.title + ' must be a valid number',
				};
			}

			// Check minimum value
			if ( field.minimum !== undefined && numValue < field.minimum ) {
				return {
					isValid: false,
					message: field.title + ' must be at least ' + field.minimum,
				};
			}

			// Check maximum value
			if ( field.maximum !== undefined && numValue > field.maximum ) {
				return {
					isValid: false,
					message: field.title + ' must be at most ' + field.maximum,
				};
			}
			break;

		case 'select':
			if ( field.options && field.options.length > 0 ) {
				const validOptions = field.options.map( ( opt ) => opt.value );
				if ( ! validOptions.includes( value ) ) {
					return {
						isValid: false,
						message: field.title + ' has an invalid selection',
					};
				}
			}
			break;
	}

	return { isValid: true, message: '' };
};

/**
 * Validate all settings against the structure
 *
 * @param  {Object} structure The structure object
 * @param  {Object} settings  The current settings values
 * @return {Object}           Object with isValid and errors properties
 */
export const validateSettings = ( structure, settings ) => {
	const errors = {};
	let isValid = true;

	// Get all visible fields (only validate fields that are currently visible)
	const visibleFields = getVisibleFields( structure, settings );

	visibleFields.forEach( ( { field, key } ) => {
		const value = settings[ key ];
		const validation = validateFieldValue( field, value );

		if ( ! validation.isValid ) {
			errors[ key ] = validation.message;
			isValid = false;
		}
	} );

	return { isValid, errors };
};

/**
 * Populate default values for all fields in the structure
 *
 * @param  {Object} structure The structure object
 * @return {Object}           The updated settings object with default values
 */
export const populateDefaultValues = ( structure ) => {
	const populatedSettings = {};

	// Iterate through all fields in the structure
	for ( const tabId in structure ) {
		const tab = structure[ tabId ];
		for ( const sectionId in tab.sections ) {
			const section = tab.sections[ sectionId ];
			for ( const fieldId in section.fields ) {
				const field = section.fields[ fieldId ];
				const key = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

				// Use the field's value if available, otherwise use default
				let value = field.value;

				// If value is undefined or null, use the default value
				if ( value === undefined || value === null ) {
					value = field.default !== undefined ? field.default : '';
				}

				// Handle specific variant types
				if ( [ 'toggle', 'checkbox' ].includes( field.variant ) ) {
					// Convert to boolean for toggle/checkbox fields
					value = Boolean( value === true || value === 'true' || value === '1' || value === 1 );
				} else if ( field.variant === 'number' && value !== '' ) {
					// For number fields, ensure it's a proper number
					const num = parseFloat( value );
					if ( ! isNaN( num ) ) {
						value = num;
					}
				} else if ( field.variant === 'multiselect' && ! Array.isArray( value ) ) {
					// Ensure multiselect values are arrays
					value = value ? [ value ] : [];
				}

				populatedSettings[ key ] = value;
			}
		}
	}

	return populatedSettings;
};

/**
 * Merge API settings data with populated default values
 *
 * @param  {Object} structure   The structure object
 * @param  {Object} apiSettings The settings data from API
 * @return {Object}             The merged settings object
 */
export const mergeApiSettings = ( structure, apiSettings ) => {
	// First get the default populated settings
	const defaultSettings = populateDefaultValues( structure );

	// If no API settings provided, return defaults
	if ( ! apiSettings || ! apiSettings.data ) {
		return defaultSettings;
	}

	const mergedSettings = { ...defaultSettings };
	const apiData = apiSettings.data;

	// Map API data to our settings format
	for ( const tabId in structure ) {
		const tab = structure[ tabId ];

		// Skip if this tab doesn't exist in API data
		if ( ! apiData[ tabId ] ) {
			continue;
		}

		const tabData = apiData[ tabId ];

		for ( const sectionId in tab.sections ) {
			const section = tab.sections[ sectionId ];

			for ( const fieldId in section.fields ) {
				const field = section.fields[ fieldId ];
				const key = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

				// Check if this field exists in API data
				if ( tabData[ fieldId ] !== undefined ) {
					let value = tabData[ fieldId ];

					// Apply type conversion
					if ( [ 'toggle', 'checkbox' ].includes( field.variant ) ) {
						value = Boolean( value === true || value === 'true' || value === '1' || value === 1 );
					} else if ( field.variant === 'number' && value !== '' ) {
						const num = parseFloat( value );
						if ( ! isNaN( num ) ) {
							value = num;
						}
					} else if ( field.variant === 'multiselect' && ! Array.isArray( value ) ) {
						value = value ? [ value ] : [];
					}

					mergedSettings[ key ] = value;
				}
			}
		}
	}

	return mergedSettings;
};

export default {
	getFieldByKey,
	isFieldVisible,
	getVisibleFields,
	validateFieldValue,
	validateSettings,
	populateDefaultValues,
	mergeApiSettings,
};
