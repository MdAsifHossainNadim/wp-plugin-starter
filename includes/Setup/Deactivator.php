<?php

namespace WPPluginStarter\Setup;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Runnable;
use Throwable;

/**
 * Class Deactivator
 *
 * Handles plugin deactivation
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup
 */
class Deactivator implements Runnable {
	/**
	 * Run on plugin deactivation
	 *
	 * @return void
	 */
	public function run(): void {
		try {
			// Get system settings if they exist
			$data_store = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
			$settings   = $data_store->get_settings_by_name( 'system' );

			// Update only if settings exist
			if ( $settings instanceof Settings_Model ) {
				$settings->update( 'is_active', false );
				$settings->update( 'last_deactivation_time', time() );
				$settings->save();
			}

			// Clear caches
			wp_cache_flush();

			/**
			 * Action that fires after plugin deactivation
			 *
			 * @param Settings_Model|null $system_settings The system settings object or null if not found
			 */
			do_action( 'WPPluginStarter_deactivated', $settings ?? null );
		} catch ( Throwable $e ) {
			// Handle any exceptions that occur during the process
		}
	}
}
