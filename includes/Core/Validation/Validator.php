<?php

namespace WPPluginStarter\Core\Validation;

/**
 * Validator Class
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Validation
 */
class Validator {
	/**
	 * Data to validate
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $data = array();

	/**
	 * Validation rules
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $rules = array();

	/**
	 * Validation errors
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $errors = array();

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param array $data  Data to validate
	 * @param array $rules Validation rules
	 */
	public function __construct( array $data = array(), array $rules = array() ) {
		$this->data  = $data;
		$this->rules = $rules;
	}

	/**
	 * Set data
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Data to validate
	 *
	 * @return self
	 */
	public function set_data( array $data ): Validator {
		$this->data = $data;

		return $this;
	}

	/**
	 * Set rules
	 *
	 * @since 1.0.0
	 *
	 * @param array $rules Validation rules
	 *
	 * @return self
	 */
	public function set_rules( array $rules ): Validator {
		$this->rules = $rules;

		return $this;
	}

	/**
	 * Validate data
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function validate(): bool {
		$this->errors = array();

		foreach ( $this->rules as $field => $rules_string ) {
			$field_rules = explode( '|', $rules_string );

			foreach ( $field_rules as $rule ) {
				$params = array();

				// Check if rule has parameters
				if ( strpos( $rule, ':' ) !== false ) {
					list( $rule, $param_string ) = explode( ':', $rule );
					$params                      = explode( ',', $param_string );
				}

				$method = 'validate_' . $rule;

				if ( method_exists( $this, $method ) ) {
					$result = $this->$method( $field, $params );

					if ( $result !== true ) {
						if ( ! isset( $this->errors[ $field ] ) ) {
							$this->errors[ $field ] = array();
						}

						$this->errors[ $field ][] = $result;
					}
				}
			}
		}

		return empty( $this->errors );
	}

	/**
	 * Get validation errors
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Get first error for a field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field Field name
	 *
	 * @return string|null
	 */
	public function get_first_error( string $field ): ?string {
		return isset( $this->errors[ $field ] ) && ! empty( $this->errors[ $field ] ) ? $this->errors[ $field ][0] : null;
	}

	/**
	 * Validate required field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_required( string $field, array $params = array() ) {
		if ( ! isset( $this->data[ $field ] ) || $this->data[ $field ] === '' || $this->data[ $field ] === null ) {
			return sprintf( __( 'The %s field is required.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate email field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_email( string $field, array $params = array() ) {
		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! is_email( $this->data[ $field ] ) ) {
			return sprintf( __( 'The %s field must be a valid email address.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate numeric field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_numeric( string $field, array $params = array() ) {
		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! is_numeric( $this->data[ $field ] ) ) {
			return sprintf( __( 'The %s field must be numeric.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate integer field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_integer( string $field, array $params = array() ) {
		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! filter_var( $this->data[ $field ], FILTER_VALIDATE_INT ) ) {
			return sprintf( __( 'The %s field must be an integer.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate minimum value
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_min( string $field, array $params = array() ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$min = (float) $params[0];

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && (float) $this->data[ $field ] < $min ) {
			return sprintf( __( 'The %1$s field must be at least %2$s.', 'wp-plugin-starter' ), $field, $min );
		}

		return true;
	}

	/**
	 * Validate maximum value
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_max( string $field, array $params = array() ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$max = (float) $params[0];

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && (float) $this->data[ $field ] > $max ) {
			return sprintf( __( 'The %1$s field must be at most %2$s.', 'wp-plugin-starter' ), $field, $max );
		}

		return true;
	}

	/**
	 * Validate minimum length
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_min_length( string $field, array $params = array() ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$min = (int) $params[0];

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && mb_strlen( $this->data[ $field ] ) < $min ) {
			return sprintf( __( 'The %1$s field must be at least %2$d characters.', 'wp-plugin-starter' ), $field, $min );
		}

		return true;
	}

	/**
	 * Validate maximum length
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_max_length( string $field, array $params = array() ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$max = (int) $params[0];

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && mb_strlen( $this->data[ $field ] ) > $max ) {
			return sprintf( __( 'The %1$s field must be at most %2$d characters.', 'wp-plugin-starter' ), $field, $max );
		}

		return true;
	}

	/**
	 * Validate URL field
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_url( string $field, array $params = array() ) {
		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! filter_var( $this->data[ $field ], FILTER_VALIDATE_URL ) ) {
			return sprintf( __( 'The %s field must be a valid URL.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate in list
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_in( string $field, array $params = array() ) {
		if ( empty( $params ) ) {
			return true;
		}

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! in_array( $this->data[ $field ], $params ) ) {
			return sprintf( __( 'The %1$s field must be one of: %2$s.', 'wp-plugin-starter' ), $field, implode( ', ', $params ) );
		}

		return true;
	}

	/**
	 * Validate not in list
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_not_in( string $field, array $params = array() ) {
		if ( empty( $params ) ) {
			return true;
		}

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && in_array( $this->data[ $field ], $params ) ) {
			return sprintf( __( 'The %1$s field must not be one of: %2$s.', 'wp-plugin-starter' ), $field, implode( ', ', $params ) );
		}

		return true;
	}

	/**
	 * Validate regex pattern
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_regex( string $field, array $params = array() ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$pattern = $params[0];

		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' && ! preg_match( $pattern, $this->data[ $field ] ) ) {
			return sprintf( __( 'The %s field format is invalid.', 'wp-plugin-starter' ), $field );
		}

		return true;
	}

	/**
	 * Validate date
	 *
	 * @since 1.0.0
	 *
	 * @param string $field  Field name
	 * @param array  $params Validation parameters
	 *
	 * @return bool|string
	 */
	protected function validate_date( string $field, array $params = array() ) {
		if ( isset( $this->data[ $field ] ) && $this->data[ $field ] !== '' ) {
			$format = ! empty( $params ) ? $params[0] : 'Y-m-d';
			$date   = \DateTime::createFromFormat( $format, $this->data[ $field ] );

			if ( ! $date || $date->format( $format ) !== $this->data[ $field ] ) {
				return sprintf( __( 'The %s field must be a valid date.', 'wp-plugin-starter' ), $field );
			}
		}

		return true;
	}
}
