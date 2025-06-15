<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Base_Service_Provider;

/**
 * Admin Dashboard Service Provider
 *
 * Registers and manages the admin dashboard services for WPPluginStarter.
 *
 * @since 1.0.0
 */
class Admin_Dashboard_Service_Provider extends Base_Service_Provider {
	/**
	 * Tag for services added to the container.
	 *
	 * @var array
	 */
	protected array $tags = array( 'admin-dashboard-service' );

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		\WPPluginStarter\Admin\Dashboard\Pages\Dashboard::class,
		\WPPluginStarter\Admin\Dashboard\Pages\Features::class,
		\WPPluginStarter\Admin\Dashboard\Dashboard::class,
	);

	/**
	 * Register the services.
	 *
	 * @return void
	 */
	public function register(): void {
		// Register regular services
		foreach ( $this->services as $service ) {
			$definition = $this->share_with_implements_tags( $service );
			$this->add_tags( $definition, $this->tags );
		}
	}
}
