<?php

namespace WPPluginStarter\Admin;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Hooks Class
 *
 * Handles admin hooks registration and callbacks
 *
 * @since   1.0.0
 * @package WPPluginStarter\Admin
 */
class Hooks implements Hookable {

	/**
	 * Setup hooks
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before admin hooks are registered
		 *
		 * @since 1.0.0
		 * @param Hooks $this Hooks instance
		 */
		do_action( 'WPPluginStarter_before_admin_hooks_registration', $this );

		// Plugin action links
		add_filter( 'plugin_action_links_' . WPPluginStarter_BASENAME, array( $this, 'plugin_action_links' ) );

		// Plugin row meta
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

		/**
		 * Action to register additional admin hooks
		 *
		 * @since 1.0.0
		 * @param Hooks $this Hooks instance
		 */
		do_action( 'WPPluginStarter_register_hooks', $this );

		/**
		 * Action after admin hooks are registered
		 *
		 * @since 1.0.0
		 * @param Hooks $this Hooks instance
		 */
		do_action( 'WPPluginStarter_after_admin_hooks_registration', $this );
	}

	/**
	 * Add plugin action links
	 *
	 * @since 1.0.0
	 *
	 * @param array $links Plugin action links
	 *
	 * @return array Modified action links
	 */
	public function plugin_action_links( array $links ): array {
		/**
		 * Filter the settings URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The settings URL.
		 */
		$settings_url = apply_filters( 'WPPluginStarter_settings_url', admin_url( 'admin.php?page=wp-plugin-starter' ) );

		/**
		 * Filter the settings text.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text The settings text.
		 */
		$settings_text = apply_filters( 'WPPluginStarter_settings_text', __( 'SettingsModel', 'wp-plugin-starter' ) );

		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			$settings_url,
			$settings_text
		);

		/**
		 * Filter the settings link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $settings_link The settings link.
		 * @param string $settings_url  The settings URL.
		 * @param string $settings_text The settings text.
		 */
		$settings_link = apply_filters( 'WPPluginStarter_settings_link', $settings_link, $settings_url, $settings_text );

		/**
		 * Action before plugin action links are modified
		 *
		 * @since 1.0.0
		 * @param array $links Original plugin action links
		 */
		do_action( 'WPPluginStarter_before_plugin_action_links', $links );

		array_unshift( $links, $settings_link );

		/**
		 * Filter the plugin action links.
		 *
		 * @since 1.0.0
		 *
		 * @param array $links The plugin action links.
		 */
		return apply_filters( 'WPPluginStarter_plugin_action_links', $links );
	}

	/**
	 * Add plugin row meta
	 *
	 * @since 1.0.0
	 *
	 * @param array  $links Plugin row meta
	 * @param string $file  Plugin file
	 *
	 * @return array Modified row meta
	 */
	public function plugin_row_meta( array $links, string $file ): array {
		if ( WPPluginStarter_BASENAME !== $file ) {
			return $links;
		}

		/**
		 * Action before plugin row meta is modified
		 *
		 * @since 1.0.0
		 * @param array $links Original plugin row meta
		 * @param string $file Plugin file
		 */
		do_action( 'WPPluginStarter_before_plugin_row_meta', $links, $file );

		/**
		 * Filter the documentation URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The documentation URL.
		 */
		$docs_url = apply_filters( 'WPPluginStarter_docs_url', 'https://wordpress.org/plugins/wp-plugin-starter/' );

		/**
		 * Filter the support URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $url The support URL.
		 */
		$support_url = apply_filters( 'WPPluginStarter_support_url', 'https://wordpress.org/support/plugin/wp-plugin-starter/' );

		/**
		 * Filter the documentation text.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text The documentation text.
		 */
		$docs_text = apply_filters( 'WPPluginStarter_docs_text', __( 'Documentation', 'wp-plugin-starter' ) );

		/**
		 * Filter the support text.
		 *
		 * @since 1.0.0
		 *
		 * @param string $text The support text.
		 */
		$support_text = apply_filters( 'WPPluginStarter_support_text', __( 'Support', 'wp-plugin-starter' ) );

		$row_meta = array(
			'docs'    => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( $docs_url ),
				$docs_text
			),
			'support' => sprintf(
				'<a href="%s" target="_blank">%s</a>',
				esc_url( $support_url ),
				$support_text
			),
		);

		/**
		 * Filter the plugin row meta.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $row_meta The plugin row meta.
		 * @param string $file     The plugin file.
		 */
		$row_meta = apply_filters( 'WPPluginStarter_plugin_row_meta', $row_meta, $file );

		/**
		 * Action after plugin row meta is modified
		 *
		 * @since 1.0.0
		 * @param array $row_meta Modified plugin row meta
		 * @param array $links Original plugin row meta
		 * @param string $file Plugin file
		 */
		do_action( 'WPPluginStarter_after_plugin_row_meta', $row_meta, $links, $file );

		return array_merge( $links, $row_meta );
	}
}
