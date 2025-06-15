<?php

namespace WPPluginStarter\REST\Controllers;

use WPPluginStarter\REST\Api_Response;
use WPPluginStarter\REST\Middleware\Authentication;
use WPPluginStarter\REST\Middleware\Validation;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Abstract REST Controller
 *
 * Base class for all REST API controllers in the plugin.
 * Provides standardized methods for request handling, validation,
 * authentication, and response formatting.
 *
 * @since 1.0.0
 * @package WPPluginStarter\REST\Controllers
 */
abstract class Abstract_Controller extends WP_REST_Controller {

	/**
	 * Endpoint namespace
	 *
	 * @var string
	 */
	protected $namespace = 'wp-plugin-starter/v1';

	/**
	 * Route base
	 *
	 * @var string
	 */
	protected $rest_base = '';

	/**
	 * API Response handler
	 *
	 * @var Api_Response
	 */
	protected $response;

	/**
	 * Constructor
	 *
	 * Initializes the controller with required dependencies
	 */
	public function __construct() {
		$this->response = new Api_Response();

		/**
		 * Action fired after a REST controller is instantiated.
		 *
		 * Allows for extending controller functionality,
		 * adding filters, or injecting dependencies.
		 *
		 * @since 1.0.0
		 *
		 * @param Abstract_Controller $controller The controller instance.
		 */
		do_action( 'WPPluginStarter_rest_controller_init', $this );
	}

