<?php

namespace WPPluginStarter\Tests\TestHelpers;

use WP_Error;

/**
 * Helper class for Dokan testing
 */
class DokanTestHelper {
	/**
	 * Check if Dokan is active and available for testing
	 *
	 * @return bool
	 */
	public static function is_dokan_active(): bool {
		return class_exists( 'WeDevs_Dokan' );
	}

	/**
	 * Create a vendor user
	 *
	 * @param array $args Vendor args
	 *
	 * @return int|WP_Error Vendor user ID or WP_Error
	 */
	public static function create_vendor( array $args = array() ) {
		if ( ! self::is_dokan_active() ) {
			return new \WP_Error( 'dokan_inactive', 'Dokan is not active' );
		}

		$defaults = array(
			'role'         => 'seller',
			'user_login'   => 'vendor_' . TestHelper::random_string( 5 ),
			'user_pass'    => 'password',
			'user_email'   => 'vendor_' . TestHelper::random_string( 5 ) . '@example.com',
			'display_name' => 'Test Vendor ' . TestHelper::random_string( 5 ),
			'meta'         => array(
				'dokan_store_name'     => 'Test Store ' . TestHelper::random_string( 5 ),
				'dokan_enable_selling' => 'yes',
				'dokan_publishing'     => 'yes',
				'dokan_store_url'      => 'store_' . TestHelper::random_string( 5 ),
				'dokan_store_ppp'      => 10,
				'dokan_address'        => array(
					'street_1' => '123 Test Street',
					'street_2' => '',
					'city'     => 'Test City',
					'zip'      => '12345',
					'state'    => 'CA',
					'country'  => 'US',
				),
				'payment'              => array(
					'paypal' => array(
						'email' => 'paypal_' . TestHelper::random_string( 5 ) . '@example.com',
					),
					'bank'   => array(
						'ac_name'        => 'Test Account',
						'ac_number'      => '123456789',
						'bank_name'      => 'Test Bank',
						'bank_addr'      => 'Test Bank Address',
						'routing_number' => '123456789',
						'iban'           => '',
						'swift'          => '',
					),
				),
			),
		);

		$args = wp_parse_args( $args, $defaults );
		$meta = $args['meta'] ?? array();
		unset( $args['meta'] );

		// Create the user
		$vendor_id = wp_insert_user( $args );

		if ( is_wp_error( $vendor_id ) ) {
			return $vendor_id;
		}

		// Add user meta
		foreach ( $meta as $key => $value ) {
			update_user_meta( $vendor_id, $key, $value );
		}

		// Call Dokan's vendor creation hooks if needed
		do_action( 'dokan_new_seller_created', $vendor_id, $args );

		return $vendor_id;
	}

	/**
	 * Create a vendor product
	 *
	 * @param int   $vendor_id Vendor user ID
	 * @param array $args      Product args
	 *
	 * @return int|null Product ID or null on failure
	 */
	public static function create_vendor_product( int $vendor_id, array $args = array() ): ?int {
		if ( ! self::is_dokan_active() || ! WooCommerceTestHelper::is_woocommerce_active() ) {
			return null;
		}

		// Create a product using WooCommerce helper
		$product = WooCommerceTestHelper::create_product( $args );

		if ( ! $product ) {
			return null;
		}

		// Set the product author as the vendor
		wp_update_post(
			array(
				'ID'          => $product->get_id(),
				'post_author' => $vendor_id,
			)
		);

		// Add Dokan product meta
		update_post_meta( $product->get_id(), 'dokan_vendor_id', $vendor_id );

		// Clear caches
		clean_post_cache( $product->get_id() );
		wc_delete_product_transients( $product->get_id() );

		return $product->get_id();
	}

	/**
	 * Create multiple vendor products
	 *
	 * @param int   $vendor_id Vendor user ID
	 * @param int   $count     Number of products to create
	 * @param array $args      Product args
	 *
	 * @return array Product IDs
	 */
	public static function create_vendor_products( int $vendor_id, int $count = 3, array $args = array() ): array {
		$product_ids = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$product_id = self::create_vendor_product( $vendor_id, $args );
			if ( $product_id ) {
				$product_ids[] = $product_id;
			}
		}

		return $product_ids;
	}
}
