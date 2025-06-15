<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Base_Service_Provider;
use WPPluginStarter\REST\Controllers\Version1\Dashboard_Controller;
use WPPluginStarter\REST\Controllers\V1\Settings_Controller;

/**
 * REST Service Provider
 *
 * Registers REST API services with the container
 *
 * @package WPPluginStarter\Core\DI\Providers
 */
class Rest_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		Dashboard_Controller::class,
		Settings_Controller::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'rest-service' );

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
