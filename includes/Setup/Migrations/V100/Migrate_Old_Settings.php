<?php
/**
 * Migrate_Old_Settings Migration Class
 *
 * @since   1.0.0
 * @package WPPluginStarter\Setup\Migrations
 */

namespace WPPluginStarter\Setup\Migrations\V100;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Setup\Migrations\Abstract_Migration;

/**
 * Class Migrate_Old_Settings
 *
 * Handles migration of old settings to new data model.
 *
 * @since 1.0.0
 */
class Migrate_Old_Settings extends Abstract_Migration {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'1.0.0',
			'migrate_old_settings',
			'data_migration',
			esc_html__( 'Migrates settings from older plugin versions to the new data structure', 'wp-plugin-starter' ),
		);
	}

	/**
	 * Run the migration.
	 *
	 * @param string $version Version being migrated to.
	 *
	 * @return void
	 * @throws \Exception If migration fails.
	 */
	public function migrate( string $version ): void {
		$data_store       = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$old_settings_map = $this->get_old_settings_map();

		// Flag to track if any changes were made.
		$settings_updated = false;

		// Migrate settings for each category.
		foreach ( $old_settings_map as $category => $option_keys ) {
			// Get the settings for this category.
			$settings = $data_store->get_settings_by_name( $category );

			// If settings don't exist, create them.
			if ( ! $settings instanceof Settings_Model ) {
				$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
				$settings->set_name( $category );
				$settings->set_value( array() );
			}

			// Get current values.
			$current_values = $settings->get_value();
			$updates        = array();
			$has_updates    = false;

			foreach ( $option_keys as $key ) {
				$old_value = get_option( $key, null );

				// Only update if old value exists.
				if ( null !== $old_value ) {
					// Handle different types of values.
					if ( in_array( $key, array( 'enable_dimension_restrictions', 'enable_size_restrictions' ), true ) ) {
						$updates[ $key ] = (bool) $old_value;
					} elseif ( is_numeric( $old_value ) && in_array( $key, array( 'image_max_width', 'image_max_height', 'image_max_size' ), true ) ) {
						$updates[ $key ] = (float) $old_value;
					} else {
						$updates[ $key ] = '1' === $old_value;
					}

					$has_updates      = true;
					$settings_updated = true;

					// Delete the old option after migration.
					delete_option( $key );
				}
			}

			// Save if changes were made.
			if ( $has_updates ) {
				// Merge with existing values.
				$merged_values = array_merge( $current_values, $updates );
				$settings->set_value( $merged_values );
				$settings->save();
			}
		}

		// Delete the old version option.
		delete_option( 'WPPluginStarter_version' );

		/**
		 * Action fired after migrating old settings to new model.
		 *
		 * @since 1.0.0
		 *
		 * @param string $version          Migration version.
		 * @param bool   $settings_updated Whether any settings were updated.
		 */
		do_action( 'WPPluginStarter_after_migrate_old_settings', $version, $settings_updated );
	}

	/**
	 * Get mapping of old settings to categories.
	 *
	 * @return array Old settings map.
	 */
	private function get_old_settings_map(): array {
		return array(
			'vendor'   => array(
				'remove_vendor_checkbox',
				'set_default_seller_role_checkbox',
				'remove_become_a_vendor_button_checkbox',
				'enable_own_product_purchase_checkbox',
				'enable_vendor_product_review',
			),
			'product'  => array(
				'remove_simple_product_checkbox',
				'remove_variable_product_checkbox',
				'remove_external_product_checkbox',
				'remove_grouped_product_checkbox',
				'remove_short_description_checkbox',
				'remove_long_description_checkbox',
				'remove_inventory_section_checkbox',
				'remove_geolocation_option_checkbox',
				'remove_shipping_tax_option_checkbox',
				'remove_linked_product_checkbox',
				'remove_attribute_variation_checkbox',
				'remove_bulk_discount_checkbox',
				'remove_rma_checkbox',
				'remove_wholesale_checkbox',
				'remove_min_max_product_checkbox',
				'remove_other_options_checkbox',
				'remove_product_advertisement_checkbox',
				'remove_catalog_mode_checkbox',
				'remove_downloadable_checkbox',
				'remove_virtual_checkbox',
				'show_image_requirements_notice_checkbox',
			),
			'shipping' => array(
				'auto_complete_order_checkbox',
				'remove_split_shipping_checkbox',
				'remove_split_shipping_pro_checkbox',
			),
			'cart'     => array(
				'hide_add_to_cart_button_checkbox',
			),
			'display'  => array(
				'enable_dimension_restrictions',
				'enable_size_restrictions',
				'image_max_width',
				'image_max_height',
				'image_max_size',
			),
		);
	}
}
