/**
 * External dependencies
 */
import { twMerge } from 'tailwind-merge';

/**
 * Utility function to merge Tailwind CSS classes
 *
 * @param  {string} classes - Class strings to merge
 * @return {string}         - Merged class string
 */
export const cn = ( ...classes ) => {
	return twMerge( classes );
};

/**
 * Creates a function that returns class names based on variants and defaults
 * Similar to class-variance-authority but simpler
 *
 * @param  {Object}   config          - Configuration object
 * @param  {string}   config.base     - Base class names
 * @param  {Object}   config.variants - Variant options
 * @param  {Object}   config.defaults - Default variant values
 * @return {Function}                 - Function that generates class names
 */
export const cva = ( config ) => {
	const { base = '', variants = {}, defaults = {} } = config;

	return ( props = {} ) => {
		const variantClassNames = Object.entries( variants ).reduce( ( acc, [ variantName, variantOptions ] ) => {
			const variantValue = props[ variantName ] || defaults[ variantName ];
			if ( variantValue && variantOptions[ variantValue ] ) {
				acc.push( variantOptions[ variantValue ] );
			}
			return acc;
		}, [] );

		return cn( base, ...variantClassNames, props.className || '' );
	};
};

export { twMerge };
