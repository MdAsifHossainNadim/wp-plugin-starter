<?php
/**
 * Migration Service Provider Class
 *
 * @since 1.0.0
 * @package WPPluginStarter\Core\DI\Providers
 */

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Base_Service_Provider;
use WPPluginStarter\Setup\Migrations\V100;
use WPPluginStarter\Setup\Migrator;

/**
 * Class Migration_Service_Provider
 *
 * Registers migration services with the container.
 *
 * @since 1.0.0
 */
class Migration_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		Migrator::class,

		// Migration classes
		V100\Create_Default_Settings::class,
		V100\Migrate_Old_Settings::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'migration-service' );

	/**
	 * Register hooks for the provider services.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register(): void {
		// Register services with tags
		foreach ( $this->services as $service ) {
			$definition = $this->share_with_implements_tags( $service );
			$this->add_tags( $definition, $this->tags );
		}
	}
}
