<?php

namespace WPPluginStarter\Core\Exception;

use Exception;
use Throwable;
use WP_Error;
use WP_REST_Response;

/**
 * Base Exception
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Exception
 */
class Base_Exception extends Exception {
	/**
	 * Error data
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $data = array();

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string         $message  Error message
	 * @param int            $code     Error code
	 * @param array          $data     Error data
	 * @param Throwable|null $previous Previous exception
	 */
	public function __construct( $message = '', $code = 0, $data = array(), Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );

		$this->data = $data;
	}

	/**
	 * Get error data
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_data(): array {
		return $this->data;
	}

	/**
	 * Convert exception to WP_Error
	 *
	 * @since 1.0.0
	 * @return WP_Error
	 */
	public function to_wp_error(): WP_Error {
		return new WP_Error(
			$this->getCode() ?? 'WPPluginStarter_error',
			$this->getMessage(),
			$this->get_data()
		);
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
	public function to_rest_response( int $status_code = 400 ): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'code'    => $this->getCode() ?? 'WPPluginStarter_error',
				'message' => $this->getMessage(),
				'data'    => $this->get_data(),
			),
			$status_code
		);
	}
}
