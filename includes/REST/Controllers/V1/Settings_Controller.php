<?php

namespace WPPluginStarter\REST\Controllers\V1;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\REST\Controllers\Abstract_Controller;
use JsonException;
use Throwable;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * SettingsModel REST Controller
 *
 * Handles REST API endpoints for managing plugin settings.
 * This is a sample implementation demonstrating how to use models and data stores
 * for managing settings through the REST API, with proper validation and error handling.
 *
 * @since   1.0.0
 * @package WPPluginStarter\REST\Controllers\V1
 */
class Settings_Controller extends Abstract_Controller {
	/**
	 * Route base
	 *
	 * @var string
	 */
	protected $rest_base = 'settings';

	/**
	 * SettingsModel data store instance
	 *
	 * @var Settings_Data_Store
	 */
	protected $data_store;

	/**
	 * Constructor
	 *
	 * Initializes the controller with its dependencies
	 */
	public function __construct() {
		parent::__construct();

		// Get the data store from the container
		$this->data_store = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );

		/**
		 * Action fired after a settings controller is instantiated.
		 *
		 * Allows for extending settings controller functionality or
		 * registering additional dependencies.
		 *
		 * @since 1.0.0
		 *
		 * @param Settings_Controller $controller The controller instance.
		 * @param Settings_Data_Store $data_store The settings data store.
		 */
		do_action( 'WPPluginStarter_settings_controller_init', $this, $this->data_store );
	}

	/**
	 * Register routes
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
					'args'                => $this->get_update_params(),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		// Register route for individual setting groups
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<group>[a-zA-Z0-9_-]+)',
			array(
				'args'   => array(
					'group' => array(
						'description' => __( 'SettingsModel group key.', 'wp-plugin-starter' ),
						'type'        => 'string',
						'required'    => true,
						'enum'        => array( 'product', 'vendor', 'cart', 'shipping', 'general' ),
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_group_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update_group_settings' ),
					'permission_callback' => array( $this, 'check_admin_permission' ),
				),
				'schema' => array( $this, 'get_item_schema' ),
			)
		);

		/**
		 * Action hook fired after settings routes are registered.
		 *
		 * Allows plugins to register additional settings-related routes.
		 *
		 * @since 1.0.0
		 *
		 * @param string             $namespace  The namespace for the routes.
		 * @param string             $rest_base  The base for the routes.
		 * @param Settings_Controller $controller The controller instance.
		 */
		do_action( 'WPPluginStarter_settings_rest_routes_registered', $this->namespace, $this->rest_base, $this );
	}

	/**
	 * Check if user has admin permission
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object
	 *
	 * @return bool|WP_Error
	 */
	public function check_admin_permission( WP_REST_Request $request ) {
		/**
		 * Filter the capability required for managing settings.
		 *
		 * @since 1.0.0
		 *
		 * @param string          $capability The default capability (manage_options).
		 * @param WP_REST_Request $request    The request object.
		 * @param string          $rest_base  The settings REST base.
		 *
		 * @return string The capability to check.
		 */
		$capability = apply_filters(
			'WPPluginStarter_settings_capability',
			'manage_options',
			$request,
			$this->rest_base
		);

		if ( ! current_user_can( $capability ) ) {
			$error = new WP_Error(
				'WPPluginStarter_rest_forbidden',
				__( 'You do not have permission to access this resource.', 'wp-plugin-starter' ),
				array( 'status' => rest_authorization_required_code() )
			);

			/**
			 * Action fired when settings permission check fails.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Error        $error      The error object.
			 * @param string          $capability The capability checked.
			 * @param WP_REST_Request $request    The request object.
			 */
			do_action( 'WPPluginStarter_settings_permission_failed', $error, $capability, $request );

			return $error;
		}

		return true;
	}

	/**
	 * Get all settings
	 *
	 * Retrieves all settings from the data store with optional filtering
	 * and demonstrates proper usage of the data store pattern.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_settings( WP_REST_Request $request ) {
		try {
			/**
			 * Action fired before retrieving settings.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_get_settings', $request );

			$args = array(
				'orderby' => $request['orderby'] ?? 'name',
				'order'   => $request['order'] ?? 'ASC',
				'limit'   => $request['per_page'] ?? -1,
				'offset'  => $request['offset'] ?? 0,
			);

			// Add group filter if provided
			if ( ! empty( $request['group'] ) ) {
				$args['in'] = is_array( $request['group'] )
					? $request['group']
					: array( $request['group'] );
			} else {
				// Default groups
				$args['in'] = array(
					'product',
					'vendor',
					'cart',
					'shipping',
					'general',
				);
			}

			/**
			 * Filter arguments for retrieving settings.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $args    Arguments for retrieving settings.
			 * @param WP_REST_Request $request The request object.
			 *
			 * @return array Modified arguments.
			 */
			$args = apply_filters( 'WPPluginStarter_settings_query_args', $args, $request );

			// Use data store to retrieve settings
			$settings = $this->data_store->get_all( $args );

			// Format the response
			$data = array();
			foreach ( $settings as $setting ) {
				$item                  = $this->prepare_setting_for_response( $setting, $request );
				$data[ $item['name'] ] = $item;
			}

			/**
			 * Filter settings data before creating the response.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $data     SettingsModel data.
			 * @param array           $settings Raw settings objects.
			 * @param WP_REST_Request $request  The request object.
			 *
			 * @return array Modified settings data.
			 */
			$data = apply_filters( 'WPPluginStarter_settings_response_data', $data, $settings, $request );

			$response = $this->response()->success(
				$data,
				__( 'SettingsModel retrieved successfully.', 'wp-plugin-starter' ),
				200,
				array(
					'count'  => count( $settings ),
					'groups' => $args['in'],
				)
			);

			/**
			 * Action fired after retrieving settings.
			 *
			 * @since 1.0.0
			 *
			 * @param array            $data     SettingsModel data.
			 * @param array            $settings Raw settings objects.
			 * @param WP_REST_Request  $request  The request object.
			 * @param WP_REST_Response $response The response object.
			 */
			do_action( 'WPPluginStarter_after_get_settings', $data, $settings, $request, $response );

			return $response;
		} catch ( Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to retrieve settings.', 'wp-plugin-starter' ),
				'WPPluginStarter_settings_retrieval_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Get settings for a specific group
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object with 'group' parameter
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_group_settings( WP_REST_Request $request ) {
		$group = $request['group'];

		try {
			/**
			 * Action fired before retrieving a settings group.
			 *
			 * @since 1.0.0
			 *
			 * @param string          $group   The settings group identifier.
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_get_settings_group', $group, $request );

			// Try to get existing setting from data store
			$settings = $this->data_store->get_settings_by_name( $group );

			if ( null === $settings ) {
				return $this->response()->error(
					// translators: %s is the settings group name.
					sprintf( __( 'SettingsModel group "%s" not found.', 'wp-plugin-starter' ), $group ),
					'WPPluginStarter_settings_not_found',
					404
				);
			}

			$data = $this->prepare_setting_for_response( $settings, $request );

			/**
			 * Filter group settings data before creating the response.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $data     Setting data.
			 * @param Settings_Model  $settings The raw settings object.
			 * @param string          $group    Group identifier.
			 * @param WP_REST_Request $request  The request object.
			 *
			 * @return array Modified setting data.
			 */
			$data = apply_filters( 'WPPluginStarter_settings_group_data', $data, $settings, $group, $request );

			$response = $this->response()->success(
				$data,
				// translators: %s is the settings group name.
				sprintf( __( 'SettingsModel for group "%s" retrieved successfully.', 'wp-plugin-starter' ), $group )
			);

			/**
			 * Action fired after retrieving a settings group.
			 *
			 * @since 1.0.0
			 *
			 * @param array            $data     Setting data.
			 * @param Settings_Model   $settings The settings object.
			 * @param string           $group    Group identifier.
			 * @param WP_REST_Request  $request  The request object.
			 * @param WP_REST_Response $response The response object.
			 */
			do_action( 'WPPluginStarter_after_get_settings_group', $data, $settings, $group, $request, $response );

			return $response;

		} catch ( Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to retrieve settings group.', 'wp-plugin-starter' ),
				'WPPluginStarter_settings_retrieval_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Update or create multiple settings
	 *
	 * Demonstrates updating settings using the model and data store pattern
	 * with proper validation and error handling.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_settings( WP_REST_Request $request ) {
		try {
			/**
			 * Action fired before updating settings.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_update_settings', $request );

			$params  = $request->get_params();
			$results = array();
			$errors  = array();

			// If we're getting raw JSON data as a string
			if ( empty( $params ) && $request->get_body() ) {
				$params = json_decode( $request->get_body(), true, 512, JSON_THROW_ON_ERROR );
			}

			$settings_data = $params['settings'] ?? array();

			/**
			 * Filter settings data before processing updates.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $settings_data The settings data to update.
			 * @param WP_REST_Request $request       The request object.
			 *
			 * @return array Modified settings data.
			 */
			$settings_data = apply_filters( 'WPPluginStarter_pre_update_settings_data', $settings_data, $request );

			if ( empty( $settings_data ) ) {
				return $this->response()->error(
					__( 'No settings data provided.', 'wp-plugin-starter' ),
					'WPPluginStarter_settings_empty',
					400
				);
			}

			// Process each setting individually
			foreach ( $settings_data as $name => $value ) {
				// Skip non-string keys, internal REST API parameters, etc.
				if ( ! is_string( $name ) || '_' === $name[0] ) {
					continue;
				}

				try {
					// Parse setting key in format "group.option" or "group.section.option"
					$parts = explode( '.', $name );

					if ( count( $parts ) < 2 ) {
						throw new \RuntimeException( __( 'Invalid setting key format. Expected format: group.option or group.section.option', 'wp-plugin-starter' ) );
					}

					$group_name  = $parts[0];
					$option_name = end( $parts );

					// Get or create settings model
					$settings = $this->data_store->get_settings_by_name( $group_name );
					if ( null === $settings ) {
						// Create new settings model
						$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
						$settings->set_name( $group_name );
						$new_values = array();
					} else {
						// Update existing settings
						$old_data   = $settings->get_value();
						$new_values = is_array( $old_data ) ? $old_data : array();
					}

					// Handle nested settings using dot notation
					if ( count( $parts ) > 2 ) {
						$this->set_nested_value( $new_values, array_slice( $parts, 1 ), $value );
					} else {
						// Simple key-value setting
						$new_values[ $option_name ] = $value;
					}

					/**
					 * Filter setting values before saving.
					 *
					 * @since 1.0.0
					 *
					 * @param array          $new_values Updated setting values.
					 * @param array          $old_data   Previous setting values.
					 * @param string         $group_name Group identifier.
					 * @param Settings_Model $settings   The settings object.
					 *
					 * @return array Modified setting values.
					 */
					$new_values = apply_filters(
						'WPPluginStarter_pre_save_setting_values',
						$new_values,
						$old_data ?? array(),
						$group_name,
						$settings
					);

					/**
					 * Action fired before saving a settings object.
					 *
					 * @since 1.0.0
					 *
					 * @param Settings_Model $settings   The settings object.
					 * @param string         $group_name Group identifier.
					 * @param array          $new_values The new values being saved.
					 */
					do_action( 'WPPluginStarter_before_save_settings', $settings, $group_name, $new_values );

					// Update and save settings using the model
					$settings->set_value( $new_values );
					$settings->save();

					// Track successfully updated settings
					$results[ $name ] = $value;

					/**
					 * Action fired after successfully updating a setting.
					 *
					 * @since 1.0.0
					 *
					 * @param string         $name       The setting key (dot notation).
					 * @param mixed          $value      The setting value.
					 * @param string         $group_name Group identifier.
					 * @param Settings_Model $settings   The settings object.
					 */
					do_action( 'WPPluginStarter_setting_updated', $name, $value, $group_name, $settings );

				} catch ( Throwable $e ) {
					$errors[ $name ] = $e->getMessage();

					/**
					 * Action fired when a setting update fails.
					 *
					 * @since 1.0.0
					 *
					 * @param string    $name    The setting key that failed.
					 * @param mixed     $value   The value that was attempted.
					 * @param Throwable $e       The exception that occurred.
					 */
					do_action( 'WPPluginStarter_setting_update_failed', $name, $value, $e );
				}
			}

			if ( ! empty( $errors ) ) {
				return $this->response()->error(
					__( 'Some settings failed to update.', 'wp-plugin-starter' ),
					'WPPluginStarter_settings_update_error',
					400,
					array(
						'errors'  => $errors,
						'updated' => $results,
					)
				);
			}

			/**
			 * Action fired after successfully updating all settings.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $results The successfully updated settings.
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_after_update_settings', $results, $request );

			return $this->response()->success(
				$results,
				__( 'SettingsModel updated successfully.', 'wp-plugin-starter' )
			);

		} catch ( Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to update settings.', 'wp-plugin-starter' ),
				'WPPluginStarter_settings_update_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Update settings for a specific group
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Request object with 'group' parameter
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_group_settings( WP_REST_Request $request ) {
		$group  = $request['group'];
		$params = $request->get_params();

		try {
			/**
			 * Action fired before updating a settings group.
			 *
			 * @since 1.0.0
			 *
			 * @param string          $group   Group identifier.
			 * @param array           $params  Request parameters.
			 * @param WP_REST_Request $request The request object.
			 */
			do_action( 'WPPluginStarter_before_update_settings_group', $group, $params, $request );

			// Remove REST API specifics
			unset( $params['group'] );

			/**
			 * Filter group settings data before updates.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $params  The settings data to update.
			 * @param string          $group   Group identifier.
			 * @param WP_REST_Request $request The request object.
			 *
			 * @return array Modified settings data.
			 */
			$params = apply_filters( 'WPPluginStarter_pre_update_group_settings', $params, $group, $request );

			// Get or create settings model
			$settings = $this->data_store->get_settings_by_name( $group );
			if ( null === $settings ) {
				// Create new settings model
				$settings = wp_plugin_starter_get_container()->get( Settings_Model::class );
				$settings->set_name( $group );
				$settings->set_value( $params );

				/**
				 * Action fired after creating a new settings group.
				 *
				 * @since 1.0.0
				 *
				 * @param Settings_Model  $settings The new settings object.
				 * @param string          $group    Group identifier.
				 * @param array           $params   The settings data.
				 * @param WP_REST_Request $request  The request object.
				 */
				do_action( 'WPPluginStarter_settings_group_created', $settings, $group, $params, $request );
			} else {
				// Update existing settings - merge with existing values
				$existing_values = $settings->get_value();
				if ( is_array( $existing_values ) ) {
					$settings->set_value( array_merge( $existing_values, $params ) );
				} else {
					$settings->set_value( $params );
				}

				/**
				 * Action fired before saving existing settings group.
				 *
				 * @since 1.0.0
				 *
				 * @param Settings_Model  $settings        The settings object.
				 * @param string          $group           Group identifier.
				 * @param array           $params          The new settings data.
				 * @param mixed           $existing_values Previous values.
				 * @param WP_REST_Request $request         The request object.
				 */
				do_action(
					'WPPluginStarter_before_save_settings_group',
					$settings,
					$group,
					$params,
					$existing_values,
					$request
				);
			}

			// Validate settings before saving (optional validation)
			$validation_result = $this->validate_settings( $settings );
			if ( is_wp_error( $validation_result ) ) {
				return $this->response()->error(
					__( 'SettingsModel validation failed.', 'wp-plugin-starter' ),
					'WPPluginStarter_settings_validation_error',
					400,
					array( 'validation_errors' => $validation_result->get_error_messages() )
				);
			}

			// Save using the model
			$settings->save();

			// Return updated settings
			$updated_settings = $this->prepare_setting_for_response( $settings, $request );

			/**
			 * Action fired after updating a settings group.
			 *
			 * @since 1.0.0
			 *
			 * @param array           $updated_settings The updated settings data.
			 * @param Settings_Model  $settings         The settings object.
			 * @param string          $group            Group identifier.
			 * @param WP_REST_Request $request          The request object.
			 */
			do_action(
				'WPPluginStarter_after_update_settings_group',
				$updated_settings,
				$settings,
				$group,
				$request
			);

			return $this->response()->success(
				$updated_settings,
				// translators: %s is the settings group name.
				sprintf( __( 'SettingsModel for group "%s" updated successfully.', 'wp-plugin-starter' ), $group )
			);

		} catch ( Throwable $e ) {
			return $this->response()->error(
				__( 'Failed to update settings group.', 'wp-plugin-starter' ),
				'WPPluginStarter_settings_update_error',
				500,
				array( 'error_details' => $e->getMessage() )
			);
		}
	}

	/**
	 * Set nested value in an array using path parts
	 *
	 * @since 1.0.0
	 *
	 * @param array  $array Reference to the array to modify
	 * @param array  $path  Path parts to the target element
	 * @param mixed  $value Value to set
	 *
	 * @return void
	 */
	protected function set_nested_value( &$array, array $path, $value ): void {
		$key = array_shift( $path );

		if ( empty( $path ) ) {
			// We've reached the target key, set the value
			$array[ $key ] = $value;
		} else {
			// Still traversing, ensure the next level exists
			if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
				$array[ $key ] = array();
			}

			// Recursive call to handle the next level
			$this->set_nested_value( $array[ $key ], $path, $value );
		}
	}

	/**
	 * Validate settings before saving
	 *
	 * @since 1.0.0
	 *
	 * @param Settings_Model $settings SettingsModel model to validate
	 *
	 * @return true|WP_Error True on success, WP_Error on validation failure
	 */
	protected function validate_settings( Settings_Model $settings ) {
		$group  = $settings->get_name();
		$values = $settings->get_value();
		$errors = new WP_Error();

		/**
		 * Filter settings validation for all groups.
		 *
		 * Allows custom validation for any settings group.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error       $errors   Validation errors, initially empty.
		 * @param array          $values   The settings values to validate.
		 * @param string         $group    The settings group name.
		 * @param Settings_Model $settings The settings object.
		 *
		 * @return WP_Error Error object with any validation errors.
		 */
		$errors = apply_filters( 'WPPluginStarter_validate_settings', $errors, $values, $group, $settings );

		// Perform group-specific validation
		switch ( $group ) {
			case 'product':
				// Example validation for product settings
				if ( isset( $values['gallery_limit'] ) && ( ! is_numeric( $values['gallery_limit'] ) || $values['gallery_limit'] < 0 ) ) {
					$errors->add(
						'invalid_gallery_limit',
						__( 'Gallery limit must be a positive number.', 'wp-plugin-starter' )
					);
				}
				break;

			case 'vendor':
				// Example validation for vendor settings
				if ( isset( $values['commission_rate'] ) && ( ! is_numeric( $values['commission_rate'] ) || $values['commission_rate'] < 0 || $values['commission_rate'] > 100 ) ) {
					$errors->add(
						'invalid_commission_rate',
						__( 'Commission rate must be between 0 and 100.', 'wp-plugin-starter' )
					);
				}
				break;
		}

		/**
		 * Filter group-specific settings validation.
		 *
		 * Allows custom validation for a specific settings group.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_Error       $errors   Validation errors.
		 * @param array          $values   The settings values to validate.
		 * @param Settings_Model $settings The settings object.
		 *
		 * @return WP_Error Error object with any validation errors.
		 */
		$errors = apply_filters( "WPPluginStarter_validate_{$group}_settings", $errors, $values, $settings );

		return $errors->has_errors() ? $errors : true;
	}

	/**
	 * Prepare a setting object for API response
	 *
	 * @since 1.0.0
	 *
	 * @param Settings_Model  $item    Setting object
	 * @param WP_REST_Request $request Request object
	 *
	 * @return array
	 */
	public function prepare_setting_for_response( Settings_Model $item, WP_REST_Request $request ): array {
		$data = array(
			'id'          => $item->get_id(),
			'name'        => $item->get_name(),
			'value'       => $item->get_value(),
			'last_update' => $item->get_date_modified() ? $item->get_date_modified()->format( 'c' ) : null,
		);

		/**
		 * Filter setting data for API response.
		 *
		 * @since 1.0.0
		 *
		 * @param array           $data    Prepared setting data.
		 * @param Settings_Model  $item    The settings object.
		 * @param WP_REST_Request $request The request object.
		 *
		 * @return array Modified setting data.
		 */
		return apply_filters( 'WPPluginStarter_prepare_setting_response', $data, $item, $request );
	}

	/**
	 * Get the query params for collections
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = array(
			'orderby'  => array(
				'description' => __( 'Order by field.', 'wp-plugin-starter' ),
				'type'        => 'string',
				'default'     => 'name',
				'enum'        => array(
					'id',
					'name',
					'date_created',
					'date_modified',
				),
			),
			'order'    => array(
				'description' => __( 'Order direction.', 'wp-plugin-starter' ),
				'type'        => 'string',
				'default'     => 'ASC',
				'enum'        => array( 'ASC', 'DESC' ),
			),
			'per_page' => array(
				'description' => __( 'Number of items to fetch.', 'wp-plugin-starter' ),
				'type'        => 'integer',
				'default'     => -1,
				'minimum'     => -1,
			),
			'offset'   => array(
				'description' => __( 'Number of items to skip.', 'wp-plugin-starter' ),
				'type'        => 'integer',
				'default'     => 0,
				'minimum'     => 0,
			),
			'group'    => array(
				'description' => __( 'Filter settings by group.', 'wp-plugin-starter' ),
				'type'        => array( 'string', 'array' ),
				'items'       => array(
					'type' => 'string',
					'enum' => array( 'product', 'vendor', 'cart', 'shipping', 'general' ),
				),
			),
		);

		/**
		 * Filter collection parameters for settings endpoint.
		 *
		 * @since 1.0.0
		 *
		 * @param array $params Collection parameters.
		 *
		 * @return array Modified parameters.
		 */
		return apply_filters( 'WPPluginStarter_settings_collection_params', $params );
	}

	/**
	 * Get parameters for update requests
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_update_params() {
		$params = array(
			'settings' => array(
				'description' => __( 'SettingsModel data to update.', 'wp-plugin-starter' ),
				'type'        => 'object',
				'required'    => true,
			),
		);

		/**
		 * Filter update parameters for settings endpoint.
		 *
		 * @since 1.0.0
		 *
		 * @param array $params Update parameters.
		 *
		 * @return array Modified parameters.
		 */
		return apply_filters( 'WPPluginStarter_settings_update_params', $params );
	}

	/**
	 * Get item schema for settings
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'settings',
			'type'       => 'object',
			'properties' => array(
				'id'          => array(
					'description' => __( 'Unique identifier for the setting.', 'wp-plugin-starter' ),
					'type'        => 'integer',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
				'name'        => array(
					'description' => __( 'Setting name/group.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
				),
				'value'       => array(
					'description' => __( 'Setting value.', 'wp-plugin-starter' ),
					'type'        => array( 'object', 'string', 'number', 'boolean', 'array', 'null' ),
					'context'     => array( 'view', 'edit' ),
				),
				'last_update' => array(
					'description' => __( 'Last update timestamp.', 'wp-plugin-starter' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view' ),
					'readonly'    => true,
				),
			),
		);

		/**
		 * Filter schema for settings endpoint.
		 *
		 * @since 1.0.0
		 *
		 * @param array $schema SettingsModel schema.
		 *
		 * @return array Modified schema.
		 */
		return apply_filters( 'WPPluginStarter_settings_schema', $schema );
	}
}
