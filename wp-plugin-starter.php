<?php
/**
 * WP Plugin Starter
 *
 * @package           WP_PLUGIN_STARTER
 * @author            Md. Asif Hossain Nadim <devianadim@gmail.com>
 * @copyright         2025 Md. Asif Hossain
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:     WP Plugin Starter
 * Plugin URI:      https://wordpress.org/plugins/wp-plugin-starter/
 * Description:     This is a modern, extensible WordPress plugin boilerplate. It provides a robust foundation for building advanced plugins, featuring modular architecture, dependency injection, REST API integration, and best practices for WordPress and WooCommerce development. Ideal for both single-site and multi-vendor marketplace enhancements..
 * Version:         1.0.0
 * Author:          WPIntegrity
 * Author URI:      https://profiles.wordpress.org/devianadim9/
 * Text Domain:     wp-plugin-starter
 *
 * Requires Plugins: woocommerce, dokan-lite
 *
 * Requires at least: 6.4.2
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * WC requires at least: 7.9
 * WC tested up to: 9.8.3
 * Dokan requires at least: 3.9.7
 * Dokan tested up to: 4.0.1
 *
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'WP_PLUGIN_STARTER_VERSION', '1.0.0' );
define( 'WP_PLUGIN_STARTER_FILE', __FILE__ );
define( 'WP_PLUGIN_STARTER_BASENAME', plugin_basename( __FILE__ ) );
define( 'WP_PLUGIN_STARTER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_PLUGIN_STARTER_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'WP_PLUGIN_STARTER_ASSETS_URL', WP_PLUGIN_STARTER_PLUGIN_URL . '/assets' );
define( 'WP_PLUGIN_STARTER_BUILD_URL', WP_PLUGIN_STARTER_PLUGIN_URL . '/build' );
define( 'WP_PLUGIN_STARTER_TEMPLATE_PATH', WP_PLUGIN_STARTER_PLUGIN_PATH . 'templates/' );

// Composer autoloader.
if ( ! file_exists( WP_PLUGIN_STARTER_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	return;
}

require_once WP_PLUGIN_STARTER_PLUGIN_PATH . 'vendor/autoload.php';

/**
 * Include file for loading the WP_PLUGIN_STARTER class.
 */
require_once WP_PLUGIN_STARTER_PLUGIN_PATH . 'class-wp-plugin-starter.php';

/**
 * Declare the $wp_plugin_starter_container as global to access from inside functions.
 */
global $wp_plugin_starter_container;

/**
 * Initialize the container.
 */
$wp_plugin_starter_container = new WPPluginStarter\Core\DI\Container();
$wp_plugin_starter_container->addServiceProvider( new WPPluginStarter\Core\DI\Providers\Service_Provider() );

/**
 * Get the global container instance.
 *
 * @since 1.0.0
 *
 * @return WPPluginStarter\Core\DI\Container The global container instance.
 */
function wp_plugin_starter_get_container(): WPPluginStarter\Core\DI\Container {
	global $wp_plugin_starter_container;

	return $wp_plugin_starter_container;
}

/**
 * Initialize the main plugin
 *
 * @return WP_Plugin_Starter
 */
function wp_plugin_starter(): WP_Plugin_Starter {
	return WP_Plugin_Starter::instance();
}

// Take off the plugin.
wp_plugin_starter()->init();
