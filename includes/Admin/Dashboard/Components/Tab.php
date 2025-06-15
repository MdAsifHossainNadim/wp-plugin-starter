<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Tab Class.
 *
 * Represents a tab component in the admin dashboard.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Tab extends Settings_Element {

	/**
	 * SettingsModel Element type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Element type.
	 */
	protected string $type = 'tab';

	/**
	 * Data validation.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	public function data_validation( $data ): bool {
		/**
		 * Filter tab data validation
		 *
		 * @since 1.0.0
		 * @param bool $valid Whether the data is valid
		 * @param mixed $data The data being validated
		 * @param Tab $this Tab instance
		 */
		return apply_filters( 'WPPluginStarter_tab_data_validation', is_array( $data ), $data, $this );
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return mixed
	 */
	public function sanitize_element( $data ) {
		/**
		 * Filter tab data before sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to sanitize
		 * @param Tab $this Tab instance
		 */
		$data = apply_filters( 'WPPluginStarter_tab_before_sanitize', $data, $this );

		$sanitized = wp_unslash( $data );

		/**
		 * Filter tab data after sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $sanitized The sanitized data
		 * @param mixed $data Original data before sanitization
		 * @param Tab $this Tab instance
		 */
		return apply_filters( 'WPPluginStarter_tab_after_sanitize', $sanitized, $data, $this );
	}

	/**
	 * Escape data for display.
	 *
	 * @since 1.0.0
	 * @param array $data Data for display.
	 *
	 * @return array
	 */
	public function escape_element( $data ) {
		/**
		 * Filter tab data before escaping
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to escape
		 * @param Tab $this Tab instance
		 */
		$data = apply_filters( 'WPPluginStarter_tab_before_escape', $data, $this );

		/**
		 * Filter tab data after escaping
		 *
		 * @since 1.0.0
		 * @param array $data The escaped data
		 * @param Tab $this Tab instance
		 */
		return apply_filters( 'WPPluginStarter_tab_after_escape', $data, $this );
	}

	/**
	 * Populate the tab object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before tab population
		 *
		 * @since 1.0.0
		 * @param Tab $this Tab instance
		 */
		do_action( 'WPPluginStarter_before_tab_populate', $this );

		$data = parent::populate();

		/**
		 * Filter populated tab data
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Tab $this Tab instance
		 */
		$data = apply_filters( 'WPPluginStarter_tab_populated_data', $data, $this );

		/**
		 * Action after tab population
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Tab $this Tab instance
		 */
		do_action( 'WPPluginStarter_after_tab_populate', $data, $this );

		return $data;
	}
}
