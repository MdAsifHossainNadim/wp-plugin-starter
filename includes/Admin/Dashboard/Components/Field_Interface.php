<?php
/**
 * Field Interface
 *
 * @since   1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Interface for form field components
 *
 * The Field_Interface defines a standard contract for all form field elements,
 * providing consistent methods for validation, sanitization, and rendering.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
interface Field_Interface {
	/**
	 * Get the field ID
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_id(): string;

	/**
	 * Set the field ID
	 *
	 * @since  1.0.0
	 * @param  string $id The field ID.
	 * @return static
	 */
	public function set_id( string $id );

	/**
	 * Get the field type
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_type(): string;

	/**
	 * Get the field value
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function get_value();

	/**
	 * Set the field value
	 *
	 * @since  1.0.0
	 * @param  mixed $value The field value.
	 * @return self
	 */
	public function set_value( $value );

	/**
	 * Get the field default value
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function get_default();

	/**
	 * Set the field default value
	 *
	 * @since  1.0.0
	 * @param  mixed $default The default value.
	 * @return self
	 */
	public function set_default( $default );

	/**
	 * Validate field data
	 *
	 * This method should validate whether the provided data is valid for this field.
	 *
	 * @since  1.0.0
	 * @param  mixed $data The data to validate.
	 * @return bool True if valid, false otherwise.
	 */
	public function data_validation( $data ): bool;

	/**
	 * Sanitize field data for storage
	 *
	 * This method should sanitize the provided data before it's stored.
	 *
	 * @since  1.0.0
	 * @param  mixed $data The data to sanitize.
	 * @return mixed The sanitized data.
	 */
	public function sanitize_element( $data );

	/**
	 * Escape field data for display
	 *
	 * This method should escape the provided data before it's displayed.
	 *
	 * @since  1.0.0
	 * @param  mixed $data The data to escape.
	 * @return mixed The escaped data.
	 */
	public function escape_element( $data );

	/**
	 * Populate field data for rendering
	 *
	 * This method should return an array representation of the field for rendering.
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function populate(): array;
}
