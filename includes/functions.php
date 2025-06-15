<?php
/**
 * WP Plugin Starter Helper Functions
 *
 * @package WPPluginStarter
 */

use WPPluginStarter\Admin\Notices;
use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Template_Manager;
use WPPluginStarter\Utils\Logger;

/**
 * Get a service from the container
 *
 * @param string $id            Service ID
 * @param mixed  $default_value Default value if service doesn't exist
 *
 * @return mixed
 */
function wp_plugin_starter_service( string $id, $default_value = null ) {
	$container = wp_plugin_starter_get_container();

	if ( $container->has( $id ) ) {
		return $container->get( $id );
	}

	return $default_value;
}

/**
 * Get template manager
 *
 * @return Template_Manager
 */
function wp_plugin_starter_template_manager(): Template_Manager {
	return wp_plugin_starter_get_container()->get( Template_Manager::class );
}

/**
 * Get notice manager
 *
 * @return Notices
 */
function wp_plugin_starter_notice_manager(): Notices {
	return wp_plugin_starter_get_container()->get( Notices::class );
}

/**
 * Check if Dokan is active
 *
 * @return bool
 */
function wp_plugin_starter_is_dokan_active(): bool {
	return class_exists( 'WeDevs_Dokan' );
}

/**
 * Check if Dokan Pro is active
 *
 * @return bool
 */
function wp_plugin_starter_is_dokan_pro_active(): bool {
	return function_exists( 'dokan_pro' );
}

function wp_plugin_starter_is_woocommerce_active(): bool {
	return class_exists( 'WooCommerce' ) && function_exists( 'wc_get_logger' );
}

/**
 * Get template part (for templates in template-parts/ directory)
 *
 * @param string $slug Template slug
 * @param string $name Template name (optional)
 * @param array  $args Template arguments (optional)
 *
 * @return void
 */
function wp_plugin_starter_get_template_part( string $slug, string $name = '', array $args = array() ) {
	$template_name = $slug . ( $name ? '-' . $name : '' );
	wp_plugin_starter_template_manager()->get_template( $template_name, $args );
}

/**
 * Get template
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments (optional)
 *
 * @return void
 */
function wp_plugin_starter_get_template( string $template_name, array $args = array() ) {
	wp_plugin_starter_template_manager()->get_template( $template_name, $args );
}

/**
 * Get the Logger instance
 *
 * @return Logger Logger instance
 */
function wp_plugin_starter_logger(): Logger {
	return wp_plugin_starter_get_container()->get( Logger::class );
}
