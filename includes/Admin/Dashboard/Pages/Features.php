<?php

namespace WPPluginStarter\Admin\Dashboard\Pages;

use WPPluginStarter\Admin\Dashboard\Components\Component_Factory as Factory;

/**
 * Features Page
 *
 * Manages features in the admin dashboard
 *
 * @since 1.0.0
 */
class Features extends Abstract_Page {

	/**
	 * @inheritDoc
	 */
	protected $structure;

	/**
	 * Get the ID of the page.
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */
	public function get_id(): string {
		return 'features';
	}

	/**
	 * @inheritDoc
	 */
	public function menu( string $capability, string $position ): array {
		return array(
			'page_title' => __( 'WP Plugin Starter Features', 'wp-plugin-starter' ),
			'menu_title' => __( 'Features', 'wp-plugin-starter' ),
			'route'      => 'status',
			'capability' => $capability,
			'position'   => 99,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function describe_settings(): void {
		// Initialize the structure with factory
		$this->structure = Factory::section( 'wp-plugin-starter-features' );

		// Vendor SettingsModel Group
		$this->add_vendor_settings();

		// Product SettingsModel Group
		$this->add_product_settings();

		// Shipping SettingsModel Group
		$this->add_shipping_settings();

		// Cart & Checkout SettingsModel Group
		$this->add_cart_settings();

		// Allow other modules to extend our features
		do_action( 'WPPluginStarter_describe_settings', $this->structure );
	}

	/**
	 * Add vendor features to the structure
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_vendor_settings(): void {
		/**
		 * Action before adding vendor settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_vendor_settings', $this->structure );

		$vendor_section = Factory::section( 'vendor' )
								->set_title( esc_html__( 'Vendor', 'wp-plugin-starter' ) )
								->set_icon( 'dashicons-businessman' );

		/**
		 * Filter vendor section
		 *
		 * @since 1.0.0
		 * @param object $vendor_section The vendor section
		 */
		$vendor_section = apply_filters( 'WPPluginStarter_vendor_section', $vendor_section );

		// Registration
		$registration = Factory::sub_section( 'registration' )
								->set_title( esc_html__( 'Vendor Registration', 'wp-plugin-starter' ) )
								->set_description( esc_html__( 'Customize how vendors register and join your marketplace.', 'wp-plugin-starter' ) );

		/**
		 * Filter vendor registration subsection
		 *
		 * @since 1.0.0
		 * @param object $registration The registration subsection
		 */
		$registration = apply_filters( 'WPPluginStarter_vendor_registration_subsection', $registration );

		/**
		 * Action before adding registration fields
		 *
		 * @since 1.0.0
		 * @param object $registration The registration subsection
		 */
		do_action( 'WPPluginStarter_before_add_vendor_registration_fields', $registration );

		$registration->add(
			Factory::field( 'remove_vendor_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Vendor Registration', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the "I am a vendor" checkbox from the WooCommerce My Account registration page.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'vendor.registration.remove_vendor_checkbox' )
		);

		$registration->add(
			Factory::field( 'set_default_seller_role_checkbox', 'toggle' )
					->set_title( esc_html__( 'Enable "I am a Vendor" by default', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Pre-select the "I am a Vendor" checkbox on the registration form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'vendor.registration.set_default_seller_role_checkbox' )
					->add_dependency( 'vendor.registration.remove_vendor_checkbox', false )
		);

		$registration->add(
			Factory::field( 'remove_become_a_vendor_button_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Become a Vendor Button', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the "Become a Vendor" button from the WooCommerce My Account dashboard.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'vendor.registration.remove_become_a_vendor_button_checkbox' )
		);

		/**
		 * Action after adding registration fields
		 *
		 * @since 1.0.0
		 * @param object $registration The registration subsection
		 */
		do_action( 'WPPluginStarter_after_add_vendor_registration_fields', $registration );

		// Capabilities
		$capabilities = Factory::sub_section( 'capabilities' )
								->set_title( esc_html__( 'Vendor Capabilities', 'wp-plugin-starter' ) )
								->set_description( esc_html__( 'Manage what vendors can do within your marketplace.', 'wp-plugin-starter' ) );

		/**
		 * Filter vendor capabilities subsection
		 *
		 * @since 1.0.0
		 * @param object $capabilities The capabilities subsection
		 */
		$capabilities = apply_filters( 'WPPluginStarter_vendor_capabilities_subsection', $capabilities );

		/**
		 * Action before adding capability fields
		 *
		 * @since 1.0.0
		 * @param object $capabilities The capabilities subsection
		 */
		do_action( 'WPPluginStarter_before_add_vendor_capability_fields', $capabilities );

		$capabilities->add(
			Factory::field( 'enable_own_product_purchase_checkbox', 'toggle' )
					->set_title( esc_html__( 'Enable Purchase of Own Products', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Allow vendors to purchase products from their own store.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'vendor.capabilities.enable_own_product_purchase_checkbox' )
		);

		/**
		 * Action after adding capability fields
		 *
		 * @since 1.0.0
		 * @param object $capabilities The capabilities subsection
		 */
		do_action( 'WPPluginStarter_after_add_vendor_capability_fields', $capabilities );

		$vendor_section->add( $registration )->add( $capabilities );

		/**
		 * Action for adding additional vendor subsections
		 *
		 * @since 1.0.0
		 * @param object $vendor_section The vendor section
		 */
		do_action( 'WPPluginStarter_add_vendor_subsections', $vendor_section );

		/**
		 * Action before adding vendor section to structure
		 *
		 * @since 1.0.0
		 * @param object $vendor_section The vendor section
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_vendor_section', $vendor_section, $this->structure );

		$this->structure->add( $vendor_section );

		/**
		 * Action after adding vendor settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_after_add_vendor_settings', $this->structure );
	}

	/**
	 * Add product features to the structure
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_product_settings(): void {
		/**
		 * Action before adding product settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_product_settings', $this->structure );

		$product_section = Factory::section( 'product' )
									->set_title( esc_html__( 'Product', 'wp-plugin-starter' ) )
									->set_icon( 'dashicons-products' );

		/**
		 * Filter product section
		 *
		 * @since 1.0.0
		 * @param object $product_section The product section
		 */
		$product_section = apply_filters( 'WPPluginStarter_product_section', $product_section );

		// Product Types
		$types = Factory::sub_section( 'types' )
						->set_title( esc_html__( 'Product Types', 'wp-plugin-starter' ) )
						->set_description( esc_html__( 'Control which product types vendors can create in their stores.', 'wp-plugin-starter' ) );

		/**
		 * Filter product types subsection
		 *
		 * @since 1.0.0
		 * @param object $types The product types subsection
		 */
		$types = apply_filters( 'WPPluginStarter_product_types_subsection', $types );

		/**
		 * Action before adding product type fields
		 *
		 * @since 1.0.0
		 * @param object $types The product types subsection
		 */
		do_action( 'WPPluginStarter_before_add_product_type_fields', $types );

		$types->add(
			Factory::field( 'remove_simple_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Simple Products', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the simple product type option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.types.remove_simple_product_checkbox' )
		);

		$types->add(
			Factory::field( 'remove_variable_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Variable Products', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the variable product type option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.types.remove_variable_product_checkbox' )
		);

		$types->add(
			Factory::field( 'remove_external_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove External Products', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the external/affiliate product type option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.types.remove_external_product_checkbox' )
		);

		$types->add(
			Factory::field( 'remove_grouped_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Grouped Products', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the grouped product type option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.types.remove_grouped_product_checkbox' )
		);

		/**
		 * Action after adding product type fields
		 *
		 * @since 1.0.0
		 * @param object $types The product types subsection
		 */
		do_action( 'WPPluginStarter_after_add_product_type_fields', $types );

		// Product Fields
		$fields = Factory::sub_section( 'fields' )
						->set_title( esc_html__( 'Product Fields', 'wp-plugin-starter' ) )
						->set_description( esc_html__( 'Customize which fields appear in the vendor product editor.', 'wp-plugin-starter' ) );

		/**
		 * Filter product fields subsection
		 *
		 * @since 1.0.0
		 * @param object $fields The product fields subsection
		 */
		$fields = apply_filters( 'WPPluginStarter_product_fields_subsection', $fields );

		/**
		 * Action before adding product field options
		 *
		 * @since 1.0.0
		 * @param object $fields The product fields subsection
		 */
		do_action( 'WPPluginStarter_before_add_product_field_options', $fields );

		$fields->add(
			Factory::field( 'remove_short_description_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Short Description', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the short description field from the product edit form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_short_description_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_long_description_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Long Description', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the main product description editor from the product edit form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_long_description_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_inventory_section_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Inventory Section', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the entire inventory management section from the product edit form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_inventory_section_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_downloadable_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Downloadable Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the downloadable product option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_downloadable_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_virtual_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Virtual Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the virtual product option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_virtual_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_linked_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Linked Product Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the linked product options (up-sells, cross-sells, etc.) from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_linked_product_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_attribute_variation_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Attribute and Variation Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the product attributes and variations section from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_attribute_variation_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_shipping_tax_option_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Product Shipping Tax Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the shipping and tax section from the product editor.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_shipping_tax_option_checkbox' )
		);

		$fields->add(
			Factory::field( 'remove_geolocation_option_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Geolocation Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the geolocation option from the product edit form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.fields.remove_geolocation_option_checkbox' )
		);

		/**
		 * Action after adding product field options
		 *
		 * @since 1.0.0
		 * @param object $fields The product fields subsection
		 */
		do_action( 'WPPluginStarter_after_add_product_field_options', $fields );

		// Product Images
		$images = Factory::sub_section( 'images' )
						->set_title( esc_html__( 'Product Images', 'wp-plugin-starter' ) )
						->set_description( esc_html__( 'Control product image quality and appearance with these settings.', 'wp-plugin-starter' ) )
						->set_badge( esc_html__( 'Pro', 'wp-plugin-starter' ), 'primary' );

		/**
		 * Filter product images subsection
		 *
		 * @since 1.0.0
		 * @param object $images The product images subsection
		 */
		$images = apply_filters( 'WPPluginStarter_product_images_subsection', $images );

		/**
		 * Action before adding product image fields
		 *
		 * @since 1.0.0
		 * @param object $images The product images subsection
		 */
		do_action( 'WPPluginStarter_before_add_product_image_fields', $images );

		$images->add(
			Factory::field( 'enable_dimension_restrictions', 'toggle' )
					->set_title( esc_html__( 'Enable Image Restrictions', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Enforce minimum dimensions for product images.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.images.enable_dimension_restrictions' )
		);

		$images->add(
			Factory::field( 'image_max_width', 'number' )
					->set_title( esc_html__( 'Minimum Image Width', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Set the minimum width (in pixels) required for product images.', 'wp-plugin-starter' ) )
					->set_default( 800 )
					->set_minimum( 0 )
					->set_dependency_key( 'product.images.image_max_width' )
					->add_dependency( 'product.images.enable_dimension_restrictions', true )
		);

		$images->add(
			Factory::field( 'image_max_height', 'number' )
					->set_title( esc_html__( 'Minimum Image Height', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Set the minimum height (in pixels) required for product images.', 'wp-plugin-starter' ) )
					->set_default( 800 )
					->set_minimum( 0 )
					->set_dependency_key( 'product.images.image_max_height' )
					->add_dependency( 'product.images.enable_dimension_restrictions', true )
		);

		$images->add(
			Factory::field( 'enable_size_restrictions', 'toggle' )
					->set_title( esc_html__( 'Enable Size Restrictions', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Limit the maximum file size for product images.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.images.enable_size_restrictions' )
		);

		$images->add(
			Factory::field( 'image_max_size', 'number' )
					->set_title( esc_html__( 'Maximum Image Size', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Set the maximum file size (in MB) allowed for product images.', 'wp-plugin-starter' ) )
					->set_default( 2 )
					->set_dependency_key( 'product.images.image_max_size' )
					->add_dependency( 'product.images.enable_size_restrictions', true )
		);

		$images->add(
			Factory::field( 'show_image_requirements_notice_checkbox', 'toggle' )
					->set_title( esc_html__( 'Show Image Requirements Notice', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Display a helpful notice on the product edit page informing vendors about image requirements before they upload.', 'wp-plugin-starter' ) )
					->set_default( true )
					->set_dependency_key( 'product.images.show_image_requirements_notice_checkbox' )
					->add_dependency( 'product.images.enable_dimension_restrictions', true )
		);

		/**
		 * Action after adding product image fields
		 *
		 * @since 1.0.0
		 * @param object $images The product images subsection
		 */
		do_action( 'WPPluginStarter_after_add_product_image_fields', $images );

		// Additional Features
		$additional = Factory::sub_section( 'additional' )
							->set_title( esc_html__( 'Additional Features', 'wp-plugin-starter' ) )
							->set_description( esc_html__( 'Control additional product options and miscellaneous settings that can simplify the vendor experience.', 'wp-plugin-starter' ) );

		/**
		 * Filter product additional features subsection
		 *
		 * @since 1.0.0
		 * @param object $additional The product additional features subsection
		 */
		$additional = apply_filters( 'WPPluginStarter_product_additional_features_subsection', $additional );

		/**
		 * Action before adding additional product feature fields
		 *
		 * @since 1.0.0
		 * @param object $additional The product additional features subsection
		 */
		do_action( 'WPPluginStarter_before_add_additional_product_fields', $additional );

		$additional->add(
			Factory::field( 'remove_other_options_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Other Options', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide miscellaneous product options from the edit form.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.additional.remove_other_options_checkbox' )
		);

		/**
		 * Action after adding additional product feature fields
		 *
		 * @since 1.0.0
		 * @param object $additional The product additional features subsection
		 */
		do_action( 'WPPluginStarter_after_add_additional_product_fields', $additional );

		// Pro Features
		$pro_features = Factory::sub_section( 'pro_features' )
							->set_title( esc_html__( 'WooCommerce Pro Features', 'wp-plugin-starter' ) )
							->set_description( esc_html__( 'Control the availability of premium WooCommerce product features.', 'wp-plugin-starter' ) );

		/**
		 * Filter product pro features subsection
		 *
		 * @since 1.0.0
		 * @param object $pro_features The product pro features subsection
		 */
		$pro_features = apply_filters( 'WPPluginStarter_product_pro_features_subsection', $pro_features );

		/**
		 * Action before adding product pro feature fields
		 *
		 * @since 1.0.0
		 * @param object $pro_features The product pro features subsection
		 */
		do_action( 'WPPluginStarter_before_add_product_pro_feature_fields', $pro_features );

		$pro_features->add(
			Factory::field( 'remove_bulk_discount_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Bulk Discount Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the bulk discount feature from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_bulk_discount_checkbox' )
		);

		$pro_features->add(
			Factory::field( 'remove_rma_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove RMA Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the Return Merchandise Authorization (RMA) options from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_rma_checkbox' )
		);

		$pro_features->add(
			Factory::field( 'remove_wholesale_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Wholesale Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the wholesale product options from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_wholesale_checkbox' )
		);

		$pro_features->add(
			Factory::field( 'remove_min_max_product_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Min Max Product Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the minimum and maximum purchase quantity options from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_min_max_product_checkbox' )
		);

		$pro_features->add(
			Factory::field( 'remove_product_advertisement_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Product Advertisement Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the product advertisement feature from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_product_advertisement_checkbox' )
		);

		$pro_features->add(
			Factory::field( 'remove_catalog_mode_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Catalog Mode Option', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the catalog mode option from vendors.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'product.pro_features.remove_catalog_mode_checkbox' )
		);

		/**
		 * Action after adding product pro feature fields
		 *
		 * @since 1.0.0
		 * @param object $pro_features The product pro features subsection
		 */
		do_action( 'WPPluginStarter_after_add_product_pro_feature_fields', $pro_features );

		// Only add the additional section if it has fields
		if ( count( $additional->get_children() ) > 0 ) {
			$product_section->add( $types )->add( $fields )->add( $images )->add( $additional )->add( $pro_features );
		} else {
			$product_section->add( $types )->add( $fields )->add( $images )->add( $pro_features );
		}

		/**
		 * Action for adding additional product subsections
		 *
		 * @since 1.0.0
		 * @param object $product_section The product section
		 */
		do_action( 'WPPluginStarter_add_product_subsections', $product_section );

		/**
		 * Action before adding product section to structure
		 *
		 * @since 1.0.0
		 * @param object $product_section The product section
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_product_section', $product_section, $this->structure );

		$this->structure->add( $product_section );

		/**
		 * Action after adding product settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_after_add_product_settings', $this->structure );
	}

	/**
	 * Add shipping features to the structure
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_shipping_settings(): void {
		/**
		 * Action before adding shipping settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_shipping_settings', $this->structure );

		$section = Factory::section( 'shipping' )
							->set_title( esc_html__( 'Shipping', 'wp-plugin-starter' ) )
							->set_icon( 'dashicons-car' );

		/**
		 * Filter shipping section
		 *
		 * @since 1.0.0
		 * @param object $section The shipping section
		 */
		$section = apply_filters( 'WPPluginStarter_shipping_section', $section );

		// Split Shipping
		$sub_section = Factory::sub_section( 'split' )
						->set_title( esc_html__( 'Split Shipping', 'wp-plugin-starter' ) )
						->set_description( esc_html__( 'Control split shipping options to manage how shipping costs are divided among vendors in multi-vendor orders.', 'wp-plugin-starter' ) );

		/**
		 * Filter split shipping subsection
		 *
		 * @since 1.0.0
		 * @param object $sub_section The split shipping subsection
		 */
		$sub_section = apply_filters( 'WPPluginStarter_split_shipping_subsection', $sub_section );

		/**
		 * Action before adding split shipping fields
		 *
		 * @since 1.0.0
		 * @param object $sub_section The split shipping subsection
		 */
		do_action( 'WPPluginStarter_before_add_split_shipping_fields', $sub_section );

		$sub_section->add(
			Factory::field( 'remove_split_shipping_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Split Shipping (Lite)', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Remove split shipping from cart and checkout pages in Dokan Lite.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'shipping.split.remove_split_shipping_checkbox' )
		);

		$sub_section->add(
			Factory::field( 'remove_split_shipping_pro_checkbox', 'toggle' )
					->set_title( esc_html__( 'Remove Split Shipping (Pro)', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Remove split shipping from cart and checkout pages in Dokan Pro.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'shipping.split.remove_split_shipping_pro_checkbox' )
		);

		/**
		 * Action after adding split shipping fields
		 *
		 * @since 1.0.0
		 * @param object $sub_section The split shipping subsection
		 */
		do_action( 'WPPluginStarter_after_add_split_shipping_fields', $sub_section );

		// Order Processing
		$orders = Factory::sub_section( 'orders' )
						->set_title( esc_html__( 'Order Processing', 'wp-plugin-starter' ) )
						->set_description( esc_html__( 'Control order processing options to streamline the handling of orders in your marketplace.', 'wp-plugin-starter' ) );

		/**
		 * Filter order processing subsection
		 *
		 * @since 1.0.0
		 * @param object $orders The order processing subsection
		 */
		$orders = apply_filters( 'WPPluginStarter_order_processing_subsection', $orders );

		/**
		 * Action before adding order processing fields
		 *
		 * @since 1.0.0
		 * @param object $orders The order processing subsection
		 */
		do_action( 'WPPluginStarter_before_add_order_processing_fields', $orders );

		$orders->add(
			Factory::field( 'auto_complete_order_checkbox', 'toggle' )
					->set_title( esc_html__( 'Auto-Complete Virtual Orders', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Automatically complete orders containing only virtual and downloadable products.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'shipping.orders.auto_complete_order_checkbox' )
		);

		/**
		 * Action after adding order processing fields
		 *
		 * @since 1.0.0
		 * @param object $orders The order processing subsection
		 */
		do_action( 'WPPluginStarter_after_add_order_processing_fields', $orders );

		$section->add( $sub_section )->add( $orders );

		/**
		 * Action for adding additional shipping subsections
		 *
		 * @since 1.0.0
		 * @param object $section The shipping section
		 */
		do_action( 'WPPluginStarter_add_shipping_subsections', $section );

		/**
		 * Action before adding shipping section to structure
		 *
		 * @since 1.0.0
		 * @param object $section The shipping section
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_shipping_section', $section, $this->structure );

		$this->structure->add( $section );

		/**
		 * Action after adding shipping settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_after_add_shipping_settings', $this->structure );
	}

	/**
	 * Add cart & checkout features to the structure
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function add_cart_settings(): void {
		/**
		 * Action before adding cart settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_cart_settings', $this->structure );

		$section = Factory::section( 'cart' )
							->set_title( esc_html__( 'Cart & Checkout', 'wp-plugin-starter' ) )
							->set_icon( 'dashicons-cart' );

		/**
		 * Filter cart section
		 *
		 * @since 1.0.0
		 * @param object $section The cart section
		 */
		$section = apply_filters( 'WPPluginStarter_cart_section', $section );

		// Cart Buttons
		$sub_section = Factory::sub_section( 'buttons' )
							->set_title( esc_html__( 'Cart Buttons', 'wp-plugin-starter' ) )
							->set_description( esc_html__( 'Control the visibility and functionality of cart buttons to enhance the shopping experience.', 'wp-plugin-starter' ) );

		/**
		 * Filter cart buttons subsection
		 *
		 * @since 1.0.0
		 * @param object $sub_section The cart buttons subsection
		 */
		$sub_section = apply_filters( 'WPPluginStarter_cart_buttons_subsection', $sub_section );

		/**
		 * Action before adding cart button fields
		 *
		 * @since 1.0.0
		 * @param object $sub_section The cart buttons subsection
		 */
		do_action( 'WPPluginStarter_before_add_cart_button_fields', $sub_section );

		$sub_section->add(
			Factory::field( 'hide_add_to_cart_button_checkbox', 'toggle' )
					->set_title( esc_html__( 'Hide Add to Cart Button', 'wp-plugin-starter' ) )
					->set_description( esc_html__( 'Hide the Add to Cart button from WooCommerce product pages.', 'wp-plugin-starter' ) )
					->set_default( false )
					->set_dependency_key( 'cart.buttons.hide_add_to_cart_button_checkbox' )
		);

		/**
		 * Action after adding cart button fields
		 *
		 * @since 1.0.0
		 * @param object $sub_section The cart buttons subsection
		 */
		do_action( 'WPPluginStarter_after_add_cart_button_fields', $sub_section );

		$section->add( $sub_section );

		/**
		 * Action for adding additional cart subsections
		 *
		 * @since 1.0.0
		 * @param object $section The cart section
		 */
		do_action( 'WPPluginStarter_add_cart_subsections', $section );

		/**
		 * Action before adding cart section to structure
		 *
		 * @since 1.0.0
		 * @param object $section The cart section
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_before_add_cart_section', $section, $this->structure );

		$this->structure->add( $section );

		/**
		 * Action after adding cart settings
		 *
		 * @since 1.0.0
		 * @param object $structure The settings structure
		 */
		do_action( 'WPPluginStarter_after_add_cart_settings', $this->structure );
	}

	/**
	 * @inheritDoc
	 */
	public function settings(): array {
		return $this->structure->populate();
	}

	/**
	 * @inheritDoc
	 */
	public function scripts(): array {
		return array( 'wp-plugin-starter-dashboard-app' );
	}

	/**
	 * Get the styles.
	 *
	 * @since 4.0.0
	 *
	 * @return array<string> An array of style handles.
	 */
	public function styles(): array {
		return array( 'wp-plugin-starter-dashboard-app' );
	}

	/**
	 * Register the page scripts and styles.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function register(): void {}
}
