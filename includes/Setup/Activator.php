<?php
/**
 * Activator Class
 *
 * Handles plugin activation tasks.
 *
 * FIXED: This class now properly handles fresh installs vs. updates by:
 * 1. Setting db_version to '0.0.0' initially to ensure migrations run
 * 2. Letting the migrator update the db_version after migrations complete
 * 3. Creating default settings only for fresh installs
 * 4. Properly detecting legacy installations
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup
 */

namespace WPPluginStarter\Setup;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Runnable;
use Throwable;

/**
 * Class Activator
 *
 * Handles plugin activation
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup
 */
class Activator implements Runnable {
	/**
	 * Run the activator
	 *
	 * FIXED: Completely refactored to ensure proper migration sequence.
	 * The key changes:
	 * 1. Don't set db_version to current plugin version immediately
	 * 2. Let migrations run and update db_version when complete
	 * 3. Better detection of fresh vs. legacy installs
	 *
	 * @since 1.0.0
	 * @return void
	 * @throws Throwable If activation fails.
	 */
	public function run(): void {
		try {
			// Get installation type and system settings.
			$installation_type = $this->determine_installation_type();
			$settings          = $this->get_or_create_system_settings( $installation_type );

			// Handle fresh installation setup if needed.
			if ( 'fresh' === $installation_type['type'] ) {
				$this->handle_fresh_installation( $settings );
			}

			// Create database tables.
			$this->initialize_data_stores();

			// Update activation metadata.
			$this->update_activation_metadata( $settings );

			// Add capabilities to user roles.
			$this->add_capabilities();

			/**
			 * Action that fires after plugin activation
			 *
			 * @param Settings_Model $system_settings   The system settings object.
			 * @param array          $installation_type Installation type information.
			 */
			do_action( 'WPPluginStarter_activated', $settings, $installation_type );

		} catch ( Throwable $e ) {
			$this->handle_activation_error( $e );
		}
	}

	/**
	 * Determine the type of installation (fresh, legacy, or update).
	 *
	 * @return array Installation type information.
	 */
	private function determine_installation_type(): array {
		$data_store = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$settings   = $data_store->get_settings_by_name( 'system' );

		// Existing installation with system settings.
		if ( $settings instanceof Settings_Model ) {
			return array(
				'type'                => 'update',
				'previous_version'    => $settings->get( 'db_version' ),
				'has_system_settings' => true,
			);
		}

		// Legacy installation (old version).

		$migrator = wp_plugin_starter_get_container()->get( Migrator::class );
		if ( $migrator->has_migration_options() ) {
			return array(
				'type'                => 'legacy_update',
				'previous_version'    => '$legacy_version',
				'has_system_settings' => false,
			);
		}

		// Fresh installation.
		return array(
			'type'                => 'fresh',
			'previous_version'    => '0.0.0',
			'has_system_settings' => false,
		);
	}

	/**
	 * Get existing or create new system settings.
	 *
	 * @param array $installation_type Installation type information.
	 *
	 * @return Settings_Model System settings object.
	 */
	private function get_or_create_system_settings( array $installation_type ): Settings_Model {
		$data_store = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$settings   = $data_store->get_settings_by_name( 'system' );

		// Return existing settings if available.
		if ( $settings instanceof Settings_Model ) {
			return $settings;
		}

		// Create new system settings.
		$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
		$settings->set_name( 'system' );

		// Set initial version for migrations to run.
		$initial_db_version = '0.0.0';

		// For legacy updates, preserve the old version.
		if ( 'legacy_update' === $installation_type['type'] ) {
			$initial_db_version = $installation_type['previous_version'];
		}

		// Set initial values.
		$settings->set_value(
			array(
				'db_version'            => $initial_db_version,
				'install_date'          => current_time( 'mysql' ),
				'is_active'             => true,
				'activated_version'     => WPPluginStarter_VERSION,
				'last_activation_time'  => time(),
				'migrated_from_version' => $installation_type['previous_version'],
				'installation_type'     => $installation_type['type'],
			)
		);

		// Set default values.
		$settings->set_default(
			array(
				'db_version'   => $initial_db_version,
				'install_date' => current_time( 'mysql' ),
				'is_active'    => true,
			)
		);

		$settings->save();

		return $settings;
	}

