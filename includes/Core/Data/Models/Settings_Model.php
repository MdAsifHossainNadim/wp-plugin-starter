<?php

namespace WPPluginStarter\Core\Data\Models;

use WPPluginStarter\Core\Data\Model;
use WC_Data_Store;
use WC_DateTime;
use DateTimeInterface;

/**
 * SettingsModel Model
 *
 * This model handles the storage and retrieval of plugin settings data.
 * It provides a structured approach to managing configuration data across
 * the plugin, organized by setting groups.
 *
 * @since 1.0.0
 * @since 1.0.0 Enhanced with additional methods, hooks, and improved documentation
 * @package WPPluginStarter\Core\Data\Models
 */
class Settings_Model extends Model {
	/**
	 * Data array
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $data = array(
		'name'          => '',
		'value'         => '',
		'default'       => '',
		'date_created'  => null,
		'date_modified' => null,
	);

	/**
	 * This is the name of this object type
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $object_type = 'settings';

	/**
	 * Cache group for this object type
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-settings';

	/**
	 * Constructor
	 *
	 * Initializes the SettingsModel model with optional data.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Enhanced with improved error handling and hooks
	 *
	 * @param array|int $data Data to initialize the object, or ID of the setting to load
	 */
	public function __construct( $data = array() ) {
		parent::__construct();

		if ( is_numeric( $data ) && $data > 0 ) {
			$this->set_id( $data );
		} elseif ( is_array( $data ) ) {
			$this->set_id( $data['id'] ?? 0 );
			$this->set_name( $data['name'] ?? '' );
			$this->set_value( maybe_unserialize( $data['value'] ?? '' ) );
			$this->set_default( maybe_unserialize( $data['default'] ?? '' ) );

			if ( isset( $data['date_created'] ) ) {
				$this->set_date_created( $data['date_created'] );
			}

			if ( isset( $data['date_modified'] ) ) {
				$this->set_date_modified( $data['date_modified'] );
			}
		}

		$this->set_object_read( true );

		// If we have an ID, load the setting from the data store
		if ( $this->get_id() ) {
			try {
				$this->data_store = WC_Data_Store::load( 'WPPluginStarter_settings' );

				if ( $this->data_store ) {
					$this->data_store->read( $this );
				}
			} catch ( \Exception $e ) {
				$this->data_store = null;

				// Log the error but don't halt execution
				error_log( sprintf( 'Error loading settings model with ID %d: %s', $this->get_id(), $e->getMessage() ) );
			}
		} else {
			$this->data_store = WC_Data_Store::load( 'WPPluginStarter_settings' );
		}

		/**
		 * Action fired after a settings object is initialized.
		 *
		 * @since 1.0.0
		 * @since 1.0.0 Added the raw data parameter
		 *
		 * @param Settings_Model $this The settings object
		 * @param mixed          $data The data used to initialize the object
		 */
		do_action( 'WPPluginStarter_settings_initialized', $this, $data );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get settings name
	 *
	 * @since 1.0.0
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return string
	 */
	public function get_name( string $context = 'view' ): string {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get settings value
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added filtering capability
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return mixed
	 */
	public function get_value( string $context = 'view' ) {
		$value = $this->get_prop( 'value', $context );

		/**
		 * Filter the settings value.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed          $value   The setting value.
		 * @param string         $name    The setting name/group.
		 * @param Settings_Model $this    The settings object.
		 * @param string         $context The context ('view' or 'edit').
		 *
		 * @return mixed The filtered value.
		 */
		return apply_filters( 'WPPluginStarter_settings_value', $value, $this->get_name( $context ), $this, $context );
	}

	/**
	 * Get default value
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added filtering capability
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 *
	 * @return mixed
	 */
	public function get_default( string $context = 'view' ) {
		$default = $this->get_prop( 'default', $context );

		/**
		 * Filter the default settings value.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed          $default The default value.
		 * @param string         $name    The setting name/group.
		 * @param Settings_Model $this    The settings object.
		 * @param string         $context The context ('view' or 'edit').
		 *
		 * @return mixed The filtered default value.
		 */
		return apply_filters( 'WPPluginStarter_settings_default_value', $default, $this->get_name( $context ), $this, $context );
	}

	/**
	 * Get date created.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Improved return type documentation
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return WC_DateTime|null Object if the date is set or null if there is no date.
	 */
	public function get_date_created( string $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get date modified.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Improved return type documentation
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return WC_DateTime|null Object if the date is set or null if there is no date.
	 */
	public function get_date_modified( string $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set settings name.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added filtering capability
	 * @param string $name Setting name.
	 * @return void
	 */
	public function set_name( string $name ): void {
		/**
		 * Filter the settings name before it's set.
		 *
		 * @since 1.0.0
		 *
		 * @param string         $name The setting name to be set.
		 * @param Settings_Model $this The settings object.
		 *
		 * @return string The filtered setting name.
		 */
		$filtered_name = apply_filters( 'WPPluginStarter_pre_set_setting_name', $name, $this );

		$this->set_prop( 'name', sanitize_text_field( $filtered_name ) );
	}

	/**
	 * Set settings value.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added filtering capability
	 * @param mixed $value SettingsModel value.
	 * @return void
	 */
	public function set_value( $value ): void {
		/**
		 * Filter the settings value before it's set.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed          $value The setting value to be set.
		 * @param string         $name  The setting name/group.
		 * @param Settings_Model $this  The settings object.
		 *
		 * @return mixed The filtered setting value.
		 */
		$filtered_value = apply_filters( 'WPPluginStarter_pre_set_setting_value', $value, $this->get_name(), $this );

		$this->set_prop( 'value', $filtered_value );
	}

	/**
	 * Set default value.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added filtering capability
	 * @param mixed $value Default value.
	 * @return void
	 */
	public function set_default( $value ): void {
		/**
		 * Filter the default settings value before it's set.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed          $value The default value to be set.
		 * @param string         $name  The setting name/group.
		 * @param Settings_Model $this  The settings object.
		 *
		 * @return mixed The filtered default value.
		 */
		$filtered_value = apply_filters( 'WPPluginStarter_pre_set_setting_default', $value, $this->get_name(), $this );

		$this->set_prop( 'default', $filtered_value );
	}

	/**
	 * Set date created.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added support for DateTimeInterface and improved type handling
	 * @param string|integer|DateTimeInterface $date_created UTC timestamp, or ISO 8601 DateTime.
	 * @return void
	 */
	public function set_date_created( $date_created ): void {
		$this->set_date_prop( 'date_created', $date_created );
	}

	/**
	 * Set date modified.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added support for DateTimeInterface and improved type handling
	 * @param string|integer|DateTimeInterface $date_modified UTC timestamp, or ISO 8601 DateTime.
	 * @return void
	 */
	public function set_date_modified( $date_modified ): void {
		$this->set_date_prop( 'date_modified', $date_modified );
	}

	/**
	 * Save the settings model data.
	 *
	 * @since 1.0.0
	 * @since 1.0.0 Added hooks and improved error handling
	 * @return int The settings ID
	 */
	public function save(): int {
		if ( ! $this->data_store ) {
			return $this->get_id();
		}

		/**
		 * Action triggered before saving settings model.
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model $this The settings model being saved.
		 * @param string         $name The settings name/group.
		 */
		do_action( 'WPPluginStarter_before_settings_save', $this, $this->get_name() );

		// Set date modified
		$this->set_date_modified( time() );

		// If creating, set date created
		if ( ! $this->get_id() ) {
			$this->set_date_created( time() );
		}

		// Save the data
		$id = $this->data_store->save( $this );

		/**
		 * Action triggered after saving settings model.
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model $this The settings model that was saved.
		 * @param string         $name The settings name/group.
		 * @param int            $id   The settings ID.
		 */
		do_action( 'WPPluginStarter_after_settings_save', $this, $this->get_name(), $id );

		return $id;
	}
}
