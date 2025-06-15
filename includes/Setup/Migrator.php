<?php
/**
 * Migrator Class
 *
 * Handles database migrations and version management for the plugin.
 * This class manages all database updates between plugin versions.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup
 */

namespace WPPluginStarter\Setup;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Runnable;
use WPPluginStarter\Setup\Migrations\Migration_Interface;
use Throwable;

/**
 * Class Migrator
 *
 * @since 1.0.0
 */
class Migrator implements Runnable {

	/**
	 * Run migrations if needed.
	 *
	 * @return void
	 */
	public function run(): void {
		// Skip on ajax requests.
		if ( wp_doing_ajax() ) {
			return;
		}

		// Skip on heartbeat.
		if ( isset( $_POST['action'] ) && 'heartbeat' === $_POST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return;
		}

		// Skip if we're running a cron job.
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Run migrations if needed.
		if ( $this->needs_migration() ) {
			// Set a flag to prevent multiple migration attempts.
			set_transient( 'WPPluginStarter_migration_running', true, 5 * MINUTE_IN_SECONDS );

			try {
				$this->run_migrations();
			} catch ( Throwable $e ) {
				$this->log_migration_error( $e );
			}

			// Clear the flag.
			delete_transient( 'WPPluginStarter_migration_running' );
		}
	}

	/**
	 * Check if migrations are needed.
	 *
	 * @return bool True if migrations are needed, false otherwise.
	 */
	public function needs_migration(): bool {
		// If migration is already running, don't start another one.
		if ( get_transient( 'WPPluginStarter_migration_running' ) ) {
			return false;
		}

		// Check if old option data exists.
		if ( $this->has_migration_options() ) {
			return true;
		}

		return ! $this->has_migration_run( WPPluginStarter_VERSION );
	}

	/**
	 * Check if a migration is currently running.
	 *
	 * @return bool True if migration is running, false otherwise.
	 */
	public function is_migration_running(): bool {
		return (bool) get_transient( 'WPPluginStarter_migration_running' );
	}

