<?php

namespace WPPluginStarter\Features;

/**
 * Feature Interface
 *
 * This interface defines the contract for all feature implementations.
 * Features provide modular functionality to the plugin that can be
 * enabled or disabled independently.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Features
 */
interface Feature_Interface {

	/**
	 * Check if the feature is enabled
	 *
	 * Determines whether the feature should be active based on
	 * user settings or other conditions.
	 *
	 * @since 1.0.0
	 * @return bool True if the feature is enabled, false otherwise.
	 */
	public function is_enabled(): bool;

	/**
	 * Get feature name
	 *
	 * Returns the human-readable name of the feature for display
	 * in features screens and other UI elements.
	 *
	 * @since 1.0.0
	 * @return string The feature name.
	 */
	public function get_name(): string;

	/**
	 * Get feature description
	 *
	 * Returns a detailed description of what the feature does,
	 * for display in features screens and documentation.
	 *
	 * @since 1.0.0
	 * @return string The feature description.
	 */
	public function get_description(): string;

	/**
	 * Get feature slug
	 *
	 * Returns a unique identifier for the feature.
	 *
	 * @since 1.0.0
	 * @return string The feature slug.
	 */
	public function get_slug(): string;
}
