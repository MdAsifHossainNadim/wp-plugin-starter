<?php

/**
 * WP Plugin Starter - Main Plugin Class
 *
 * This file contains the main plugin class that initializes and bootstraps the
 * WP Plugin Starter plugin. It provides a centralized API for registering features,
 * managing features, and extending the plugin functionality.
 *
 * @since   1.0.0
 * @package WPPluginStarter
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use WPPluginStarter\Core\Bootstrap;
use WPPluginStarter\Core\Data\Stores\Product_Data_Store;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Hookable;
use WPPluginStarter\Setup\System_Check;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main WP Plugin Starter Plugin Class
 *
 * This class serves as the core of the WP Plugin Starter plugin, implementing the singleton pattern
 * to ensure only one instance is loaded. It manages plugin initialization, handling
 * dependencies, hooks registration, and provides an extensive API for developers to extend
 * and interact with the plugin.
 *
 * Features:
 * - Dependency Injection Container integration
 * - SettingsModel management system
 * - Feature registration and management
 * - Extensible hooks architecture
 * - WooCommerce compatibility management
 *
 * @since   1.0.0
 * @package WPPluginStarter
 */
final class WP_Plugin_Starter {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public string $version = WP_PLUGIN_STARTER_VERSION;

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	public string $slug = 'wp-plugin-starter';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * Implements the singleton pattern to ensure only one instance of the plugin exists
	 * at any time. This method is the preferred way to access the plugin functionality.
	 *
	 * @since  1.0.0
	 * @static
	 * @access public
	 *
	 * @return WP_Plugin_Starter A single instance of this class.
	 */
	public static function instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize plugin hooks
	 *
	 * Sets up all the necessary WordPress hooks for the plugin lifecycle:
	 * - WooCommerce compatibility
	 * - Plugin bootstrap
	 * - Activation/deactivation hooks
	 *
	 * Provides 'before' and 'after' action hooks for third-party extensions.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @return void
	 */
	public function init(): void {
		/**
		 * Action hook that fires before initializing the plugin hooks
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_before_init_hooks', $this );

		// WooCommerce compatibility hooks.
		add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility' ) );
		add_filter( 'woocommerce_data_stores', array( $this, 'load_data_stores' ) );

		// Bootstrap the plugin after all plugins are loaded
		add_action( 'init', array( $this, 'load_hookable_services' ) );
		add_action( 'init', array( $this, 'boot' ) );

		// Register activation and deactivation hooks
		register_activation_hook( WP_PLUGIN_STARTER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( WP_PLUGIN_STARTER_FILE, array( $this, 'deactivate' ) );

		/**
		 * Action hook that fires after initializing the plugin hooks
		 * Use this to add additional hooks to the plugin
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_after_init_hooks', $this );
	}

	/**
	 * Get container instance
	 *
	 * Retrieves the dependency injection container used by the plugin.
	 * This container holds all registered services and can be extended
	 * by third-party developers using the 'wp_plugin_starter_get_container' filter.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return \WPPluginStarter\Core\DI\Container The dependency injection container instance.
	 */
	public function get_container(): \WPPluginStarter\Core\DI\Container {
		$container = wp_plugin_starter_get_container();

		/**
		 * Filter the container instance
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter                   $this      Plugin instance
		 * @param \WPPluginStarter\Core\DI\Container $container The container instance
		 */
		return apply_filters( 'wp_plugin_starter_get_container', $container, $this );
	}

	/**
	 * Load hookable services
	 *
	 * Loads the hookable services required for the plugin to function.
	 * This includes setting up the dependency injection container
	 * and registering all necessary services with WordPress hooks.
	 * Services must implement the Hookable interface to be registered.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function load_hookable_services(): void {
		/**
		 * Action hook that fires before loading hookable services
		 *
		 * Use this hook to register custom services to the container
		 * before the plugin loads its hookable services.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_before_load_hookable_services', $this );

		// Get all registered hookable services
		$hooks = wp_plugin_starter_get_container()->get( Hookable::class );

		/**
		 * Filter the hookable services
		 *
		 * Allows third-party developers to add or modify the hookable services
		 * that get registered with WordPress.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $hooks Array of hookable service instances
		 * @param WP_Plugin_Starter $this  Plugin instance
		 */
		$hooks = apply_filters( 'wp_plugin_starter_hookable_services', $hooks, $this );

		// Register hooks for each service
		foreach ( $hooks as $hook ) {
			$hook->register_hooks();
		}

		/**
		 * Action hook that fires after loading and registering hookable services
		 *
		 * @since 1.0.0
		 *
		 * @param array      $hooks Array of hookable service instances that were registered
		 * @param WP_Plugin_Starter $this  Plugin instance
		 */
		do_action( 'wp_plugin_starter_after_load_hookable_services', $hooks, $this );
	}

