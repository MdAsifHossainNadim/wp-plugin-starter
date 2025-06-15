/**
 * WordPress dependencies
 */
import { SlotFillProvider } from '@wordpress/components';
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';

/**
 * External dependencies
 */
import { StrictMode } from 'react';
import { createHashRouter, RouterProvider } from 'react-router-dom';

/**
 * Internal dependencies
 */
import ErrorBoundary from '@admin/components/common/error-boundary';
import AppLayout from '@admin/components/layout';
import { NoticesProvider } from '@admin/context/notices-context';
import AboutPage from '@admin/pages/about';
import DashboardPage from '@admin/pages/dashboard';
import FeaturesPage from '@admin/pages/features';
import NotFoundPage from '@admin/pages/not-found';
// import TailwindMergeDemo from '@admin/pages/tailwind-demo';

import './app.scss';

// Create routes configuration
const router = createHashRouter( [
	{
		path: '/',
		element: <AppLayout />,
		errorElement: <ErrorBoundary />,
		children: [
			{
				index: true,
				element: <DashboardPage />,
			},
			{
				path: 'features',
				element: <FeaturesPage />,
			},
			{
				path: 'about',
				element: <AboutPage />,
			},
			// {
			// 	path: 'tailwind-demo',
			// 	element: <TailwindMergeDemo />,
			// },
		],
	},
	{
		path: '*',
		element: <NotFoundPage />,
	},
] );

// Initialize the app
domReady( () => {
	const rootElement = document.getElementById( 'wp-plugin-starter-admin-root' );

	if ( ! rootElement ) {
		return;
	}

	// Add the scoping class to the root element
	rootElement.classList.add( 'wp-plugin-starter-admin-page' );

	const root = createRoot( rootElement );

	// Check if there's an initial path from the server
	const initialPath = window.WP_Plugin_Starter?.initialPath || '/';

	// Use initialPath to set the router's initial location
	const routerWithInitialEntry = createHashRouter( router.routes, {
		initialEntries: [ initialPath ],
	} );

	root.render(
		<StrictMode>
			<SlotFillProvider>
				<NoticesProvider>
					<RouterProvider router={ routerWithInitialEntry } />
				</NoticesProvider>
			</SlotFillProvider>
		</StrictMode>
	);
} );
