<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Field Group Class.
 *
 * Represents a field group component in the admin dashboard.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Field_Group extends Settings_Element {

	/**
	 * SettingsModel Element type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Element type.
	 */
	protected string $type = 'fieldgroup';

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
		 * Filter field group data validation
		 *
		 * @since 1.0.0
		 * @param bool $valid Whether the data is valid
		 * @param mixed $data The data being validated
		 * @param Field_Group $this Field_Group instance
		 */
		return apply_filters( 'WPPluginStarter_field_group_data_validation', is_array( $data ), $data, $this );
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
		 * Filter field group data before sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to sanitize
		 * @param Field_Group $this Field_Group instance
		 */
		$data = apply_filters( 'WPPluginStarter_field_group_before_sanitize', $data, $this );

		$sanitized = wp_unslash( $data );

		/**
		 * Filter field group data after sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $sanitized The sanitized data
		 * @param mixed $data Original data before sanitization
		 * @param Field_Group $this Field_Group instance
		 */
		return apply_filters( 'WPPluginStarter_field_group_after_sanitize', $sanitized, $data, $this );
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
		 * Filter field group data before escaping
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to escape
		 * @param Field_Group $this Field_Group instance
		 */
		$data = apply_filters( 'WPPluginStarter_field_group_before_escape', $data, $this );

		/**
		 * Filter field group data after escaping
		 *
		 * @since 1.0.0
		 * @param array $data The escaped data
		 * @param Field_Group $this Field_Group instance
		 */
		return apply_filters( 'WPPluginStarter_field_group_after_escape', $data, $this );
	}

	/**
	 * Populate the field group object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before field group population
		 *
		 * @since 1.0.0
		 * @param Field_Group $this Field_Group instance
		 */
		do_action( 'WPPluginStarter_before_field_group_populate', $this );

		$data = parent::populate();

		/**
		 * Filter populated field group data
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Field_Group $this Field_Group instance
		 */
		$data = apply_filters( 'WPPluginStarter_field_group_populated_data', $data, $this );

		/**
		 * Action after field group population
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param Field_Group $this Field_Group instance
		 */
		do_action( 'WPPluginStarter_after_field_group_populate', $data, $this );

		return $data;
	}
}
