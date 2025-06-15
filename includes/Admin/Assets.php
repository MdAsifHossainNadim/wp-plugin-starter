<?php

namespace WPPluginStarter\Admin;

use _WP_Dependency;
use WPPluginStarter\Core\Interfaces\Hookable;
use Throwable;
use WP_Screen;
use WP_Scripts;
use WP_Styles;

/**
 * Assets Class
 *
 * Handles admin assets registration and enqueuing
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin
 */
class Assets implements Hookable {

	/**
	 * Initialize assets
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before admin assets hooks are registered
		 *
		 * @since 1.0.0
		 * @param Assets $this Assets instance
		 */
		do_action( 'wp_plugin_starter_before_admin_assets_hooks', $this );

		add_action( 'wp_plugin_starter_enqueue_admin_scripts', array( $this, 'register_all_scripts' ) );

		// Asset cleanup for plugin pages.
		add_action( 'admin_enqueue_scripts', array( $this, 'clean_third_party_deps' ), 999999999999 );
		add_action( 'admin_head', array( $this, 'clean_admin_content_section' ), 999999999999 );

		/**
		 * Action after admin assets hooks are registered
		 *
		 * @since 1.0.0
		 * @param Assets $this Assets instance
		 */
		do_action( 'wp_plugin_starter_after_admin_assets_hooks', $this );
	}

	/**
	 * Register all scripts
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_all_scripts(): void {
		$asset_file = plugin_dir_path( WP_PLUGIN_STARTER_FILE ) . '/build/admin/app.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset   = include $asset_file;
		$deps    = $asset['dependencies'] ?? array();
		$version = $asset['version'] ?? WP_PLUGIN_STARTER_VERSION;

		/**
		 * Filter the admin script dependencies
		 *
		 * @since 1.0.0
		 * @param array $deps Script dependencies
		 */
		$deps = apply_filters( 'wp_plugin_starter_admin_script_deps', $deps );

		/**
		 * Filter the admin script version
		 *
		 * @since 1.0.0
		 * @param string $version Script version
		 */
		$version = apply_filters( 'wp_plugin_starter_admin_script_version', $version );

		// Register scripts and styles specific to this page.
		wp_register_script(
			'wp-plugin-starter-dashboard-app',
			WP_PLUGIN_STARTER_BUILD_URL . '/admin/app.js',
			$deps,
			$version,
			true
		);

		wp_register_style(
			'wp-plugin-starter-dashboard-app',
			WP_PLUGIN_STARTER_BUILD_URL . '/admin/app.css',
			array( 'wp-components' ),
			$version
		);

		// Get the current initial path.
		$initial_path = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Get plugin data for front-end components.
		$plugin_data = array(
			'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
			'apiRoot'     => esc_url_raw( rest_url( 'wp-plugin-starter/v1' ) ),
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'assetsUrl'   => WP_PLUGIN_STARTER_ASSETS_URL,
			'adminUrl'    => admin_url( 'admin.php' ),
			'version'     => WP_PLUGIN_STARTER_VERSION,
			'initialPath' => $initial_path,
			'pageUrls'    => array(
				'dashboard' => admin_url( 'admin.php?page=wp-plugin-starter' ),
				'features'  => admin_url( 'admin.php?page=wp-plugin-starter-features' ),
				'about'     => admin_url( 'admin.php?page=wp-plugin-starter-about' ),
			),
		);

		/**
		 * Filter admin script data
		 *
		 * @since 1.0.0
		 * @param array $plugin_data Script data
		 */
		$plugin_data = apply_filters( 'wp_plugin_starter_admin_script_data', $plugin_data );

		wp_localize_script( 'wp-plugin-starter-dashboard-app', 'wp_plugin_starter', $plugin_data );
		wp_set_script_translations( 'wp-plugin-starter-dashboard-app', 'wp-plugin-starter' );

		/**
		 * Action after admin scripts are registered
		 *
		 * @since 1.0.0
		 * @param string $handle The script handle
		 */
		do_action( 'wp_plugin_starter_after_admin_scripts_registered', 'wp-plugin-starter-dashboard-app' );
	}

	/**
	 * Remove all notices from the plugin admin pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function clean_admin_content_section(): void {
		try {
			// Check if the current screen is available.
			if ( ! function_exists( '\get_current_screen' ) ) {
				return;
			}

			$screen = get_current_screen();

			if ( ! $screen instanceof WP_Screen || ! strpos( $screen->id, 'wp-plugin-starter' ) ) {
				return;
			}

			/**
			 * Filter whether to clean admin notices on plugin pages.
			 *
			 * @since 1.0.0
			 *
			 * @param bool      $should_clean Whether to clean admin notices.
			 * @param WP_Screen $screen       Current admin screen.
			 */
			$should_clean = apply_filters( 'wp_plugin_starter_should_clean_admin_notices', true, $screen );

			if ( ! $should_clean ) {
				return;
			}

			/**
			 * Fires before admin notices are cleaned.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Screen $screen Current admin screen.
			 */
			do_action( 'wp_plugin_starter_before_clean_admin_notices', $screen );

			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'all_admin_notices' );
			remove_all_actions( 'user_admin_notices' );

			/**
			 * Fires after admin notices are cleaned.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Screen $screen Current admin screen.
			 */
			do_action( 'wp_plugin_starter_after_clean_admin_notices', $screen );
		} catch ( Throwable $e ) {
			wp_plugin_starter_logger()->error( 'Failed to clean admin content section' );
		}
	}

	/**
	 * Remove all third party dependencies from the plugin admin pages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function clean_third_party_deps(): void {
		try {
			global $wp_scripts, $wp_styles;

			// Check if the current screen is available.
			if ( ! function_exists( '\get_current_screen' ) ) {
				return;
			}

			$screen = get_current_screen();

			if ( ! $screen instanceof WP_Screen || ! strpos( $screen->id, 'wp-plugin-starter' ) ) {
				return;
			}

			/**
			 * Filter whether to clean third party dependencies on plugin pages.
			 *
			 * @since 1.0.0
			 *
			 * @param bool $should_clean Whether to clean third party dependencies.
			 */
			$should_clean = apply_filters( 'wp_plugin_starter_should_clean_dependencies', true );

			if ( ! $should_clean ) {
				return;
			}

			/**
			 * Fires before third party dependencies are cleaned.
			 *
			 * @since 3.3.0
			 *
			 * @param WP_Scripts $wp_scripts WordPress scripts registry.
			 * @param WP_Styles  $wp_styles  WordPress styles registry.
			 */
			do_action( 'wp_plugin_starter_before_clean_dependencies', $wp_scripts, $wp_styles );

			// Dequeue the scripts and styles of the current page that are not required.
			$this->remove_unnecessary_dependencies( $wp_scripts );
			$this->remove_unnecessary_dependencies( $wp_styles );

			/**
			 * Fires after third party dependencies are cleaned.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_Scripts $wp_scripts WordPress scripts registry.
			 * @param WP_Styles  $wp_styles  WordPress styles registry.
			 */
			do_action( 'wp_plugin_starter_after_clean_dependencies', $wp_scripts, $wp_styles );
		} catch ( Throwable $e ) {
			wp_plugin_starter_logger()->error( 'Failed to clean third party dependencies' );
		}
	}

	/**
	 * Remove unnecessary styles from the current page.
	 *
	 * @since 1.0.0
	 * @param WP_Scripts|WP_Styles $root The Core class of dependencies.
	 *
	 * @return void
	 */
	public function remove_unnecessary_dependencies( $root ): void {
		try {
			// Get site url.
			$site_url = home_url( '/' );

			// Allowed plugin paths.
			$allowed_plugin_defaults = array( 'wp-console' );

			/**
			 * Filter the list of allowed plugin paths that won't be cleaned up.
			 *
			 * @since 1.0.0
			 *
			 * @param array                $allowed_plugin_paths The allowed plugin paths.
			 * @param WP_Scripts|WP_Styles $root                 The Core class of dependencies.
			 *
			 * @return array
			 */
			$allowed_plugin_paths = apply_filters( 'wp_plugin_starter_dependencies_cleaning_allowed_plugin_paths', $allowed_plugin_defaults, $root );

			/**
			 * Remove all dependencies of the current page that are not required.
			 *
			 * @see https://developer.wordpress.org/reference/classes/wp_styles/
			 * @see https://developer.wordpress.org/reference/classes/wp_scripts/
			 */
			foreach ( $root->registered as $dependency ) {
				if ( ! $dependency instanceof _WP_Dependency || false === $dependency->src ) {
					continue;
				}

				// Check if the dependency should be dequeued and removed.
				$should_remove = strpos( $dependency->handle, 'wp-plugin-starter-' ) !== 0 && strpos( $dependency->src, $site_url ) !== false;

				// Check allowed plugin paths.
				foreach ( $allowed_plugin_paths as $plugin_path ) {
					if ( strpos( $dependency->src, "wp-content/plugins/$plugin_path" ) !== false ) {
						$should_remove = false;
						break;
					}
				}

				/**
				 * Filter whether a specific dependency should be removed.
				 *
				 * @since 1.0.0
				 *
				 * @param bool                 $should_remove Whether to remove the dependency.
				 * @param _WP_Dependency       $dependency    The dependency.
				 * @param WP_Scripts|WP_Styles $root          The Core class of dependencies.
				 */
				$should_remove = apply_filters( 'wp_plugin_starter_should_remove_dependency', $should_remove, $dependency, $root );

				// Dequeue and remove the dependency if it should be removed.
				if ( ! $should_remove ) {
					continue;
				}

				// Dequeue the dependency and remove it from the registered list.
				$root->dequeue( $dependency->handle );
				$root->remove( $dependency->handle );
			}
		} catch ( Throwable $e ) {
			wp_plugin_starter_logger()->error( 'Failed to remove unnecessary dependencies' );
		}
	}
}
