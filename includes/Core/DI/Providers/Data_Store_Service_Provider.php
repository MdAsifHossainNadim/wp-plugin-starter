<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\DI\Base_Service_Provider;

/**
 * Data Store Service Provider
 *
 * Registers data store services with the container
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\DI\Providers
 */
class Data_Store_Service_Provider extends Base_Service_Provider {
	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		Settings_Model::class,
		Settings_Data_Store::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'data-store-service' );

	/**
	 * Register services with the container
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register(): void {
		// Register models and data stores with shared_with_implements_tags
		foreach ( $this->services as $service ) {
			$definition = $this->share_with_implements_tags( $service );
			$this->add_tags( $definition, $this->tags );
		}
	}
}
