<?php

namespace WPPluginStarter\Core\Data;

use WC_Data;
use WC_Data_Store_WP;
use WC_Object_Data_Store_Interface;

/**
 * Data Store Base Class
 *
 * Handles storage and retrieval of data
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Data\Stores
 */
abstract class Data_Store extends WC_Data_Store_WP implements WC_Object_Data_Store_Interface {
	/**
	 * Database version setting key
	 *
	 * @var string
	 */
	protected const DB_VERSION_KEY = 'db_version';

	/**
	 * Table name for the data
	 *
	 * @var string
	 */
	protected string $table_name;

	/**
	 * Meta type for data
	 *
	 * @var string
	 */
	protected $meta_type = 'data';

	/**
	 * Object type this store handles
	 *
	 * @var string
	 */
	protected $object_type = 'data';

	/**
	 * Data stored in meta keys, but not considered "meta" for an object.
	 *
	 * @var array
	 */
	protected $internal_meta_keys = array();

	/**
	 * Cache group for this object type
	 *
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-data';

	/**
	 * Constructor
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name               = $wpdb->prefix . 'WPPluginStarter_' . $this->meta_type;
		$this->object_id_field_for_meta = $this->meta_type . '_id';
	}

	/**
	 * Initialize the data store
	 *
	 * Checks if database tables need to be created or updated
	 * and runs the necessary operations.
	 *
	 * @return void
	 */
	public function initialize(): void {
		if ( $this->needs_db_update() ) {
			$this->create_tables();
			$this->update_db_version();
		}
	}

	/**
	 * Check if database update is needed
	 *
	 * @return bool
	 */
	protected function needs_db_update(): bool {
		// Get DB version - default to '0.0.0' if not set
		$db_version = $this->get_setting_value( self::DB_VERSION_KEY, '0.0.0' );

		// If no DB version is set, tables need to be created
		if ( empty( $db_version ) ) {
			return true;
		}

		// If DB version is different from plugin version, tables need to be updated
		$needs_update = version_compare( $db_version, WPPluginStarter_VERSION, '<' );

		/**
		 * Filter whether database needs an update
		 *
		 * @since 1.0.0
		 *
		 * @param bool       $needs_update Whether the database needs an update
		 * @param string     $db_version   Current database version
		 * @param Data_Store $this         The current data store instance
		 */
		return apply_filters( 'WPPluginStarter_datastore_needs_db_update', $needs_update, $db_version, $this );
	}

	/**
	 * Update database version
	 *
	 * @return void
	 */
	protected function update_db_version(): void {
		/**
		 * Action that fires after updating the database version
		 *
		 * @since 1.0.0
		 *
		 * @param string     $version The new database version
		 * @param Data_Store $this    The current data store instance
		 */
		do_action( 'WPPluginStarter_datastore_update_db_version', WPPluginStarter_VERSION, $this );
	}

	/**
	 * Prepare value for database storage
	 *
	 * @param mixed $value Value to prepare.
	 *
	 * @return string
	 */
	protected function prepare_value_for_db( $value ): string {
		/**
		 * Filter the value before storing it in the database.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed      $value The value being prepared.
		 * @param Data_Store $this  The current data store instance.
		 */
		$value = apply_filters( 'WPPluginStarter_prepare_value_for_db', $value, $this );

		// Handle arrays and objects by serializing them
		if ( is_array( $value ) || is_object( $value ) ) {
			return maybe_serialize( $value );
		}

		return (string) $value;
	}

	/**
	 * Prepare value from database for use
	 *
	 * @param string $value Value from database.
	 *
	 * @return mixed
	 */
	protected function prepare_value_from_db( string $value ) {
		// Try to unserialize if it's serialized
		if ( is_serialized( $value ) ) {
			$prepared_value = maybe_unserialize( $value );
		} elseif ( $this->is_json( $value ) ) {
			$decoded        = json_decode( $value, true );
			$prepared_value = json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
		} else {
			$prepared_value = $value;
		}

		/**
		 * Filter the value after retrieving it from the database.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed      $prepared_value The prepared value.
		 * @param string     $raw_value      The raw value from the database.
		 * @param Data_Store $this           The current data store instance.
		 */
		return apply_filters( 'WPPluginStarter_prepare_value_from_db', $prepared_value, $value, $this );
	}

	/**
	 * Check if a string is JSON
	 *
	 * @param string $string_data String to check.
	 *
	 * @return bool
	 */
	protected function is_json( string $string_data ): bool {
		if ( empty( $string_data ) ) {
			return false;
		}

		$first_char = $string_data[0];
		$last_char  = substr( $string_data, -1 );

		// Quick check for JSON-like structure
		if ( ( '{' === $first_char && '}' === $last_char ) ||
			( '[' === $first_char && ']' === $last_char ) ) {
			try {
				json_decode( $string_data, false, 512, JSON_THROW_ON_ERROR );
				return true;
			} catch ( \JsonException $e ) {
				return false;
			}
		}

		return false;
	}