	/**
	 * Check if migration options exist.
	 *
	 * @return bool True if migration options exist, false otherwise.
	 */
	public function has_migration_options(): bool {
		$all_options = wp_load_alloptions();

		// Check for old options.
		$old_options = array(
			'WPPluginStarter_version',
			'remove_vendor_checkbox',
			'set_default_seller_role_checkbox',
			'enable_dimension_restrictions',
			'enable_size_restrictions',
		);

		foreach ( $old_options as $option ) {
			if ( array_key_exists( $option, $all_options ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a specific migration has been run.
	 *
	 * @param string $version Migration version to check.
	 * @return bool True if migration has been run, false otherwise.
	 */
	public function has_migration_run( string $version ): bool {
		$data_store        = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$migration_history = $data_store->get_settings_by_name( 'migration_history' );

		if ( ! $migration_history instanceof Settings_Model ) {
			return false;
		}

		$history = $migration_history->get_value();
		return is_array( $history ) && in_array( $version, $history, true );
	}

	/**
	 * Mark a migration as completed.
	 *
	 * @param string $version Migration version to mark as completed.
	 * @return void
	 */
	protected function mark_migration_completed( string $version ): void {
		$data_store        = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$migration_history = $data_store->get_settings_by_name( 'migration_history' );

		if ( ! $migration_history instanceof Settings_Model ) {
			$migration_history = wp_plugin_starter_get_container()->get( Settings_Model::class );
			$migration_history->set_name( 'migration_history' );
			$migration_history->set_value( array() );
			$migration_history->set_default( array() );
		}

		$history = $migration_history->get_value();
		if ( ! is_array( $history ) ) {
			$history = array();
		}

		if ( ! in_array( $version, $history, true ) ) {
			$history[] = $version;
			$migration_history->set_value( $history );
			$migration_history->save();
		}
	}

	/**
	 * Get available migrations.
	 *
	 * Uses WordPress filter system to collect migrations from various sources.
	 *
	 * @since 1.0.0
	 * @return array Array of Migration_Interface objects
	 */
	public function get_migrations(): array {
		/**
		 * Filter the list of available migrations.
		 *
		 * @since 1.0.0
		 *
		 * @param array $migrations Array of Migration_Interface objects.
		 */
		$migrations = apply_filters( 'WPPluginStarter_migrations', array() );

		// Group migrations by version
		$by_version = array();
		foreach ( $migrations as $migration ) {
			if ( ! $migration instanceof Migration_Interface ) {
				continue;
			}

			$version = $migration->get_version();
			if ( ! isset( $by_version[ $version ] ) ) {
				$by_version[ $version ] = array();
			}

			$by_version[ $version ][] = $migration;
		}

		// Sort migrations by priority within each version
		foreach ( $by_version as $version => $version_migrations ) {
			usort(
				$version_migrations,
				static function ( $a, $b ) {
					return $a->get_priority() - $b->get_priority();
				}
			);

			$by_version[ $version ] = $version_migrations;
		}

		return $by_version;
	}

	/**
	 * Get ordered list of available migration versions.
	 *
	 * @since 1.0.0
	 * @return array Array of version strings in ascending order
	 */
	public function get_ordered_versions(): array {
		$versions = array_keys( $this->get_migrations() );
		usort( $versions, 'version_compare' );

		return $versions;
	}

	/**
	 * Get migrations for a specific version.
	 *
	 * @since 1.0.0
	 *
	 * @param string $version Version to get migrations for.
	 *
	 * @return array Array of Migration_Interface objects for the given version
	 */
	public function get_migrations_for_version( string $version ): array {
		$migrations = $this->get_migrations();

		return $migrations[ $version ] ?? array();
	}

	/**
	 * Run migrations.
	 *
	 * @return void
	 * @throws \Exception If migration fails.
	 */
	public function run_migrations(): void {
		// Track executed migrations.
		$executed_migrations = array();

		// Run all migrations that are newer than current version.
		foreach ( $this->get_ordered_versions() as $version ) {
			// Check if this specific migration has already been run.
			if ( $this->has_migration_run( $version ) ) {
				continue;
			}

			try {
				// Execute migration tasks for this version.
				$migrations = $this->get_migrations_for_version( $version );
				$this->run_migrations_for_version( $version, $migrations );

				// Mark this migration as completed.
				$this->mark_migration_completed( $version );

				$executed_migrations[] = $version;
			} catch ( Throwable $e ) {
				// Log error and stop migration.
				$message = sprintf( 'Migration to version %s failed: %s', $version, $e->getMessage() );
				throw new \Exception( esc_html( $message ), 0, $e );
			}
		}

		/**
		 * Action fired after migrations are complete.
		 *
		 * @since 1.0.0
		 * @param string $from_version Previous version.
		 * @param string $to_version Current version.
		 * @param array  $executed_migrations List of executed migrations.
		 */
		do_action( 'WPPluginStarter_after_migrations', WPPluginStarter_VERSION, $executed_migrations );
	}

	/**
	 * Run migrations for a specific version.
	 *
	 * @param string $version    Version.
	 * @param array  $migrations Migrations to run.
	 *
	 * @return void
	 * @throws \Exception If a migration fails.
	 */
	protected function run_migrations_for_version( string $version, array $migrations ): void {
		if ( empty( $migrations ) ) {
			return;
		}

		foreach ( $migrations as $migration ) {
			if ( $migration instanceof Migration_Interface ) {
				try {
					$migration->migrate( $version );
				} catch ( \Exception $e ) {
					throw $e; // Re-throw to stop migration.
				}
			}
		}
	}

	/**
	 * Log migration error.
	 *
	 * @param Throwable $exception Exception that occurred.
	 * @return void
	 */
	protected function log_migration_error( Throwable $exception ): void {
		if ( function_exists( 'WPPluginStarter_logger' ) ) {
			WPPluginStarter_logger()->error(
				sprintf(
					'WPPluginStarter Migration Error: %s in %s on line %s',
					$exception->getMessage(),
					$exception->getFile(),
					$exception->getLine()
				),
				array( 'exception' => $exception )
			);
		}
	}
}
