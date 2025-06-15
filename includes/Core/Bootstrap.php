<?php

namespace WPPluginStarter\Core;

use WPPluginStarter\Core\Interfaces\Hookable;
use WPPluginStarter\Core\Interfaces\Initable;
use WPPluginStarter\Setup\Migrator;
use WP_REST_Controller;

/**
 * Bootstrap
 *
 * Handles plugin bootstrapping
 *
 * @package WPPluginStarter\Core
 */
class Bootstrap implements Hookable {

	public function register_hooks(): void {
		add_action( 'rest_api_init', array( $this, 'boot_rest' ), 10 );
	}

	/**
	 * Bootstrap the plugin
	 *
	 * This method initializes all components of the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function boot(): void {
		/**
		 * Action fired before bootstrapping the plugin components.
		 *
		 * @since 1.0.0
		 *
		 * @param Bootstrap $this Bootstrap instance
		 */
		do_action( 'wp_plugin_starter_before_bootstrap', $this );

		$this->boot_core();
		$this->boot_admin();
		$this->boot_settings_setup();
		$this->init_migrations();

		/**
		 * Action fired after bootstrapping all plugin components.
		 *
		 * @since 1.0.0
		 *
		 * @param Bootstrap $this Bootstrap instance
		 */
		do_action( 'wp_plugin_starter_bootstrap', $this );
	}

	protected function boot_core(): void {
		$services = wp_plugin_starter_get_container()->get( 'core-service' );
		if ( $services instanceof Initable ) {
			$services->initialize();
		}
	}

	/**
	 * Boot admin components
	 *
	 * @return void
	 */
	protected function boot_admin(): void {
		if ( is_admin() ) {
			wp_plugin_starter_get_container()->get( 'admin-dashboard-service' );
			wp_plugin_starter_get_container()->get( 'admin-service' );
		}

		$services = wp_plugin_starter_get_container()->get( 'setup-service' );
		foreach ( $services as $service ) {
			if ( $service instanceof Initable ) {
				$service->initialize();
			}
		}

		/**
		 * Fire action after admin bootstrap
		 *
		 * @param Bootstrap $this Bootstrap instance
		 */
		do_action( 'wp_plugin_starter_after_admin_bootstrap', $this );
	}

	/**
	 * Boot REST API
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function boot_rest(): void {
		/**
		 * Filter the REST controllers to be registered.
		 *
		 * @since 1.0.0
		 *
		 * @param array     $controllers The REST controllers.
		 * @param Bootstrap $this        The bootstrap instance.
		 */
		$controllers = wp_plugin_starter_get_container()->get( 'rest-service' );

		/**
		 * Action fired before registering REST routes.
		 *
		 * @since 1.0.0
		 *
		 * @param array     $controllers The REST controllers.
		 * @param Bootstrap $this        The bootstrap instance.
		 */
		do_action( 'wp_plugin_starter_before_rest_routes_registration', $controllers, $this );

		foreach ( $controllers as $controller ) {
			if ( ! $controller instanceof WP_REST_Controller ) {
				continue;
			}

			/**
			 * Action fired before registering a specific REST controller's routes.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Controller $controller The REST controller.
			 * @param Bootstrap          $this       The bootstrap instance.
			 */
			do_action( 'wp_plugin_starter_before_rest_controller_routes', $controller, $this );

			$controller->register_routes();

			/**
			 * Action fired after registering a specific REST controller's routes.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Controller $controller The REST controller.
			 * @param Bootstrap          $this       The bootstrap instance.
			 */
			do_action( 'wp_plugin_starter_after_rest_controller_routes', $controller, $this );
		}

		/**
		 * Action fired after registering REST routes.
		 *
		 * @since 1.0.0
		 *
		 * @param array     $controllers The REST controllers.
		 * @param Bootstrap $this        The bootstrap instance.
		 */
		do_action( 'wp_plugin_starter_after_rest_routes_registration', $controllers, $this );

		/**
		 * Action fired after REST bootstrap.
		 *
		 * @since 1.0.0
		 *
		 * @param Bootstrap $this Bootstrap instance.
		 */
		do_action( 'wp_plugin_starter_after_rest_bootstrap', $this );
	}

	/**
	 * Boot features setup
	 *
	 * @return void
	 */
	protected function boot_settings_setup(): void {
		wp_plugin_starter_get_container()->get( 'setup-service' );

		/**
		 * Fire action after features setup bootstrap
		 *
		 * @param Bootstrap $this Bootstrap instance
		 */
		do_action( 'wp_plugin_starter_after_settings_setup_bootstrap', $this );
	}

	/**
	 * Initialize migrations
	 *
	 * @return void
	 */
	public function init_migrations(): void {
		wp_plugin_starter_get_container()->get( 'migration-service' );

		$migrator = wp_plugin_starter_get_container()->get( Migrator::class );
		$migrator->run();
	}
}
