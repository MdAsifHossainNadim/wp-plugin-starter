<?php

namespace WPPluginStarter\Tests\TestHelpers;

use WC_Cache_Helper;

/**
 * Helper class for WooCommerce testing
 */
class WooCommerceTestHelper {
	/**
	 * Check if WooCommerce is active and available for testing
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active(): bool {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Create a test product
	 *
	 * @param array $args Product arguments
	 *
	 * @return \WC_Product|null
	 */
	public static function create_product( array $args = array() ): ?\WC_Product {
		if ( ! self::is_woocommerce_active() ) {
			return null;
		}

		$defaults = array(
			'name'               => 'Test Product ' . TestHelper::random_string( 5 ),
			'regular_price'      => wp_rand( 10, 100 ),
			'price'              => wp_rand( 10, 100 ),
			'sku'                => 'TEST-' . TestHelper::random_string( 5 ),
			'status'             => 'publish',
			'catalog_visibility' => 'visible',
			'description'        => 'Test product description',
			'short_description'  => 'Test product short description',
		);

		$args = wp_parse_args( $args, $defaults );

		return wc_create_product( 'simple', $args );
	}

	/**
	 * Create multiple test products
	 *
	 * @param int   $count Number of products to create
	 * @param array $args  Product arguments
	 *
	 * @return array
	 */
	public static function create_products( int $count = 3, array $args = array() ): array {
		$products = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$product = self::create_product( $args );
			if ( $product ) {
				$products[] = $product;
			}
		}

		return $products;
	}

	/**
	 * Create a test order
	 *
	 * @param array $args Order arguments
	 *
	 * @return \WC_Order|null
	 */
	public static function create_order( array $args = array() ): ?\WC_Order {
		if ( ! self::is_woocommerce_active() ) {
			return null;
		}

		$defaults = array(
			'status'      => 'processing',
			'customer_id' => 1,
		);

		$args = wp_parse_args( $args, $defaults );

		$order = wc_create_order( $args );

		if ( empty( $args['products'] ) ) {
			// Add a random product if none specified
			$product = self::create_product();
			if ( $product ) {
				$order->add_product( $product, wp_rand( 1, 3 ) );
			}
		} else {
			foreach ( $args['products'] as $product_info ) {
				$product_id = is_array( $product_info ) ? $product_info['product_id'] : $product_info;
				$quantity   = is_array( $product_info ) ? ( $product_info['quantity'] ?? 1 ) : 1;

				$order->add_product( wc_get_product( $product_id ), $quantity );
			}
		}

		$order->calculate_totals();
		$order->save();

		return $order;
	}

	/**
	 * Create a test customer
	 *
	 * @param array $args Customer arguments
	 *
	 * @return int WP User ID
	 */
	public static function create_customer( array $args = array() ): int {
		$defaults = array(
			'role'     => 'customer',
			'username' => 'customer_' . TestHelper::random_string( 5 ),
			'password' => 'password',
			'email'    => 'customer_' . TestHelper::random_string( 5 ) . '@example.com',
		);

		$args = wp_parse_args( $args, $defaults );

		return wp_insert_user( $args );
	}

	/**
	 * Clear WooCommerce transients
	 *
	 * @return void
	 */
	public static function clear_woocommerce_transients(): void {
		if ( ! self::is_woocommerce_active() ) {
			return;
		}

		wc_delete_shop_order_transients();
		wc_delete_product_transients();

		// Clear any cached reports
		WC_Cache_Helper::get_transient_version( 'report', true );
	}
}
