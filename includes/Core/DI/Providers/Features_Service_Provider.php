<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Base_Service_Provider;
use WPPluginStarter\Features\Cart\Cart_Buttons;
use WPPluginStarter\Features\Order\Auto_Complete_Virtual_Orders;
use WPPluginStarter\Features\Product\Code_Editor;
use WPPluginStarter\Features\Product\Hide_Add_To_Cart_Button;
use WPPluginStarter\Features\Product\Image_Restrictions;
use WPPluginStarter\Features\Product\Product_Fields;
use WPPluginStarter\Features\Product\Product_Fields_Visibility;
use WPPluginStarter\Features\Product\Product_Types;
use WPPluginStarter\Features\Shipping\Remove_Split_Shipping;
use WPPluginStarter\Features\Vendor\Default_Seller_Role;
use WPPluginStarter\Features\Vendor\Enable_Own_Product_Purchase;
use WPPluginStarter\Features\Vendor\Remove_Become_Vendor_Button;
use WPPluginStarter\Features\Vendor\Vendor_Registration;

/**
 * Features Service Provider
 *
 * Registers feature services with the container
 *
 * @package WPPluginStarter\Core\DI\Providers
 */
class Features_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		// Vendor Features
		Vendor_Registration::class,
		Enable_Own_Product_Purchase::class,
		Default_Seller_Role::class,
		Remove_Become_Vendor_Button::class,

		// Product Features
		Image_Restrictions::class,
		Product_Fields::class,
		Product_Types::class,
		Hide_Add_To_Cart_Button::class,
		Code_Editor::class,
		Product_Fields_Visibility::class,

		// Cart Features
		Cart_Buttons::class,

		// Order Features
		Auto_Complete_Virtual_Orders::class,

		// Shipping Features
		Remove_Split_Shipping::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'feature-service' );

	/**
	 * Register services with the container
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->services as $service ) {
			$definition = $this->share_with_implements_tags( $service );
			$this->add_tags( $definition, $this->tags );
		}
	}
}
