<?php

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

use WPPluginStarter\Admin\Dashboard\Components\Field;
use WPPluginStarter\Admin\Dashboard\Components\Settings_Element;

/**
 * CheckboxGroup Field.
 */
class Multi_Check extends Field {

	/**
	 * Default Value.
	 *
	 * @var array $default Default.
	 */
	protected $default = array();

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'multicheck';

	/**
	 * Options.
	 *
	 * @var array $options Options.
	 */
	protected $options = array();

	/**
	 * Constructor.
	 *
	 * @param string $id Input ID.
	 */
	public function __construct( string $id ) {
		$this->id = $id;
	}

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_options(): array {
		return $this->options;
	}

	/**
	 * Set options.
	 *
	 * @param array $options Options.
	 *
	 * @return Settings_Element
	 */
	public function set_options( array $options ): Settings_Element {
		$this->options = $options;

		return $this;
	}

	/**
	 * Add an option.
	 *
	 * @param string      $option option to Display.
	 * @param string|null $value value for the checkbox option. Default is null.
	 *
	 * @return Multi_Check
	 */
	public function add_option( string $option, string $value ): Multi_Check {
		$this->options[] = array(
			'value' => $value,
			'title' => $option,
		);

		return $this;
	}

	/**
	 * Get Default.
	 *
	 * @return array
	 */
	public function get_default(): array {
		return $this->default;
	}

	/**
	 * Set Default.
	 *
	 * @param array $default Default value.
	 *
	 * @return Multi_Check
	 */
	public function set_default( array $default ): Multi_Check {
		$this->default = $default;

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
		return isset( $data ) && is_array( $data );
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data            = parent::populate();
		$data['value']   = $this->get_value();
		$data['default'] = $this->get_default();
		$data['options'] = $this->get_options();

		return $data;
	}
}
