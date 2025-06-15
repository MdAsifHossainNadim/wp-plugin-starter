<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Base_Service_Provider;
use WPPluginStarter\Setup\Activator;
use WPPluginStarter\Setup\Deactivator;
use WPPluginStarter\Setup\Migrator;
use WPPluginStarter\Setup\System_Check;

/**
 * Setup Service Provider
 *
 * @since   1.0.0
 * @package WPPluginStarter\Providers
 */
class Setup_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		System_Check::class,
		Activator::class,
		Deactivator::class,
		Migrator::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'setup-service' );

	/**
	 * Register services
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
