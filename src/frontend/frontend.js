/**
 * Internal dependencies
 */
import '@scss/frontend/tailwind-base.scss';

import './frontend.scss'; // Component-specific styles

// Initialize frontend features
document.addEventListener( 'DOMContentLoaded', () => {
	// Add the scoping class to all frontend elements
	document.querySelectorAll( '.wp-plugin-starter-element' ).forEach( ( element ) => {
		element.classList.add( 'wp-plugin-starter-frontend' );
	} );

	// Initialize frontend modules
} );
