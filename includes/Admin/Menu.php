<?php

namespace WPPluginStarter\Admin;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Menu Class
 *
 * Handles admin menu registration for WP Plugin Starter
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin
 */
class Menu implements Hookable {

	/**
	 * Register hooks for admin menu
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before admin menu hooks registration
		 *
		 * @since 1.0.0
		 * @param Menu $this Menu instance
		 */
		do_action( 'wp_plugin_starter_before_admin_menu_hooks', $this );

		add_filter( 'wp_plugin_starter_admin_menu_icon', array( $this, 'get_svg_icon' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		/**
		 * Action after admin menu hooks registration
		 *
		 * @since 1.0.0
		 * @param Menu $this Menu instance
		 */
		do_action( 'wp_plugin_starter_after_admin_menu_hooks', $this );
	}

	/**
	 * Add WP Plugin Starter admin menu
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_menu(): void {
		global $submenu;

		/**
		 * Action before admin menu is added
		 *
		 * @since 1.0.0
		 */
		do_action( 'wp_plugin_starter_before_admin_menu' );

		$menu_position = $this->get_menu_position();
		$capability    = $this->get_capability();
		$slug          = 'wp-plugin-starter';

		// Menu icon as SVG data URI
		$menu_icon = 'dashicons-plugins';

		/**
		 * Filter the admin menu icon
		 *
		 * @since 1.0.0
		 * @param string $menu_icon Menu icon
		 */
		$menu_icon = apply_filters( 'wp_plugin_starter_admin_menu_icon', $menu_icon );

		// Add main menu page
		$dashboard = add_menu_page(
			__( 'WP Plugin Starter', 'wp-plugin-starter' ),
			__( 'WP Plugin Starter', 'wp-plugin-starter' ),
			$capability,
			$slug,
			array( $this, 'render_dashboard_page' ),
			$menu_icon,
			$menu_position
		);

		if ( current_user_can( $capability ) ) {
			// phpcs:disable
			$submenu[$slug][] = array( __('Dashboard', 'wp-plugin-starter'), $capability, 'admin.php?page=' . $slug . '#/' );
			$submenu[$slug][] = array( __('Features', 'wp-plugin-starter'), $capability, 'admin.php?page=' . $slug . '#/features' );

			// Add about submenu
			$submenu[$slug][] = array( __('About', 'wp-plugin-starter'), $capability, 'admin.php?page=' . $slug . '#/about' );

			/**
			 * Filter admin menu submenu items
			 *
			 * @since 1.0.0
			 * @param array $submenu_items Submenu items
			 * @param string $capability Menu capability
			 * @param string $slug Menu slug
			 */
			$submenu[$slug] = apply_filters( 'wp_plugin_starter_admin_menu_submenu', $submenu[$slug], $capability, $slug );
		}

		/**
		 * Action for adding custom menu items
		 *
		 * @since 1.0.0
		 * @param string $capability Menu capability
		 * @param int $menu_position Menu position
		 */
		do_action( 'wp_plugin_starter_admin_menu', $capability, $menu_position );

		// Enqueue dashboard scripts
		add_action( $dashboard, array( $this, 'dashboard_scripts' ) );

		/**
		 * Action after admin menu is added
		 *
		 * @since 1.0.0
		 * @param string $dashboard Hook suffix for the dashboard page
		 */
		do_action( 'wp_plugin_starter_after_admin_menu', $dashboard );
	}

	/**
	 * Get menu capability
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_capability(): string {
		/**
		 * Filter admin menu capability
		 *
		 * @since 1.0.0
		 * @param string $capability Default capability
		 */
		return apply_filters( 'wp_plugin_starter_menu_capability', 'manage_options' );
	}

	/**
	 * Get menu position
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_menu_position(): int {
		/**
		 * Filter admin menu position
		 *
		 * @since 1.0.0
		 * @param int $position Default position
		 */
		return apply_filters( 'wp_plugin_starter_menu_position', 56 );
	}

	/**
	 * Dashboard scripts and styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function dashboard_scripts(): void {
		/**
		 * Action before dashboard scripts are enqueued
		 *
		 * @since 1.0.0
		 */
		do_action( 'wp_plugin_starter_before_enqueue_admin_scripts' );

		do_action( 'wp_plugin_starter_enqueue_admin_scripts' );

		/**
		 * Action after dashboard scripts are enqueued
		 *
		 * @since 1.0.0
		 */
		do_action( 'wp_plugin_starter_after_enqueue_admin_scripts' );
	}

	/**
	 * Load Dashboard Template
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_dashboard_page(): void {
		/**
		 * Action before dashboard template is rendered
		 *
		 * @since 1.0.0
		 */
		do_action( 'wp_plugin_starter_before_dashboard_template' );

		ob_start();
		printf(
			'<div class="wrap"><div id="wp-plugin-starter-admin-root" class="wp-plugin-starter-dashboard" data-version="%s" data-loading-text="%s"></div></div>',
			esc_attr( WP_PLUGIN_STARTER_VERSION ),
			esc_attr__( 'Loading...', 'wp-plugin-starter' )
		);
		echo ob_get_clean();

		/**
		 * Action after dashboard template is rendered
		 *
		 * @since 1.0.0
		 */
		do_action( 'wp_plugin_starter_after_dashboard_template' );
	}

	/**
	 * Check for plugin updates
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function check_for_updates(): bool {
		/**
		 * Filter whether the plugin has updates
		 *
		 * @since 1.0.0
		 * @param bool $has_update Default update status
		 */
		return apply_filters( 'wp_plugin_starter_has_update', false );
	}

	/**
	 * Get SVG icon for the admin menu
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_svg_icon(): string {
		/**
		 * Filter to provide a custom SVG icon for the admin menu
		 *
		 * @since 1.0.0
		 *
		 * @param string $icon SVG icon data URI
		 */
		return apply_filters( 'wp_plugin_starter_admin_menu_icon_svg', 'dashicons-plugins' );
	}
}
