<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Section Class.
 *
 * Represents a section component in the admin dashboard.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Section extends Settings_Element {

	/**
	 * SettingsModel Element type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Element type.
	 */
	protected string $type = 'section';

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
		 * Filter section data validation
		 *
		 * @since 1.0.0
		 * @param bool $valid Whether the data is valid
		 * @param mixed $data The data being validated
		 * @param Section $this Section instance
		 */
		return apply_filters( 'WPPluginStarter_section_data_validation', is_array( $data ), $data, $this );
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
		 * Filter section data before sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to sanitize
		 * @param Section $this Section instance
		 */
		$data = apply_filters( 'WPPluginStarter_section_before_sanitize', $data, $this );

		$sanitized = wp_unslash( $data );

		/**
		 * Filter section data after sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $sanitized The sanitized data
		 * @param mixed $data Original data before sanitization
		 * @param Section $this Section instance
		 */
		return apply_filters( 'WPPluginStarter_section_after_sanitize', $sanitized, $data, $this );
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
		 * Filter section data before escaping
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to escape
		 * @param Section $this Section instance
		 */
		$data = apply_filters( 'WPPluginStarter_section_before_escape', $data, $this );

		/**
		 * Filter section data after escaping
		 *
		 * @since 1.0.0
		 * @param array $data The escaped data
		 * @param Section $this Section instance
		 */
		return apply_filters( 'WPPluginStarter_section_after_escape', $data, $this );
	}

	/**
	 * Populate the section object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before section population
		 *
		 * @since 1.0.0
		 * @param Section $this Section instance
		 */
		do_action( 'WPPluginStarter_before_section_populate', $this );

		$data = parent::populate();

		/**
		 * Filter populated section data
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Section $this Section instance
		 */
		$data = apply_filters( 'WPPluginStarter_section_populated_data', $data, $this );

		/**
		 * Action after section population
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Section $this Section instance
		 */
		do_action( 'WPPluginStarter_after_section_populate', $data, $this );

		return $data;
	}
}
