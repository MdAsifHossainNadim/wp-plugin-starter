/**
 * WordPress dependencies
 */
/**
 * External dependencies
 */

import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { useRouteError, Link } from 'react-router-dom';

/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * Error Boundary component - Handles route errors
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The Error Boundary component
 */
const ErrorBoundary = ( { className } ) => {
	const error = useRouteError();
	// Log error in development only
	if ( process.env.NODE_ENV === 'development' ) {
		// eslint-disable-next-line no-console
		console.error( error );
	}

	return (
		<div
			className={ cn(
				'dk-min-h-[400px] dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-8 dk-text-center dk-bg-white dk-shadow-md dk-rounded-lg dk-max-w-4xl dk-mx-auto dk-mt-wp-8',
				className
			) }
		>
			<div className="dk-flex dk-justify-center dk-mb-wp-6">
				<div className="dk-inline-flex dk-items-center dk-justify-center dk-w-20 dk-h-20 dk-rounded-full dk-bg-red-100 dk-border-4 dk-border-red-200 dk-shadow-inner">
					<span className="dashicons dashicons-warning dk-text-4xl dk-text-red-600"></span>
				</div>
			</div>

			<h1 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-3">{ __( 'Oops! Something went wrong', 'wp-plugin-starter' ) }</h1>

			<div className="dk-max-w-lg dk-text-gray-600 dk-mb-wp-6 dk-bg-gray-50 dk-p-wp-4 dk-rounded-md dk-border dk-border-gray-200">
				<p className="dk-font-medium dk-mb-wp-2 dk-text-red-600">{ error?.status ? `Error ${ error.status }` : __( 'Error', 'wp-plugin-starter' ) }</p>
				<p>{ error?.message || __( 'An unexpected error occurred. Please try again or return to the dashboard.', 'wp-plugin-starter' ) }</p>
			</div>

			<div className="dk-flex dk-items-center dk-space-x-wp-4">
				<Link
					to="/"
					className={ cn( 'dk-admin-button dk-bg-primary-600 hover:dk-bg-primary-700 dk-text-white dk-shadow-md hover:dk-shadow-lg dk-transition dk-duration-200 dk-flex dk-items-center' ) }
				>
					<span className="dashicons dashicons-dashboard dk-mr-wp-2"></span>
					{ __( 'Back to Dashboard', 'wp-plugin-starter' ) }
				</Link>

				<Button variant="secondary" onClick={ () => window.location.reload() } className="dk-shadow-md hover:dk-shadow-lg dk-transition dk-duration-200">
					<span className="dashicons dashicons-image-rotate dk-mr-wp-2"></span>
					{ __( 'Reload Page', 'wp-plugin-starter' ) }
				</Button>
			</div>

			<div className="dk-mt-wp-10 dk-text-gray-500 dk-text-sm dk-flex dk-flex-col dk-items-center">
				<p className="dk-mb-wp-2">{ __( 'If this problem persists, please contact support.', 'wp-plugin-starter' ) }</p>
				<a
					href="https://wordpress.org/support/plugin/wp-plugin-starter/"
					target="_blank"
					rel="noopener noreferrer"
					className={ cn( 'dk-text-primary-600 hover:dk-text-primary-800 dk-flex dk-items-center' ) }
				>
					<span className="dashicons dashicons-sos dk-mr-wp-1 dk-text-sm"></span>
					{ __( 'Get Support', 'wp-plugin-starter' ) }
				</a>
			</div>
		</div>
	);
};

export default ErrorBoundary;
