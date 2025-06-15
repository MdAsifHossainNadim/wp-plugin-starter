<?php

namespace WPPluginStarter\Core\Exception;

use Throwable;
use WP_REST_Response;

/**
 * Validation Exception
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Exception
 */
class Validation_Exception extends Base_Exception {
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string         $message  Error message
	 * @param array          $errors   Validation errors
	 * @param int            $code     Error code
	 * @param Throwable|null $previous Previous exception
	 */
	public function __construct( $message = 'Validation failed', $errors = array(), $code = 422, Throwable $previous = null ) {
		parent::__construct( $message, $code, array( 'errors' => $errors ), $previous );
	}

	/**
	 * Get validation errors
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_errors(): array {
		return $this->data['errors'] ?? array();
	}

	/**
	 * Convert exception to REST response
	 *
	 * @since 1.0.0
	 *
	 * @param int $status_code HTTP status code
	 *
	 * @return WP_REST_Response
	 */
	public function to_rest_response( int $status_code = 422 ): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'code'    => $this->getCode() ?? 'validation_failed',
				'message' => $this->getMessage(),
				'data'    => array(
					'status' => $status_code,
					'errors' => $this->get_errors(),
				),
			),
			$status_code
		);
	}
}
