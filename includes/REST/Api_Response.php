<?php

namespace WPPluginStarter\REST;

use WPPluginStarter\Core\DI\Container_Exception;
use WPPluginStarter\Core\Exception\Base_Exception;
use WPPluginStarter\Core\Exception\Validation_Exception;
use Throwable;
use WP_Error;
use WP_REST_Response;

/**
 * API Response Helper
 *
 * Standardizes REST API responses throughout the plugin
 *
 * @package WPPluginStarter\REST
 */
class Api_Response {
	/**
	 * Create a success response
	 *
	 * @since 1.0.0
	 *
	 * @param mixed  $data    Response data
	 * @param string $message Success message
	 * @param int    $status  HTTP status code
	 * @param array  $headers Response headers
	 *
	 * @return WP_REST_Response
	 */
	public static function success( $data = array(), string $message = '', int $status = 200, array $headers = array() ): WP_REST_Response {
		/**
		 * Action fired before creating a success response.
		 *
		 * This hook allows developers to perform actions before a success response is created,
		 * such as logging, analytics tracking, or data modification.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $data    The response data that will be sent to the client.
		 * @param string $message The success message to be included in the response.
		 * @param int    $status  The HTTP status code for the response.
		 * @param array  $headers Additional headers to be sent with the response.
		 */
		do_action( 'WPPluginStarter_before_api_success_response', $data, $message, $status, $headers );

		/**
		 * Filter the success response data before processing.
		 *
		 * This filter allows developers to modify the response data before it's formatted
		 * into the final response structure. Useful for adding additional data, transforming
		 * existing data, or implementing data sanitization.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $data    The response data to be filtered.
		 * @param string $message The success message.
		 * @param int    $status  The HTTP status code.
		 * @param array  $headers The response headers.
		 *
		 * @return mixed The filtered response data.
		 */
		$data = apply_filters( 'WPPluginStarter_api_response_success_data', $data, $message, $status, $headers );

		/**
		 * Filter the success response message.
		 *
		 * Allows customization of success messages based on context, user role,
		 * or other criteria. Messages can be localized or personalized here.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The success message to be filtered.
		 * @param mixed  $data    The response data.
		 * @param int    $status  The HTTP status code.
		 * @param array  $headers The response headers.
		 *
		 * @return string The filtered success message.
		 */
		$message = apply_filters( 'WPPluginStarter_api_response_success_message', $message, $data, $status, $headers );

		/**
		 * Filter the success response HTTP status code.
		 *
		 * Allows modification of the HTTP status code based on specific conditions
		 * or business logic requirements.
		 *
		 * @since 1.0.0
		 *
		 * @param int    $status  The HTTP status code to be filtered.
		 * @param mixed  $data    The response data.
		 * @param string $message The success message.
		 * @param array  $headers The response headers.
		 *
		 * @return int The filtered HTTP status code.
		 */
		$status = apply_filters( 'WPPluginStarter_api_response_success_status', $status, $data, $message, $headers );

		/**
		 * Filter the success response headers.
		 *
		 * Enables addition of custom headers for security, caching, CORS,
		 * or other HTTP header requirements.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $headers The response headers to be filtered.
		 * @param mixed  $data    The response data.
		 * @param string $message The success message.
		 * @param int    $status  The HTTP status code.
		 *
		 * @return array The filtered response headers.
		 */
		$headers = apply_filters( 'WPPluginStarter_api_response_success_headers', $headers, $data, $message, $status );

		// Prepare the standardized response structure
		$response_data = array(
			'success' => true,
			'data'    => $data,
		);

		// Add message if provided
		if ( ! empty( $message ) ) {
			$response_data['message'] = $message;
		}

		/**
		 * Filter the complete success response data structure.
		 *
		 * This filter provides full control over the response structure before
		 * it's converted to a WP_REST_Response object. Allows for complete
		 * customization of the response format.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $response_data The complete response data structure.
		 * @param mixed  $data          The original response data.
		 * @param string $message       The success message.
		 * @param int    $status        The HTTP status code.
		 * @param array  $headers       The response headers.
		 *
		 * @return array The filtered response data structure.
		 */
		$response_data = apply_filters( 'WPPluginStarter_api_response_success_structure', $response_data, $data, $message, $status, $headers );

		$response = new WP_REST_Response( $response_data, $status, $headers );

		/**
		 * Filter the final success response object.
		 *
		 * This is the last opportunity to modify the response before it's sent
		 * to the client. Can be used for final validation, adding metadata,
		 * or implementing response transformations.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The REST response object.
		 * @param mixed            $data     The original response data.
		 * @param string           $message  The success message.
		 * @param int              $status   The HTTP status code.
		 * @param array            $headers  The response headers.
		 *
		 * @return WP_REST_Response The filtered response object.
		 */
		$response = apply_filters( 'WPPluginStarter_api_success_response', $response, $data, $message, $status, $headers );

		/**
		 * Action fired after creating a success response.
		 *
		 * Perfect for logging successful API calls, updating analytics,
		 * or triggering post-response actions like cache invalidation.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The final response object.
		 * @param mixed            $data     The original response data.
		 * @param string           $message  The success message.
		 * @param int              $status   The HTTP status code.
		 * @param array            $headers  The response headers.
		 */
		do_action(
			'WPPluginStarter_after_api_success_response',
			$response,
			$data,
			$message,
			$status,
			$headers
		);

		return $response;
	}

