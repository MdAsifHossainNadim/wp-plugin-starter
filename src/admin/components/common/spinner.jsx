/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { cn } from '@admin/utils/tailwind-utils';

/**
 * Size variants for the spinner
 */
const sizeVariants = {
	sm: 'dk-h-4 dk-w-4',
	md: 'dk-h-8 dk-w-8',
	lg: 'dk-h-12 dk-w-12',
};

/**
 * Spinner component - Shows a loading spinner with optional text
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.size      Size of the spinner (sm, md, lg)
 * @param  {string}      props.message   Optional message to display
 * @param  {string}      props.className Additional CSS classes
 * @param  {string}      props.color     Color class for the spinner
 * @return {JSX.Element}                 The Spinner component
 */
const Spinner = ( {
	size = 'md',
	message,
	className = '',
	color = 'dk-text-primary-600',
} ) => {
	const sizeClass = sizeVariants[ size ] || sizeVariants.md;

	return (
		<div className={ cn( 'dk-flex dk-flex-col dk-items-center dk-justify-center', className ) }>
			<span className={ cn( 'dk-inline-block dk-animate-spin', sizeClass, color ) }>
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle
						className="dk-opacity-25"
						cx="12"
						cy="12"
						r="10"
						stroke="currentColor"
						strokeWidth="4"
					></circle>
					<path
						className="dk-opacity-75"
						fill="currentColor"
						d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
					></path>
				</svg>
			</span>
			{ message && <p className="dk-mt-wp-2 dk-text-sm dk-text-gray-600">{ message }</p> }
		</div>
	);
};

/**
 * FullPageSpinner component - Shows a spinner centered on the page
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.message   Optional message to display
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The FullPageSpinner component
 */
export const FullPageSpinner = ( {
	message = __( 'Loading…', 'wp-plugin-starter' ),
	className = '',
} ) => {
	return (
		<div className={ cn( 'dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-12 dk-min-h-[300px]', className ) }>
			<Spinner size="lg" message={ message } />
		</div>
	);
};

export default Spinner;
