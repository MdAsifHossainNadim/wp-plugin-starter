<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

use Exception;

/**
 * SettingsModel Element Class.
 *
 * Base class for all settings UI elements.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
abstract class Settings_Element {

	/**
	 * ID of the features element.
	 *
	 * @since 1.0.0
	 * @var string $id ID.
	 */
	protected string $id = '';

	/**
	 * Title of the  features element.
	 *
	 * @since 1.0.0
	 * @var string $title Title.
	 */
	protected string $title = '';

	/**
	 * Description of the features element.
	 *
	 * @since 1.0.0
	 * @var string $description Description.
	 */
	protected string $description = '';

	/**
	 * The Icon class for the features element.
	 *
	 * @since 1.0.0
	 * @var string $icon Icon.
	 */
	protected string $icon = '';

	/**
	 * SettingsModel Element Value.
	 *
	 * @since 1.0.0
	 * @var mixed $value Value.
	 */
	protected $value;

	/**
	 * Is the element support children?
	 *
	 * @since 1.0.0
	 * @var bool $support_children Has children.
	 */
	protected bool $support_children = true;

	/**
	 * Children SettingsModel elements.
	 *
	 * @since 1.0.0
	 * @var Settings_Element[] $children Children Elements.
	 */
	protected array $children = array();

	/**
	 * The features dependencies.
	 *
	 * @since 1.0.0
	 * @var array $dependencies Dependencies.
	 */
	protected array $dependencies = array();

	/**
	 * SettingsModel Type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Type.
	 */
	protected string $type = '';

	/**
	 * The key for generating dynamic hook.
	 *
	 * @since 1.0.0
	 * @var string $hook_key Hook Key.
	 */
	public string $hook_key = '';

	/**
	 * The key for generating dynamic Dependency.
	 *
	 * @since 1.0.0
	 * @var string $dependency_key Dependency Key.
	 */
	public string $dependency_key = '';

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 * @param string $id ID of the features.
	 */
	public function __construct( string $id ) {
		/**
		 * Action before settings element initialization
		 *
		 * @since 1.0.0
		 * @param string $id Element ID
		 */
		do_action( 'WPPluginStarter_before_settings_element_init', $id );

		$this->id = $id;

		/**
		 * Action after settings element initialization
		 *
		 * @since 1.0.0
		 * @param Settings_Element $this Element instance
		 * @param string $id Element ID
		 */
		do_action( 'WPPluginStarter_after_settings_element_init', $this, $id );
	}

	/**
	 * Get the ID of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_id(): string {
		/**
		 * Filter element ID
		 *
		 * @since 1.0.0
		 * @param string $id Element ID
		 * @param Settings_Element $this Element instance
		 */
		return apply_filters( 'WPPluginStarter_settings_element_id', $this->id, $this );
	}

	/**
	 * Set the ID of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 *
	 * @return Settings_Element
	 */
	public function set_id( string $id ): Settings_Element {
		$this->id = $id;

		return $this;
	}

	/**
	 * Get the Type of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_type(): string {
		/**
		 * Filter element type
		 *
		 * @since 1.0.0
		 * @param string $type Element type
		 * @param Settings_Element $this Element instance
		 */
		return apply_filters( 'WPPluginStarter_settings_element_type', $this->type, $this );
	}

	/**
	 * Get the Title of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_title(): string {
		/**
		 * Filter element title
		 *
		 * @since 1.0.0
		 * @param string $title Element title
		 * @param Settings_Element $this Element instance
		 */
		return apply_filters( 'WPPluginStarter_settings_element_title', $this->title, $this );
	}

	/**
	 * Set the Title of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @param string $title Title.
	 *
	 * @return Settings_Element
	 */
	public function set_title( string $title ): Settings_Element {
		/**
		 * Filter element title before setting
		 *
		 * @since 1.0.0
		 * @param string $title Element title
		 * @param Settings_Element $this Element instance
		 */
		$this->title = apply_filters( 'WPPluginStarter_settings_element_set_title', $title, $this );

		return $this;
	}

	/**
	 * Get the Description of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Set the Description of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @param string $description The description.
	 *
	 * @return Settings_Element
	 */
	public function set_description( string $description ): Settings_Element {
		$this->description = $description;

		return $this;
	}

	/**
	 * Get the icon of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_icon(): string {
		return $this->icon;
	}

	/**
	 * Set the icon of the SettingsModel element.
	 *
	 * @since 1.0.0
	 * @param string $icon Icon class.
	 *
	 * @return Settings_Element
	 */
	public function set_icon( string $icon ): Settings_Element {
		$this->icon = $icon;

		return $this;
	}

	/**
	 * Get Hook Key.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_hook_key(): string {
		return $this->hook_key;
	}

	/**
	 * Set Hook key.
	 *
	 * @since 1.0.0
	 * @param string $hook_key Key.
	 *
	 * @return Settings_Element
	 */
	public function set_hook_key( string $hook_key ): Settings_Element {
		$this->hook_key = $hook_key;

		return $this;
	}

	/**
	 * Get Dependencies key.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_dependency_key(): string {
		return $this->dependency_key;
	}

	/**
	 * Set Dependencies key.
	 *
	 * @since 1.0.0
	 * @param string $dependency_key The dependency_key.
	 *
	 * @return Settings_Element
	 */
	public function set_dependency_key( string $dependency_key ): Settings_Element {
		$this->dependency_key = $dependency_key;

		return $this;
	}

	/**
	 * Get the Value for the element.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_value() {
		$value = $this->value;

		if ( ! isset( $value ) && method_exists( $this, 'get_default' ) ) {
			$value = $this->get_default();
		}

		return $this->sanitize_element( $value );
	}

	/**
	 * Set The element value.
	 *
	 * @since 1.0.0
	 * @param mixed $value The element value.
	 *
	 * @return Settings_Element
	 */
	public function set_value( $value ): Settings_Element {
		$this->value = $this->sanitize_element( $value );

		if ( $this->is_support_children() ) {
			$children = array();
			foreach ( $this->get_children() as $child ) {
				if ( isset( $value[ $child->get_id() ] ) ) {
					$child->set_value( $value[ $child->get_id() ] );
				}
				$children[ $child->get_id() ] = $child;
			}
			$this->set_children( $children );
		}

		return $this;
	}

	/**
	 * Check is the features element support children.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_support_children(): bool {
		return $this->support_children;
	}

	/**
	 * Get the children of the features elements.
	 *
	 * @since 1.0.0
	 * @return Settings_Element[]
	 */
	public function get_children(): array {
		$children = array();

		/**
		 * An array containing the filtered list of child elements or objects.
		 * This variable is intended to store a subset of children
		 * after applying specific filtering criteria.
		 *
		 * @since 4.0.0
		 *
		 * @param array            $children
		 * @param Settings_Element $this
		 */
		$filtered_children = apply_filters( $this->get_hook_key() . '_children', $this->children, $this ); // phpcs:ignore.

		foreach ( $filtered_children as $child ) {
			$child->set_hook_key( $this->get_hook_key() . '_' . $child->get_id() );
			$child->set_dependency_key( trim( $this->get_dependency_key() . '.' . $child->get_id(), '. ' ) );
			$children[ $child->get_id() ] = $child;
		}

		return $children;
	}

	/**
	 * Set Children.
	 *
	 * @since 1.0.0
	 * @param array $children Children.
	 *
	 * @return Settings_Element
	 * @throws Exception If children are not attachable.
	 */
	public function set_children( array $children ): Settings_Element {
		if ( ! $this->is_support_children() ) {
			// translators: %s is SettingsModel element type.
			throw new Exception( sprintf( esc_html__( 'SettingsModel %s Does not support adding any children.', 'wp-plugin-starter' ), esc_html( $this->get_type() ) ) );
		}

		$this->children = $children;

		return $this;
	}

	/**
	 * Get element dependency array.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_dependencies(): array {
		$dependency_key = $this->get_dependency_key();

		return array_map(
			static function ( $dependency ) use ( $dependency_key ) {
				$dependency['self'] = $dependency_key;
				return $dependency;
			},
			$this->dependencies
		);
	}

	/**
	 * Set Dependencies.
	 *
	 * @since 1.0.0
	 * @param array $dependencies Dependencies.
	 *
	 * @return Settings_Element
	 */
	public function set_dependencies( array $dependencies ): Settings_Element {
		$this->dependencies = $dependencies;

		return $this;
	}

	/**
	 * Add Dependencies to the SettingsElement.
	 *
	 * @since 1.0.0
	 * @param string $key       Dot (.) seperated key string.
	 * @param mixed  $value     Value for comparison.
	 * @param bool   $to_self   Value for comparison.
	 * @param string $attribute Attributes for operation (Optional).
	 * @param string $effect    The effect of dependency (Optional).
	 * @param string $comparison Value comparison operator (Optional).
	 *
	 * @return Settings_Element
	 */
	public function add_dependency( string $key, $value, bool $to_self = true, string $attribute = 'display', string $effect = 'hide', string $comparison = '=' ): Settings_Element {
		$this->dependencies[] = array(
			'key'        => $key,
			'value'      => $value,
			'to_self'    => $to_self,
			'attribute'  => $attribute,
			'effect'     => $effect,
			'comparison' => $comparison,
		);

		return $this;
	}

	/**
	 * Add child element.
	 *
	 * @since 1.0.0
	 * @param Settings_Element $element SettingsModel element.
	 *
	 * @return $this
	 * @throws Exception If child element is not attachable.
	 */
	public function add( Settings_Element $element ): Settings_Element {
		if ( ! $this->is_support_children() ) {
			// translators: %s is SettingsModel element type.
			throw new \RuntimeException( sprintf( esc_html__( 'SettingsModel %s Does not support adding any children.', 'wp-plugin-starter' ), esc_html( $this->get_type() ) ) );
		}

		$this->children[ $element->get_id() ] = $element;

		return $this;
	}

	/**
	 * Detach any child element.
	 *
	 * @since 1.0.0
	 * @param Settings_Element $element Child Element.
	 *
	 * @return Settings_Element
	 * @throws Exception If Element is not removable.
	 */
	public function remove( Settings_Element $element ): Settings_Element {
		if ( ! $this->is_support_children() ) {
			// translators: %s is SettingsModel element type.
			throw new \RuntimeException( sprintf( esc_html__( 'SettingsModel %s Does not support removing any children.', 'wp-plugin-starter' ), esc_html( $this->get_type() ) ) );
		}

		$this->children = array_filter(
			$this->children,
			static function ( $child ) use ( $element ) {
				return $child !== $element;
			}
		);
		return $this;
	}

	/**
	 * Validate the Data.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data to store.
	 *
	 * @return bool
	 */
	public function validate( $data ): bool {
		$validity = $this->data_validation( $data );

		if ( $validity && $this->is_support_children() ) {
			foreach ( $this->get_children() as $child ) {
				if ( ! isset( $data[ $child->get_id() ] ) || ! $child->validate( $data[ $child->get_id() ] ) ) {
					$validity = false;
					break;
				}
			}
		}

		return $validity;
	}

	/**
	 * Populate The features array.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		$children = array();
		if ( $this->is_support_children() ) {
			foreach ( $this->get_children() as $child ) {
				$children[] = $child->populate();
			}
		}

		$populated_data = array(
			'id'             => $this->get_id(),
			'type'           => $this->get_type(),
			'title'          => $this->get_title(),
			'icon'           => $this->get_icon(),
			'display'        => true, // to manage element display action from dependencies.
			'hook_key'       => $this->get_hook_key(),
			'children'       => $children,
			'description'    => $this->get_description(),
			'dependency_key' => $this->get_dependency_key(),
			'dependencies'   => $this->get_dependencies(),
		);

		/**
		 * Filters the populated data for a features element.
		 * This filter allows modification of the complete array of populated data
		 * before it's returned from the populate method. The data includes all element
		 * properties such as ID, type, title, children, dependencies, etc.
		 *
		 * @since 4.0.0
		 *
		 * @param array            $populated_data The array containing all element data.
		 * @param Settings_Element $this           The current features element instance.
		 *
		 * @return array Modified populated data.
		 */
		return apply_filters( $this->get_hook_key() . '_populate', $populated_data, $this );
	}

	/**
	 * Sanitize the features element.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return array|string
	 */
	public function sanitize( $data ) {
		$data = $this->sanitize_element( $data );

		if ( $this->is_support_children() ) {
			foreach ( $this->get_children() as $child ) {
				$child_id = $child->get_id();

				$data[ $child_id ] = $child->sanitize( $data[ $child_id ] );
			}
		}
		return $data;
	}

	/**
	 * Data Validation condition.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	abstract public function data_validation( $data ): bool;

	/**
	 * Sanitize data for storage.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return mixed
	 */
	abstract public function sanitize_element( $data );

	/**
	 * Escape Output for usage.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return mixed
	 */
	abstract public function escape_element( $data );
}
