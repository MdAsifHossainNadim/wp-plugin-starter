<?php

namespace WPPluginStarter\Admin\Dashboard;

use WPPluginStarter\Admin\Dashboard\Pages\Abstract_Page;
use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Admin Dashboard
 *
 * Manages the admin dashboard pages for WPPluginStarter
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard
 */
class Dashboard implements Hookable {
	/**
	 * Admin pages
	 *
	 * @since 1.0.0
	 * @var array<Abstract_Page>
	 */
	protected array $pages = array();

	/**
	 * Dashboard script key
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected string $script_key = 'wp-plugin-starter-dashboard-app';

	/**
	 * Register hooks
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before dashboard hooks registration
		 *
		 * @since 1.0.0
		 * @param Dashboard $this Dashboard instance
		 */
		do_action( 'WPPluginStarter_before_dashboard_hooks', $this );

		// todo: will be implement in the future.
		// add_action( 'WPPluginStarter_admin_menu', array( $this, 'register_menu' ), 99, 2 );
		// add_action( 'WPPluginStarter_register_scripts', array( $this, 'register_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'maybe_redirect_to_dashboard' ), 30 );

		/**
		 * Action after dashboard hooks registration
		 *
		 * @since 1.0.0
		 * @param Dashboard $this Dashboard instance
		 */
		do_action( 'WPPluginStarter_after_dashboard_hooks', $this );
	}

	/**
	 * Get all pages
	 *
	 * @since 1.0.0
	 * @return array<Abstract_Page>
	 */
	public function get_pages(): array {
		/**
		 * Filter admin dashboard pages
		 *
		 * @since 1.0.0
		 * @param array $pages Dashboard pages
		 */
		$pages = apply_filters( 'WPPluginStarter_admin_dashboard_pages', $this->pages );

		if ( ! is_array( $pages ) ) {
			return $this->pages;
		}

		/**
		 * Action before dashboard pages validation
		 *
		 * @since 1.0.0
		 * @param array $pages Dashboard pages
		 */
		do_action( 'WPPluginStarter_before_dashboard_pages_validation', $pages );

		$validated_pages = array_filter(
			$pages,
			static function ( $page ) {
				if ( ! $page instanceof Pageable ) {
					throw new \InvalidArgumentException( esc_html__( 'The page must be an instance of Pageable.', 'wp-plugin-starter' ) );
				}
				return true;
			}
		);

		/**
		 * Filter validated dashboard pages
		 *
		 * @since 1.0.0
		 * @param array $validated_pages Validated dashboard pages
		 */
		return apply_filters( 'WPPluginStarter_validated_dashboard_pages', $validated_pages );
	}

	/**
	 * Get the scripts needed for all pages
	 *
	 * @since 1.0.0
	 * @return array<string>
	 */
	public function get_scripts(): array {
		$pages = $this->get_pages();

		/**
		 * Action before collecting dashboard scripts
		 *
		 * @since 1.0.0
		 * @param array $pages Dashboard pages
		 */
		do_action( 'WPPluginStarter_before_collect_dashboard_scripts', $pages );

		$scripts = array_unique(
			array_reduce(
				$pages,
				fn( $scripts, Abstract_Page $page ): array => array_merge(
					$scripts,
					$page->scripts(),
					array( $this->script_key )
				),
				array()
			)
		);

		/**
		 * Filter dashboard scripts
		 *
		 * @since 1.0.0
		 * @param array $scripts Dashboard scripts
		 * @param array $pages Dashboard pages
		 */
		return apply_filters( 'WPPluginStarter_dashboard_scripts', $scripts, $pages );
	}

	/**
	 * Get the styles needed for all pages
	 *
	 * @since 1.0.0
	 * @return array<string>
	 */
	public function get_styles(): array {
		$pages = $this->get_pages();

		/**
		 * Action before collecting dashboard styles
		 *
		 * @since 1.0.0
		 * @param array $pages Dashboard pages
		 */
		do_action( 'WPPluginStarter_before_collect_dashboard_styles', $pages );

		$styles = array_unique(
			array_reduce(
				$pages,
				fn( $styles, Abstract_Page $page ): array => array_merge(
					$styles,
					$page->styles(),
					array( $this->script_key )
				),
				array()
			)
		);

		/**
		 * Filter dashboard styles
		 *
		 * @since 1.0.0
		 * @param array $styles Dashboard styles
		 * @param array $pages Dashboard pages
		 */
		return apply_filters( 'WPPluginStarter_dashboard_styles', $styles, $pages );
	}

	/**
	 * Get all features.
	 *
	 * @since 1.0.0
	 * @return array<string, mixed>
	 */
	public function settings(): array {
		/**
		 * Action before collecting dashboard settings
		 *
		 * @since 1.0.0
		 */
		do_action( 'WPPluginStarter_before_dashboard_settings' );

		$dashboard_url = admin_url( 'admin.php?page=wp-plugin-starter' );
		$header_info   = array(
			'lite_version'  => WPPluginStarter_VERSION,
			'is_pro_exists' => WPPluginStarter()->is_pro_active(),
			'dashboard_url' => $dashboard_url,
		);

		// todo: will be implement in the future.
		// if ( WPPluginStarter()->is_pro_active() ) {
			// $header_info['pro_version']  = DOKAN_PRO_PLUGIN_VERSION;
			// $header_info['license_plan'] = WPPluginStarter_pro()->license->get_plan();
		// }

		/**
		 * Filter the admin setup guides header info
		 *
		 * @since 1.0.0
		 * @param array $header_info Header info
		 */
		$header_info = apply_filters( 'WPPluginStarter_admin_header_info', $header_info );

		$settings = array(
			'header_info' => apply_filters( 'WPPluginStarter_admin_setup_guides_header_info', $header_info ),
		);

		/**
		 * Action before collecting page settings
		 *
		 * @since 1.0.0
		 * @param array $settings Dashboard settings
		 */
		do_action( 'WPPluginStarter_before_dashboard_page_settings', $settings );

		foreach ( $this->get_pages() as $page ) {
			if ( ! $page instanceof Pageable ) {
				throw new \InvalidArgumentException( esc_html__( 'The page must be an instance of Pageable.', 'wp-plugin-starter' ) );
			}

			$page->describe_settings();

			$page_id = $page->get_id();

			/**
			 * Filter the features for a specific page.
			 *
			 * @since 1.0.0
			 * @param array  $settings The features.
			 * @param string $page_id The page ID.
			 * @param Pageable $page The page.
			 */
			$settings[ $page_id ] = apply_filters( 'WPPluginStarter_admin_dashboard_page_settings', $page->settings(), $page_id, $page );
		}

		/**
		 * Action after collecting page settings
		 *
		 * @since 1.0.0
		 * @param array $settings Dashboard settings
		 */
		do_action( 'WPPluginStarter_after_dashboard_page_settings', $settings );

		/**
		 * Filter the features.
		 *
		 * @since 1.0.0
		 * @param array<string, mixed> $settings The features.
		 */
		return apply_filters( 'WPPluginStarter_admin_dashboard_pages_settings', $settings );
	}

	/**
	 * Enqueue dashboard scripts.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts(): void {
		$screen = get_current_screen();

		if ( ! $screen instanceof \WP_Screen ) {
			return;
		}

		if ( 'toplevel_page_wp-plugin-starter' !== $screen->id ) {
			return;
		}

		/**
		 * Action before dashboard scripts enqueuing
		 *
		 * @since 1.0.0
		 * @param \WP_Screen $screen Current screen
		 */
		do_action( 'WPPluginStarter_before_dashboard_scripts', $screen );

		foreach ( $this->get_scripts() as $handle ) {
			wp_enqueue_script( $handle );
		}

		foreach ( $this->get_styles() as $handle ) {
			wp_enqueue_style( $handle );
		}

		/**
		 * Action after dashboard scripts enqueuing
		 *
		 * @since 1.0.0
		 * @param \WP_Screen $screen Current screen
		 */
		do_action( 'WPPluginStarter_after_dashboard_scripts', $screen );

		add_filter(
			'WPPluginStarter_admin_script_data',
			function ( $data ) {
				return array_merge_recursive(
					$this->settings(),
					$data
				);
			}
		);
	}

	/**
	 * Maybe redirect to dashboard on activation
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function maybe_redirect_to_dashboard(): void {
		/**
		 * Filter whether to redirect to dashboard on activation
		 *
		 * @since 1.0.0
		 * @param bool $should_redirect Whether to redirect
		 */
		$should_redirect = apply_filters( 'WPPluginStarter_should_redirect_to_dashboard', true );

		if ( ! $should_redirect || ! get_transient( 'WPPluginStarter_activation_redirect' ) ) {
			return;
		}

		/**
		 * Action before dashboard redirect
		 *
		 * @since 1.0.0
		 */
		do_action( 'WPPluginStarter_before_dashboard_redirect' );

		delete_transient( 'WPPluginStarter_activation_redirect' );

		wp_safe_redirect( admin_url( 'admin.php?page=wp-plugin-starter' ) );
		exit;
	}
}
