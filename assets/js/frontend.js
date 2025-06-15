/**
 * WP Plugin Starter Frontend JavaScript
 * @param $
 */
( function( $ ) {
	'use strict';

	/**
	 * Initialize frontend functions
	 */
	const WP_Plugin_Starter_Frontend = {
		/**
		 * Initialize
		 */
		init() {
			// Add 'wp-plugin-starter-hide-add-to-cart' class to body if needed
			if ( WP_Plugin_Starter_Frontend.hideAddToCart === 'yes' ) {
				$( 'body' ).addClass( 'wp-plugin-starter-hide-add-to-cart' );
			}

			// Initialize image validation if on product edit page
			if ( this.isVendorDashboard() ) {
				this.initImageValidation();
			}
		},

		/**
		 * Check if we're on vendor dashboard
		 */
		isVendorDashboard() {
			return $( 'body' ).hasClass( 'dokan-dashboard' );
		},

		/**
		 * Initialize image validation
		 */
		initImageValidation() {
			// This is now handled by the inline script in ImageRestrictions class
			// This method is kept for future extensibility
		},
	};

	// Initialize on document ready
	$( document ).ready( function() {
		WP_Plugin_Starter_Frontend.init();
	} );
}( jQuery ) );
