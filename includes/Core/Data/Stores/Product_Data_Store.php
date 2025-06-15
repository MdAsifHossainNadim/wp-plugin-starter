<?php

namespace WPPluginStarter\Core\Data\Stores;

use WPPluginStarter\Core\Data\Data_Store;
use WPPluginStarter\Core\Data\Models\Product_Model;
use WC_Data;

/**
 * Product Data Store
 *
 * This class handles the storage and retrieval of product model data.
 * It demonstrates how to implement a custom data store for extending
 * product data beyond the standard WooCommerce product data.
 *
 * @since 3.0.0
 * @package WPPluginStarter\Core\Data\Stores
 */
class Product_Data_Store extends Data_Store {

	/**
	 * Primary key column name
	 *
	 * @var string
	 */
	protected string $primary_key = 'id';

	/**
	 * Meta type for product data
	 *
	 * @var string
	 */
	protected $meta_type = 'product';

	/**
	 * Object type this store handles
	 *
	 * @var string
	 */
	protected $object_type = 'product';

	/**
	 * Cache group for product objects
	 *
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-product-models';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		global $wpdb;

		$this->table_name = $wpdb->prefix . 'WPPluginStarter_product_data';
	}

	/**
	 * Method to create a new record of a WC_Data based object.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 *
	 * @return int
	 */
	public function create( &$product ) {
		global $wpdb;

		$data   = $this->prepare_data_for_db( $product );
		$format = $this->get_db_format();

		$result = $wpdb->insert(
			$this->table_name,
			$data,
			$format
		);

		if ( $result && $wpdb->insert_id ) {
			$product->set_id( $wpdb->insert_id );
			$product->set_object_read( true );

			$this->clear_cache( $product );
		}

		/**
		 * Action triggered after creating a product model in the database.
		 *
		 * @since 3.0.0
		 *
		 * @param int           $id      The product model ID.
		 * @param Product_Model $product The product model object.
		 * @param array         $data    The data that was inserted into the database.
		 */
		do_action( 'WPPluginStarter_product_model_created', $product->get_id(), $product, $data );

		return $product->get_id();
	}

	/**
	 * Method to read a record from the database.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 *
	 * @return void
	 */
	public function read( &$product ) {
		global $wpdb;

		$id = $product->get_id();

		// Return early if no ID
		if ( ! $id ) {
			$product->set_object_read( true );
			return;
		}

		// Try to get from cache
		$data = wp_cache_get( $id, $this->cache_group );

		if ( false === $data ) {
			// Not in cache, fetch from database
			$data = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = %d LIMIT 1",
					$id
				),
				ARRAY_A
			);

