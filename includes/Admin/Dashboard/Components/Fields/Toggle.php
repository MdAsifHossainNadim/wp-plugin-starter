<?php

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

/**
 * Toggle Field.
 *
 * Provides a simple toggle switch for boolean values.
 */
class Toggle extends Text {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'toggle';

	/**
	 * Default state of the toggle.
	 *
	 * @var bool $is_checked Default checked state.
	 */
	protected bool $is_checked = false;

	/**
	 * On text.
	 *
	 * @var string $on_text Text to display when toggle is on.
	 */
	protected string $on_text = 'On';

	/**
	 * Off text.
	 *
	 * @var string $off_text Text to display when toggle is off.
	 */
	protected string $off_text = 'Off';

	/**
	 * Get the default checked state.
	 *
	 * @return bool
	 */
	public function is_checked(): bool {
		return $this->is_checked;
	}

	/**
	 * Set the default checked state.
	 *
	 * @param bool $is_checked Whether the toggle is checked by default.
	 *
	 * @return Toggle
	 */
	public function set_checked( bool $is_checked ): Toggle {
		$this->is_checked = $is_checked;

		return $this;
	}

	/**
	 * Get on text.
	 *
	 * @return string
	 */
	public function get_on_text(): string {
		return $this->on_text;
	}

	/**
	 * Set on text.
	 *
	 * @param string $text Text to display when toggle is on.
	 *
	 * @return Toggle
	 */
	public function set_on_text( string $text ): Toggle {
		$this->on_text = $text;

		return $this;
	}

	/**
	 * Get off text.
	 *
	 * @return string
	 */
	public function get_off_text(): string {
		return $this->off_text;
	}

	/**
	 * Set off text.
	 *
	 * @param string $text Text to display when toggle is off.
	 *
	 * @return Toggle
	 */
	public function set_off_text( string $text ): Toggle {
		$this->off_text = $text;

		return $this;
	}

	/**
	 * Data validation.
	 *
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	public function data_validation( $data ): bool {
		return isset( $data ) && is_string( $data );
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @param mixed $data Data for sanitization.
	 *
	 * @return string
	 */
	public function sanitize_element( $data ) {
		return sanitize_text_field( parent::sanitize_element( $data ) );
	}

	/**
	 * Escape data for display.
	 *
	 * @param string $data Data for display.
	 *
	 * @return string
	 */
	public function escape_element( $data ): string {
		return esc_attr( $data );
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data             = parent::populate();
		$data['checked']  = $this->is_checked();
		$data['on_text']  = $this->get_on_text();
		$data['off_text'] = $this->get_off_text();

		return $data;
	}
}
