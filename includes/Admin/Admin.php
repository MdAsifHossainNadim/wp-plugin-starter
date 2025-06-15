<?php

namespace WPPluginStarter\Admin;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Admin Class
 *
 * Handles the admin functionality
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin
 */
class Admin implements Hookable {

	/**
	 * Initialize the admin
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		/**
		 * Action after admin initialization
		 *
		 * @since 1.0.0
		 * @param Admin $this Admin instance
		 */
		do_action( 'WPPluginStarter_admin_init', $this );
	}

	/**
	 * Register hooks
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before admin hooks are registered
		 *
		 * @since 1.0.0
		 * @param Admin $this Admin instance
		 */
		do_action( 'WPPluginStarter_before_admin_hooks', $this );

		// Register admin-specific hooks here
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		/**
		 * Action after admin hooks are registered
		 *
		 * @since 1.0.0
		 * @param Admin $this Admin instance
		 */
		do_action( 'WPPluginStarter_after_admin_hooks', $this );
	}

	/**
	 * Admin init callback
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_init(): void {
		/**
		 * Action during admin initialization
		 *
		 * @since 1.0.0
		 * @param Admin $this Admin instance
		 */
		do_action( 'WPPluginStarter_admin_initialized', $this );
	}
}
