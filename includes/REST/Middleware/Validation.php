<?php

namespace WPPluginStarter\REST\Middleware;

use WPPluginStarter\REST\Api_Response;
use WP_Error;
use WP_REST_Request;

/**
 * Validation Middleware
 *
 * Handles request validation for REST API
 *
 * @package WPPluginStarter\REST\Middleware
 */
class Validation {
	/**
	 * Validate request data
	 *
	 * @param WP_REST_Request $request  Full details about the request
	 * @param array           $rules    Validation rules
	 * @param array           $messages Custom validation messages
	 *
	 * @return true|WP_Error True if validation passes, WP_Error otherwise
	 */
	public function validate( WP_REST_Request $request, array $rules, array $messages = array() ) {
		/**
		 * Action fired before request validation begins.
		 *
		 * This hook allows developers to perform pre-validation tasks,
		 * such as request sanitization, logging, or custom validation preparation.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request  The REST request object being validated.
		 * @param array           $rules    The validation rules to be applied.
		 * @param array           $messages Custom validation messages.
		 */
		do_action( 'WPPluginStarter_before_rest_validation', $request, $rules, $messages );

		/**
		 * Filter to override validation entirely.
		 *
		 * Return a non-null value to bypass the default validation logic.
		 * Useful for implementing custom validation systems or testing scenarios.
		 *
		 * @since 1.0.0
		 *
		 * @param null|true|WP_Error $pre_validation Pre-validation result. Return non-null to override.
		 * @param WP_REST_Request    $request        The REST request object.
		 * @param array              $rules          The validation rules.
		 * @param array              $messages       Custom validation messages.
		 *
		 * @return null|true|WP_Error Validation result or null to continue with default logic.
		 */
		$pre_validation = apply_filters( 'WPPluginStarter_pre_rest_validation', null, $request, $rules, $messages );
		if ( null !== $pre_validation ) {
			return $pre_validation;
		}

		/**
		 * Filter the validation rules before processing.
		 *
		 * Allows dynamic modification of validation rules based on context,
		 * user role, or other request characteristics.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $rules    The validation rules to be filtered.
		 * @param WP_REST_Request $request  The REST request object.
		 * @param array           $messages Custom validation messages.
		 *
		 * @return array The filtered validation rules.
		 */
		$rules = apply_filters( 'WPPluginStarter_rest_validation_rules', $rules, $request, $messages );

		/**
		 * Filter the custom validation messages before processing.
		 *
		 * Allows localization or customization of validation error messages
		 * based on user preferences or context.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $messages The custom validation messages to be filtered.
		 * @param array           $rules    The validation rules.
		 * @param WP_REST_Request $request  The REST request object.
		 *
		 * @return array The filtered validation messages.
		 */
		$messages = apply_filters( 'WPPluginStarter_rest_validation_messages', $messages, $rules, $request );

		$errors = array();
		$data   = $this->get_request_data( $request );

		/**
		 * Filter the request data before validation.
		 *
		 * Allows preprocessing of request data, including sanitization,
		 * normalization, or addition of computed fields.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data     The request data to be filtered.
		 * @param WP_REST_Request $request  The REST request object.
		 * @param array           $rules    The validation rules.
		 * @param array           $messages Custom validation messages.
		 *
		 * @return array The filtered request data.
		 */
		$data = apply_filters( 'WPPluginStarter_rest_validation_data', $data, $request, $rules, $messages );

		/**
		 * Action fired when validation processing begins.
		 *
		 * Useful for validation-specific logging or analytics tracking.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data     The request data being validated.
		 * @param array           $rules    The validation rules.
		 * @param WP_REST_Request $request  The REST request object.
		 */
		do_action( 'WPPluginStarter_rest_validation_started', $data, $rules, $request );

		foreach ( $rules as $field => $field_rules ) {
			/**
			 * Action fired before validating a specific field.
			 *
			 * Allows field-specific preprocessing or logging of validation attempts.
			 *
			 * @since 1.0.0
			 *
			 * @param string          $field       The field name being validated.
			 * @param mixed           $field_rules The validation rules for this field.
			 * @param mixed           $field_value The field value (if present).
			 * @param WP_REST_Request $request     The REST request object.
			 */
			do_action( 'WPPluginStarter_before_rest_field_validation', $field, $field_rules, $data[ $field ] ?? null, $request );

			/**
			 * Filter the field rules before validation.
			 *
			 * Allows dynamic modification of validation rules for specific fields
			 * based on context or other field values.
			 *
			 * @since 1.0.0
			 *
			 * @param mixed           $field_rules The field validation rules to be filtered.
			 * @param string          $field       The field name.
			 * @param array           $data        The complete request data.
			 * @param WP_REST_Request $request     The REST request object.
			 *
			 * @return mixed The filtered field validation rules.
			 */
			$field_rules = apply_filters( 'WPPluginStarter_rest_field_validation_rules', $field_rules, $field, $data, $request );

			$field_rules = is_string( $field_rules ) ? explode( '|', $field_rules ) : $field_rules;

			foreach ( $field_rules as $rule ) {
				/**
				 * Action fired before applying a specific validation rule.
				 *
				 * Useful for rule-specific logging or custom rule preprocessing.
				 *
				 * @since 1.0.0
				 *
				 * @param string          $rule        The validation rule being applied.
				 * @param string          $field       The field name.
				 * @param mixed           $field_value The field value.
				 * @param WP_REST_Request $request     The REST request object.
				 */
				do_action( 'WPPluginStarter_before_rest_rule_validation', $rule, $field, $data[ $field ] ?? null, $request );

				// Handle rule with parameters
				$params = array();
				if ( is_string( $rule ) && strpos( $rule, ':' ) !== false ) {
					[ $rule_name, $rule_params ] = explode( ':', $rule, 2 );
					$params                          = explode( ',', $rule_params );
					$rule                            = $rule_name;
				}

				/**
				 * Filter the validation rule parameters.
				 *
				 * Allows dynamic modification of rule parameters based on context
				 * or other validation criteria.
				 *
				 * @since 1.0.0
				 *
				 * @param array           $params      The rule parameters to be filtered.
				 * @param string          $rule        The validation rule name.
				 * @param string          $field       The field name.
				 * @param mixed           $field_value The field value.
				 * @param WP_REST_Request $request     The REST request object.
				 *
				 * @return array The filtered rule parameters.
				 */
				$params = apply_filters( 'WPPluginStarter_rest_rule_validation_params', $params, $rule, $field, $data[ $field ] ?? null, $request );

				$method = 'validate_' . $rule;
				if ( method_exists( $this, $method ) ) {
					$field_value = $data[ $field ] ?? null;

					/**
					 * Filter the field value before rule validation.
					 *
					 * Allows preprocessing of field values for specific validation rules.
					 *
					 * @since 1.0.0
					 *
					 * @param mixed           $field_value The field value to be filtered.
					 * @param string          $rule        The validation rule.
					 * @param string          $field       The field name.
					 * @param array           $params      The rule parameters.
					 * @param WP_REST_Request $request     The REST request object.
					 *
					 * @return mixed The filtered field value.
					 */
					$field_value = apply_filters( 'WPPluginStarter_rest_field_value_for_validation', $field_value, $rule, $field, $params, $request );

					$result = $this->$method( $field, $field_value, $params, $data );

					/**
					 * Filter the validation rule result.
					 *
					 * Allows modification of validation results, including custom
					 * error handling or conditional validation logic.
					 *
					 * @since 1.0.0
					 *
					 * @param true|string     $result      The validation result.
					 * @param string          $rule        The validation rule.
					 * @param string          $field       The field name.
					 * @param mixed           $field_value The field value.
					 * @param array           $params      The rule parameters.
					 * @param WP_REST_Request $request     The REST request object.
					 *
					 * @return true|string The filtered validation result.
					 */
					$result = apply_filters( 'WPPluginStarter_rest_rule_validation_result', $result, $rule, $field, $field_value, $params, $request );

					if ( true !== $result ) {
						// Use custom message if provided
						$message_key = $field . '.' . $rule;
						$error_message = $messages[ $message_key ] ?? $result;

						/**
						 * Filter the validation error message for a specific field rule.
						 *
						 * Allows customization of error messages including localization
						 * and context-specific messaging.
						 *
						 * @since 1.0.0
						 *
						 * @param string          $error_message The error message to be filtered.
						 * @param string          $field         The field name.
						 * @param string          $rule          The validation rule.
						 * @param mixed           $field_value   The field value.
						 * @param array           $params        The rule parameters.
						 * @param WP_REST_Request $request       The REST request object.
						 *
						 * @return string The filtered error message.
						 */
						$error_message = apply_filters( 'WPPluginStarter_rest_validation_error_message', $error_message, $field, $rule, $field_value, $params, $request );

						$errors[ $field ] = $error_message;

						/**
						 * Action fired when a validation rule fails.
						 *
						 * Useful for validation failure analytics, user experience
						 * tracking, or triggering corrective actions.
						 *
						 * @since 1.0.0
						 *
						 * @param string          $field         The field that failed validation.
						 * @param string          $rule          The validation rule that failed.
						 * @param string          $error_message The error message.
						 * @param mixed           $field_value   The field value.
						 * @param WP_REST_Request $request       The REST request object.
						 */
						do_action( 'WPPluginStarter_rest_validation_rule_failed', $field, $rule, $error_message, $field_value, $request );

						break; // Stop validation for this field
					}

					/**
					 * Action fired when a validation rule passes.
					 *
					 * Useful for validation success tracking or progressive validation workflows.
					 *
					 * @since 1.0.0
					 *
					 * @param string          $field       The field that passed validation.
					 * @param string          $rule        The validation rule that passed.
					 * @param mixed           $field_value The field value.
					 * @param WP_REST_Request $request     The REST request object.
					 */
					do_action( 'WPPluginStarter_rest_validation_rule_passed', $field, $rule, $field_value, $request );
				} else {
					/**
					 * Action fired when a validation rule method doesn't exist.
					 *
					 * Useful for debugging validation configuration or implementing
					 * dynamic validation rule registration.
					 *
					 * @since 1.0.0
					 *
					 * @param string          $rule    The validation rule that doesn't exist.
					 * @param string          $method  The expected method name.
					 * @param string          $field   The field name.
					 * @param WP_REST_Request $request The REST request object.
					 */
					do_action( 'WPPluginStarter_rest_validation_rule_not_found', $rule, $method, $field, $request );
				}
			}

			/**
			 * Action fired after validating a specific field.
			 *
			 * Allows field-specific post-processing or logging of validation results.
			 *
			 * @since 1.0.0
			 *
			 * @param string          $field       The field name that was validated.
			 * @param bool            $field_valid Whether the field passed validation.
			 * @param mixed           $field_value The field value.
			 * @param WP_REST_Request $request     The REST request object.
			 */
			do_action( 'WPPluginStarter_after_rest_field_validation', $field, ! isset( $errors[ $field ] ), $data[ $field ] ?? null, $request );
		}

		/**
		 * Filter the complete validation errors before final processing.
		 *
		 * Allows global modification of validation errors, including error
		 * aggregation, prioritization, or formatting changes.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $errors   The validation errors to be filtered.
		 * @param array           $data     The request data.
		 * @param array           $rules    The validation rules.
		 * @param WP_REST_Request $request  The REST request object.
		 *
		 * @return array The filtered validation errors.
		 */
		$errors = apply_filters( 'WPPluginStarter_rest_validation_errors', $errors, $data, $rules, $request );

		if ( ! empty( $errors ) ) {
			/**
			 * Action fired when validation fails with errors.
			 *
			 * Perfect for validation failure logging, user experience analytics,
			 * or triggering validation improvement workflows.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $errors  The validation errors.
			 * @param array           $data    The request data.
			 * @param array           $rules   The validation rules.
			 * @param WP_REST_Request $request The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_validation_failed', $errors, $data, $rules, $request );

			/**
			 * Filter the validation error response.
			 *
			 * Allows customization of the validation error response format
			 * and structure before it's returned to the client.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error_response The validation error response.
			 * @param array           $errors         The validation errors.
			 * @param array           $data           The request data.
			 * @param WP_REST_Request $request        The REST request object.
			 *
			 * @return WP_Error The filtered validation error response.
			 */
			return apply_filters( 'WPPluginStarter_rest_validation_error_response', Api_Response::validation_error( $errors ), $errors, $data, $request );
		}

		/**
		 * Action fired when validation passes successfully.
		 *
		 * Perfect for validation success logging, analytics, or triggering
		 * post-validation workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data    The validated request data.
		 * @param array           $rules   The validation rules that passed.
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_rest_validation_passed', $data, $rules, $request );

		/**
		 * Filter the successful validation result.
		 *
		 * Allows final modification of the validation success state.
		 *
		 * @since 1.0.0
		 *
		 * @param true            $success The validation success state.
		 * @param array           $data    The validated request data.
		 * @param array           $rules   The validation rules.
		 * @param WP_REST_Request $request The REST request object.
		 *
		 * @return true The filtered validation result.
		 */
		return apply_filters( 'WPPluginStarter_rest_validation_success', true, $data, $rules, $request );
	}

