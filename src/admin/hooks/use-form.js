/**
 * WordPress dependencies
 */
import { useCallback, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { validateSettings } from '@admin/utils/structure-helpers';

/**
 * Custom hook for form handling with validation
 *
 * @param  {Object}   structure     Form structure object
 * @param  {Object}   initialValues Initial form values
 * @param  {Function} saveCallback  Function to call when saving form data
 * @return {Object}                 Form handling methods and state
 */
export const useForm = ( structure, initialValues, saveCallback ) => {
	const [ values, setValues ] = useState( initialValues || {} );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ message, setMessage ] = useState( null );
	const [ isError, setIsError ] = useState( false );
	const [ validationErrors, setValidationErrors ] = useState( {} );

	/**
	 * Update a single form value
	 *
	 * @param {string} key   The field key
	 * @param {*}      value The new value
	 */
	const updateValue = useCallback( ( key, value ) => {
		setValues( ( prev ) => ( {
			...prev,
			[ key ]: value,
		} ) );
	}, [] );

	/**
	 * Update multiple form values at once
	 *
	 * @param {Object} updates Key-value pairs to update
	 */
	const updateValues = useCallback( ( updates ) => {
		if ( ! updates || typeof updates !== 'object' ) {
			return;
		}

		setValues( ( prev ) => ( {
			...prev,
			...updates,
		} ) );
	}, [] );

	/**
	 * Reset form to initial values
	 */
	const resetForm = useCallback( () => {
		setValues( initialValues || {} );
		setValidationErrors( {} );
		setMessage( null );
		setIsError( false );
	}, [ initialValues ] );

	/**
	 * Clear form notifications
	 */
	const clearNotifications = useCallback( () => {
		setMessage( null );
		setIsError( false );
	}, [] );

	/**
	 * Validate form values
	 *
	 * @return {boolean} Whether the form is valid
	 */
	const validateForm = useCallback( () => {
		if ( ! structure ) {
			return true;
		}

		const { isValid, errors } = validateSettings( structure, values );

		setValidationErrors( errors );

		if ( ! isValid ) {
			setMessage( __( 'Please fix the validation errors before saving.', 'wp-plugin-starter' ) );
			setIsError( true );
		}

		return isValid;
	}, [ structure, values ] );

	/**
	 * Save form data after validation
	 */
	const saveForm = useCallback( async () => {
		// Validate form first
		if ( ! validateForm() ) {
			return false;
		}

		// Clear validation errors and set saving state
		setValidationErrors( {} );
		setIsSaving( true );
		setMessage( null );
		setIsError( false );

		try {
			// Call the save callback with current values
			const result = await saveCallback( values );

			setIsSaving( false );
			setMessage( result.message );
			setIsError( ! result.success );

			// Clear message after 3 seconds
			setTimeout( () => {
				setMessage( null );
			}, 3000 );

			return result.success;
		} catch ( error ) {
			setIsSaving( false );
			setMessage( __( 'Failed to save form data.', 'wp-plugin-starter' ) );
			setIsError( true );
			return false;
		}
	}, [ validateForm, saveCallback, values ] );

	return {
		values,
		isSaving,
		message,
		isError,
		validationErrors,
		updateValue,
		updateValues,
		saveForm,
		resetForm,
		clearNotifications,
		validateForm,
	};
};

export default useForm;
