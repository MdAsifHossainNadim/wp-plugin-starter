<?php
/**
 * MultiSelect Field.
 *
 * @since      4.0.0
 * @subpackage Admin\Dashboard\Components\Fields
 * @package WPPluginStarter
 */

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

/**
 * MultiSelect Field.
 *
 * @since 4.0.0
 */
class Multi_Select extends Select {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'multiselect';

	/**
	 * Data validation.
	 *
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	public function data_validation( $data ): bool {
		return isset( $data ) && is_array( $data );
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @param mixed $data Data for sanitization.
	 *
	 * @return array
	 */
	public function sanitize_element( $data ) {
		if ( ! is_array( $data ) ) {
			return array();
		}

		return array_map( 'sanitize_text_field', wp_unslash( $data ) );
	}

	/**
	 * Escape data for display.
	 *
	 * @param mixed $data Data for display.
	 *
	 * @return array
	 */
	public function escape_element( $data ): array {
		if ( ! is_array( $data ) ) {
			return array();
		}

		return array_map( 'esc_attr', $data );
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data = parent::populate();

		// Ensure default is an array.
		if ( ! is_array( $data['default'] ) ) {
			$data['default'] = $data['default'] ? array( $data['default'] ) : array();
		}

		return $data;
	}
}
