/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect, useCallback } from '@wordpress/element';

/**
 * Custom hook to manage animations
 *
 * @return {Object} Animation utilities and settings
 */
export const useAnimations = () => {
	const [ animationSettings, setAnimationSettings ] = useState( {} );
	const [ isLoading, setIsLoading ] = useState( true );

	// Fetch animation settings on mount
	useEffect( () => {
		const fetchAnimationSettings = async () => {
			try {
				setIsLoading( true );
				const response = await apiFetch( {
					path: '/wp-plugin-starter/v1/settings/animations',
				} );

				if ( response.data ) {
					// Convert response to a simpler object format with just name and value
					const animSettings = {};
					Object.entries( response.data ).forEach( ( [ key, setting ] ) => {
						animSettings[ key ] = setting.value;
					} );
					setAnimationSettings( animSettings );
				}
			} catch ( error ) {
				console.error( 'Failed to fetch animation settings:', error );
			} finally {
				setIsLoading( false );
			}
		};

		fetchAnimationSettings();
	}, [] );

	/**
	 * Get animation classes based on component type and state
	 *
	 * @param  {string} componentType Component type like 'field', 'button', 'card', etc.
	 * @param  {Object} options       Options for animations (state, etc.)
	 * @return {string}               CSS classes for the animation
	 */
	const getAnimationClasses = useCallback(
		( componentType, options = {} ) => {
			const { state = 'default', isError = false, isActive = false } = options;

			// Base animation classes that always apply
			let classes = 'dk-transition';

			// Type-specific classes
			const prefix = `animation_${ componentType }_`;
			const settingKey = `${ prefix }${ state }`;

			// Add specific animation if available in settings
			if ( animationSettings[ settingKey ] ) {
				classes += ` ${ animationSettings[ settingKey ] }`;
			}

			// Error animations override others
			if ( isError && animationSettings[ `${ prefix }error` ] ) {
				classes += ` ${ animationSettings[ `${ prefix }error` ] }`;
			}

			// Active state
			if ( isActive && animationSettings[ `${ prefix }active` ] ) {
				classes += ` ${ animationSettings[ `${ prefix }active` ] }`;
			}

			return classes;
		},
		[ animationSettings ]
	);

	/**
	 * Update animation setting
	 *
	 * @param {string} key   Setting key
	 * @param {*}      value Setting value
	 */
	const updateAnimationSetting = useCallback( ( key, value ) => {
		// Update local state
		setAnimationSettings( ( prev ) => ( {
			...prev,
			[ key ]: value,
		} ) );
	}, [] );

	return {
		animationSettings,
		isLoading,
		getAnimationClasses,
		updateAnimationSetting,
	};
};
