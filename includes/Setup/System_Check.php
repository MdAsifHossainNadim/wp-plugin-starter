<?php
namespace WPPluginStarter\Setup;

use WPPluginStarter\Admin\Notices;
use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * System Check
 *
 * Checks system requirements for the plugin and provides extensible
 * architecture for third-party developers to add custom requirement checks.
 *
 * @since   1.0.0
 * @package WPPluginStarter\Setup
 */
class System_Check implements Hookable {
	/**
	 * Requirements.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $requirements = array(
		'php'         => '7.4',
		'wp'          => '5.8',
		'dokan_lite'  => '3.9.7',
		'woocommerce' => '6.0.0',
		'extensions'  => array(
			'curl'     => true,
			'json'     => true,
			'mbstring' => true,
		),
	);

	/**
	 * PHP error messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $php_errors = array();

	/**
	 * WordPress error messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $wp_errors = array();

	/**
	 * Plugin error messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $plugin_errors = array();

	/**
	 * Extension error messages.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $extension_errors = array();

	/**
	 * Notice ID for system requirements.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected string $notice_id = 'WPPluginStarter_system_requirements';

	/**
	 * Register hooks.
	 *
	 * Implements the Hookable interface to register all necessary hooks
	 * for the System Check functionality.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		add_action( 'admin_init', array( $this, 'maybe_display_admin_notice' ), 20 );
	}

	/**
	 * Get system requirements.
	 *
	 * @since 1.0.0
	 * @return array System requirements.
	 */
	public function get_requirements(): array {
		/**
		 * Filter the system requirements
		 *
		 * Allows third-party developers to modify or add additional
		 * system requirements for WP Plugin Starter.
		 *
		 * @since 1.0.0
		 * @param array $requirements The system requirements array
		 */
		return apply_filters( 'WPPluginStarter_system_requirements', $this->requirements );
	}

	/**
	 * Get system status settings model.
	 *
	 * @since 1.0.0
	 * @return Settings_Model
	 * @throws \Exception If settings cannot be retrieved or created.
	 */
	protected function get_system_status(): Settings_Model {
		$data_store    = wp_plugin_starter_get_container()->get( Settings_Data_Store::class );
		$system_status = $data_store->get_settings_by_name( 'system_status' );

		// If we somehow still couldn't get the settings, create a fallback instance.
		if ( ! $system_status instanceof Settings_Model ) {
			$system_status = wp_plugin_starter_get_container()->get( Settings_Model::class );
			$system_status->set_name( 'system_status' );
			$system_status->set_value(
				array(
					'last_check_time' => 0,
					'status'          => 'unknown',
					'errors'          => array(),
				)
			);
			$system_status->save();
		}

		return $system_status;
	}

	/**
	 * Check system requirements.
	 *
	 * Performs comprehensive checks on PHP version, WordPress version,
	 * required plugins, and PHP extensions. Can be extended via filters.
	 *
	 * @since 1.0.0
	 * @return bool True if all requirements are met, false otherwise.
	 */
	public function check(): bool {
		/**
		 * Action hook that fires before system requirements check
		 *
		 * @since 1.0.0
		 * @param System_Check $this System_Check instance
		 */
		do_action( 'WPPluginStarter_before_system_check', $this );

		// Reset errors
		$this->php_errors       = array();
		$this->wp_errors        = array();
		$this->plugin_errors    = array();
		$this->extension_errors = array();

		// Get filtered requirements
		$requirements = $this->get_requirements();

		// Check PHP version.
		if ( version_compare( PHP_VERSION, $requirements['php'], '<' ) ) {
			$this->php_errors[] = sprintf(
				// translators: %1$s: Current PHP version, %2$s: Required PHP version.
				__( 'Current PHP version (%1$s) does not meet the minimum required version (%2$s).', 'wp-plugin-starter' ),
				PHP_VERSION,
				$requirements['php']
			);
		}

		// Check WordPress version.
		$wp_version = get_bloginfo( 'version' );
		if ( version_compare( $wp_version, $requirements['wp'], '<' ) ) {
			$this->wp_errors[] = sprintf(
				// translators: %1$s: Current WordPress version, %2$s: Required WordPress version.
				__( 'Current WordPress version (%1$s) does not meet the minimum required version (%2$s).', 'wp-plugin-starter' ),
				$wp_version,
				$requirements['wp']
			);
		}

		// Check Dokan Lite.
		if ( defined( 'DOKAN_PLUGIN_VERSION' ) ) {
			if ( version_compare( DOKAN_PLUGIN_VERSION, $requirements['dokan_lite'], '<' ) ) {
				$this->plugin_errors[] = sprintf(
					// translators: %1$s: Current Dokan version, %2$s: Required Dokan version.
					__( 'Current Dokan version (%1$s) does not meet the minimum required version (%2$s).', 'wp-plugin-starter' ),
					DOKAN_PLUGIN_VERSION,
					$requirements['dokan_lite']
				);
			}
		} else {
			$this->plugin_errors[] = __( 'Dokan Lite is not installed or activated.', 'wp-plugin-starter' );
		}

		// Check WooCommerce.
		if ( defined( 'WC_VERSION' ) ) {
			if ( version_compare( WC_VERSION, $requirements['woocommerce'], '<' ) ) {
				$this->plugin_errors[] = sprintf(
					// translators: %1$s: Current WooCommerce version, %2$s: Required WooCommerce version.
					__( 'Current WooCommerce version (%1$s) does not meet the minimum required version (%2$s).', 'wp-plugin-starter' ),
					WC_VERSION,
					$requirements['woocommerce']
				);
			}
		} else {
			$this->plugin_errors[] = __( 'WooCommerce is not installed or activated.', 'wp-plugin-starter' );
		}

		// Check extensions.
		foreach ( $requirements['extensions'] as $extension => $required ) {
			if ( $required && ! extension_loaded( $extension ) ) {
				$this->extension_errors[] = sprintf(
					// translators: %s: PHP extension name.
					__( 'Required PHP extension %s is missing.', 'wp-plugin-starter' ),
					$extension
				);
			}
		}

		/**
		 * Filter PHP errors
		 *
		 * @since 1.0.0
		 * @param array $php_errors PHP requirement errors
		 */
		$this->php_errors = apply_filters( 'WPPluginStarter_system_check_php_errors', $this->php_errors );

		/**
		 * Filter WordPress errors
		 *
		 * @since 1.0.0
		 * @param array $wp_errors WordPress requirement errors
		 */
		$this->wp_errors = apply_filters( 'WPPluginStarter_system_check_wp_errors', $this->wp_errors );

		/**
		 * Filter plugin errors
		 *
		 * @since 1.0.0
		 * @param array $plugin_errors Plugin dependency errors
		 */
		$this->plugin_errors = apply_filters( 'WPPluginStarter_system_check_plugin_errors', $this->plugin_errors );

		/**
		 * Filter PHP extension errors
		 *
		 * @since 1.0.0
		 * @param array $extension_errors PHP extension requirement errors
		 */
		$this->extension_errors = apply_filters( 'WPPluginStarter_system_check_extension_errors', $this->extension_errors );

		// Update system status.
		$this->update_system_status();

		$result = empty( $this->php_errors ) &&
					empty( $this->wp_errors ) &&
					empty( $this->plugin_errors ) &&
					empty( $this->extension_errors );

		/**
		 * Action hook that fires after system requirements check
		 *
		 * @since 1.0.0
		 * @param System_Check $this   System_Check instance
		 * @param bool         $result Whether all requirements are met
		 */
		do_action( 'WPPluginStarter_after_system_check', $this, $result );

		return $result;
	}

