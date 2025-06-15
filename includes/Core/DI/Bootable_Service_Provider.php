<?php

namespace WPPluginStarter\Core\DI;

use WPPluginStarter\Dependencies\League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Bootable service provider
 *
 * Service provider that can be booted
 *
 * @package WPPluginStarter\Core\DI\ServiceProvider
 */
abstract class Bootable_Service_Provider extends Base_Service_Provider implements BootableServiceProviderInterface {
}