	/**
	 * Handle fresh installation specific tasks.
	 *
	 * @param Settings_Model $settings System settings object.
	 *
	 * @return void
	 */
	private function handle_fresh_installation( Settings_Model $settings ): void {
		$settings->update( 'first_activation', time() );
		$settings->update( 'is_fresh_install', true );
		$settings->save();

		/**
		 * Action fired during fresh installation.
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Model $settings System settings object.
		 */
		do_action( 'WPPluginStarter_fresh_install', $settings );
	}

	/**
	 * Update activation metadata without changing db_version.
	 *
	 * @param Settings_Model $settings System settings object.
	 *
	 * @return void
	 */
	private function update_activation_metadata( Settings_Model $settings ): void {
		$settings->update( 'is_active', true );
		$settings->update( 'activated_version', WPPluginStarter_VERSION );
		$settings->update( 'last_activation_time', time() );
		$settings->save();
	}

	/**
	 * Initialize all data stores to create necessary tables.
	 *
	 * @since 1.0.0
	 * @return void
	 * @throws Throwable If initialization fails.
	 */
	private function initialize_data_stores(): void {
		// Get all data stores from the container.
		$data_stores = wp_plugin_starter_get_container()->getByTag( 'data-store-service' );

		// Initialize each data store that has an initialize method.
		foreach ( $data_stores as $data_store ) {
			if ( method_exists( $data_store, 'initialize' ) ) {
				$data_store->initialize();
			}
		}

		/**
		 * Action after initializing all data stores during activation.
		 *
		 * @since 1.0.0
		 * @param array $data_stores Array of initialized data stores.
		 */
		do_action( 'WPPluginStarter_activation_data_stores_initialized', $data_stores );
	}

	/**
	 * Add capabilities to appropriate user roles.
	 *
	 * @return void
	 */
	private function add_capabilities(): void {
		// Add capabilities to administrator role.
		$admin_role = get_role( 'administrator' );
		if ( $admin_role ) {
			$admin_role->add_cap( 'manage_WPPluginStarter' );
			$admin_role->add_cap( 'edit_WPPluginStarter_settings' );
			$admin_role->add_cap( 'view_WPPluginStarter_reports' );
		}

		// Add capabilities to shop manager role.
		$shop_manager = get_role( 'shop_manager' );
		if ( $shop_manager ) {
			$shop_manager->add_cap( 'manage_WPPluginStarter' );
			$shop_manager->add_cap( 'edit_WPPluginStarter_settings' );
			$shop_manager->add_cap( 'view_WPPluginStarter_reports' );
		}

		/**
		 * Action fired after adding capabilities during activation.
		 *
		 * @since 1.0.0
		 */
		do_action( 'WPPluginStarter_activation_capabilities_added' );
	}

	/**
	 * Handle activation errors.
	 *
	 * @param Throwable $e Exception that occurred.
	 * @return void
	 */
	private function handle_activation_error( Throwable $e ): void {
		// Log error if logger is available.
		if ( function_exists( 'WPPluginStarter_logger' ) ) {
			WPPluginStarter_logger()->error(
				sprintf(
					'WPPluginStarter Activation Error: %s in %s on line %s',
					$e->getMessage(),
					$e->getFile(),
					$e->getLine()
				)
			);
		}

		// Show admin notice about activation failure.
		add_action(
			'admin_notices',
			function () use ( $e ) {
				echo '<div class="notice notice-error"><p>';
				echo '<strong>WP Plugin Starter Activation Failed:</strong> ';
				echo esc_html( $e->getMessage() );
				echo '</p></div>';
			}
		);
	}
}
