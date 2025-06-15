<?php
/**
 * Migration Interface
 *
 * @since 1.0.0
 * @package WPPluginStarter\Setup\Migrations
 */

namespace WPPluginStarter\Setup\Migrations;

/**
 * Interface Migration_Interface
 *
 * @since 1.0.0
 */
interface Migration_Interface {

	/**
	 * Run the migration.
	 *
	 * @param string $version Version being migrated to.
	 *
	 * @return void
	 * @throws \Exception If migration fails.
	 */
	public function migrate( string $version ): void;

	/**
	 * Get the migration version.
	 *
	 * @return string Migration version.
	 */
	public function get_version(): string;

	/**
	 * Get the migration ID.
	 *
	 * @return string Migration ID.
	 */
	public function get_id(): string;

	/**
	 * Get the full migration name.
	 *
	 * Returns a unique identifier that includes version and ID.
	 *
	 * @return string Full migration name.
	 */
	public function get_full_name(): string;

	/**
	 * Get the migration type.
	 *
	 * @return string Migration type.
	 */
	public function get_type(): string;

	/**
	 * Get the migration description.
	 *
	 * @return string Migration description.
	 */
	public function get_description(): string;

	/**
	 * Get the migration priority.
	 *
	 * @return int Migration priority.
	 */
	public function get_priority(): int;
}
