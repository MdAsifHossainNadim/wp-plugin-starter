<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Admin\Hooks;
use WPPluginStarter\Core\Bootstrap;
use WPPluginStarter\Core\DI\Base_Service_Provider;
use WPPluginStarter\Core\Template_Manager;
use WPPluginStarter\Utils\Logger;

/**
 * Core Service Provider
 *
 * Registers core services with the container
 *
 * @package WPPluginStarter\Core\DI\Providers
 */
class Core_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		Hooks::class,
		Template_Manager::class,
		Logger::class,
		Bootstrap::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'core-service' );

	/**
	 * Register services with the container
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->services as $service ) {
			$definition = $this->share_with_implements_tags( $service );
			$this->add_tags( $definition, $this->tags );
		}
	}
}
