<?php

namespace WPPluginStarter\Frontend;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Frontend Assets Class
 *
 * @package WPPluginStarter\Frontend
 */
class Assets implements Hookable {

	/**
	 * Initialize assets
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		// add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	/**
	 * Register frontend assets
	 *
	 * @return void
	 */
	public function register_assets() {
		// Only enqueue if needed
		if ( ! $this->should_load_assets() ) {
			return;
		}

		// Register styles
		wp_enqueue_style(
			'wp-plugin-starter-frontend',
			WP_PLUGIN_STARTER_BUILD_URL . '/frontend/frontend.css',
			array(),
			WP_PLUGIN_STARTER_VERSION
		);

		// Register scripts
		wp_enqueue_script(
			'wp-plugin-starter-frontend',
			WP_PLUGIN_STARTER_BUILD_URL . '/frontend/frontend.js',
			array( 'jquery' ),
			WP_PLUGIN_STARTER_VERSION,
			true
		);

		// Add localization data
		wp_localize_script(
			'wp-plugin-starter-frontend',
			'wpPluginStarterFrontend',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'wp_plugin_starter_frontend_nonce' ),
			)
		);

		/**
		 * Action after frontend assets are registered
		 *
		 * @param Assets $this Assets instance
		 */
		do_action( 'wp_plugin_starter_frontend_assets_registered', $this );
	}

	/**
	 * Check if assets should be loaded
	 *
	 * @return bool
	 */
	protected function should_load_assets() {
		$should_load = false;

		// Load if any of these features are enabled
		$features_requiring_assets = array(
			'enable_dimension_restrictions',
			'enable_size_restrictions',
			'hide_add_to_cart_button_checkbox',
		);

		foreach ( $features_requiring_assets as $feature ) {
			if ( get_option( $feature ) === '1' ) {
				$should_load = true;
				break;
			}
		}

		// Load on vendor dashboard
		if ( function_exists( 'dokan_is_seller_dashboard' ) && dokan_is_seller_dashboard() ) {
			$should_load = true;
		}

		/**
		 * Filter whether to load frontend assets
		 *
		 * @param bool   $should_load Whether to load assets
		 * @param Assets $this        Assets instance
		 */
		return apply_filters( 'wp_plugin_starter_load_frontend_assets', $should_load, $this );
	}
}
