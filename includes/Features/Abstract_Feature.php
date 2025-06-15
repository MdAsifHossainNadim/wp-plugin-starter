<?php

namespace WPPluginStarter\Features;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Abstract Feature
 *
 * Base class for all features in WP Plugin Starter.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Features
 */
abstract class Abstract_Feature implements Feature_Interface, Hookable {

	/**
	 * Feature name
	 *
	 * @var string
	 */
	protected string $name = '';

	/**
	 * Feature description
	 *
	 * @var string
	 */
	protected string $description = '';

	/**
	 * Feature option key
	 *
	 * @var string
	 */
	protected string $option_key = '';

	/**
	 * Feature settings group
	 *
	 * @var string
	 */
	protected string $settings_group = 'general';

	/**
	 * Constructor
	 *
	 * Initialize feature properties. Child classes should override this
	 * to set their specific values and make strings translatable.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize feature properties
	 *
	 * Child classes should override this method to set their name,
	 * description, and option key with translatable strings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	abstract protected function init(): void;

	/**
	 * Register hooks with WordPress
	 *
	 * This method must be implemented by all feature classes.
	 * It should register all necessary hooks for the feature to work.
	 *
	 * @return void
	 */
	abstract public function register_hooks(): void;

	/**
	 * Check if the feature is enabled
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_enabled(): bool {
		// If option key is empty, feature is considered always enabled
		if ( empty( $this->option_key ) ) {
			return apply_filters( 'WPPluginStarter_feature_' . $this->get_slug() . '_enabled', true, $this );
		}

		// Get the setting value and convert to boolean
		$enabled = wc_string_to_bool( $this->get_setting( $this->option_key, false ) );

		return apply_filters( 'WPPluginStarter_feature_' . $this->get_slug() . '_enabled', $enabled, $this );
	}

	/**
	 * Get setting by key
	 *
	 * @since 1.0.0
	 * @param string $key     Setting key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	protected function get_setting( string $key, $default = '' ) {
		// Get settings data store
		$data_store = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );

		if ( ! $data_store ) {
			return get_option( $key, $default );
		}

		// Get settings by group
		$settings = $data_store->get_settings_by_name( $this->settings_group );

		if ( ! $settings instanceof Settings_Model || ! $settings->get_value() ) {
			return get_option( $key, $default );
		}

		return $settings->get( $key ) ?? $default;
	}

	/**
	 * Get feature name
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_name(): string {
		return apply_filters( 'WPPluginStarter_feature_name', $this->name, $this );
	}

	/**
	 * Get feature description
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_description(): string {
		return apply_filters( 'WPPluginStarter_feature_description', $this->description, $this );
	}

	/**
	 * Get feature slug
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_slug(): string {
		$class_name = get_class( $this );
		$parts      = explode( '\\', $class_name );
		$last_part  = end( $parts );

		$slug = strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $last_part ) );

		return apply_filters( 'WPPluginStarter_feature_slug', $slug, $this, $class_name );
	}
}
