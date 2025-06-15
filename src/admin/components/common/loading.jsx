/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * Loading component for route transitions
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.message   Custom loading message
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The Loading component
 */
const Loading = ( { message = __( 'Loading…', 'wp-plugin-starter' ), className } ) => {
	return (
		<div className={ cn( 'dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-8 dk-min-h-[300px]', className ) }>
			<div className="dk-inline-block dk-animate-spin dk-h-10 dk-w-10 dk-text-primary-600 dk-mb-wp-4">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle className="dk-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
					<path className="dk-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
			</div>
			<p className="dk-text-gray-600 dk-text-center dk-animate-pulse">{ message }</p>
		</div>
	);
};

export default Loading;
