<?php

namespace WPPluginStarter\REST\Middleware;

use WPPluginStarter\REST\Api_Response;
use WP_Error;
use WP_REST_Request;

/**
 * Authentication Middleware
 *
 * Handles authentication for REST API requests
 *
 * @package WPPluginStarter\REST\Middleware
 */
class Authentication {
	/**
	 * Validate the authentication
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if authentication passes, WP_Error otherwise
	 */
	public function validate( WP_REST_Request $request ) {
		/**
		 * Action fired before authentication validation begins.
		 *
		 * This hook allows developers to perform pre-authentication tasks,
		 * such as rate limiting, request logging, or custom security checks.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The REST request object being authenticated.
		 */
		do_action( 'WPPluginStarter_before_rest_authentication', $request );

		/**
		 * Filter to override authentication validation entirely.
		 *
		 * Return a non-null value to bypass the default authentication logic.
		 * Useful for implementing custom authentication mechanisms or testing.
		 *
		 * @since 1.0.0
		 *
		 * @param null|bool|WP_Error $pre_auth Pre-authentication result. Return non-null to override.
		 * @param WP_REST_Request    $request  The REST request object.
		 *
		 * @return null|bool|WP_Error Authentication result or null to continue with default logic.
		 */
		$pre_auth = apply_filters( 'WPPluginStarter_pre_rest_authentication', null, $request );
		if ( null !== $pre_auth ) {
			return $pre_auth;
		}

		// Check if user is logged in
		if ( ! is_user_logged_in() ) {
			/**
			 * Action fired when a user is not logged in during authentication.
			 *
			 * Useful for logging unauthorized access attempts or implementing
			 * rate limiting for unauthenticated requests.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Request $request The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_authentication_not_logged_in', $request );

			/**
			 * Filter the authentication error for non-logged-in users.
			 *
			 * Allows customization of the error response when users are not logged in.
			 * Can be used to provide different messages based on the endpoint or context.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error   The default authentication error.
			 * @param WP_REST_Request $request The REST request object.
			 *
			 * @return WP_Error The filtered authentication error.
			 */
			return apply_filters(
				'WPPluginStarter_rest_authentication_not_logged_in_error',
				Api_Response::unauthorized(
					__( 'You must be logged in to access this endpoint.', 'wp-plugin-starter' )
				),
				$request
			);
		}

		/**
		 * Action fired when a user is successfully logged in during authentication.
		 *
		 * Perfect for user activity logging, session management, or security auditing.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $user_id The ID of the logged-in user.
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_rest_authentication_user_logged_in', get_current_user_id(), $request );

		// Basic permission check - override in specific controllers
		if ( ! current_user_can( 'manage_options' ) ) {
			/**
			 * Action fired when a user lacks required permissions.
			 *
			 * Useful for tracking permission violations, implementing progressive
			 * access controls, or logging security events.
			 *
			 * @since 1.0.0
			 *
			 * @param int             $user_id    The ID of the user lacking permissions.
			 * @param string          $capability The required capability.
			 * @param WP_REST_Request $request    The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_authentication_insufficient_permissions', get_current_user_id(), 'manage_options', $request );

			/**
			 * Filter the authentication error for insufficient permissions.
			 *
			 * Allows customization of permission error messages and can be used
			 * to implement different permission models or provide helpful guidance.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error   The default permission error.
			 * @param int             $user_id The ID of the user.
			 * @param string          $capability The required capability.
			 * @param WP_REST_Request $request The REST request object.
			 *
			 * @return WP_Error The filtered permission error.
			 */
			return apply_filters(
				'WPPluginStarter_rest_authentication_permission_error',
				Api_Response::forbidden( __( 'You do not have permission to access this endpoint.', 'wp-plugin-starter' ) ),
				get_current_user_id(),
				'manage_options',
				$request
			);
		}

