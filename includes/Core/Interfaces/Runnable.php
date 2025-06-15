<?php

/**
 * WP Plugin Starter - Core Interfaces
 *
 * This file contains the core interfaces for the WP Plugin Starter plugin.
 * These interfaces define the contracts for various components of the plugin,
 * ensuring a consistent and extensible architecture.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Interfaces
 */

namespace WPPluginStarter\Core\Interfaces;

/**
 * Runnable Interface
 *
 * Interface for classes that need to be run.
 * Classes implementing this interface should provide
 * a run method that performs their primary function.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Interfaces
 */
interface Runnable {

	/**
	 * Run the class functionality
	 *
	 * This method is called to execute the main functionality of the class.
	 * It should handle any setup steps and then perform the primary action.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function run(): void;
}
