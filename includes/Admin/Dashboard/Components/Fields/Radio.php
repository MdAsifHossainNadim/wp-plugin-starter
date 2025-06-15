<?php

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

/**
 * Checkbox Field.
 */
class Radio extends Checkbox {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'radio';

	/**
	 * Data validation.
	 *
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	public function data_validation( $data ): bool {
		return isset( $data );
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data            = parent::populate();
		$data['default'] = $this->get_default();
		$data['options'] = $this->get_options();

		return $data;
	}
}
