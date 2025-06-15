/**
 * WordPress dependencies
 */
/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

import { Link } from 'react-router-dom';

/**
 * Internal dependencies
 */
import { cn } from '../utils/tailwind-utils';

/**
 * 404 Not Found page component
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The 404 page component
 */
const NotFoundPage = ( { className } ) => {
	return (
		<div className={ cn( 'dk-min-h-[400px] dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-8 dk-text-center', className ) }>
			<div className="dk-flex dk-justify-center dk-mb-wp-6">
				<div className="dk-inline-flex dk-flex-col dk-items-center dk-justify-center">
					<div className="dk-text-8xl dk-font-bold dk-text-primary-200 dk-mb-wp-4">404</div>
					<div className="dk-inline-flex dk-items-center dk-justify-center dk-w-20 dk-h-20 dk-rounded-full dk-bg-blue-100 dk-border-4 dk-border-blue-200 dk-shadow-inner">
						<span className="dashicons dashicons-marker dk-text-4xl dk-text-blue-600"></span>
					</div>
				</div>
			</div>

			<h1 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-3">{ __( 'Page Not Found', 'wp-plugin-starter' ) }</h1>

			<div className="dk-max-w-lg dk-text-gray-600 dk-mb-wp-6">
				<p className="dk-mb-wp-3">{ __( "We couldn't find the page you're looking for.", 'wp-plugin-starter' ) }</p>
				<p>{ __( 'It might have been moved, deleted, or perhaps you entered an incorrect URL.', 'wp-plugin-starter' ) }</p>
			</div>

			<div className="dk-flex dk-flex-col md:dk-flex-row dk-items-center dk-space-y-wp-4 md:dk-space-y-0 md:dk-space-x-wp-4">
				<Link
					to="/"
					className={ cn( 'dk-admin-button dk-bg-primary-600 hover:dk-bg-primary-700 dk-text-white dk-shadow-md hover:dk-shadow-lg dk-transition dk-duration-200 dk-flex dk-items-center' ) }
				>
					<span className="dashicons dashicons-dashboard dk-mr-wp-2"></span>
					{ __( 'Go to Dashboard', 'wp-plugin-starter' ) }
				</Link>

				<Link to="/features" className={ cn( 'dk-admin-button dk-admin-button-secondary dk-shadow-md hover:dk-shadow-lg dk-transition dk-duration-200 dk-flex dk-items-center' ) }>
					<span className="dashicons dashicons-admin-settings dk-mr-wp-2"></span>
					{ __( 'Go to Features', 'wp-plugin-starter' ) }
				</Link>
			</div>

			<div className="dk-mt-wp-8 dk-pt-wp-6 dk-border-t dk-border-gray-200 dk-max-w-md">
				<div className="dk-flex dk-justify-center dk-space-x-wp-6">
					<a
						href="https://wordpress.org/support/plugin/wp-plugin-starter/"
						target="_blank"
						rel="noopener noreferrer"
						className={ cn( 'dk-text-primary-600 hover:dk-text-primary-800 dk-flex dk-flex-col dk-items-center' ) }
					>
						<span className="dashicons dashicons-sos dk-text-xl dk-mb-wp-1"></span>
						<span className="dk-text-sm">{ __( 'Support', 'wp-plugin-starter' ) }</span>
					</a>
					<a
						href="https://wordpress.org/plugins/wp-plugin-starter/"
						target="_blank"
						rel="noopener noreferrer"
						className={ cn( 'dk-text-primary-600 hover:dk-text-primary-800 dk-flex dk-flex-col dk-items-center' ) }
					>
						<span className="dashicons dashicons-book dk-text-xl dk-mb-wp-1"></span>
						<span className="dk-text-sm">{ __( 'Documentation', 'wp-plugin-starter' ) }</span>
					</a>
					<a
						href="https://wordpress.org/plugins/wp-plugin-starter/#developers"
						target="_blank"
						rel="noopener noreferrer"
						className={ cn( 'dk-text-primary-600 hover:dk-text-primary-800 dk-flex dk-flex-col dk-items-center' ) }
					>
						<span className="dashicons dashicons-admin-plugins dk-text-xl dk-mb-wp-1"></span>
						<span className="dk-text-sm">{ __( 'Plugin Info', 'wp-plugin-starter' ) }</span>
					</a>
				</div>
			</div>
		</div>
	);
};

export default NotFoundPage;
