<?php

namespace WPPluginStarter\Core\Data\Models;

use WPPluginStarter\Core\Data\Model;
use WC_Data_Store;
use WC_DateTime;
use DateTimeInterface;

/**
 * Product Model class
 *
 * This is a sample model implementation demonstrating how to create custom product data models
 * that integrate with WordPress and WooCommerce. This provides additional product data and
 * functionality beyond what the standard WC_Product offers.
 *
 * @since 3.0.0
 * @package WPPluginStarter\Core\Data\Models
 */
class Product_Model extends Model {
	/**
	 * Data array
	 *
	 * @since 3.0.0
	 * @var array
	 */
	protected $data = array(
		'product_id'         => 0,
		'custom_attributes'  => array(),
		'additional_options' => array(),
		'visibility'         => true,
		'featured_priority'  => 0,
		'marketplace_status' => 'approved',
		'date_created'       => null,
		'date_modified'      => null,
	);

	/**
	 * This is the name of this object type
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $object_type = 'product_model';

	/**
	 * Cache group for this object type
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $cache_group = 'wp-plugin-starter-products';

	/**
	 * Constructor
	 *
	 * Initializes the Product model with optional data.
	 *
	 * @since 3.0.0
	 *
	 * @param int|array $data Product ID or data to initialize the object
	 */
	public function __construct( $data = 0 ) {
		parent::__construct();

		if ( is_numeric( $data ) && $data > 0 ) {
			$this->set_id( $data );
		} elseif ( is_array( $data ) ) {
			$this->set_props( $data );
		} else {
			$this->set_object_read( true );
		}

		// If we have an ID, load the product from the data store
		if ( $this->get_id() ) {
			try {
				$this->data_store = WC_Data_Store::load( 'product-model' );

				if ( $this->data_store ) {
					$this->data_store->read( $this );
				}
			} catch ( \Exception $e ) {
				$this->data_store = null;

				// Log the error but don't halt execution
				error_log( sprintf( 'Error loading product model with ID %d: %s', $this->get_id(), $e->getMessage() ) );
			}
		} else {
			$this->data_store = WC_Data_Store::load( 'WPPluginStarter_product' );
			$this->set_object_read( true );
		}

		/**
		 * Action triggered after a product model is initialized.
		 *
		 * @since 3.0.0
		 *
		 * @param Product_Model $this      The product model instance.
		 * @param mixed         $data      The data used to initialize the model.
		 */
		do_action( 'WPPluginStarter_product_model_init', $this, $data );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get product ID.
	 *
	 * @since 3.0.0
	 * @return int
	 */
	public function get_product_id(): int {
		return $this->get_prop( 'product_id', 0 );
	}

	/**
	 * Get custom attributes.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function get_custom_attributes(): array {
		return $this->get_prop( 'custom_attributes', array() );
	}

	/**
	 * Get additional options.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function get_additional_options(): array {
		return $this->get_prop( 'additional_options', array() );
	}

	/**
	 * Get visibility.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public function get_visibility(): bool {
		return (bool) $this->get_prop( 'visibility', true );
	}

	/**
	 * Get featured priority.
	 *
	 * @since 3.0.0
	 * @return int
	 */
	public function get_featured_priority(): int {
		return (int) $this->get_prop( 'featured_priority', 0 );
	}

	/**
	 * Get marketplace status.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_marketplace_status(): string {
		return $this->get_prop( 'marketplace_status', 'approved' );
	}

	/**
	 * Get date created.
	 *
	 * @since 3.0.0
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return WC_DateTime|null
	 */
	public function get_date_created( $context = 'view' ) {
		return $this->get_prop( 'date_created', $context );
	}

	/**
	 * Get date modified.
	 *
	 * @since 3.0.0
	 * @param string $context What the value is for. Valid values are 'view' and 'edit'.
	 * @return WC_DateTime|null
	 */
	public function get_date_modified( $context = 'view' ) {
		return $this->get_prop( 'date_modified', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Set product ID.
	 *
	 * @since 3.0.0
	 * @param int $product_id Product ID.
	 * @return void
	 */
	public function set_product_id( int $product_id ): void {
		$this->set_prop( 'product_id', absint( $product_id ) );
	}

	/**
	 * Set custom attributes.
	 *
	 * @since 3.0.0
	 * @param array $attributes Custom attributes.
	 * @return void
	 */
	public function set_custom_attributes( array $attributes ): void {
		$this->set_prop( 'custom_attributes', $attributes );
	}

	/**
	 * Set additional options.
	 *
	 * @since 3.0.0
	 * @param array $options Additional options.
	 * @return void
	 */
	public function set_additional_options( array $options ): void {
		$this->set_prop( 'additional_options', $options );
	}

	/**
	 * Set visibility.
	 *
	 * @since 3.0.0
	 * @param bool $visibility Product visibility.
	 * @return void
	 */
	public function set_visibility( bool $visibility ): void {
		$this->set_prop( 'visibility', (bool) $visibility );
	}

	/**
	 * Set featured priority.
	 *
	 * @since 3.0.0
	 * @param int $priority Featured priority.
	 * @return void
	 */
	public function set_featured_priority( int $priority ): void {
		$this->set_prop( 'featured_priority', absint( $priority ) );
	}

	/**
	 * Set marketplace status.
	 *
	 * @since 3.0.0
	 * @param string $status Marketplace status.
	 * @return void
	 */
	public function set_marketplace_status( string $status ): void {
		$allowed_statuses = array( 'pending', 'approved', 'rejected', 'suspended' );
		$status           = in_array( $status, $allowed_statuses, true ) ? $status : 'approved';

		$this->set_prop( 'marketplace_status', $status );
	}

	/**
	 * Set date created.
	 *
	 * @since 3.0.0
	 * @param string|integer|DateTimeInterface $date Date created.
	 * @return void
	 */
	public function set_date_created( $date ): void {
		$this->set_date_prop( 'date_created', $date );
	}

	/**
	 * Set date modified.
	 *
	 * @since 3.0.0
	 * @param string|integer|DateTimeInterface $date Date modified.
	 * @return void
	 */
	public function set_date_modified( $date ): void {
		$this->set_date_prop( 'date_modified', $date );
	}

	/*
	|--------------------------------------------------------------------------
	| Additional Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the WooCommerce product object.
	 *
	 * @since 3.0.0
	 * @return \WC_Product|null
	 */
	public function get_wc_product() {
		$product_id = $this->get_product_id();

		if ( ! $product_id ) {
			return null;
		}

		return wc_get_product( $product_id );
	}

	/**
	 * Add a custom attribute.
	 *
	 * @since 3.0.0
	 * @param string $key   Attribute key.
	 * @param mixed  $value Attribute value.
	 * @return void
	 */
	public function add_custom_attribute( string $key, $value ): void {
		$attributes         = $this->get_custom_attributes();
		$attributes[ $key ] = $value;

		$this->set_custom_attributes( $attributes );
	}

	/**
	 * Remove a custom attribute.
	 *
	 * @since 3.0.0
	 * @param string $key Attribute key.
	 * @return void
	 */
	public function remove_custom_attribute( string $key ): void {
		$attributes = $this->get_custom_attributes();

		if ( isset( $attributes[ $key ] ) ) {
			unset( $attributes[ $key ] );
			$this->set_custom_attributes( $attributes );
		}
	}

	/**
	 * Add an additional option.
	 *
	 * @since 3.0.0
	 * @param string $key   Option key.
	 * @param mixed  $value Option value.
	 * @return void
	 */
	public function add_additional_option( string $key, $value ): void {
		$options         = $this->get_additional_options();
		$options[ $key ] = $value;

		$this->set_additional_options( $options );
	}

	/**
	 * Remove an additional option.
	 *
	 * @since 3.0.0
	 * @param string $key Option key.
	 * @return void
	 */
	public function remove_additional_option( string $key ): void {
		$options = $this->get_additional_options();

		if ( isset( $options[ $key ] ) ) {
			unset( $options[ $key ] );
			$this->set_additional_options( $options );
		}
	}

	/**
	 * Get formatted marketplace status.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_formatted_marketplace_status(): string {
		$status   = $this->get_marketplace_status();
		$statuses = array(
			'pending'   => __( 'Pending Review', 'wp-plugin-starter' ),
			'approved'  => __( 'Approved', 'wp-plugin-starter' ),
			'rejected'  => __( 'Rejected', 'wp-plugin-starter' ),
			'suspended' => __( 'Suspended', 'wp-plugin-starter' ),
		);

		/**
		 * Filter the formatted marketplace statuses.
		 *
		 * @since 3.0.0
		 *
		 * @param array  $statuses Array of status labels.
		 * @param string $status   Current status.
		 * @param Product_Model $this     Product model instance.
		 *
		 * @return array
		 */
		$statuses = apply_filters( 'WPPluginStarter_product_marketplace_statuses', $statuses, $status, $this );

		return $statuses[ $status ] ?? $status;
	}

	/**
	 * Check if the product is visible.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public function is_visible(): bool {
		$visibility = $this->get_visibility();
		$status     = $this->get_marketplace_status();

		// Product is only visible if both visibility is true and status is approved
		$is_visible = $visibility && 'approved' === $status;

		/**
		 * Filter whether a product is visible.
		 *
		 * @since 3.0.0
		 *
		 * @param bool          $is_visible Whether the product is visible.
		 * @param Product_Model $this       Product model instance.
		 *
		 * @return bool
		 */
		return apply_filters( 'WPPluginStarter_product_is_visible', $is_visible, $this );
	}

	/**
	 * Save the product model data.
	 *
	 * @since 3.0.0
	 * @return int
	 */
	public function save(): int {
		if ( ! $this->data_store ) {
			return $this->get_id();
		}

		/**
		 * Action triggered before saving product model.
		 *
		 * @since 3.0.0
		 *
		 * @param Product_Model $this        The product model being saved.
		 * @param int           $product_id  The associated WC product ID.
		 */
		do_action( 'WPPluginStarter_before_product_model_save', $this, $this->get_product_id() );

		// Set date modified
		$this->set_date_modified( time() );

		// If creating, set date created
		if ( ! $this->get_id() ) {
			$this->set_date_created( time() );
		}

		// Save the data
		$id = $this->data_store->save( $this );

		/**
		 * Action triggered after saving product model.
		 *
		 * @since 3.0.0
		 *
		 * @param Product_Model $this        The product model that was saved.
		 * @param int           $product_id  The associated WC product ID.
		 */
		do_action( 'WPPluginStarter_after_product_model_save', $this, $this->get_product_id() );

		return $id;
	}
}
