<?php
/**
 * Number field component
 *
 * @package WPPluginStarter\Admin\Dashboard\Components\Fields
 */

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

/**
 * Test Field.
 */
class Number extends Text {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'number';

	/**
	 * Minimum.
	 *
	 * @var float $minimum Minimum.
	 */
	protected float $minimum = 0;

	/**
	 * Maximum.
	 *
	 * @var float Maximum.
	 */
	protected float $maximum = 2000;

	/**
	 * Increment number.
	 *
	 * @var float $step Increment number.
	 */
	protected float $step = 0.1;

	/**
	 * Display type (number or range)
	 *
	 * @var string $display Display type.
	 */
	protected string $display = 'number';

	/**
	 * Get minimum value.
	 *
	 * @return float
	 */
	public function get_minimum(): float {
		return $this->minimum;
	}

	/**
	 * Set minimum value.
	 *
	 * @param float $minimum The minimum value.
	 *
	 * @return Number
	 */
	public function set_minimum( float $minimum ): Number {
		$this->minimum = $minimum;

		return $this;
	}

	/**
	 * Get minimum value.
	 *
	 * @return float
	 */
	public function get_maximum(): ?float {
		return $this->maximum;
	}

	/**
	 * Set maximum value.
	 *
	 * @param float $maximum Value.
	 *
	 * @return Number
	 */
	public function set_maximum( float $maximum ): Number {
		$this->maximum = $maximum;

		return $this;
	}

	/**
	 * Get step value.
	 *
	 * @return float
	 */
	public function get_step(): ?float {
		return $this->step;
	}

	/**
	 * Set step value.
	 *
	 * @param float $step Value.
	 *
	 * @return Number
	 */
	public function set_step( float $step ): Number {
		$this->step = $step;

		return $this;
	}

	/**
	 * Get display type.
	 *
	 * @return string
	 */
	public function get_display(): string {
		return $this->display;
	}

	/**
	 * Set display type.
	 *
	 * @param string $display Display type (number or range).
	 *
	 * @return Number
	 */
	public function set_display( string $display ): Number {
		$this->display = in_array( $display, array( 'number', 'range' ), true ) ? $display : 'number';

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
		return isset( $data )
			&& is_numeric( $data )
			&& ( ! isset( $this->minimum ) || $data >= $this->minimum )
			&& ( ! isset( $this->maximum ) || $data <= $this->maximum );
	}


	/**
	 * Sanitize data for storage.
	 *
	 * @param mixed $data Data for sanitization.
	 *
	 * @return mixed
	 */
	public function sanitize_element( $data ) {
		return (float) wc_format_decimal( parent::sanitize_element( $data ) );
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data                = parent::populate();
		$data['default']     = $this->get_default();
		$data['placeholder'] = $this->get_placeholder();
		$data['minimum']     = $this->get_minimum();
		$data['maximum']     = $this->get_maximum();
		$data['step']        = $this->get_step();
		$data['display']     = $this->get_display();

		return $data;
	}
}