	/**
	 * Load data stores
	 *
	 * Loads the data stores required for the plugin to function.
	 * This includes setting up the dependency injection container
	 * and registering all necessary data stores.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @param array $stores Array of data stores to load.
	 *
	 * @return array Modified array of data stores.
	 */
	public function load_data_stores( array $stores ): array {
		$stores['wp_plugin_starter_product']  = Product_Data_Store::class;
		$stores['wp_plugin_starter_settings'] = Settings_Data_Store::class;

		/**
		 * Filter the data stores to be loaded
		 *
		 * Allows third-party developers to register their own data stores
		 * for use with the WP Plugin Starter plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $stores The array of data stores
		 * @param WP_Plugin_Starter $this   Plugin instance
		 */
		return apply_filters( 'wp_plugin_starter_data_stores', $stores, $this );
	}

	/**
	 * Boot the plugin
	 *
	 * Main plugin initialization sequence:
	 * 1. Loads text domain for internationalization
	 * 2. Checks plugin dependencies
	 * 3. Bootstraps the plugin components
	 * 4. Fires plugin loaded actions
	 *
	 * Includes multiple action hooks for third-party extensions to tap into
	 * the plugin's lifecycle events.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function boot(): void {
		/**
		 * Action hook that fires before the plugin boots
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_before_boot', $this );

		// Check system requirements
		$system_check = wp_plugin_starter_get_container()->get( System_Check::class );
		if ( ! $system_check->check() ) {
			add_action( 'admin_notices', array( $system_check, 'admin_notice' ) );
			return;
		}

		// Initialize database tables from each data store
		$this->initialize_data_stores();

		// Load bootstrap
		$bootstrap = wp_plugin_starter_get_container()->get( Bootstrap::class );
		$bootstrap->boot();

		/**
		 * Fire an action when the plugin is fully loaded
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_loaded', $this );
	}

	/**
	 * Initialize all registered data stores
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function initialize_data_stores(): void {
		// Get all data stores from the container
		$data_stores = $this->get_container()->get( 'data-store-service' );

		// Initialize each data store that has an initialize method
		foreach ( $data_stores as $data_store ) {
			if ( method_exists( $data_store, 'initialize' ) ) {
				$data_store->initialize();
			}
		}

		/**
		 * Action after initializing all data stores
		 *
		 * @since 1.0.0
		 *
		 * @param array $data_stores Array of initialized data stores
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_data_stores_initialized', $data_stores, $this );
	}

	/**
	 * Declare compatibility with WooCommerce custom order tables
	 *
	 * @return void
	 */
	public function declare_compatibility(): void {
		if ( ! class_exists( FeaturesUtil::class ) ) {
			return;
		}

		// Declare compatibility with WooCommerce custom order tables.
		FeaturesUtil::declare_compatibility( 'custom_order_tables', WP_PLUGIN_STARTER_FILE, true );
	}

	/**
	 * Plugin activation
	 *
	 * @return void
	 */
	public function activate(): void {
		/**
		 * Action hook that fires before plugin activation
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_before_activate', $this );

		$activator = wp_plugin_starter_get_container()->get( WPPluginStarter\Setup\Activator::class );
		$activator->run();

		/**
		 * Action hook that fires after plugin activation
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_after_activate', $this );
	}

	/**
	 * Plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate(): void {
		/**
		 * Action hook that fires before plugin deactivation
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_before_deactivate', $this );

		$deactivator = wp_plugin_starter_get_container()->get( WPPluginStarter\Setup\Deactivator::class );
		$deactivator->run();

		/**
		 * Action hook that fires after plugin deactivation
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Plugin_Starter $this Plugin instance
		 */
		do_action( 'wp_plugin_starter_after_deactivate', $this );
	}

	/**
	 * Check if Pro version is active
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_pro_active(): bool {
		return apply_filters( 'wp_plugin_starter_is_pro_active', false );
	}
}
