<?php
/**
 * Abstract Migration Class
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup\Migrations
 */

namespace WPPluginStarter\Setup\Migrations;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Abstract class Abstract_Migration
 *
 * Provides base functionality for all migrations.
 *
 * @since 1.0.0
 */
abstract class Abstract_Migration implements Migration_Interface, Hookable {

	/**
	 * Migration version.
	 *
	 * @var string
	 */
	protected string $version;

	/**
	 * Migration ID.
	 *
	 * @var string
	 */
	protected string $id;

	/**
	 * Migration type.
	 *
	 * @var string
	 */
	protected string $type;

	/**
	 * Migration description.
	 *
	 * @var string
	 */
	protected string $description;

	/**
	 * Migration priority.
	 *
	 * @var int
	 */
	protected int $priority = 10;

	/**
	 * Constructor.
	 *
	 * @param string $version     Migration version.
	 * @param string $id          Migration identifier.
	 * @param string $type        Migration type (e.g., 'settings', 'database', 'data').
	 * @param string $description Brief description of what this migration does.
	 * @param int    $priority    Migration priority (lower numbers run first).
	 */
	public function __construct( string $version, string $id, string $type = 'general', string $description = '', int $priority = 10 ) {
		$this->version     = $version;
		$this->id          = $id;
		$this->type        = $type;
		$this->description = $description;
		$this->priority    = $priority;
	}

	/**
	 * Register hooks with WordPress
	 *
	 * This method registers the migration with the global migration registry.
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		add_filter( 'WPPluginStarter_migrations', array( $this, 'register_migration' ) );
	}

	/**
	 * Register this migration with the collection of migrations.
	 *
	 * @param array $migrations Existing migrations array.
	 *
	 * @return array Updated migrations array.
	 */
	public function register_migration( array $migrations ): array {
		$migrations[] = $this;

		return $migrations;
	}

	/**
	 * Get the migration version.
	 *
	 * @return string Migration version.
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * Get the migration ID.
	 *
	 * @return string Migration ID.
	 */
	public function get_id(): string {
		return $this->id;
	}

	/**
	 * Get the full migration name.
	 *
	 * Returns a unique identifier that includes version and ID to prevent duplicate names.
	 *
	 * @return string Full migration name.
	 */
	public function get_full_name(): string {
		return "v{$this->get_version()}_{$this->get_id()}";
	}

	/**
	 * Get the migration type.
	 *
	 * @return string Migration type.
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * Get the migration description.
	 *
	 * @return string Migration description.
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Get the migration priority.
	 *
	 * @return int Migration priority.
	 */
	public function get_priority(): int {
		return $this->priority;
	}

	/**
	 * Log error.
	 *
	 * @param \Throwable $exception Exception that occurred.
	 *
	 * @return void
	 */
	protected function log_error( \Throwable $exception ): void {
		if ( function_exists( 'WPPluginStarter_logger' ) ) {
			WPPluginStarter_logger()->error(
				sprintf(
					'WPPluginStarter Migration Error (%s): %s in %s on line %s',
					$this->id,
					$exception->getMessage(),
					$exception->getFile(),
					$exception->getLine()
				)
			);
		}
	}
}