	/**
	 * Create an error response
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error message
	 * @param string $code    Error code
	 * @param int    $status  HTTP status code
	 * @param mixed  $data    Additional error data
	 *
	 * @return WP_Error
	 */
	public static function error( string $message, string $code = 'error', int $status = 400, $data = null ): WP_Error {
		/**
		 * Action fired before creating an error response.
		 *
		 * Useful for error logging, monitoring, alerting systems, or debugging.
		 * This hook fires before any filtering occurs.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The error message.
		 * @param string $code    The error code identifier.
		 * @param int    $status  The HTTP status code.
		 * @param mixed  $data    Additional error data.
		 */
		do_action(
			'WPPluginStarter_before_api_error_response',
			$message,
			$code,
			$status,
			$data
		);

		/**
		 * Filter the error message before processing.
		 *
		 * Allows customization of error messages for localization, user-specific
		 * messaging, or sanitization of sensitive information.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The error message to be filtered.
		 * @param string $code    The error code.
		 * @param int    $status  The HTTP status code.
		 * @param mixed  $data    Additional error data.
		 *
		 * @return string The filtered error message.
		 */
		$message = apply_filters( 'WPPluginStarter_api_response_error_message', $message, $code, $status, $data );

		/**
		 * Filter the error code.
		 *
		 * Enables standardization of error codes across the application or
		 * mapping internal codes to public-facing codes.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code    The error code to be filtered.
		 * @param string $message The error message.
		 * @param int    $status  The HTTP status code.
		 * @param mixed  $data    Additional error data.
		 *
		 * @return string The filtered error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_response_error_code', $code, $message, $status, $data );

		/**
		 * Filter the error HTTP status code.
		 *
		 * Allows adjustment of HTTP status codes based on error type,
		 * user permissions, or business requirements.
		 *
		 * @since 1.0.0
		 *
		 * @param int    $status  The HTTP status code to be filtered.
		 * @param string $message The error message.
		 * @param string $code    The error code.
		 * @param mixed  $data    Additional error data.
		 *
		 * @return int The filtered HTTP status code.
		 */
		$status = apply_filters( 'WPPluginStarter_api_response_error_status', $status, $message, $code, $data );

		/**
		 * Filter the additional error data.
		 *
		 * Useful for adding debugging information, context data, or
		 * sanitizing sensitive information from error responses.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed  $data    The additional error data to be filtered.
		 * @param string $message The error message.
		 * @param string $code    The error code.
		 * @param int    $status  The HTTP status code.
		 *
		 * @return mixed The filtered error data.
		 */
		$data = apply_filters(
			'WPPluginStarter_api_response_error_data',
			$data,
			$message,
			$code,
			$status
		);

		// Prepare error data structure
		$error_data = array( 'status' => $status );
		if ( null !== $data ) {
			$error_data['data'] = $data;
		}

		/**
		 * Filter the error data structure.
		 *
		 * Provides complete control over the error data structure that will
		 * be passed to the WP_Error object.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $error_data The error data structure.
		 * @param string $message    The error message.
		 * @param string $code       The error code.
		 * @param int    $status     The HTTP status code.
		 * @param mixed  $data       The original additional error data.
		 *
		 * @return array The filtered error data structure.
		 */
		$error_data = apply_filters( 'WPPluginStarter_api_response_error_structure', $error_data, $message, $code, $status, $data );

		$error = new WP_Error( $code, $message, $error_data );

		/**
		 * Filter the final error response object.
		 *
		 * Last chance to modify the WP_Error object before it's returned
		 * to the client or processed further.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error $error   The error response object.
		 * @param string   $message The error message.
		 * @param string   $code    The error code.
		 * @param int      $status  The HTTP status code.
		 * @param mixed    $data    Additional error data.
		 *
		 * @return WP_Error The filtered error response.
		 */
		$error = apply_filters( 'WPPluginStarter_api_error_response', $error, $message, $code, $status, $data );

		/**
		 * Action fired after creating an error response.
		 *
		 * Perfect for error logging, monitoring systems, or triggering
		 * error-specific workflows like notifications or recovery procedures.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error $error   The final error response object.
		 * @param string   $message The error message.
		 * @param string   $code    The error code.
		 * @param int      $status  The HTTP status code.
		 * @param mixed    $data    Additional error data.
		 */
		do_action(
			'WPPluginStarter_after_api_error_response',
			$error,
			$message,
			$code,
			$status,
			$data
		);

		return $error;
	}

