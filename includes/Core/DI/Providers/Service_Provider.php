<?php
namespace WPPluginStarter\Core\DI\Providers;

use WPPluginStarter\Core\DI\Container;

/**
 * Registers services in the DI container.
 */
class Service_Provider {
    public function register(Container $container): void {
        // Register services here, e.g.:
        // $container->singleton('service_name', function() { return new ServiceClass(); });
    }
}

