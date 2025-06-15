<?php
/**
 * Admin SettingsModel App Template
 *
 * This template creates the container for the React-based features app.
 *
 * @since   1.0.0
 * @package WP_Plugin_Starter
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// SettingsModel page title
// $title = $title ?? __( 'WP Plugin Starter SettingsModel', 'wp-plugin-starter' );

// Check for features update notice
$settings_updated = isset( $_GET['features-updated'] ) && 'true' === $_GET['features-updated'];
?>

<div class="wrap wp-plugin-starter-admin-page">
    <!--<h1 class="wp-heading-inline"><?php /*echo esc_html( $title ); */ ?></h1>-->

	<?php if ( $settings_updated ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'SettingsModel updated successfully.', 'wp-plugin-starter' ); ?></p>
		</div>
	<?php endif; ?>

	<div id="wp-plugin-starter-settings-root" class="dk-mt-wp-4">
		<!-- React app will render here -->
		<div class="dk-flex dk-flex-col dk-items-center dk-justify-center dk-p-wp-12">
			<span class="dk-inline-block dk-animate-spin dk-h-8 dk-w-8 dk-text-primary-600">
				<!-- Loading spinner SVG -->
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle class="dk-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path class="dk-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
			</span>
			<p class="dk-mt-wp-4 dk-text-sm dk-text-gray-600">
				<?php esc_html_e( 'Loading features...', 'wp-plugin-starter' ); ?>
			</p>
		</div>
	</div>
</div>

<!-- SettingsModel modals container for React portals -->
<div id="wp-plugin-starter-settings-modals"></div>
