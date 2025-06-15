/**
 * WordPress dependencies
 */
import { useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

/**
 * External dependencies
 */
import { NavLink, Outlet, useLocation, useNavigation } from 'react-router-dom';

/**
 * Internal dependencies
 */
import Loading from '@admin/components/common/loading';
import Notices from '@admin/components/common/notices';
import { cn } from '@admin/utils/tailwind-utils';

import logoImage from '../../../../assets/images/wp-plugin-starter-logo.png';

import './app-layout.scss';

/**
 * App Layout component - Provides common layout for all admin pages
 *
 * @return {JSX.Element} The App Layout component
 */
const AppLayout = () => {
	const location = useLocation();
	const navigation = useNavigation();
	const isLoading = navigation.state === 'loading';

	// Access the global WP_Plugin_Starter object safely
	const WP_Plugin_Starter = window.WP_Plugin_Starter || {};

	// Update the page title and manage active menu based on current route
	useEffect( () => {
		const pathToTitle = {
			'/': __( 'Dashboard', 'wp-plugin-starter' ),
			'/features': __( 'Features', 'wp-plugin-starter' ),
			'/about': __( 'About', 'wp-plugin-starter' ),
			'/tailwind-demo': __( 'Tailwind Merge Demo', 'wp-plugin-starter' ),
		};

		const title = pathToTitle[ location.pathname ];
		document.title = sprintf(
			/* translators: %s: Page title */
			__( '%s - WP Plugin Starter', 'wp-plugin-starter' ),
			title ?? __( 'Dashboard', 'wp-plugin-starter' )
		);

		// Manually manage WordPress admin menu active state
		if ( typeof document !== 'undefined' ) {
			// First, remove any active classes from all submenu items in WP Plugin Starter menu
			const submenuItems = document.querySelectorAll( '#toplevel_page_wp-plugin-starter .wp-submenu li' );
			submenuItems.forEach( ( item ) => {
				item.classList.remove( 'current' );
			} );

			// Map routes to submenu index (the first item is index 1, not 0 due to wp-submenu-head)
			const routeToSubmenuIndex = {
				'/': 1,
				'/features': 2,
				'/about': 3,
				'/tailwind-demo': 4,
			};

			// Set the active class on the current route's menu item
			const currentIndex = routeToSubmenuIndex[ location.pathname ];
			if ( currentIndex !== undefined && submenuItems[ currentIndex ] ) {
				submenuItems[ currentIndex ].classList.add( 'current' );
			}
		}
	}, [ location ] );

	// Common NavLink classes
	const navLinkClasses = ( { isActive } ) =>
		cn(
			'dk-inline-flex dk-items-center dk-px-wp-1 dk-py-wp-4 dk-text-sm dk-font-medium dk-border-b-2 !dk-shadow-none !dk-outline-none',
			isActive ? 'dk-border-primary-500 dk-text-primary-600' : 'dk-border-transparent dk-text-gray-500 hover:dk-text-gray-700 hover:dk-border-gray-300'
		);

	return (
		<div className="wp-plugin-starter-app-wrapper">
			<div className="wp-plugin-starter-app-header dk-bg-white dk-border-b dk-border-gray-200 dk-mb-wp-6">
				<div className="dk-container dk-mx-auto dk-px-wp-4">
					<div className="dk-flex dk-items-center dk-justify-between dk-pt-wp-4">
						<div className="dk-flex dk-items-center">
							<img src={ logoImage } alt="WP Plugin Starter" className="dk-h-20 dk-w-auto dk-mr-wp-3" />
							<h1 className="dk-text-xl dk-font-medium dk-text-gray-900">{ __( 'WP Plugin Starter', 'wp-plugin-starter' ) }</h1>
						</div>
						<div className="dk-flex dk-items-center dk-space-x-wp-2">
							<a href="https://wordpress.org/support/plugin/wp-plugin-starter/#new-topic-0" target="_blank" rel="noopener noreferrer" className="dk-admin-button dk-admin-button-secondary dk-text-sm">
								{ __( 'Support', 'wp-plugin-starter' ) }
							</a>
							<a href="https://wordpress.org/plugins/wp-plugin-starter/" target="_blank" rel="noopener noreferrer" className="dk-admin-button dk-admin-button-secondary dk-text-sm">
								{ __( 'Documentation', 'wp-plugin-starter' ) }
							</a>
						</div>
					</div>

					<nav className="dk-flex dk-space-x-wp-6 dk--mb-px">
						<NavLink to="/" end className={ navLinkClasses }>
							<span className="dashicons dashicons-dashboard dk-mr-wp-2"></span>
							{ __( 'Dashboard', 'wp-plugin-starter' ) }
						</NavLink>

						<NavLink to="/features" className={ navLinkClasses }>
							<span className="dashicons dashicons-admin-settings dk-mr-wp-2"></span>
							{ __( 'Features', 'wp-plugin-starter' ) }
						</NavLink>

						<NavLink to="/about" className={ navLinkClasses }>
							<span className="dashicons dashicons-info dk-mr-wp-2"></span>
							{ __( 'About', 'wp-plugin-starter' ) }
						</NavLink>

						{ /*<NavLink to="/tailwind-demo" className={ navLinkClasses }>*/ }
						{ /*	<span className="dashicons dashicons-admin-appearance dk-mr-wp-2"></span>*/ }
						{ /*	{ __( 'Tailwind Demo', 'wp-plugin-starter' ) }*/ }
						{ /*</NavLink>*/ }
					</nav>
				</div>
			</div>

			<div className="wp-plugin-starter-app-content dk-container dk-mx-auto dk-px-wp-4 dk-pb-wp-8">
				<Notices />

				{ /* Show loading indicator during route transitions */ }
				<div className="dk-route-transition">{ isLoading ? <Loading message={ __( 'Loading content…', 'wp-plugin-starter' ) } /> : <Outlet /> }</div>
			</div>

			<div className="wp-plugin-starter-app-footer dk-mt-wp-12 dk-py-wp-6 dk-border-t dk-border-gray-200 dk-text-center dk-text-sm dk-text-gray-500">
				<p>
					{ __( 'WP Plugin Starter', 'wp-plugin-starter' ) } { WP_Plugin_Starter.version || '3.0.0' } | { __( 'Made with', 'wp-plugin-starter' ) } ❤️ { __( 'by', 'wp-plugin-starter' ) }
					<a href="https://profiles.wordpress.org/wpintegrity/" target="_blank" rel="noopener noreferrer" className="dk-text-primary-600 hover:dk-text-primary-800 dk-ml-wp-1">
						WP Integrity
					</a>
				</p>
			</div>
		</div>
	);
};

export default AppLayout;