	/**
	 * Clear caches for an object
	 *
	 * @param WC_Data $object Object to clear caches for.
	 *
	 * @return void
	 */
	public function clear_caches( &$object ): void {
		$id = $object->get_id();
		if ( $id ) {
			wp_cache_delete( $id, $this->cache_group );

			/**
			 * Action that fires after clearing caches for an object.
			 *
			 * @since 1.0.0
			 *
			 * @param int        $id     The object ID.
			 * @param WC_Data    $object The object.
			 * @param Data_Store $this   The current data store instance.
			 */
			do_action( 'WPPluginStarter_clear_caches', $id, $object, $this );
		}
	}

	/**
	 * Helper method to process meta data
	 *
	 * @param array $raw_meta_data Raw meta data from DB.
	 *
	 * @return array
	 */
	protected function process_meta_data( array $raw_meta_data ): array {
		$meta_data = array();

		foreach ( $raw_meta_data as $meta ) {
			$meta_data[] = array(
				'id'    => $meta->meta_id,
				'key'   => $meta->meta_key,
				'value' => maybe_unserialize( $meta->meta_value ),
			);
		}

		/**
		 * Filter the processed meta data.
		 *
		 * @since 1.0.0
		 *
		 * @param array      $meta_data     The processed meta data.
		 * @param array      $raw_meta_data The raw meta data.
		 * @param Data_Store $this          The current data store instance.
		 */
		return apply_filters( 'WPPluginStarter_process_meta_data', $meta_data, $raw_meta_data, $this );
	}

	/**
	 * Convert string date to timestamp
	 *
	 * @param string $time_string Time string.
	 *
	 * @return int
	 */
	protected function string_to_timestamp( $time_string ): int {
		return wc_string_to_timestamp( $time_string );
	}

	/**
	 * Create database tables
	 *
	 * @return void
	 */
	public function create_tables(): void {
		global $wpdb;

		$wpdb->hide_errors();

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		$tables = $this->get_table_schema( $collate );

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		/**
		 * Action before creating tables
		 *
		 * @since 1.0.0
		 *
		 * @param string     $tables Table schema SQL.
		 * @param Data_Store $this   The current data store instance.
		 */
		do_action( 'WPPluginStarter_before_create_tables', $tables, $this );

		// Execute dbDelta
		dbDelta( $tables );

		/**
		 * Action after creating tables
		 *
		 * @since 1.0.0
		 *
		 * @param string     $tables Table schema SQL.
		 * @param Data_Store $this   The current data store instance.
		 */
		do_action( 'WPPluginStarter_after_create_tables', $tables, $this );

		// Verify tables exist and log results
		$this->verify_tables();
	}

	/**
	 * Get table schema for creating tables
	 *
	 * @param string $collate Collation to use.
	 *
	 * @return string SQL for table creation.
	 */
	abstract protected function get_table_schema( string $collate ): string;

	/**
	 * Verify tables were created properly
	 *
	 * @return void
	 */
	protected function verify_tables(): void {
		global $wpdb;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$table_exists = $wpdb->get_var(
				$wpdb->prepare(
					'SHOW TABLES LIKE %s',
					$this->table_name
				)
			);

			$meta_table_name   = $wpdb->prefix . $this->meta_type . 'meta';
			$meta_table_exists = $wpdb->get_var(
				$wpdb->prepare(
					'SHOW TABLES LIKE %s',
					$meta_table_name
				)
			);

			if ( function_exists( 'WPPluginStarter_logger' ) ) {
				WPPluginStarter_logger()->debug(
					'WPPluginStarter ' . ucfirst( $this->meta_type ) . ' Tables - Main table: ' . ( $table_exists ? 'Exists' : 'Missing' ) .
					', Meta table: ' . ( $meta_table_exists ? 'Exists' : 'Missing' )
				);
			}

			/**
			 * Action after verifying tables
			 *
			 * @since 1.0.0
			 *
			 * @param bool       $table_exists      Whether the main table exists.
			 * @param bool       $meta_table_exists Whether the meta table exists.
			 * @param Data_Store $this              The current data store instance.
			 */
			do_action( 'WPPluginStarter_verify_tables', $table_exists, $meta_table_exists, $this );
		}
	}

	/**
	 * Get required columns for table verification
	 *
	 * @return array Array of required columns.
	 */
	abstract public function get_required_columns(): array;

	/**
	 * Get table name
	 *
	 * @return string Table name.
	 */
	public function get_table_name(): string {
		return $this->table_name;
	}

	/**
	 * Get meta type
	 *
	 * @return string Meta type.
	 */
	public function get_meta_type(): string {
		return $this->meta_type;
	}

	/**
	 * Get object type
	 *
	 * @return string Object type.
	 */
	public function get_object_type(): string {
		return $this->object_type;
	}

	/**
	 * Get cache group
	 *
	 * @return string Cache group.
	 */
	public function get_cache_group(): string {
		return $this->cache_group;
	}

	/**
	 * Table structure is slightly different between meta types, this function will return what we need to know.
	 *
	 * @since  1.0.0
	 * @return array Array elements: table, object_id_field, meta_id_field
	 */
	protected function get_db_info() {
		global $wpdb;

		return array(
			'table'           => "{$wpdb->prefix}WPPluginStarter_{$this->object_type}meta",
			'object_id_field' => "{$this->object_type}_id",
			'meta_id_field'   => 'meta_id',
		);
	}
}
