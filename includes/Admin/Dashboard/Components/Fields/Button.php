<?php

namespace WPPluginStarter\Admin\Dashboard\Components\Fields;

use WPPluginStarter\Admin\Dashboard\Components\Field;

/**
 * Button Field.
 *
 * Provides a clickable button element.
 */
class Button extends Field {

	/**
	 * Input Type.
	 *
	 * @var string $input_type Input Type.
	 */
	protected string $input_type = 'button';

	/**
	 * Button text.
	 *
	 * @var string $button_text Text to display on the button.
	 */
	protected string $button_text = 'Button';

	/**
	 * Button type.
	 *
	 * @var string $button_type Type of button (primary, secondary, etc.).
	 */
	protected string $button_type = 'primary';

	/**
	 * Button size.
	 *
	 * @var string $button_size Size of button (small, medium, large).
	 */
	protected string $button_size = 'medium';

	/**
	 * Is the button disabled.
	 *
	 * @var bool $is_disabled Whether the button is disabled.
	 */
	protected bool $is_disabled = false;

	/**
	 * Button action.
	 *
	 * @var string $action Action to perform on click (ajax, link, form).
	 */
	protected string $action = 'ajax';

	/**
	 * Ajax action name (for ajax buttons).
	 *
	 * @var string $ajax_action Name of the WordPress ajax action.
	 */
	protected string $ajax_action = '';

	/**
	 * URL (for link buttons).
	 *
	 * @var string $url URL to navigate to when clicked.
	 */
	protected string $url = '';

	/**
	 * Additional CSS classes.
	 *
	 * @var string $classes Additional CSS classes for the button.
	 */
	protected string $classes = '';

	/**
	 * Icon class.
	 *
	 * @var string $icon Icon class to display with button.
	 */
	protected string $icon = '';

	/**
	 * Confirmation message.
	 *
	 * @var string $confirm_message Message to display for confirmation.
	 */
	protected string $confirm_message = '';

	/**
	 * Constructor.
	 *
	 * @param string $id Input ID.
	 */
	public function __construct( string $id ) {
		$this->id = $id;
	}

	/**
	 * Get button text.
	 *
	 * @return string
	 */
	public function get_button_text(): string {
		return $this->button_text;
	}

	/**
	 * Set button text.
	 *
	 * @param string $text Text to display on the button.
	 *
	 * @return Button
	 */
	public function set_button_text( string $text ): Button {
		$this->button_text = $text;

		return $this;
	}

	/**
	 * Get button type.
	 *
	 * @return string
	 */
	public function get_button_type(): string {
		return $this->button_type;
	}

	/**
	 * Set button type.
	 *
	 * @param string $type Type of button (primary, secondary, etc.).
	 *
	 * @return Button
	 */
	public function set_button_type( string $type ): Button {
		$this->button_type = $type;

		return $this;
	}

	/**
	 * Get button size.
	 *
	 * @return string
	 */
	public function get_button_size(): string {
		return $this->button_size;
	}

	/**
	 * Set button size.
	 *
	 * @param string $size Size of button (small, medium, large).
	 *
	 * @return Button
	 */
	public function set_button_size( string $size ): Button {
		$this->button_size = $size;

		return $this;
	}

	/**
	 * Is button disabled.
	 *
	 * @return bool
	 */
	public function is_disabled(): bool {
		return $this->is_disabled;
	}

	/**
	 * Set button disabled state.
	 *
	 * @param bool $disabled Whether the button is disabled.
	 *
	 * @return Button
	 */
	public function set_disabled( bool $disabled ): Button {
		$this->is_disabled = $disabled;

		return $this;
	}

	/**
	 * Get button action.
	 *
	 * @return string
	 */
	public function get_action(): string {
		return $this->action;
	}

	/**
	 * Set button action.
	 *
	 * @param string $action Action to perform on click (ajax, link, form).
	 *
	 * @return Button
	 */
	public function set_action( string $action ): Button {
		$this->action = $action;

		return $this;
	}

	/**
	 * Get ajax action name.
	 *
	 * @return string
	 */
	public function get_ajax_action(): string {
		return $this->ajax_action;
	}

	/**
	 * Set ajax action name.
	 *
	 * @param string $ajax_action Name of the WordPress ajax action.
	 *
	 * @return Button
	 */
	public function set_ajax_action( string $ajax_action ): Button {
		$this->ajax_action = $ajax_action;
		$this->action      = 'ajax';

		return $this;
	}

	/**
	 * Get button URL.
	 *
	 * @return string
	 */
	public function get_url(): string {
		return $this->url;
	}

	/**
	 * Set button URL.
	 *
	 * @param string $url URL to navigate to when clicked.
	 *
	 * @return Button
	 */
	public function set_url( string $url ): Button {
		$this->url    = $url;
		$this->action = 'link';

		return $this;
	}

	/**
	 * Get additional CSS classes.
	 *
	 * @return string
	 */
	public function get_classes(): string {
		return $this->classes;
	}

	/**
	 * Set additional CSS classes.
	 *
	 * @param string $classes Additional CSS classes for the button.
	 *
	 * @return Button
	 */
	public function set_classes( string $classes ): Button {
		$this->classes = $classes;

		return $this;
	}

	/**
	 * Get icon class.
	 *
	 * @return string
	 */
	public function get_icon(): string {
		return $this->icon;
	}

	/**
	 * Set icon class.
	 *
	 * @param string $icon Icon class to display with button.
	 *
	 * @return Button
	 */
	public function set_icon( string $icon ): Button {
		$this->icon = $icon;

		return $this;
	}

	/**
	 * Get confirmation message.
	 *
	 * @return string
	 */
	public function get_confirm_message(): string {
		return $this->confirm_message;
	}

	/**
	 * Set confirmation message.
	 *
	 * @param string $message Message to display for confirmation.
	 *
	 * @return Button
	 */
	public function set_confirm_message( string $message ): Button {
		$this->confirm_message = $message;

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
		return true; // Buttons don't store data
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @param mixed $data Data for sanitization.
	 *
	 * @return string
	 */
	public function sanitize_element( $data ) {
		return '';
	}

	/**
	 * Escape data for display.
	 *
	 * @param string $data Data for display.
	 *
	 * @return string
	 */
	public function escape_element( $data ): string {
		return '';
	}

	/**
	 * Populate features array.
	 *
	 * @return array
	 */
	public function populate(): array {
		$data                    = parent::populate();
		$data['button_text']     = $this->get_button_text();
		$data['button_type']     = $this->get_button_type();
		$data['button_size']     = $this->get_button_size();
		$data['disabled']        = $this->is_disabled();
		$data['action']          = $this->get_action();
		$data['ajax_action']     = $this->get_ajax_action();
		$data['url']             = $this->get_url();
		$data['classes']         = $this->get_classes();
		$data['icon']            = $this->get_icon();
		$data['confirm_message'] = $this->get_confirm_message();

		return $data;
	}
}
