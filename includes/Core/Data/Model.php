<?php
namespace WPPluginStarter\Core\Data;

use WC_Data;
use WC_Data_Store_WP;

/**
 * Base Model class for data entities, extends WooCommerce WC_Data.
 */
abstract class Model extends WC_Data {
	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'model';

	/**
	 * Cache group for this object type.
	 *
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-models';

	/**
	 * Sets modified status to true.
	 *
	 * Updates the modified time to the current timestamp.
	 *
	 * @return void
	 */
	protected function set_modified(): void {
		if ( array_key_exists( 'date_modified', $this->data ) ) {
			$this->set_date_modified( time() );
		}
	}

	/**
	 * Get object property.
	 *
	 * @param string $key Object property key.
	 *
	 * @return mixed
	 */
	public function __get( string $key ) {
		// Check for getter method first
		$getter = "get_{$key}";
		if ( is_callable( array( $this, $getter ) ) ) {
			return $this->$getter();
		}

		// Fallback to property access
		return $this->get_prop( $key );
	}

	/**
	 * Set object property.
	 *
	 * @param string $key   Object property key.
	 * @param mixed  $value Object property value.
	 *
	 * @return void
	 */
	public function __set( string $key, $value ): void {
		// Check for setter method first
		$setter = "set_{$key}";
		if ( is_callable( array( $this, $setter ) ) ) {
			$this->$setter( $value );
		} else {
			$this->set_prop( $key, $value );
		}
	}

	/**
	 * Check if property exists.
	 *
	 * @param string $key Object property key.
	 *
	 * @return bool
	 */
	public function __isset( string $key ): bool {
		return array_key_exists( $key, $this->data );
	}

	/**
	 * Save should create or update based on object existence.
	 *
	 * @return int
	 */
	public function save(): int {
		if ( ! $this->data_store ) {
			return $this->get_id();
		}

		/**
		 * Filter to allow objects to be saved or not
		 *
		 * @since 1.0.0
		 *
		 * @param bool   $should_save Whether the object should be saved
		 * @param Model  $model       The model being saved
		 */
		$should_save = apply_filters( "wp_plugin_starter_should_save_{$this->object_type}", true, $this );

		if ( ! $should_save ) {
			return $this->get_id();
		}

		/**
		 * Trigger action before saving to the DB. Allows you to adjust object props before save.
		 *
		 * @param Model            $this       The object being saved.
		 * @param WC_Data_Store_WP $data_store The data store persisting the data.
		 */
		do_action( 'wp_plugin_starter_before_' . $this->object_type . '_object_save', $this, $this->data_store );

		$id = $this->get_id();
		if ( $id ) {
			$this->data_store->update( $this );
		} else {
			$this->data_store->create( $this );
		}

		/**
		 * Trigger action after saving to the DB.
		 *
		 * @param Model            $this       The object being saved.
		 * @param WC_Data_Store_WP $data_store The data store persisting the data.
		 */
		do_action( 'wp_plugin_starter_after_' . $this->object_type . '_object_save', $this, $this->data_store );

		return $this->get_id();
	}

	/**
	 * Delete an object, set the ID to 0, and return result.
	 *
	 * @param  bool $force_delete Should the date be deleted permanently.
	 *
	 * @return bool result
	 */
	public function delete( $force_delete = false ): bool {
		/**
		 * Filters whether an object deletion should take place.
		 *
		 * @param mixed $check        Whether to go ahead with deletion.
		 * @param Model $this         The data object being deleted.
		 * @param bool  $force_delete Whether to bypass the trash.
		 */
		$check = apply_filters( "wp_plugin_starter_pre_delete_{$this->object_type}", null, $this, $force_delete );
		if ( null !== $check ) {
			return $check;
		}

		/**
		 * Action before deleting an object
		 *
		 * @param int   $id           The object ID
		 * @param Model $this         The object being deleted
		 * @param bool  $force_delete Whether to permanently delete or not
		 */
		do_action( "wp_plugin_starter_before_delete_{$this->object_type}", $this->get_id(), $this, $force_delete );

		if ( $this->data_store ) {
			$this->data_store->delete( $this, array( 'force_delete' => $force_delete ) );
			$this->set_id( 0 );

			/**
			 * Action after deleting an object
			 *
			 * @param int   $id           The object ID
			 * @param Model $this         The object being deleted
			 * @param bool  $force_delete Whether the object was permanently deleted
			 */
			do_action( "wp_plugin_starter_after_delete_{$this->object_type}", $this->get_id(), $this, $force_delete );

			return true;
		}

		return false;
	}

	/**
	 * Get All Meta Data.
	 *
	 * @since 2.6.0
	 * @return array of objects.
	 */
	public function get_meta_data() {
		return apply_filters( 'wp_plugin_starter_' . $this->object_type . '_get_meta_data', array() );
	}
}
