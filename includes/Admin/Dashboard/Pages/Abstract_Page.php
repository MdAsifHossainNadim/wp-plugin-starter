<?php

namespace WPPluginStarter\Admin\Dashboard\Pages;

use WPPluginStarter\Admin\Dashboard\Pageable;
use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Abstract Page Class
 *
 * Base class for all admin dashboard pages
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Pages
 */
abstract class Abstract_Page implements Pageable, Hookable {

	/**
	 * Register the hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		if ( ! is_admin() ) {
			return;
		}

		/**
		 * Action before page hooks registration
		 *
		 * @since 1.0.0
		 * @param Abstract_Page $this Page instance
		 */
		do_action( 'WPPluginStarter_before_page_hooks_registration', $this );

		add_filter( 'WPPluginStarter_admin_dashboard_pages', array( $this, 'enlist' ) );

		/**
		 * Action after page hooks registration
		 *
		 * @since 1.0.0
		 * @param Abstract_Page $this Page instance
		 */
		do_action( 'WPPluginStarter_after_page_hooks_registration', $this );
	}

	/**
	 * Add this page to the list of dashboard pages
	 *
	 * @since 1.0.0
	 * @param array $pages Existing dashboard pages
	 *
	 * @return array Modified pages list
	 */
	public function enlist( $pages ) {
		/**
		 * Filter whether to enlist this page
		 *
		 * @since 1.0.0
		 * @param bool $should_enlist Whether to enlist the page
		 * @param Abstract_Page $this Page instance
		 * @param array $pages Existing dashboard pages
		 */
		$should_enlist = apply_filters( 'WPPluginStarter_should_enlist_page', true, $this, $pages );

		if ( ! $should_enlist ) {
			return $pages;
		}

		/**
		 * Action before page is enlisted
		 *
		 * @since 1.0.0
		 * @param Abstract_Page $this Page instance
		 * @param array $pages Existing dashboard pages
		 */
		do_action( 'WPPluginStarter_before_page_enlist', $this, $pages );

		$pages[] = $this;

		/**
		 * Filter the modified pages list after enlisting
		 *
		 * @since 1.0.0
		 * @param array $pages Modified dashboard pages
		 * @param Abstract_Page $this Page instance
		 */
		return apply_filters( 'WPPluginStarter_after_page_enlist', $pages, $this );
	}

	/**
	 * Get the page ID
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function get_id(): string;

	/**
	 * Get the menu arguments
	 *
	 * @since 1.0.0
	 * @param string $capability Menu capability.
	 * @param string $position Menu position.
	 *
	 * @return array
	 */
	abstract public function menu( string $capability, string $position ): array;

	/**
	 * Describe the settings
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function describe_settings(): void {
		/**
		 * Action when describing page settings
		 *
		 * @since 1.0.0
		 * @param Abstract_Page $this Page instance
		 */
		do_action( 'WPPluginStarter_describe_page_settings', $this );
	}

	/**
	 * Get the settings values
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function settings(): array;

	/**
	 * Get the scripts required by this page
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function scripts(): array;

	/**
	 * Get the styles required by this page
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function styles(): array;

	/**
	 * Register the page scripts and styles
	 *
	 * @since 1.0.0
	 * @return void
	 */
	abstract public function register(): void;
}
