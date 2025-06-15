<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Page Class.
 *
 * Represents a page component in the admin dashboard.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Page extends Settings_Element {

	/**
	 * SettingsModel Element type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Element type.
	 */
	protected string $type = 'page';

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
		 * Filter page data validation
		 *
		 * @since 1.0.0
		 * @param bool $valid Whether the data is valid
		 * @param mixed $data The data being validated
		 * @param Page $this Page instance
		 */
		return apply_filters( 'WPPluginStarter_page_data_validation', is_array( $data ), $data, $this );
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return array|string
	 */
	public function sanitize_element( $data ) {
		/**
		 * Filter page data before sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to sanitize
		 * @param Page $this Page instance
		 */
		$data = apply_filters( 'WPPluginStarter_page_before_sanitize', $data, $this );

		$sanitized = wp_unslash( $data );

		/**
		 * Filter page data after sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $sanitized The sanitized data
		 * @param mixed $data Original data before sanitization
		 * @param Page $this Page instance
		 */
		return apply_filters( 'WPPluginStarter_page_after_sanitize', $sanitized, $data, $this );
	}

	/**
	 * Escape data for display.
	 *
	 * @since 1.0.0
	 * @param array $data Data for display.
	 *
	 * @return array
	 */
	public function escape_element( $data ): array {
		/**
		 * Filter page data before escaping
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to escape
		 * @param Page $this Page instance
		 */
		$data = apply_filters( 'WPPluginStarter_page_before_escape', $data, $this );

		/**
		 * Filter page data after escaping
		 *
		 * @since 1.0.0
		 * @param array $data The escaped data
		 * @param Page $this Page instance
		 */
		return apply_filters( 'WPPluginStarter_page_after_escape', $data, $this );
	}

	/**
	 * Populate the page object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before page population
		 *
		 * @since 1.0.0
		 * @param Page $this Page instance
		 */
		do_action( 'WPPluginStarter_before_page_populate', $this );

		$data = parent::populate();

		/**
		 * Filter populated page data
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Page $this Page instance
		 */
		$data = apply_filters( 'WPPluginStarter_page_populated_data', $data, $this );

		/**
		 * Action after page population
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Page $this Page instance
		 */
		do_action( 'WPPluginStarter_after_page_populate', $data, $this );

		return $data;
	}
}