	/**
	 * Get request data from various sources
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return array Combined request data
	 */
	protected function get_request_data( WP_REST_Request $request ): array {
		/**
		 * Action fired before extracting request data.
		 *
		 * Useful for request preprocessing or logging of data extraction attempts.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_before_rest_data_extraction', $request );

		$data = array();

		// URL parameters
		$url_params = $request->get_url_params();
		if ( ! empty( $url_params ) ) {
			/**
			 * Filter URL parameters before merging with request data.
			 *
			 * Allows preprocessing of URL parameters, including sanitization
			 * or transformation of parameter values.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $url_params The URL parameters to be filtered.
			 * @param WP_REST_Request $request    The REST request object.
			 *
			 * @return array The filtered URL parameters.
			 */
			$url_params = apply_filters( 'WPPluginStarter_rest_url_params', $url_params, $request );
			$data       = array_merge( $data, $url_params );
		}

		// Query parameters
		$query_params = $request->get_query_params();
		if ( ! empty( $query_params ) ) {
			/**
			 * Filter query parameters before merging with request data.
			 *
			 * Allows preprocessing of query parameters, including sanitization,
			 * type conversion, or filtering of sensitive parameters.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $query_params The query parameters to be filtered.
			 * @param WP_REST_Request $request      The REST request object.
			 *
			 * @return array The filtered query parameters.
			 */
			$query_params = apply_filters( 'WPPluginStarter_rest_query_params', $query_params, $request );
			$data         = array_merge( $data, $query_params );
		}

