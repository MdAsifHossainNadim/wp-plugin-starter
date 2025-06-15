<?php
/**
 * Admin Dashboard Template
 *
 * @since   1.0.0
 * @package WP_Plugin_Starter
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This template is a simple container that loads our React app with React Router.
 * All the actual dashboard content is now handled by the React components.
 */
?>

	<div id="wp-plugin-starter-admin-root" class="wp-plugin-starter-admin-page">
		<?php
		// Add a loading placeholder that will be replaced by React
		?>
		<div class="wp-plugin-starter-loading-placeholder dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-12">
			<div class="dk-inline-block dk-animate-spin dk-h-8 dk-w-8 dk-text-primary-600">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle class="dk-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="dk-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
			</div>
			<p class="dk-mt-wp-4 dk-text-sm dk-text-gray-600">
				<?php echo esc_html__( 'Loading WP Plugin Starter...', 'wp-plugin-starter' ); ?>
			</p>
		</div>
	</div>

<?php
/**
 * Notes for developers:
 *
 * The previous dashboard content has been moved to React components:
 * - Dashboard statistics are loaded via the REST API
 * - Welcome panel is rendered by the DashboardPage component
 * - Statistics cards are rendered by the DashboardPage component
 * - SettingsModel management buttons are rendered by the DashboardPage component
 * - Documentation & Support links are rendered by the DashboardPage component
 * - Import/Export functionality is handled by the DashboardPage component
 *
 * @see src/admin/app.js
 */