	/**
	 * Check general permissions for the endpoint
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	public function check_permissions( WP_REST_Request $request ) {
		$result = ( new Authentication() )->validate( $request );

		/**
		 * Filter REST API permission check result.
		 *
		 * Allows modifying the result of permission checks for REST endpoints.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|WP_Error   $result  The original permission check result.
		 * @param WP_REST_Request $request The REST request object.
		 * @param string          $rest_base The current controller's REST base.
		 *
		 * @return bool|WP_Error Modified permission check result.
		 */
		return apply_filters(
			'WPPluginStarter_rest_check_permissions',
			$result,
			$request,
			$this->rest_base
		);
	}

	/**
	 * Validate request data
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request    Full details about the request
	 * @param array           $rules      Validation rules
	 * @param array           $messages   Custom error messages
	 *
	 * @return bool|WP_Error True if validation passes, WP_Error otherwise
	 */
	protected function validate_request( WP_REST_Request $request, array $rules = [], array $messages = [] ) {
		$validation_result = ( new Validation() )->validate( $request, $rules, $messages );

		/**
		 * Filter REST API request validation result.
		 *
		 * Allows modifying the result of validation before it's returned to the controller.
		 *
		 * @since 1.0.0
		 *
		 * @param bool|WP_Error   $validation_result The validation result.
		 * @param WP_REST_Request $request           The request being validated.
		 * @param array           $rules             The validation rules.
		 * @param array           $messages          Custom error messages.
		 * @param string          $rest_base         The current controller's REST base.
		 *
		 * @return bool|WP_Error Modified validation result.
		 */
		return apply_filters(
			'WPPluginStarter_rest_validate_request',
			$validation_result,
			$request,
			$rules,
			$messages,
			$this->rest_base
		);
	}

	/**
	 * Get API response handler
	 *
	 * Returns the response object for chaining
	 * Example usage: $this->response()->success($data)
	 *
	 * @since 1.0.0
	 *
	 * @return Api_Response
	 */
	protected function response(): Api_Response {
		return $this->response;
	}

	/**
	 * Check capabilities for the item
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request    Full details about the request
	 * @param string          $capability Capability to check
	 *
	 * @return bool|WP_Error True if permission granted, WP_Error otherwise
	 */
	protected function check_item_permissions( WP_REST_Request $request, string $capability = 'manage_options' ) {
		/**
		 * Filter the capability required for a REST item operation.
		 *
		 * Allows fine-grained control over which capabilities are required
		 * for specific REST operations.
		 *
		 * @since 1.0.0
		 *
		 * @param string          $capability The capability being checked.
		 * @param WP_REST_Request $request    The current request object.
		 * @param string          $rest_base  The current controller's REST base.
		 * @param int|string|null $item_id    The ID of the item being accessed, if available.
		 *
		 * @return string The capability to check.
		 */
		$capability = apply_filters(
			'WPPluginStarter_rest_item_capability',
			$capability,
			$request,
			$this->rest_base,
			$request['id'] ?? null
		);

		if ( ! current_user_can( $capability ) ) {
			$error = new WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to perform this action.', 'wp-plugin-starter' ),
				[ 'status' => rest_authorization_required_code() ]
			);

			/**
			 * Action fired when a REST item permission check fails.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error      The error object.
			 * @param string          $capability The capability that was checked.
			 * @param WP_REST_Request $request    The request object.
			 * @param string          $rest_base  The current controller's REST base.
			 */
			do_action(
				'WPPluginStarter_rest_item_permission_failed',
				$error,
				$capability,
				$request,
				$this->rest_base
			);

			return $error;
		}

		return true;
	}

	/**
	 * Get collection params
	 *
	 * Provides standard collection parameters for list endpoints.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = [
			'page'     => [
				'description'       => __( 'Current page of the collection.', 'wp-plugin-starter' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
				'minimum'           => 1,
			],
			'per_page' => [
				'description'       => __( 'Maximum number of items to be returned in result set.', 'wp-plugin-starter' ),
				'type'              => 'integer',
				'default'           => 10,
				'minimum'           => 1,
				'maximum'           => 100,
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			],
			'search'   => [
				'description'       => __( 'Limit results to those matching a string.', 'wp-plugin-starter' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => 'rest_validate_request_arg',
			],
			'order'    => [
				'description'       => __( 'Order sort attribute ascending or descending.', 'wp-plugin-starter' ),
				'type'              => 'string',
				'default'           => 'desc',
				'enum'              => [ 'asc', 'desc' ],
				'validate_callback' => 'rest_validate_request_arg',
			],
			'orderby'  => [
				'description'       => __( 'Sort collection by object attribute.', 'wp-plugin-starter' ),
				'type'              => 'string',
				'default'           => 'date',
				'validate_callback' => 'rest_validate_request_arg',
			],
		];

		/**
		 * Filter the collection parameters for REST controllers.
		 *
		 * Allows customizing the default collection parameters used in REST endpoints.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $params    The default collection parameters.
		 * @param string $rest_base The current controller's REST base.
		 *
		 * @return array Modified collection parameters.
		 */
		return apply_filters( 'WPPluginStarter_rest_collection_params', $params, $this->rest_base );
	}

	/**
	 * Add pagination headers to the response
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Response $response   The response object
	 * @param int              $total      Total number of items
	 * @param int              $per_page   Items per page
	 * @param int              $current    Current page number
	 *
	 * @return WP_REST_Response The response with pagination headers
	 */
	protected function add_pagination_headers( WP_REST_Response $response, int $total, int $per_page, int $current = 1 ): WP_REST_Response {
		$max_pages = ceil( $total / $per_page );

		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		// Add next/prev page links to Link header if appropriate
		$links = [];

		if ( $current < $max_pages ) {
			$links[] = '<' . $this->get_page_link( $current + 1, $per_page ) . '>; rel="next"';
		}

		if ( $current > 1 ) {
			$links[] = '<' . $this->get_page_link( $current - 1, $per_page ) . '>; rel="prev"';
		}

		if ( ! empty( $links ) ) {
			$response->header( 'Link', implode( ', ', $links ) );
		}

		/**
		 * Filter REST response after adding pagination headers.
		 *
		 * Allows modifying the response after pagination headers have been added.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response   The modified response with pagination headers.
		 * @param int              $total      Total number of items.
		 * @param int              $per_page   Number of items per page.
		 * @param int              $current    Current page number.
		 * @param int              $max_pages  Maximum number of pages.
		 * @param string           $rest_base  The current controller's REST base.
		 *
		 * @return WP_REST_Response Modified response.
		 */
		return apply_filters(
			'WPPluginStarter_rest_paginated_response',
			$response,
			$total,
			$per_page,
			$current,
			$max_pages,
			$this->rest_base
		);
	}

	/**
	 * Get link for a specific page
	 *
	 * @since 1.0.0
	 *
	 * @param int $page     Page number
	 * @param int $per_page Items per page
	 *
	 * @return string URL for the specified page
	 */
	protected function get_page_link( int $page, int $per_page ): string {
		$request = $this->get_current_request();

		// Build URL based on current request
		$route = '/' . $this->namespace . '/' . $this->rest_base;
		$query = [
			'page'     => $page,
			'per_page' => $per_page,
		];

		// Add additional query parameters from current request
		foreach ( [ 'search', 'order', 'orderby' ] as $param ) {
			if ( isset( $request[ $param ] ) ) {
				$query[ $param ] = $request[ $param ];
			}
		}

		return add_query_arg( urlencode_deep( $query ), rest_url( $route ) );
	}

	/**
	 * Get current REST request object
	 *
	 * @since 1.0.0
	 *
	 * @return WP_REST_Request|array Empty array if no current request
	 */
	protected function get_current_request() {
		if ( isset( $GLOBALS['wp_rest_server']->current_request ) ) {
			return $GLOBALS['wp_rest_server']->current_request;
		}

		return [];
	}

	/**
	 * Prepare item links for the response
	 *
	 * @since 1.0.0
	 *
	 * @param int|string $id Item ID
	 *
	 * @return array Links for the given item
	 */
	protected function prepare_links( $id ): array {
		$base = '/' . $this->namespace . '/' . $this->rest_base;

		$links = [
			'self' => [
				'href' => rest_url( trailingslashit( $base ) . $id ),
			],
			'collection' => [
				'href' => rest_url( $base ),
			],
		];

		/**
		 * Filter the links prepared for a REST item response.
		 *
		 * Allows customizing the HATEOAS links included in item responses.
		 *
		 * @since 1.0.0
		 *
		 * @param array       $links     The links prepared for the response.
		 * @param int|string  $id        The ID of the item.
		 * @param string      $rest_base The current controller's REST base.
		 * @param string      $namespace The API namespace.
		 *
		 * @return array Modified links array.
		 */
		return apply_filters(
			'WPPluginStarter_rest_prepare_links',
			$links,
			$id,
			$this->rest_base,
			$this->namespace
		);
	}
}