		// Body parameters
		$body_params = $request->get_body_params();
		if ( ! empty( $body_params ) ) {
			/**
			 * Filter body parameters before merging with request data.
			 *
			 * Allows preprocessing of body parameters, including sanitization,
			 * structure validation, or transformation of complex data types.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $body_params The body parameters to be filtered.
			 * @param WP_REST_Request $request     The REST request object.
			 *
			 * @return array The filtered body parameters.
			 */
			$body_params = apply_filters( 'WPPluginStarter_rest_body_params', $body_params, $request );
			$data        = array_merge( $data, $body_params );
		}

		// JSON parameters
		$json_params = $request->get_json_params();
		if ( ! empty( $json_params ) ) {
			/**
			 * Filter JSON parameters before merging with request data.
			 *
			 * Allows preprocessing of JSON parameters, including validation
			 * of JSON structure or transformation of nested objects.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $json_params The JSON parameters to be filtered.
			 * @param WP_REST_Request $request     The REST request object.
			 *
			 * @return array The filtered JSON parameters.
			 */
			$json_params = apply_filters( 'WPPluginStarter_rest_json_params', $json_params, $request );
			$data        = array_merge( $data, $json_params );
		}

		/**
		 * Filter the combined request data after extraction.
		 *
		 * Final opportunity to modify the complete request data before validation,
		 * including global sanitization or addition of computed fields.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data    The combined request data to be filtered.
		 * @param WP_REST_Request $request The REST request object.
		 *
		 * @return array The filtered combined request data.
		 */
		$data = apply_filters( 'WPPluginStarter_rest_combined_request_data', $data, $request );

