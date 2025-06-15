<?php

namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Admin\Admin;
use WPPluginStarter\Admin\Assets;
use WPPluginStarter\Admin\Notices;
use WPPluginStarter\Admin\Menu;
use WPPluginStarter\Core\DI\Base_Service_Provider;

/**
 * Admin Service Provider
 */
class Admin_Service_Provider extends Base_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected array $services = array(
		Admin::class,
		Assets::class,
		Menu::class,
		Notices::class,
	);

	/**
	 * Tags for this provider
	 *
	 * @var array
	 */
	protected array $tags = array( 'admin-service' );

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
