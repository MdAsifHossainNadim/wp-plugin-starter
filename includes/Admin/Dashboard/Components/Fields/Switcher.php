<?php

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

/**
 * Switcher Field.
 *
 * Custom field that provides a toggle switch for boolean values.
 */
class Switcher extends Radio {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'switch';

	/**
	 * Options.
	 *
	 * @var array $options Options.
	 */
	protected array $states = array();

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function get_states(): array {
		return $this->states;
	}

	/**
	 * Set active value.
	 *
	 * @param string $label Enable state label.
	 * @param string $value Enable state value.
	 *
	 * @return Switcher
	 */
	public function set_enable_state( string $label, string $value ): Switcher {
		$this->states['enable'] = array(
			'value' => $value,
			'title' => $label,
		);

		return $this;
	}

	/**
	 * Get active value.
	 *
	 * @return array
	 */
	public function get_enable_state(): array {
		return $this->states['enable'];
	}

	public function set_disable_state( string $label, string $value ): Switcher {
		$this->states['disable'] = array(
			'value' => $value,
			'title' => $label,
		);

		return $this;
	}

	/**
	 * Get active value.
	 *
	 * @return array
	 */

	public function get_disable_state(): array {
		return $this->states['disable'];
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data                  = parent::populate();
		$data['default']       = $this->get_default();
		$data['enable_state']  = $this->get_enable_state();
		$data['disable_state'] = $this->get_disable_state();

		return $data;
	}
}