	/**
	 * Update system status.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function update_system_status(): void {
		$system_status = $this->get_system_status();

		$errors = $this->get_all_errors();
		$status = empty( $errors ) ? 'ok' : 'error';

		$status_data = array(
			'last_check_time' => time(),
			'status'          => $status,
			'errors'          => $errors,
		);

		/**
		 * Filter the system status data before saving
		 *
		 * @since 1.0.0
		 * @param array $status_data The system status data
		 * @param System_Check $this System_Check instance
		 */
		$status_data = apply_filters( 'WPPluginStarter_system_status_data', $status_data, $this );

		$system_status->set_value( $status_data );

		try {
			$system_status->save();

			/**
			 * Action hook that fires after system status is updated
			 *
			 * @since 1.0.0
			 * @param array $status_data The saved status data
			 * @param System_Check $this System_Check instance
			 */
			do_action( 'WPPluginStarter_system_status_updated', $status_data, $this );
		} catch ( \Exception $e ) {
			// Just log the error and continue. Not being able to save the status
			// shouldn't prevent the plugin from operating.
			// error_log( sprintf( 'Failed to save system status: %s', $e->getMessage() ) );
		}
	}

	/**
	 * Get all error messages.
	 *
	 * @since 1.0.0
	 * @return array All error messages.
	 */
	public function get_all_errors(): array {
		$errors = array_merge(
			$this->php_errors,
			$this->wp_errors,
			$this->plugin_errors,
			$this->extension_errors
		);

		/**
		 * Filter all system check errors
		 *
		 * @since 1.0.0
		 * @param array $errors All collected error messages
		 * @param System_Check $this System_Check instance
		 */
		return apply_filters( 'WPPluginStarter_system_check_errors', $errors, $this );
	}

	/**
	 * Check if we need to display an admin notice.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function maybe_display_admin_notice(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Re-run the check to ensure we have fresh results
		$check_result = $this->check();

		// Remove existing notice if requirements are met
		if ( $check_result ) {
			$notices = wp_plugin_starter_get_container()->get( Notices::class );
			$notices->remove_notice( $this->notice_id );
			return;
		}

		// Add notice with errors if requirements aren't met
		$this->display_system_requirements_notice();
	}

	/**
	 * Display system requirements notice using the Notices class.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function display_system_requirements_notice(): void {
		$errors = $this->get_all_errors();

		if ( empty( $errors ) ) {
			return;
		}

		/**
		 * Filter the admin notice title
		 *
		 * @since 1.0.0
		 * @param string $title The notice title
		 */
		$notice_title = apply_filters(
			'WPPluginStarter_system_check_notice_title',
			__( 'WP Plugin Starter - System Requirements Not Met', 'wp-plugin-starter' )
		);

		// Build notice message with title and error list
		$message  = sprintf( '<strong>%s</strong>', esc_html( $notice_title ) );
		$message .= '<ul class="wp-plugin-starter-requirements-errors">';

		foreach ( $errors as $error ) {
			$message .= '<li>' . esc_html( $error ) . '</li>';
		}

		$message .= '</ul>';

		/**
		 * Filter the complete notice message
		 *
		 * @since 1.0.0
		 * @param string $message The formatted notice message
		 * @param array  $errors  The error messages array
		 */
		$message = apply_filters( 'WPPluginStarter_system_requirements_notice_message', $message, $errors );

		// Add notice using the Notices API
		$notices = wp_plugin_starter_get_container()->get( Notices::class );
		$notices->error( $message, true, $this->notice_id, true );

		/**
		 * Action hook that fires after system requirements notice is created
		 *
		 * @since 1.0.0
		 * @param array   $errors  The error messages
		 * @param Notices $notices The notices instance
		 */
		do_action( 'WPPluginStarter_system_requirements_notice_created', $errors, $notices );
	}
}
