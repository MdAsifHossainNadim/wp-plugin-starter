<?php
/**
 * Create_Default_Settings Migration Class
 *
 * @since   1.0.0
 * @package WPPluginStarter\Setup\Migrations
 */

namespace WPPluginStarter\Setup\Migrations\V100;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Setup\Migrations\Abstract_Migration;

/**
 * Class Create_Default_Settings
 *
 * Handles migration of creating default settings.
 *
 * @since 1.0.0
 */
class Create_Default_Settings extends Abstract_Migration {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'1.0.0',
			'create_default_settings',
			'settings',
			esc_html__( 'Creates default settings for the plugin', 'wp-plugin-starter' ),
			5
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
		$default_settings = $this->get_default_settings();

		// Create settings for each category.
		foreach ( $default_settings as $category => $values ) {
			$settings = $data_store->get_settings_by_name( $category );

			if ( ! $settings instanceof Settings_Model ) {
				$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
				$settings->set_name( $category );
				$settings->set_value( $values );
				$settings->set_default( $values );
				$settings->save();
			}
		}

		/**
		 * Action fired after creating default settings during migration.
		 *
		 * @since 1.0.0
		 *
		 * @param string $version Migration version.
		 */
		do_action( 'WPPluginStarter_after_migrate_settings', $version );
	}

	/**
	 * Get default settings for all categories.
	 *
	 * @return array Default settings.
	 */
	private function get_default_settings(): array {
		return array(
			'vendor'   => array(
				'remove_vendor_checkbox'                 => false,
				'set_default_seller_role_checkbox'       => false,
				'remove_become_a_vendor_button_checkbox' => false,
				'enable_own_product_purchase_checkbox'   => false,
			),
			'product'  => array(
				'remove_variable_product_checkbox'      => false,
				'remove_external_product_checkbox'      => false,
				'remove_grouped_product_checkbox'       => false,
				'remove_short_description_checkbox'     => false,
				'remove_long_description_checkbox'      => false,
				'remove_inventory_section_checkbox'     => false,
				'remove_geolocation_option_checkbox'    => false,
				'remove_shipping_tax_option_checkbox'   => false,
				'remove_linked_product_checkbox'        => false,
				'remove_attribute_variation_checkbox'   => false,
				'remove_bulk_discount_checkbox'         => false,
				'remove_rma_checkbox'                   => false,
				'remove_wholesale_checkbox'             => false,
				'remove_min_max_product_checkbox'       => false,
				'remove_other_options_checkbox'         => false,
				'remove_product_advertisement_checkbox' => false,
				'remove_catalog_mode_checkbox'          => false,
				'remove_downloadable_checkbox'          => false,
				'remove_virtual_checkbox'               => false,
			),
			'shipping' => array(
				'remove_split_shipping_checkbox'     => false,
				'remove_split_shipping_pro_checkbox' => false,
			),
			'cart'     => array(
				'hide_add_to_cart_button_checkbox' => false,
			),
			'display'  => array(
				'enable_dimension_restrictions' => false,
				'enable_size_restrictions'      => false,
				'image_max_width'               => 800,
				'image_max_height'              => 800,
				'image_max_size'                => 2,
			),
		);
	}
}
