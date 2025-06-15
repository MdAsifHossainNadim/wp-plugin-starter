<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

use WPPluginStarter\Admin\Dashboard\Components\Fields\Checkbox;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Currency;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Multi_Check;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Multi_Select;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Number;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Password;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Radio;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Radio_Box;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Select;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Switcher;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Tel;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Text;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Toggle;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Button;


/**
 * SettingsModel element Field.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Field extends Settings_Element implements Field_Interface {

	/**
	 * Is children Supported.
	 *
	 * @since 1.0.0
	 * @var bool $support_children Children support.
	 */
	protected bool $support_children = false;

	/**
	 * The Input Element Type.
	 *
	 * @since 1.0.0
	 * @var string $input_type The Input Element Type.
	 */
	protected string $input_type = 'text';

	/**
	 * The SettingsModel Element Type.
	 *
	 * @since 1.0.0
	 * @var string $type Type Field.
	 */
	protected string $type = 'field';

	/**
	 * Map for the Input type.
	 *
	 * @since 1.0.0
	 * @var string[] $field_map Map for the Input type.
	 */
	protected array $field_map = array(
		'text'        => Text::class,
		'number'      => Number::class,
		'checkbox'    => Checkbox::class,
		'select'      => Select::class,
		'multiselect' => Multi_Select::class,
		'radio'       => Radio::class,
		'tel'         => Tel::class,
		'password'    => Password::class,
		'radio_box'   => Radio_Box::class,
		'switch'      => Switcher::class,
		'multicheck'  => Multi_Check::class,
		'currency'    => Currency::class,
		'toggle'      => Toggle::class,
		'button'      => Button::class,
	);

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param string $id ID of the input field.
	 * @param string $type Type of the input field.
	 */
	public function __construct( string $id, string $type = 'text' ) {
		/**
		 * Action before field initialization
		 *
		 * @since 1.0.0
		 * @param string $id Field ID
		 * @param string $type Field type
		 */
		do_action( 'WPPluginStarter_before_field_init', $id, $type );

		parent::__construct( $id );
		$this->input_type = $type;

		/**
		 * Action after field initialization
		 *
		 * @since 1.0.0
		 * @param Field $this Field instance
		 * @param string $id Field ID
		 * @param string $type Field type
		 */
		do_action( 'WPPluginStarter_after_field_init', $this, $id, $type );
	}

	/**
	 * Get input field.
	 *
	 * @since 1.0.0
	 * @return Settings_Element
	 */
	public function get_input(): Settings_Element {
		/**
		 * Filter field map before getting input
		 *
		 * @since 1.0.0
		 * @param array $field_map Field type to class mapping
		 * @param string $id Field ID
		 * @param string $input_type Field type
		 */
		$this->field_map = apply_filters( 'WPPluginStarter_field_map', $this->field_map, $this->get_id(), $this->input_type );

		return $this->input_map( $this->get_id(), $this->input_type );
	}

	/**
	 * Populate The Page Object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before field population
		 *
		 * @since 1.0.0
		 * @param Field $this Field instance
		 */
		do_action( 'WPPluginStarter_before_field_populate', $this );

		$data            = parent::populate();
		$data['variant'] = $this->input_type;

		$value = $this->get_value();

		/**
		 * Filter field value before escaping and populating
		 *
		 * @since 1.0.0
		 * @param mixed $value Field value
		 * @param string $id Field ID
		 * @param string $input_type Field type
		 */
		$value = apply_filters( 'WPPluginStarter_field_value', $value, $this->get_id(), $this->input_type );

		$data['value'] = $this->escape_element( $value );

		/**
		 * Filter populated field data
		 *
		 * @since 1.0.0
		 * @param array $data Field data
		 * @param Field $this Field instance
		 */
		return apply_filters( 'WPPluginStarter_field_populated_data', $data, $this );
	}

	/**
	 * Map input type to field class
	 *
	 * @since 1.0.0
	 * @param string $id Field ID
	 * @param string $type Field type
	 *
	 * @return Settings_Element
	 */
	protected function input_map( string $id, string $type ): Settings_Element {
		/**
		 * Action before input mapping
		 *
		 * @since 1.0.0
		 * @param string $id Field ID
		 * @param string $type Field type
		 */
		do_action( 'WPPluginStarter_before_input_map', $id, $type );

		// Check if the type exists in the field map
		if ( ! isset( $this->field_map[ $type ] ) ) {
			$type = 'text'; // Default to text field if type not found
		}

		$class_name = $this->field_map[ $type ];

		/**
		 * Filter the field class name used for a specific field type
		 *
		 * @since 1.0.0
		 * @param string $class_name Field class name
		 * @param string $id Field ID
		 * @param string $type Field type
		 */
		$class_name = apply_filters( 'WPPluginStarter_field_class_name', $class_name, $id, $type );

		$field = new $class_name( $id );

		/**
		 * Action after input mapping
		 *
		 * @since 1.0.0
		 * @param Settings_Element $field Created field instance
		 * @param string $id Field ID
		 * @param string $type Field type
		 */
		do_action( 'WPPluginStarter_after_input_map', $field, $id, $type );

		return $field;
	}

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
	 * Sanitize data for storage.
	 *
	 * @param mixed $data Data for sanitization.
	 *
	 * @return array|float|string
	 */
	public function sanitize_element( $data ) {
		return wp_unslash( $data );
	}

	/**
	 * Escape data for display.
	 *
	 * @param mixed $data Data for display.
	 *
	 * @return mixed
	 */
	public function escape_element( $data ) {
		return $data;
	}

	/**
	 * Get the field default value
	 *
	 * @since  1.0.0
	 * @return mixed
	 */
	public function get_default() {
		return $this->default ?? '';
	}

	/**
	 * Set the field default value
	 *
	 * @since  1.0.0
	 * @param  mixed $default The default value.
	 * @return self
	 */
	public function set_default( $default ) {
		$this->default = $default;

		return $this;
	}
}