	/**
	 * Create a validation error response
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $errors Validation errors
	 * @param string       $code   Error code
	 *
	 * @return WP_Error
	 */
	public static function validation_error( $errors, string $code = 'validation_failed' ): WP_Error {
		/**
		 * Action fired before creating a validation error response.
		 *
		 * Useful for tracking validation failures, implementing rate limiting
		 * based on validation errors, or debugging validation issues.
		 *
		 * @since 1.0.0
		 *
		 * @param array|string $errors The validation errors.
		 * @param string       $code   The error code.
		 */
		do_action( 'WPPluginStarter_before_api_validation_error', $errors, $code );

		/**
		 * Filter the validation errors before processing.
		 *
		 * Allows transformation of validation error formats, sanitization
		 * of error messages, or addition of context information.
		 *
		 * @since 1.0.0
		 *
		 * @param array|string $errors The validation errors to be filtered.
		 * @param string       $code   The error code.
		 *
		 * @return array|string The filtered validation errors.
		 */
		$errors = apply_filters( 'WPPluginStarter_api_validation_errors', $errors, $code );

		/**
		 * Filter the validation error code.
		 *
		 * Enables customization of validation error codes for consistency
		 * or integration with external error tracking systems.
		 *
		 * @since 1.0.0
		 *
		 * @param string       $code   The error code to be filtered.
		 * @param array|string $errors The validation errors.
		 *
		 * @return string The filtered validation error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_validation_error_code', $code, $errors );

		// Normalize errors to array format
		if ( is_string( $errors ) ) {
			$errors = array( 'general' => $errors );
		}

		/**
		 * Filter the validation error message.
		 *
		 * Allows customization of the main validation error message that
		 * summarizes the validation failure.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The validation error message.
		 * @param array  $errors  The detailed validation errors.
		 * @param string $code    The error code.
		 *
		 * @return string The filtered validation error message.
		 */
		$message = apply_filters(
			'WPPluginStarter_api_validation_error_message',
			__( 'Validation failed', 'wp-plugin-starter' ),
			$errors,
			$code
		);

		$error = self::error(
			$message,
			$code,
			422,
			array( 'validation_errors' => $errors )
		);

		/**
		 * Filter the validation error response object.
		 *
		 * Final opportunity to modify the validation error response,
		 * including adding additional metadata or transforming the structure.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error $error  The validation error response object.
		 * @param array    $errors The validation errors.
		 * @param string   $code   The error code.
		 *
		 * @return WP_Error The filtered validation error response.
		 */
		$error = apply_filters( 'WPPluginStarter_api_validation_error_response', $error, $errors, $code );

		/**
		 * Action fired after creating a validation error response.
		 *
		 * Perfect for validation error analytics, user experience tracking,
		 * or triggering validation improvement workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error $error  The final validation error response.
		 * @param array    $errors The validation errors.
		 * @param string   $code   The error code.
		 */
		do_action( 'WPPluginStarter_after_api_validation_error', $error, $errors, $code );

		return $error;
	}