		/**
		 * Action fired after successful authentication validation.
		 *
		 * Perfect for post-authentication tasks like updating last login time,
		 * recording user activity, or triggering welcome workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $user_id The ID of the authenticated user.
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_after_rest_authentication_success', get_current_user_id(), $request );

		/**
		 * Filter the successful authentication result.
		 *
		 * Allows final modification of the authentication success state.
		 * Can be used to add additional context or metadata.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $success The authentication success state.
		 * @param int             $user_id The ID of the authenticated user.
		 * @param WP_REST_Request $request The REST request object.
		 *
		 * @return bool The filtered authentication result.
		 */
		return apply_filters( 'WPPluginStarter_rest_authentication_success', true, get_current_user_id(), $request );
	}

	/**
	 * Check if user has required capability
	 *
	 * @param WP_REST_Request $request    Full details about the request
	 * @param string          $capability Required capability
	 *
	 * @return bool|WP_Error True if user has capability, WP_Error otherwise
	 */
	public function check_capability( WP_REST_Request $request, string $capability ) {
		/**
		 * Action fired before capability checking begins.
		 *
		 * Useful for implementing custom capability logic, logging capability
		 * checks, or performing pre-capability validation tasks.
		 *
		 * @since 1.0.0
		 *
		 * @param string          $capability The capability being checked.
		 * @param WP_REST_Request $request    The REST request object.
		 */
		do_action( 'WPPluginStarter_before_rest_capability_check', $capability, $request );

		/**
		 * Filter to override capability checking entirely.
		 *
		 * Return a non-null value to bypass the default capability logic.
		 * Useful for implementing role-based access control or custom permission systems.
		 *
		 * @since 1.0.0
		 *
		 * @param null|bool|WP_Error $pre_capability Pre-capability result. Return non-null to override.
		 * @param string             $capability     The capability being checked.
		 * @param WP_REST_Request    $request        The REST request object.
		 *
		 * @return null|bool|WP_Error Capability result or null to continue with default logic.
		 */
		$pre_capability = apply_filters( 'WPPluginStarter_pre_rest_capability_check', null, $capability, $request );
		if ( null !== $pre_capability ) {
			return $pre_capability;
		}

		// Check authentication first
		$auth_check = $this->validate( $request );
		if ( is_wp_error( $auth_check ) ) {
			/**
			 * Action fired when authentication fails during capability check.
			 *
			 * Allows tracking of authentication failures in capability-protected endpoints.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $auth_check The authentication error.
			 * @param string          $capability The capability being checked.
			 * @param WP_REST_Request $request    The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_capability_check_auth_failed', $auth_check, $capability, $request );

			return $auth_check;
		}

		/**
		 * Filter the capability before checking.
		 *
		 * Allows dynamic capability assignment or capability mapping based on
		 * context, user role, or other factors.
		 *
		 * @since 1.0.0
		 *
		 * @param string          $capability The capability to be checked.
		 * @param WP_REST_Request $request    The REST request object.
		 * @param int             $user_id    The ID of the current user.
		 *
		 * @return string The filtered capability.
		 */
		$capability = apply_filters( 'WPPluginStarter_rest_check_capability_name', $capability, $request, get_current_user_id() );

		// Check if user has required capability
		if ( ! current_user_can( $capability ) ) {
			/**
			 * Action fired when a user lacks a specific capability.
			 *
			 * Useful for capability-specific logging, progressive permissions,
			 * or implementing capability upgrade suggestions.
			 *
			 * @since 1.0.0
			 *
			 * @param int             $user_id    The ID of the user lacking the capability.
			 * @param string          $capability The required capability.
			 * @param WP_REST_Request $request    The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_capability_check_failed', get_current_user_id(), $capability, $request );

			/**
			 * Filter the capability error response.
			 *
			 * Allows customization of capability error messages and can provide
			 * helpful guidance for users lacking specific permissions.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error      The default capability error.
			 * @param string          $capability The required capability.
			 * @param int             $user_id    The ID of the user.
			 * @param WP_REST_Request $request    The REST request object.
			 *
			 * @return WP_Error The filtered capability error.
			 */
			return apply_filters(
				'WPPluginStarter_rest_capability_check_error',
				Api_Response::forbidden(
					sprintf(
						/* translators: %s: capability name */
						__( 'You do not have the required capability: %s', 'wp-plugin-starter' ),
						$capability
					)
				),
				$capability,
				get_current_user_id(),
				$request
			);
		}

		/**
		 * Action fired after successful capability validation.
		 *
		 * Perfect for logging successful capability checks, updating user
		 * activity records, or triggering capability-based workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $user_id    The ID of the user with the capability.
		 * @param string          $capability The validated capability.
		 * @param WP_REST_Request $request    The REST request object.
		 */
		do_action( 'WPPluginStarter_after_rest_capability_check_success', get_current_user_id(), $capability, $request );

		/**
		 * Filter the successful capability check result.
		 *
		 * Allows final modification of the capability validation state.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $success    The capability check success state.
		 * @param string          $capability The validated capability.
		 * @param int             $user_id    The ID of the user.
		 * @param WP_REST_Request $request    The REST request object.
		 *
		 * @return bool The filtered capability check result.
		 */
		return apply_filters( 'WPPluginStarter_rest_capability_check_success', true, $capability, get_current_user_id(), $request );
	}

	/**
	 * Check if user is vendor
	 *
	 * @param WP_REST_Request $request Full details about the request
	 *
	 * @return bool|WP_Error True if user is vendor, WP_Error otherwise
	 */
	public function check_vendor( WP_REST_Request $request ) {
		/**
		 * Action fired before vendor validation begins.
		 *
		 * Useful for implementing custom vendor logic, logging vendor access
		 * attempts, or performing pre-vendor validation tasks.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_before_rest_vendor_check', $request );

		/**
		 * Filter to override vendor checking entirely.
		 *
		 * Return a non-null value to bypass the default vendor validation logic.
		 * Useful for implementing custom vendor systems or testing scenarios.
		 *
		 * @since 1.0.0
		 *
		 * @param null|bool|WP_Error $pre_vendor Pre-vendor result. Return non-null to override.
		 * @param WP_REST_Request    $request    The REST request object.
		 *
		 * @return null|bool|WP_Error Vendor result or null to continue with default logic.
		 */
		$pre_vendor = apply_filters( 'WPPluginStarter_pre_rest_vendor_check', null, $request );
		if ( null !== $pre_vendor ) {
			return $pre_vendor;
		}

		// Check authentication first
		$auth_check = $this->validate( $request );
		if ( is_wp_error( $auth_check ) ) {
			/**
			 * Action fired when authentication fails during vendor check.
			 *
			 * Allows tracking of authentication failures in vendor-protected endpoints.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $auth_check The authentication error.
			 * @param WP_REST_Request $request    The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_vendor_check_auth_failed', $auth_check, $request );

			return $auth_check;
		}

		$user_id = get_current_user_id();

		/**
		 * Filter the vendor validation logic.
		 *
		 * Allows customization of vendor detection logic, including integration
		 * with different vendor management systems or custom vendor roles.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $is_vendor Default vendor check result.
		 * @param int             $user_id   The ID of the user being checked.
		 * @param WP_REST_Request $request   The REST request object.
		 *
		 * @return bool Whether the user is considered a vendor.
		 */
		$is_vendor = apply_filters(
			'WPPluginStarter_rest_is_user_vendor',
			function_exists( 'dokan_is_user_seller' ) && dokan_is_user_seller( $user_id ),
			$user_id,
			$request
		);

		// Check if user is vendor
		if ( ! $is_vendor ) {
			/**
			 * Action fired when a user is not a vendor.
			 *
			 * Useful for tracking non-vendor access attempts to vendor-only
			 * endpoints or implementing vendor registration prompts.
			 *
			 * @since 1.0.0
			 *
			 * @param int             $user_id The ID of the non-vendor user.
			 * @param WP_REST_Request $request The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_vendor_check_not_vendor', $user_id, $request );

			/**
			 * Filter the vendor error response.
			 *
			 * Allows customization of vendor requirement error messages and can
			 * provide helpful guidance for becoming a vendor.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error   The default vendor error.
			 * @param int             $user_id The ID of the non-vendor user.
			 * @param WP_REST_Request $request The REST request object.
			 *
			 * @return WP_Error The filtered vendor error.
			 */
			return apply_filters(
				'WPPluginStarter_rest_vendor_check_error',
				Api_Response::forbidden(
					__( 'You must be a vendor to access this endpoint.', 'wp-plugin-starter' )
				),
				$user_id,
				$request
			);
		}

		/**
		 * Action fired after successful vendor validation.
		 *
		 * Perfect for vendor-specific logging, updating vendor activity records,
		 * or triggering vendor-based workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $user_id The ID of the validated vendor.
		 * @param WP_REST_Request $request The REST request object.
		 */
		do_action( 'WPPluginStarter_after_rest_vendor_check_success', $user_id, $request );

		/**
		 * Filter the successful vendor check result.
		 *
		 * Allows final modification of the vendor validation state.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $success The vendor check success state.
		 * @param int             $user_id The ID of the vendor.
		 * @param WP_REST_Request $request The REST request object.
		 *
		 * @return bool The filtered vendor check result.
		 */
		return apply_filters( 'WPPluginStarter_rest_vendor_check_success', true, $user_id, $request );
	}

	/**
	 * Check if user owns the resource
	 *
	 * @param WP_REST_Request $request        Full details about the request
	 * @param int             $resource_owner Resource owner user ID
	 *
	 * @return bool|WP_Error True if user owns the resource, WP_Error otherwise
	 */
	public function check_ownership( WP_REST_Request $request, int $resource_owner ) {
		/**
		 * Action fired before ownership validation begins.
		 *
		 * Useful for implementing custom ownership logic, logging ownership
		 * checks, or performing pre-ownership validation tasks.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $resource_owner The ID of the resource owner.
		 * @param WP_REST_Request $request        The REST request object.
		 */
		do_action( 'WPPluginStarter_before_rest_ownership_check', $resource_owner, $request );

		/**
		 * Filter to override ownership checking entirely.
		 *
		 * Return a non-null value to bypass the default ownership validation logic.
		 * Useful for implementing custom ownership models or delegation systems.
		 *
		 * @since 1.0.0
		 *
		 * @param null|bool|WP_Error $pre_ownership Pre-ownership result. Return non-null to override.
		 * @param int                $resource_owner The ID of the resource owner.
		 * @param WP_REST_Request    $request        The REST request object.
		 *
		 * @return null|bool|WP_Error Ownership result or null to continue with default logic.
		 */
		$pre_ownership = apply_filters( 'WPPluginStarter_pre_rest_ownership_check', null, $resource_owner, $request );
		if ( null !== $pre_ownership ) {
			return $pre_ownership;
		}

		// Check authentication first
		$auth_check = $this->validate( $request );
		if ( is_wp_error( $auth_check ) ) {
			/**
			 * Action fired when authentication fails during ownership check.
			 *
			 * Allows tracking of authentication failures in ownership-protected endpoints.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $auth_check     The authentication error.
			 * @param int             $resource_owner The ID of the resource owner.
			 * @param WP_REST_Request $request        The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_ownership_check_auth_failed', $auth_check, $resource_owner, $request );

			return $auth_check;
		}

		$current_user_id = get_current_user_id();

		// Admin can access all resources
		if ( current_user_can( 'manage_options' ) ) {
			/**
			 * Action fired when an admin bypasses ownership check.
			 *
			 * Useful for auditing admin access to user resources or implementing
			 * admin activity logging.
			 *
			 * @since 1.0.0
			 *
			 * @param int             $admin_id       The ID of the admin user.
			 * @param int             $resource_owner The ID of the resource owner.
			 * @param WP_REST_Request $request        The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_ownership_check_admin_bypass', $current_user_id, $resource_owner, $request );

			/**
			 * Filter the admin bypass result for ownership checks.
			 *
			 * Allows customization of admin access behavior, including implementing
			 * additional admin restrictions or logging requirements.
			 *
			 * @since 1.0.0
			 *
			 * @param bool            $bypass         Whether admin should bypass ownership.
			 * @param int             $admin_id       The ID of the admin user.
			 * @param int             $resource_owner The ID of the resource owner.
			 * @param WP_REST_Request $request        The REST request object.
			 *
			 * @return bool Whether admin can bypass ownership check.
			 */
			$admin_bypass = apply_filters( 'WPPluginStarter_rest_ownership_admin_bypass', true, $current_user_id, $resource_owner, $request );

			if ( $admin_bypass ) {
				return true;
			}
		}

		/**
		 * Filter the ownership validation logic.
		 *
		 * Allows customization of ownership determination, including support for
		 * delegated access, team ownership, or hierarchical ownership models.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $owns_resource  Default ownership check result.
		 * @param int             $current_user   The ID of the current user.
		 * @param int             $resource_owner The ID of the resource owner.
		 * @param WP_REST_Request $request        The REST request object.
		 *
		 * @return bool Whether the current user owns the resource.
		 */
		$owns_resource = apply_filters(
			'WPPluginStarter_rest_user_owns_resource',
			$current_user_id === $resource_owner,
			$current_user_id,
			$resource_owner,
			$request
		);

		// Check if user owns the resource
		if ( ! $owns_resource ) {
			/**
			 * Action fired when a user doesn't own a resource.
			 *
			 * Useful for tracking unauthorized resource access attempts or
			 * implementing progressive access control systems.
			 *
			 * @since 1.0.0
			 *
			 * @param int             $current_user   The ID of the user lacking ownership.
			 * @param int             $resource_owner The ID of the resource owner.
			 * @param WP_REST_Request $request        The REST request object.
			 */
			do_action( 'WPPluginStarter_rest_ownership_check_failed', $current_user_id, $resource_owner, $request );

			/**
			 * Filter the ownership error response.
			 *
			 * Allows customization of ownership error messages and can provide
			 * helpful guidance or alternative access methods.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error          The default ownership error.
			 * @param int             $current_user   The ID of the current user.
			 * @param int             $resource_owner The ID of the resource owner.
			 * @param WP_REST_Request $request        The REST request object.
			 *
			 * @return WP_Error The filtered ownership error.
			 */
			return apply_filters(
				'WPPluginStarter_rest_ownership_check_error',
				Api_Response::forbidden( __( 'You do not have permission to access this resource.', 'wp-plugin-starter' ) ),
				$current_user_id,
				$resource_owner,
				$request
			);
		}

		/**
		 * Action fired after successful ownership validation.
		 *
		 * Perfect for ownership-specific logging, updating resource access records,
		 * or triggering ownership-based workflows.
		 *
		 * @since 1.0.0
		 *
		 * @param int             $current_user   The ID of the resource owner.
		 * @param int             $resource_owner The ID of the resource owner (should match current_user).
		 * @param WP_REST_Request $request        The REST request object.
		 */
		do_action( 'WPPluginStarter_after_rest_ownership_check_success', $current_user_id, $resource_owner, $request );

		/**
		 * Filter the successful ownership check result.
		 *
		 * Allows final modification of the ownership validation state.
		 *
		 * @since 1.0.0
		 *
		 * @param bool            $success        The ownership check success state.
		 * @param int             $current_user   The ID of the current user.
		 * @param int             $resource_owner The ID of the resource owner.
		 * @param WP_REST_Request $request        The REST request object.
		 *
		 * @return bool The filtered ownership check result.
		 */
		return apply_filters( 'WPPluginStarter_rest_ownership_check_success', true, $current_user_id, $resource_owner, $request );
	}
}