		/**
		 * Action fired after extracting request data.
		 *
		 * Useful for data extraction analytics or logging of processed request data.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data    The extracted request data.
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_after_rest_data_extraction', $data, $request );

		return $data;
	}

	/**
	 * Validate required field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_required( string $field, $value, array $params, array $data ) {
		if ( null === $value || '' === $value ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field is required.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate string field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_string( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! is_string( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a string.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate numeric field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_numeric( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! is_numeric( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a number.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate integer field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_integer( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! filter_var( $value, FILTER_VALIDATE_INT, array( 'flags' => FILTER_NULL_ON_FAILURE ) ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be an integer.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate boolean field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_boolean( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! is_bool( $value ) && 0 !== $value && 1 !== $value && '0' !== $value && '1' !== $value ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be true or false.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate email field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_email( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! is_email( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a valid email address.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate URL field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_url( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a valid URL.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate minimum length/value
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_min( string $field, $value, array $params, array $data ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$min = (int) $params[0];

		if ( is_string( $value ) && mb_strlen( $value ) < $min ) {
			return sprintf(
			/* translators: 1: field name, 2: minimum length */
				__( 'The %1$s field must be at least %2$d characters.', 'wp-plugin-starter' ),
				$field,
				$min
			);
		}

		if ( is_numeric( $value ) && $value < $min ) {
			return sprintf(
			/* translators: 1: field name, 2: minimum value */
				__( 'The %1$s field must be at least %2$d.', 'wp-plugin-starter' ),
				$field,
				$min
			);
		}

		return true;
	}

	/**
	 * Validate maximum length/value
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_max( string $field, $value, array $params, array $data ) {
		if ( ! isset( $params[0] ) ) {
			return true;
		}

		$max = (int) $params[0];

		if ( is_string( $value ) && mb_strlen( $value ) > $max ) {
			return sprintf(
			/* translators: 1: field name, 2: maximum length */
				__( 'The %1$s field must not exceed %2$d characters.', 'wp-plugin-starter' ),
				$field,
				$max
			);
		}

		if ( is_numeric( $value ) && $value > $max ) {
			return sprintf(
			/* translators: 1: field name, 2: maximum value */
				__( 'The %1$s field must not exceed %2$d.', 'wp-plugin-starter' ),
				$field,
				$max
			);
		}

		return true;
	}

	/**
	 * Validate that a field is in a list of values
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_in( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! in_array( $value, $params, true ) ) {
			return sprintf(
			/* translators: 1: field name, 2: allowed values */
				__( 'The %1$s field must be one of: %2$s.', 'wp-plugin-starter' ),
				$field,
				implode( ', ', $params )
			);
		}

		return true;
	}

	/**
	 * Validate array field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_array( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! is_array( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be an array.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate date field
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_date( string $field, $value, array $params, array $data ) {
		if ( null === $value ) {
			return true;
		}

		$format = $params[0] ?? 'Y-m-d';
		$date   = \DateTime::createFromFormat( $format, $value );

		if ( ! $date || $date->format( $format ) !== $value ) {
			return sprintf(
			/* translators: 1: field name, 2: date format */
				__( 'The %1$s field must be a valid date in the format %2$s.', 'wp-plugin-starter' ),
				$field,
				$format
			);
		}

		return true;
	}

	/**
	 * Validate regex pattern
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_regex( string $field, $value, array $params, array $data ) {
		if ( null === $value || ! isset( $params[0] ) ) {
			return true;
		}

		$pattern = $params[0];
		if ( ! preg_match( $pattern, $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field format is invalid.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate alpha characters only
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_alpha( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! ctype_alpha( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field may only contain letters.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate alphanumeric characters only
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_alpha_num( string $field, $value, array $params, array $data ) {
		if ( null !== $value && ! ctype_alnum( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field may only contain letters and numbers.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate JSON string
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_json( string $field, $value, array $params, array $data ) {
		if ( null === $value ) {
			return true;
		}

		if ( ! is_string( $value ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a valid JSON string.', 'wp-plugin-starter' ),
				$field
			);
		}

		json_decode( $value );
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a valid JSON string.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate that field exists in another array/dataset
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_exists( string $field, $value, array $params, array $data ) {
		if ( null === $value || ! isset( $params[0] ) ) {
			return true;
		}

		$table_or_function = $params[0];
		$column            = $params[1] ?? 'id';

		// If it's a WordPress function, call it
		if ( function_exists( $table_or_function ) ) {
			$exists = $table_or_function( $value );
			if ( ! $exists ) {
				return sprintf(
				/* translators: %s: field name */
					__( 'The selected %s is invalid.', 'wp-plugin-starter' ),
					$field
				);
			}
		}

		return true;
	}

	/**
	 * Validate file upload
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_file( string $field, $value, array $params, array $data ) {
		if ( null === $value ) {
			return true;
		}

		if ( ! is_array( $value ) || ! isset( $value['tmp_name'] ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a file.', 'wp-plugin-starter' ),
				$field
			);
		}

		if ( ! is_uploaded_file( $value['tmp_name'] ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be a valid uploaded file.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate image file
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_image( string $field, $value, array $params, array $data ) {
		$file_validation = $this->validate_file( $field, $value, $params, $data );
		if ( true !== $file_validation ) {
			return $file_validation;
		}

		if ( null === $value ) {
			return true;
		}

		$image_info = getimagesize( $value['tmp_name'] );
		if ( false === $image_info ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s field must be an image.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate confirmed field (password confirmation)
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_confirmed( string $field, $value, array $params, array $data ) {
		$confirmation_field = $field . '_confirmation';

		if ( ! isset( $data[ $confirmation_field ] ) ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s confirmation field is required.', 'wp-plugin-starter' ),
				$field
			);
		}

		if ( $value !== $data[ $confirmation_field ] ) {
			return sprintf(
			/* translators: %s: field name */
				__( 'The %s confirmation does not match.', 'wp-plugin-starter' ),
				$field
			);
		}

		return true;
	}

	/**
	 * Validate unique value
	 *
	 * @param string $field  Field name
	 * @param mixed  $value  Field value
	 * @param array  $params Rule parameters
	 * @param array  $data   All request data
	 *
	 * @return true|string True if validation passes, error message otherwise
	 */
	protected function validate_unique( string $field, $value, array $params, array $data ) {
		if ( null === $value || ! isset( $params[0] ) ) {
			return true;
		}

		$table_or_function = $params[0];
		$column            = $params[1] ?? $field;
		$ignore_id         = $params[2] ?? null;

		// If it's a WordPress function, call it
		if ( function_exists( $table_or_function ) ) {
			$exists = $table_or_function( $value, $column, $ignore_id );
			if ( $exists ) {
				return sprintf(
				/* translators: %s: field name */
					__( 'The %s has already been taken.', 'wp-plugin-starter' ),
					$field
				);
			}
		}

		return true;
	}
}