	/**
	 * Create a not found error response
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error message
	 * @param string $code    Error code
	 *
	 * @return WP_Error
	 */
	public static function not_found( string $message = '', string $code = 'not_found' ): WP_Error {
		/**
		 * Filter the not found error message.
		 *
		 * Allows customization of 404 error messages based on context,
		 * user role, or resource type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The not found error message.
		 * @param string $code    The error code.
		 *
		 * @return string The filtered not found error message.
		 */
		$message = apply_filters(
			'WPPluginStarter_api_not_found_message',
			! empty( $message ) ? $message : __( 'Resource not found', 'wp-plugin-starter' ),
			$code
		);

		/**
		 * Filter the not found error code.
		 *
		 * Enables standardization of not found error codes across
		 * different resource types or API endpoints.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code    The error code.
		 * @param string $message The error message.
		 *
		 * @return string The filtered not found error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_not_found_code', $code, $message );

		return self::error( $message, $code, 404 );
	}

	/**
	 * Create an unauthorized error response
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error message
	 * @param string $code    Error code
	 *
	 * @return WP_Error
	 */
	public static function unauthorized( string $message = '', string $code = 'unauthorized' ): WP_Error {
		/**
		 * Filter the unauthorized error message.
		 *
		 * Allows customization of authentication error messages while
		 * maintaining security by not revealing sensitive information.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The unauthorized error message.
		 * @param string $code    The error code.
		 *
		 * @return string The filtered unauthorized error message.
		 */
		$message = apply_filters(
			'WPPluginStarter_api_unauthorized_message',
			! empty( $message ) ? $message : __( 'You are not authorized to perform this action', 'wp-plugin-starter' ),
			$code
		);

		/**
		 * Filter the unauthorized error code.
		 *
		 * Enables standardization of authentication error codes for
		 * consistent error handling across the application.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code    The error code.
		 * @param string $message The error message.
		 *
		 * @return string The filtered unauthorized error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_unauthorized_code', $code, $message );

		return self::error( $message, $code, 401 );
	}

	/**
	 * Create a forbidden error response
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error message
	 * @param string $code    Error code
	 *
	 * @return WP_Error
	 */
	public static function forbidden( string $message = '', string $code = 'forbidden' ): WP_Error {
		/**
		 * Filter the forbidden error message.
		 *
		 * Allows customization of authorization error messages based on
		 * user capabilities, resource ownership, or business rules.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The forbidden error message.
		 * @param string $code    The error code.
		 *
		 * @return string The filtered forbidden error message.
		 */
		$message = apply_filters(
			'WPPluginStarter_api_forbidden_message',
			! empty( $message ) ? $message : __( 'You do not have permission to perform this action', 'wp-plugin-starter' ),
			$code
		);

		/**
		 * Filter the forbidden error code.
		 *
		 * Enables standardization of authorization error codes for
		 * consistent permission handling across endpoints.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code    The error code.
		 * @param string $message The error message.
		 *
		 * @return string The filtered forbidden error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_forbidden_code', $code, $message );

		return self::error( $message, $code, 403 );
	}

	/**
	 * Create a server error response
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Error message
	 * @param string $code    Error code
	 *
	 * @return WP_Error
	 */
	public static function server_error( string $message = '', string $code = 'server_error' ): WP_Error {
		/**
		 * Filter the server error message.
		 *
		 * Allows customization of server error messages while ensuring
		 * sensitive system information is not exposed to clients.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message The server error message.
		 * @param string $code    The error code.
		 *
		 * @return string The filtered server error message.
		 */
		$message = apply_filters(
			'WPPluginStarter_api_server_error_message',
			! empty( $message ) ? $message : __( 'Internal server error', 'wp-plugin-starter' ),
			$code
		);

		/**
		 * Filter the server error code.
		 *
		 * Enables categorization and standardization of server error codes
		 * for better error tracking and monitoring.
		 *
		 * @since 1.0.0
		 *
		 * @param string $code    The error code.
		 * @param string $message The error message.
		 *
		 * @return string The filtered server error code.
		 */
		$code = apply_filters( 'WPPluginStarter_api_server_error_code', $code, $message );

		return self::error( $message, $code, 500 );
	}

	/**
	 * Create an error response from an exception
	 *
	 * @since 1.0.0
	 *
	 * @param Throwable $exception The exception to convert to an error response
	 * @param int       $status    HTTP status code (optional, defaults based on exception type)
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public static function from_exception( Throwable $exception, int $status = null ) {
		/**
		 * Action fired before processing an exception into an API response.
		 *
		 * Useful for exception logging, monitoring, alerting, or debugging.
		 * This hook fires before any exception-specific processing occurs.
		 *
		 * @since 1.0.0
		 *
		 * @param Throwable $exception The exception being processed.
		 * @param int|null  $status    The optional HTTP status code.
		 */
		do_action( 'WPPluginStarter_before_api_exception_response', $exception, $status );

		// Log the exception for debugging and monitoring
		WPPluginStarter_logger()->error(
			'Exception in API request',
			array(
				'message' => $exception->getMessage(),
				'code'    => $exception->getCode(),
				'class'   => get_class( $exception ),
				'file'    => $exception->getFile(),
				'line'    => $exception->getLine(),
				'trace'   => $exception->getTraceAsString(),
			)
		);

		/**
		 * Filter the exception before converting to API response.
		 *
		 * Allows transformation or wrapping of exceptions before they're
		 * converted to API responses. Useful for exception standardization.
		 *
		 * @since 1.0.0
		 *
		 * @param Throwable $exception The exception to be filtered.
		 * @param int|null  $status    The optional HTTP status code.
		 *
		 * @return Throwable The filtered exception.
		 */
		$exception = apply_filters( 'WPPluginStarter_api_exception_filter', $exception, $status );

		// Handle different exception types
		if ( $exception instanceof Validation_Exception ) {
			$response = $exception->to_rest_response( $status ?? 422 )->get_data();
		} elseif ( $exception instanceof Base_Exception ) {
			$response = $exception->to_rest_response( $status ?? 400 )->get_data();
		} elseif ( $exception instanceof Container_Exception ) {
			$response = self::server_error(
				$exception->getMessage(),
				$exception->getCode() ?? 'container_error'
			);
		} else {
			// Handle generic exceptions
			$message = $exception->getMessage();
			$code    = $exception->getCode();

			if ( empty( $message ) ) {
				$message = __( 'An unexpected error occurred', 'wp-plugin-starter' );
			}

			if ( empty( $code ) || ! is_string( $code ) ) {
				$code = 'unexpected_error';
			}

			$response = self::error( $message, $code, $status ?? 500 );
		}

		/**
		 * Filter the final exception-based API response.
		 *
		 * Last opportunity to modify the response created from an exception,
		 * including sanitization of error details or addition of context.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error|WP_REST_Response $response  The response created from the exception.
		 * @param Throwable                 $exception The original exception.
		 * @param int|null                  $status    The HTTP status code.
		 *
		 * @return WP_Error|WP_REST_Response The filtered exception response.
		 */
		$response = apply_filters( 'WPPluginStarter_api_exception_response', $response, $exception, $status );

		/**
		 * Action fired after processing an exception into an API response.
		 *
		 * Perfect for post-exception processing like cleanup, notifications,
		 * or triggering error recovery procedures.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error|WP_REST_Response $response  The final exception response.
		 * @param Throwable                 $exception The original exception.
		 * @param int|null                  $status    The HTTP status code.
		 */
		do_action( 'WPPluginStarter_after_api_exception_response', $response, $exception, $status );

		return $response;
	}
}
