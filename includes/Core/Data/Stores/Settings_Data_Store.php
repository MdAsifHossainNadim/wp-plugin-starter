<?php

namespace WPPluginStarter\Core\Data\Stores;

use WPPluginStarter\Core\Data\Data_Store;
use WPPluginStarter\Core\Data\Models\Settings_Model;

/**
 * SettingsModel Data Store
 *
 * Handles storage and retrieval of SettingsModel data
 *
 * @since 1.0.0
 * @package WPPluginStarter\Core\Data\Stores
 */
class Settings_Data_Store extends Data_Store {
	/**
	 * Meta type for settings
	 *
	 * @var string
	 */
	protected $meta_type = 'settings';

	/**
	 * Object type this store handles
	 *
	 * @var string
	 */
	protected $object_type = 'settings';

	/**
	 * Data stored in meta keys, but not considered "meta" for an object.
	 *
	 * @var array<string>
	 */
	protected $internal_meta_keys = array(
		'_name',
		'_value',
		'_default',
		'_date_created',
		'_date_modified',
	);

	/**
	 * SettingsModel cache group
	 *
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-settings';

	/**
	 * Table name for settings data
	 *
	 * @var string
	 */
	protected string $table_name;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'wp_plugin_starter_settings';
	}

	/**
	 * Method to create a new setting in the database
	 *
	 * @param Settings_Model $settings SettingsModel object
	 *
	 * @return void
	 */
	public function create( &$settings ) {
		global $wpdb;

		if ( ! $settings->get_date_created( 'edit' ) ) {
			$settings->set_date_created( time() );
		}

		if ( ! $settings->get_date_modified( 'edit' ) ) {
			$settings->set_date_modified( time() );
		}

		/**
		 * Action that fires before creating a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings SettingsModel object
		 * @param Settings_Data_Store $this     Data store instance
		 */
		do_action( 'wp_plugin_starter_settings_before_create', $settings, $this );

		$data = array(
			'name'          => $settings->get_name( 'edit' ),
			'value'         => $this->prepare_value_for_db( $settings->get_value( 'edit' ) ),
			'default'       => $this->prepare_value_for_db( $settings->get_default( 'edit' ) ),
			'date_created'  => gmdate( 'Y-m-d H:i:s', $settings->get_date_created( 'edit' ) ? $settings->get_date_created( 'edit' )->getTimestamp() : time() ),
			'date_modified' => gmdate( 'Y-m-d H:i:s', $settings->get_date_modified( 'edit' ) ? $settings->get_date_modified( 'edit' )->getTimestamp() : time() ),
		);

		$result = $wpdb->insert(
			$this->table_name,
			$data,
			array(
				'%s', // name
				'%s', // value
				'%s', // default
				'%s', // date_created
				'%s', // date_modified
			)
		);

		if ( false === $result ) {
			if ( function_exists( 'wp_plugin_starter_logger' ) ) {
				wp_plugin_starter_logger()->error(
					sprintf( 'Error creating settings record: %s', $wpdb->last_error )
				);
			}
			return;
		}

		$settings_id = $wpdb->insert_id;
		$settings->set_id( $settings_id );
		$settings->apply_changes();

		// Save meta data
		$settings->save_meta_data();

		$this->clear_caches( $settings );

		/**
		 * Action that fires after creating a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param int                 $settings_id SettingsModel ID
		 * @param Settings_Model      $settings    SettingsModel object
		 * @param Settings_Data_Store $this        Data store instance
		 */
		do_action( 'wp_plugin_starter_settings_create', $settings_id, $settings, $this );
	}

	/**
	 * Method to read settings from the database
	 *
	 * @param Settings_Model $settings SettingsModel object
	 *
	 * @return void
	 */
	public function read( &$settings ) {
		global $wpdb;

		$settings->set_defaults();

		$settings_id   = $settings->get_id();
		$settings_name = $settings->get_name( 'edit' );

		// Return early if no ID or name
		if ( ! $settings_id && empty( $settings_name ) ) {
			$settings->set_object_read( true );
			return;
		}

		$data = null;

		// Try different ways to get the settings
		if ( $settings_id ) {
			// Try to get from cache
			$data = wp_cache_get( $settings_id, $this->cache_group );

			if ( false === $data ) {
				// Not in cache, fetch from database
				$data = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT * FROM {$this->table_name} WHERE id = %d LIMIT 1",
						$settings_id
					)
				);

				// Cache the result
				if ( $data ) {
					wp_cache_set( $settings_id, $data, $this->cache_group );
				}
			}
		} elseif ( $settings_name ) {
			// Try to get from cache with name
			$name_cache_key = 'settings_' . md5( $settings_name );
			$data           = wp_cache_get( $name_cache_key, $this->cache_group );

			if ( false === $data ) {
				// Not in cache, fetch from database
				$data = $wpdb->get_row(
					$wpdb->prepare(
						"SELECT * FROM {$this->table_name} WHERE name = %s LIMIT 1",
						$settings_name
					)
				);

				// Cache the result
				if ( $data ) {
					wp_cache_set( $name_cache_key, $data, $this->cache_group );
					wp_cache_set( $data->id, $data, $this->cache_group );
					$settings->set_id( $data->id );
				}
			} else {
				$settings->set_id( $data->id );
			}
		}

		if ( ! $data ) {
			// No data found, but we'll still mark as read to prevent infinite loops
			$settings->set_object_read( true );
			return;
		}

		// Set settings data
		$settings->set_props(
			array(
				'name'          => $data->name,
				'value'         => $this->prepare_value_from_db( $data->value ),
				'default'       => $this->prepare_value_from_db( $data->default ),
				'date_created'  => $this->string_to_timestamp( $data->date_created ),
				'date_modified' => $this->string_to_timestamp( $data->date_modified ),
			)
		);

		$settings->set_object_read( true );

		// Read meta data
		$settings->read_meta_data();

		/**
		 * Action that fires after reading a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings SettingsModel object
		 * @param object              $data     The data read from the database
		 */
		do_action( 'wp_plugin_starter_settings_read', $settings, $data );
	}

	/**
	 * Method to update settings in the database
	 *
	 * @param Settings_Model $settings SettingsModel object
	 *
	 * @return void
	 */
	public function update( &$settings ) {
		global $wpdb;

		// Set updated timestamp
		$settings->set_date_modified( time() );

		/**
		 * Action that fires before updating a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param int                 $settings_id SettingsModel ID
		 * @param Settings_Model      $settings    SettingsModel object
		 */
		do_action( 'WPPluginStarter_settings_before_update', $settings->get_id(), $settings );

		$data = array(
			'name'          => $settings->get_name( 'edit' ),
			'value'         => $this->prepare_value_for_db( $settings->get_value( 'edit' ) ),
			'default'       => $this->prepare_value_for_db( $settings->get_default( 'edit' ) ),
			'date_modified' => gmdate( 'Y-m-d H:i:s', $settings->get_date_modified( 'edit' )->getTimestamp() ),
		);

		$format = array(
			'%s', // name
			'%s', // value
			'%s', // default
			'%s', // date_modified
		);

		$wpdb->update(
			$this->table_name,
			$data,
			array(
				'id' => $settings->get_id(),
			),
			$format,
			array( '%d' )
		);

		$settings->apply_changes();

		// Update meta data
		$settings->save_meta_data();

		$this->clear_caches( $settings );

		/**
		 * Action that fires after updating a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param int                 $settings_id SettingsModel ID
		 * @param Settings_Model      $settings    SettingsModel object
		 * @param array               $data        The data that was updated in the database
		 */
		do_action( 'WPPluginStarter_settings_update', $settings->get_id(), $settings, $data );
	}

	/**
	 * Method to delete settings from the database
	 *
	 * @param Settings_Model $settings SettingsModel object
	 * @param array          $args     Array of args to pass to the delete method
	 *
	 * @return bool
	 */
	public function delete( &$settings, $args = array() ) {
		global $wpdb;

		$id = $settings->get_id();
		if ( ! $id ) {
			return false;
		}

		/**
		 * Filter to allow settings to be deleted or not
		 *
		 * @since 1.0.0
		 *
		 * @param bool                $delete   Whether to delete the settings
		 * @param int                 $id       SettingsModel ID
		 * @param Settings_Model      $settings SettingsModel object
		 * @param array               $args     Array of args passed to the delete method
		 * @param Settings_Data_Store $this     Data store instance
		 */
		$delete = apply_filters( 'WPPluginStarter_settings_pre_delete', true, $id, $settings, $args, $this );

		if ( ! $delete ) {
			return false;
		}

		$args = wp_parse_args(
			$args,
			array(
				'force_delete' => false,
			)
		);

		/**
		 * Action that fires before deleting a settings record
		 *
		 * @since 1.0.0
		 *
		 * @param int                 $id       SettingsModel ID
		 * @param Settings_Model      $settings SettingsModel object
		 * @param array               $args     Array of args passed to the delete method
		 * @param Settings_Data_Store $this     Data store instance
		 */
		do_action( 'WPPluginStarter_settings_before_delete', $id, $settings, $args, $this );

		if ( $args['force_delete'] ) {
			// Delete metadata
			$wpdb->delete(
				$wpdb->prefix . 'WPPluginStarter_settingsmeta',
				array( 'settings_id' => $id ),
				array( '%d' )
			);

			// Delete settings
			$result = $wpdb->delete(
				$this->table_name,
				array( 'id' => $id ),
				array( '%d' )
			);

			if ( ! $result ) {
				if ( function_exists( 'WPPluginStarter_logger' ) ) {
					WPPluginStarter_logger()->error(
						sprintf( 'Error deleting settings record: %s', $wpdb->last_error )
					);
				}
				return false;
			}

			$settings->set_id( 0 );

			/**
			 * Action that fires after deleting a settings record
			 *
			 * @since 1.0.0
			 *
			 * @param int                 $id       SettingsModel ID
			 * @param Settings_Model      $settings SettingsModel object
			 * @param Settings_Data_Store $this     Data store instance
			 */
			do_action( 'WPPluginStarter_settings_delete', $id, $settings, $this );
		} else {
			// Soft delete could be implemented here
			/**
			 * Action that fires after trashing a settings record
			 *
			 * @since 1.0.0
			 *
			 * @param int                 $id       SettingsModel ID
			 * @param Settings_Model      $settings SettingsModel object
			 * @param Settings_Data_Store $this     Data store instance
			 */
			do_action( 'WPPluginStarter_settings_trash', $id, $settings, $this );
		}

		$this->clear_caches( $settings );

		return true;
	}

	/**
	 * Method to set the ID of a settings object.
	 *
	 * This method ensures the ID is properly set and any related caching is handled.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The settings ID to set
	 *
	 * @return void
	 */
	public function set_id( &$settings, int $id ): void {
		$settings->set_id( $id );
		$this->clear_caches( $settings );
	}

	/**
	 * Method to save a record to the database.
	 *
	 * @since 1.0.0
	 *
	 * @param WC_Data $settings Settings model object.
	 *
	 * @return int
	 */
	public function save( WC_Data $settings ): int {
		$id = $settings->get_id();

		if ( $id ) {
			$this->update( $settings );
		} else {
			$this->create( $settings );
		}

		return $settings->get_id();
	}

	/**
	 * Helper method to get settings by ID
	 *
	 * @param int $settings_id SettingsModel ID
	 *
	 * @return Settings_Model|null SettingsModel object or null if not found
	 */
	public function get_settings_by_id( int $settings_id ) {
		global $wpdb;

		if ( empty( $settings_id ) ) {
			return null;
		}

		// Try to get from cache first - check for an already created model object
		$cached_settings = wp_cache_get( 'settings_model_' . $settings_id, $this->cache_group );
		if ( false !== $cached_settings && $cached_settings instanceof Settings_Model ) {
			return $cached_settings;
		}

		// Get raw data from the database or cache
		$settings_data = wp_cache_get( $settings_id, $this->cache_group );
		if ( false === $settings_data ) {
			$settings_data = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$this->table_name} WHERE id = %d LIMIT 1",
					$settings_id
				)
			);

			if ( $settings_data ) {
				wp_cache_set( $settings_id, $settings_data, $this->cache_group );
			} else {
				return null;
			}
		}

		// Create and populate a SettingsModel object
		$settings = new Settings_Model();
		$settings->set_id( $settings_data->id );
		$settings->set_props(
			array(
				'name'          => $settings_data->name,
				'value'         => $this->prepare_value_from_db( $settings_data->value ),
				'default'       => $this->prepare_value_from_db( $settings_data->default ),
				'date_created'  => $this->string_to_timestamp( $settings_data->date_created ),
				'date_modified' => $this->string_to_timestamp( $settings_data->date_modified ),
			)
		);
		$settings->set_object_read( true );

		// Cache the model object
		wp_cache_set( 'settings_model_' . $settings_id, $settings, $this->cache_group );
		// Also cache by name for faster lookups
		wp_cache_set( 'settings_model_name_' . md5( $settings_data->name ), $settings, $this->cache_group );

		/**
		 * Filter the settings object retrieved by ID
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings      SettingsModel object
		 * @param object              $settings_data Raw settings data
		 * @param int                 $settings_id   SettingsModel ID
		 * @param Settings_Data_Store $this          Data store instance
		 */
		return apply_filters( 'WPPluginStarter_settings_get_by_id', $settings, $settings_data, $settings_id, $this );
	}

	/**
	 * Helper method to get settings by name
	 *
	 * @param string $name SettingsModel name
	 *
	 * @return Settings_Model|null SettingsModel object or null if not found
	 */
	public function get_settings_by_name( string $name ): ?Settings_Model {
		global $wpdb;

		if ( empty( $name ) ) {
			return null;
		}

		// Sanitize the name to ensure consistent caching
		$name = sanitize_key( $name );

		// Try to get from cache first - check for an already created model object
		$name_cache_key  = 'settings_model_name_' . md5( $name );
		$cached_settings = wp_cache_get( $name_cache_key, $this->cache_group );
		if ( $cached_settings instanceof Settings_Model ) {
			return $cached_settings;
		}

		// Get raw data from database or cache
		$data_cache_key = 'settings_' . md5( $name );
		$settings_data  = wp_cache_get( $data_cache_key, $this->cache_group );

		if ( false === $settings_data ) {
			$settings_data = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$this->table_name} WHERE name = %s LIMIT 1",
					$name
				)
			);

			if ( $settings_data ) {
				wp_cache_set( $data_cache_key, $settings_data, $this->cache_group );
				wp_cache_set( $settings_data->id, $settings_data, $this->cache_group );
			} else {
				return null;
			}
		}

		// Create and populate a SettingsModel object
		$settings = new Settings_Model();
		$settings->set_id( $settings_data->id );
		$settings->set_props(
			array(
				'name'          => $settings_data->name,
				'value'         => $this->prepare_value_from_db( $settings_data->value ),
				'default'       => $this->prepare_value_from_db( $settings_data->default ),
				'date_created'  => $this->string_to_timestamp( $settings_data->date_created ),
				'date_modified' => $this->string_to_timestamp( $settings_data->date_modified ),
			)
		);
		$settings->set_object_read( true );

		// Cache the model object by name and ID for faster lookups
		wp_cache_set( $name_cache_key, $settings, $this->cache_group );
		wp_cache_set( 'settings_model_' . $settings_data->id, $settings, $this->cache_group );

		/**
		 * Filter the settings object retrieved by name
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings      SettingsModel object
		 * @param object              $settings_data Raw settings data
		 * @param string              $name          SettingsModel name
		 * @param Settings_Data_Store $this          Data store instance
		 */
		return apply_filters( 'WPPluginStarter_settings_get_by_name', $settings, $settings_data, $name, $this );
	}

	/**
	 * Clear caches for settings
	 *
	 * @param Settings_Model $settings SettingsModel object
	 *
	 * @return void
	 */
	public function clear_caches( &$settings ): void {
		parent::clear_caches( $settings );

		$name = $settings->get_name();
		$id   = $settings->get_id();

		wp_cache_delete( 'settings_' . md5( $name ), $this->cache_group );
		wp_cache_delete( 'settings_model_name_' . md5( $name ), $this->cache_group );

		if ( $id ) {
			wp_cache_delete( 'settings_model_' . $id, $this->cache_group );
		}

		wp_cache_delete( 'settings_all', $this->cache_group );

		/**
		 * Action that fires after clearing caches for a settings object
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings SettingsModel object
		 * @param Settings_Data_Store $this     Data store instance
		 */
		do_action( 'WPPluginStarter_settings_clear_caches', $settings, $this );
	}

	/**
	 * Get all settings
	 *
	 * @param array $args Query arguments
	 *
	 * @return array<Settings_Model>
	 */
	public function get_all( array $args = array() ): array {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'limit'   => -1,
				'offset'  => 0,
				'in'      => array(), // Array of setting names to filter by
			)
		);

		/**
		 * Filter the arguments for get_all
		 *
		 * @since 1.0.0
		 *
		 * @param array               $args Query arguments
		 * @param Settings_Data_Store $this Data store instance
		 */
		$args = apply_filters( 'WPPluginStarter_settings_get_all_args', $args, $this );

		$cache_key     = 'settings_all_' . md5( wp_json_encode( $args ) );
		$settings_data = wp_cache_get( $cache_key, $this->cache_group );

		if ( false === $settings_data ) {
			$query  = "SELECT * FROM {$this->table_name}";
			$where  = array();
			$values = array();

			// Filter by specific setting names if provided
			if ( ! empty( $args['in'] ) && is_array( $args['in'] ) ) {
				$placeholders = array();
				foreach ( $args['in'] as $name ) {
					$placeholders[] = '%s';
					$values[]       = $name;
				}
				$where[] = 'name IN (' . implode( ', ', $placeholders ) . ')';
			}

			// Add WHERE clause if conditions exist
			if ( ! empty( $where ) ) {
				$query .= ' WHERE ' . implode( ' AND ', $where );
			}

			// Add ORDER BY clause
			$orderby = sanitize_sql_orderby( $args['orderby'] . ' ' . $args['order'] );
			$query  .= " ORDER BY {$orderby}";

			// Add LIMIT clause if needed
			if ( -1 !== (int) $args['limit'] ) {
				$query .= $wpdb->prepare( ' LIMIT %d, %d', $args['offset'], $args['limit'] );
			}

			// Prepare the query if we have values to inject
			if ( ! empty( $values ) ) {
				$query = $wpdb->prepare( $query, $values );
			}

			$settings_data = $wpdb->get_results( $query );

			wp_cache_set( $cache_key, $settings_data, $this->cache_group );
		}

		$settings_objects = array_map(
			static function ( $data ) {
				return new Settings_Model( (array) $data );
			},
			$settings_data
		);

		/**
		 * Filter the settings objects retrieved
		 *
		 * @since 1.0.0
		 *
		 * @param array<Settings_Model> $settings_objects SettingsModel objects
		 * @param array                 $args             Query arguments
		 * @param array                 $settings_data    SettingsModel data from the database
		 * @param Settings_Data_Store   $this             Data store instance
		 */
		return apply_filters( 'WPPluginStarter_settings_get_all', $settings_objects, $args, $settings_data, $this );
	}

	/**
	 * Delete settings by name
	 *
	 * @param string $name SettingsModel name
	 *
	 * @return void
	 */
	public function delete_by_name( string $name ): void {
		$settings = $this->get_settings_by_name( $name );
		if ( $settings ) {
			$this->delete( $settings, array( 'force_delete' => true ) );
		}
	}

	/**
	 * Delete settings by names
	 *
	 * @param array<string> $names Array of settings names
	 *
	 * @return void
	 */
	public function delete_by_names( array $names ): void {
		foreach ( $names as $name ) {
			$this->delete_by_name( $name );
		}
	}

	/**
	 * Get the table name for this data store
	 *
	 * @param string $name
	 *
	 * @return void Table name
	 */
	public function reset_by_name( string $name ): void {
		$settings = $this->get_settings_by_name( $name );
		if ( $settings ) {
			$settings->set_value( $settings->get_default() );
			// $settings->set_value( array_map( '__return_false', $settings->get_value()) );
			$settings->save();
		}
	}

	/**
	 * Reset settings by names
	 *
	 * @param array<string> $names Array of settings names
	 *
	 * @return void
	 */
	public function reset_by_names( array $names ): void {
		foreach ( $names as $name ) {
			$this->reset_by_name( $name );
		}
	}

	/**
	 * Get table schema for creating tables
	 *
	 * @param string $collate Collation to use
	 *
	 * @return string SQL for table creation
	 */
	protected function get_table_schema( string $collate ): string {
		global $wpdb;

		/**
		 * Filter the table schema SQL
		 *
		 * @since 1.0.0
		 *
		 * @param string              $sql     SQL schema
		 * @param string              $collate Collation
		 * @param Settings_Data_Store $this    Data store instance
		 */
		return apply_filters(
			'WPPluginStarter_settings_table_schema',
			"
			CREATE TABLE {$this->table_name} (
			  `id` bigint(20) unsigned NOT NULL auto_increment,
			  `name` varchar(200) NOT NULL,
			  `value` longtext NOT NULL,
			  `default` longtext NULL,
			  `date_created` datetime NOT NULL default CURRENT_TIMESTAMP,
			  `date_modified` datetime NOT NULL default CURRENT_TIMESTAMP,
			  PRIMARY KEY  (id),
			  UNIQUE KEY name (name)
			) $collate;

			CREATE TABLE {$wpdb->prefix}WPPluginStarter_settingsmeta (
			  `meta_id` bigint(20) unsigned NOT NULL auto_increment,
			  `settings_id` bigint(20) unsigned NOT NULL,
			  `meta_key` varchar(255) default NULL,
			  `meta_value` longtext,
			  PRIMARY KEY  (meta_id),
			  KEY settings_id (settings_id),
			  KEY meta_key (meta_key(191))
			) $collate;
			",
			$collate,
			$this
		);
	}

	/**
	 * Get required columns for table verification
	 *
	 * @return array<string> Array of required columns
	 */
	public function get_required_columns(): array {
		return array(
			'id',
			'name',
			'value',
			'default',
			'date_created',
			'date_modified',
		);
	}

	/**
	 * Create or get a settings object
	 *
	 * @param string $name         SettingsModel name
	 * @param mixed  $default_value Default value
	 *
	 * @return Settings_Model
	 */
	public function create_or_get( string $name, $default_value = '' ): Settings_Model {
		$settings = new Settings_Model();
		$settings->set_name( $name );
		$settings->get_data_store()->read( $settings );

		if ( ! $settings->get_id() ) {
			$settings->set_default( $default_value );
			$settings->set_value( $default_value );
			$settings->save();
		}

		/**
		 * Filter the settings object
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model      $settings      SettingsModel object
		 * @param string              $name          SettingsModel name
		 * @param mixed               $default_value Default value
		 * @param Settings_Data_Store $this          Data store instance
		 */
		return apply_filters( 'WPPluginStarter_settings_create_or_get', $settings, $name, $default_value, $this );
	}

	/**
	 * Update database version
	 *
	 * @return void
	 */
	protected function update_db_version(): void {
		// Try to get existing setting
		$settings = $this->get_settings_by_name( 'system' );
		if ( null === $settings ) {
			$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
			$settings->set_name( 'system' );
		} else {
			$new_values = $settings->get_value() ?? array();
		}

		// Add the new value to the existing value
		$new_values[ self::DB_VERSION_KEY ] = WPPluginStarter_VERSION;

		$settings->set_value( $new_values );
		$settings->save();

		parent::update_db_version();
	}
}