			// Cache the result
			if ( $data ) {
				wp_cache_set( $id, $data, $this->cache_group );
			}
		}

		if ( ! $data ) {
			// No data found, but we'll still mark as read to prevent infinite loops
			$product->set_object_read( true );
			return;
		}

		// Setup the product data
		$this->set_product_data( $product, $data );

		// Mark as read
		$product->set_object_read( true );

		/**
		 * Action triggered after reading a product model from the database.
		 *
		 * @since 3.0.0
		 *
		 * @param Product_Model $product The product model object.
		 * @param array         $data    The data read from the database.
		 */
		do_action( 'WPPluginStarter_product_model_read', $product, $data );
	}

	/**
	 * Method to update a record in the database.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 *
	 * @return void
	 */
	public function update( &$product ) {
		global $wpdb;

		$data   = $this->prepare_data_for_db( $product );
		$format = $this->get_db_format();

		$wpdb->update(
			$this->table_name,
			$data,
			array(
				$this->primary_key => $product->get_id(),
			),
			$format,
			array( '%d' )
		);

		$this->clear_cache( $product );

		/**
		 * Action triggered after updating a product model in the database.
		 *
		 * @since 3.0.0
		 *
		 * @param int           $id      The product model ID.
		 * @param Product_Model $product The product model object.
		 * @param array         $data    The data that was updated in the database.
		 */
		do_action( 'WPPluginStarter_product_model_updated', $product->get_id(), $product, $data );
	}

	/**
	 * Method to delete a record from the database.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 * @param array         $args    Optional arguments for deleting.
	 *
	 * @return bool
	 */
	public function delete( &$product, $args = array() ) {
		global $wpdb;

		$id = $product->get_id();

		if ( ! $id ) {
			return false;
		}

		/**
		 * Action triggered before deleting a product model from the database.
		 *
		 * @since 3.0.0
		 *
		 * @param int           $id      The product model ID.
		 * @param Product_Model $product The product model object.
		 */
		do_action( 'WPPluginStarter_before_product_model_delete', $id, $product );

		$result = $wpdb->delete(
			$this->table_name,
			array(
				$this->primary_key => $id,
			),
			array( '%d' )
		);

		$this->clear_cache( $product );

		/**
		 * Action triggered after deleting a product model from the database.
		 *
		 * @since 3.0.0
		 *
		 * @param int  $id     The product model ID.
		 * @param bool $result The result of the delete operation.
		 */
		do_action( 'WPPluginStarter_after_product_model_delete', $id, (bool) $result );

		return (bool) $result;
	}

	/**
	 * Find product model by product ID.
	 *
	 * @since 3.0.0
	 *
	 * @param int $product_id WooCommerce product ID.
	 *
	 * @return int|false Product model ID, or false if not found.
	 */
	public function find_by_product_id( int $product_id ) {
		global $wpdb;

		$id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT {$this->primary_key} FROM {$this->table_name} WHERE product_id = %d LIMIT 1",
				$product_id
			)
		);

		return $id ? (int) $id : false;
	}

	/**
	 * Method to save a record to the database.
	 *
	 * @since 3.0.0
	 *
	 * @param WC_Data $product Product model object.
	 *
	 * @return int
	 */
	public function save( WC_Data $product ): int {
		$id = $product->get_id();

		if ( $id ) {
			$this->update( $product );
		} else {
			$this->create( $product );
		}

		return $product->get_id();
	}

	/**
	 * Get all product models with optional filtering.
	 *
	 * @since 3.0.0
	 *
	 * @param array $args Query arguments.
	 *
	 * @return array Array of found product models.
	 */
	public function get_all( array $args = array() ): array {
		global $wpdb;

		// Default arguments
		$defaults = array(
			'limit'      => -1,
			'offset'     => 0,
			'orderby'    => 'id',
			'order'      => 'DESC',
			'visibility' => null,
			'status'     => null,
		);

		$args  = wp_parse_args( $args, $defaults );
		$limit = $args['limit'] > 0 ? $args['limit'] : 9999;

		// Build query
		$query = "SELECT * FROM {$this->table_name} WHERE 1=1";

		// Add visibility filter if specified
		if ( null !== $args['visibility'] ) {
			$visibility = $args['visibility'] ? 1 : 0;
			$query     .= $wpdb->prepare( ' AND visibility = %d', $visibility );
		}

		// Add status filter if specified
		if ( null !== $args['status'] ) {
			$query .= $wpdb->prepare( ' AND marketplace_status = %s', $args['status'] );
		}

		// Add ordering
		$query .= $wpdb->prepare(
			" ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d",
			$limit,
			$args['offset']
		);

		// Get results
		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( ! $results ) {
			return array();
		}

		$products = array();
		foreach ( $results as $data ) {
			$product_id = $data['id'] ?? 0;
			if ( $product_id ) {
				$product = new Product_Model( $product_id );
				$this->set_product_data( $product, $data );
				$products[] = $product;
			}
		}

		/**
		 * Filter the product models retrieved from the database.
		 *
		 * @since 3.0.0
		 *
		 * @param array $products Array of product models.
		 * @param array $args     Query arguments.
		 * @param array $results  Raw data from the database.
		 *
		 * @return array
		 */
		return apply_filters( 'WPPluginStarter_get_product_models', $products, $args, $results );
	}

	/**
	 * Get a count of product models based on given criteria.
	 *
	 * @since 3.0.0
	 *
	 * @param array $args Query arguments.
	 *
	 * @return int
	 */
	public function get_count( array $args = array() ): int {
		global $wpdb;

		// Default arguments
		$defaults = array(
			'visibility' => null,
			'status'     => null,
		);

		$args = wp_parse_args( $args, $defaults );

		// Build query
		$query = "SELECT COUNT(*) FROM {$this->table_name} WHERE 1=1";

		// Add visibility filter if specified
		if ( null !== $args['visibility'] ) {
			$visibility = $args['visibility'] ? 1 : 0;
			$query     .= $wpdb->prepare( ' AND visibility = %d', $visibility );
		}

		// Add status filter if specified
		if ( null !== $args['status'] ) {
			$query .= $wpdb->prepare( ' AND marketplace_status = %s', $args['status'] );
		}

		// Get count
		$count = $wpdb->get_var( $query );

		return (int) $count;
	}

	/**
	 * Set product data from a database record.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 * @param array         $data    Data from the database.
	 *
	 * @return void
	 */
	protected function set_product_data( &$product, array $data ): void {
		// Set ID first
		if ( isset( $data['id'] ) ) {
			$product->set_id( $data['id'] );
		}

		// Set the product ID
		if ( isset( $data['product_id'] ) ) {
			$product->set_product_id( $data['product_id'] );
		}

		// Set custom attributes
		if ( isset( $data['custom_attributes'] ) ) {
			// Handle serialized data
			$custom_attributes = maybe_unserialize( $data['custom_attributes'] );
			$product->set_custom_attributes( is_array( $custom_attributes ) ? $custom_attributes : array() );
		}

		// Set additional options
		if ( isset( $data['additional_options'] ) ) {
			// Handle serialized data
			$additional_options = maybe_unserialize( $data['additional_options'] );
			$product->set_additional_options( is_array( $additional_options ) ? $additional_options : array() );
		}

		// Set visibility
		if ( isset( $data['visibility'] ) ) {
			$product->set_visibility( (bool) $data['visibility'] );
		}

		// Set featured priority
		if ( isset( $data['featured_priority'] ) ) {
			$product->set_featured_priority( $data['featured_priority'] );
		}

		// Set marketplace status
		if ( isset( $data['marketplace_status'] ) ) {
			$product->set_marketplace_status( $data['marketplace_status'] );
		}

		// Set date created
		if ( isset( $data['date_created'] ) && $data['date_created'] ) {
			$product->set_date_created( $data['date_created'] );
		}

		// Set date modified
		if ( isset( $data['date_modified'] ) && $data['date_modified'] ) {
			$product->set_date_modified( $data['date_modified'] );
		}

		/**
		 * Action triggered after setting product data from a database record.
		 *
		 * @since 3.0.0
		 *
		 * @param Product_Model $product The product model object.
		 * @param array         $data    The raw data from the database.
		 */
		do_action( 'WPPluginStarter_set_product_data', $product, $data );
	}

	/**
	 * Prepare product data for database storage.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 *
	 * @return array
	 */
	protected function prepare_data_for_db( &$product ): array {
		$data = array(
			'product_id'         => $product->get_product_id(),
			'custom_attributes'  => maybe_serialize( $product->get_custom_attributes() ),
			'additional_options' => maybe_serialize( $product->get_additional_options() ),
			'visibility'         => (int) $product->get_visibility(),
			'featured_priority'  => $product->get_featured_priority(),
			'marketplace_status' => $product->get_marketplace_status(),
		);

		// Add date created if set
		$date_created = $product->get_date_created( 'edit' );
		if ( $date_created ) {
			$data['date_created'] = gmdate( 'Y-m-d H:i:s', $date_created->getTimestamp() );
		}

		// Add date modified if set
		$date_modified = $product->get_date_modified( 'edit' );
		if ( $date_modified ) {
			$data['date_modified'] = gmdate( 'Y-m-d H:i:s', $date_modified->getTimestamp() );
		}

		/**
		 * Filter product data before saving to the database.
		 *
		 * @since 3.0.0
		 *
		 * @param array         $data    The data to be saved to the database.
		 * @param Product_Model $product The product model object.
		 *
		 * @return array
		 */
		return apply_filters( 'WPPluginStarter_product_data_for_db', $data, $product );
	}

	/**
	 * Get database column formats.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	protected function get_db_format(): array {
		return array(
			'%d', // product_id
			'%s', // custom_attributes
			'%s', // additional_options
			'%d', // visibility
			'%d', // featured_priority
			'%s', // marketplace_status
			'%s', // date_created
			'%s', // date_modified
		);
	}

	/**
	 * Clear cache for product model.
	 *
	 * @since 3.0.0
	 *
	 * @param Product_Model $product Product model object.
	 *
	 * @return void
	 */
	protected function clear_cache( &$product ): void {
		wp_cache_delete( $product->get_id(), $this->cache_group );

		/**
		 * Action triggered when product model cache is cleared.
		 *
		 * @since 3.0.0
		 *
		 * @param int           $id      The product model ID.
		 * @param Product_Model $product The product model object.
		 */
		do_action( 'WPPluginStarter_product_model_cache_cleared', $product->get_id(), $product );
	}

	/**
	 * Get table schema for creating tables
	 *
	 * @since 3.0.0
	 *
	 * @param string $collate Collation to use.
	 *
	 * @return string SQL for table creation.
	 */
	protected function get_table_schema( string $collate ): string {
		/**
		 * Filter the table schema SQL for product data
		 *
		 * @since 3.0.0
		 *
		 * @param string             $sql     SQL schema
		 * @param string             $collate Collation
		 * @param Product_Data_Store $this    Data store instance
		 */
		return apply_filters(
			'WPPluginStarter_product_table_schema',
			"CREATE TABLE {$this->table_name} (
				id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				product_id bigint(20) unsigned NOT NULL,
				custom_attributes longtext,
				additional_options longtext,
				visibility tinyint(1) DEFAULT 1,
				featured_priority int(11) DEFAULT 0,
				marketplace_status varchar(20) DEFAULT 'approved',
				date_created datetime DEFAULT NULL,
				date_modified datetime DEFAULT NULL,
				PRIMARY KEY (id),
				KEY product_id (product_id),
				KEY marketplace_status (marketplace_status),
				KEY visibility (visibility),
				KEY featured_priority (featured_priority)
			) {$collate};",
			$collate,
			$this
		);
	}

	/**
	 * Get required columns for table verification
	 *
	 * @since 3.0.0
	 *
	 * @return array<string> Array of required columns
	 */
	public function get_required_columns(): array {
		return array(
			'id',
			'product_id',
			'custom_attributes',
			'additional_options',
			'visibility',
			'featured_priority',
			'marketplace_status',
			'date_created',
			'date_modified',
		);
	}
}
