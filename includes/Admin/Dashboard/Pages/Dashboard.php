<?php

namespace WPPluginStarter\Admin\Dashboard\Pages;

/**
 * Dashboard Page Class
 *
 * Admin dashboard page for the plugin
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Pages
 */
class Dashboard extends Abstract_Page {

	/**
	 * Page structure
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $structure;

	/**
	 * Get the page ID
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_id(): string {
		/**
		 * Filter dashboard page ID
		 *
		 * @since 1.0.0
		 * @param string $id Page ID
		 */
		return apply_filters( 'WPPluginStarter_dashboard_page_id', 'dashboard' );
	}

	/**
	 * Get the menu arguments
	 *
	 * @since 1.0.0
	 * @param string $capability Menu capability
	 * @param string $position Menu position
	 *
	 * @return array
	 */
	public function menu( string $capability, string $position ): array {
		$menu = array(
			'page_title' => __( 'WP Plugin Starter Dashboard', 'wp-plugin-starter' ),
			'menu_title' => __( 'Dashboard', 'wp-plugin-starter' ),
			'route'      => $this->get_id(),
			'capability' => $capability,
			'position'   => 5,
			'hidden'     => true,
		);

		/**
		 * Filter dashboard menu arguments
		 *
		 * @since 1.0.0
		 * @param array $menu Menu arguments
		 * @param string $capability Menu capability
		 * @param string $position Menu position
		 */
		return apply_filters( 'WPPluginStarter_dashboard_menu', $menu, $capability, $position );
	}

	/**
	 * Describe the dashboard settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function describe_settings(): void {
		/**
		 * Action when describing dashboard settings
		 *
		 * @since 1.0.0
		 * @param Dashboard $this Dashboard page instance
		 */
		do_action( 'WPPluginStarter_describe_dashboard_settings', $this );
	}

	/**
	 * Get the dashboard settings
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function settings(): array {
		/**
		 * Filter dashboard settings
		 *
		 * @since 1.0.0
		 * @param array $settings Dashboard settings
		 */
		return apply_filters( 'WPPluginStarter_dashboard_settings', array() );
	}

	/**
	 * Get the scripts required for the dashboard
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function scripts(): array {
		$scripts = array( 'wp-plugin-starter-dashboard-app' );

		/**
		 * Filter dashboard scripts
		 *
		 * @since 1.0.0
		 * @param array $scripts Dashboard scripts
		 */
		return apply_filters( 'WPPluginStarter_dashboard_scripts', $scripts );
	}

	/**
	 * Get the styles required for the dashboard
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function styles(): array {
		$styles = array( 'wp-plugin-starter-dashboard-app' );

		/**
		 * Filter dashboard styles
		 *
		 * @since 1.0.0
		 * @param array $styles Dashboard styles
		 */
		return apply_filters( 'WPPluginStarter_dashboard_styles', $styles );
	}

	/**
	 * Register dashboard scripts and styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register(): void {
	}
}
