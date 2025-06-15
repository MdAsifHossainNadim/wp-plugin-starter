<?php
/**
 * PHPUnit bootstrap file for WP Plugin Starter
 */

// Define plugin directories
define( 'TEST_WP_PLUGIN_STARTER_DIR', dirname( __DIR__, 2 ) );
define( 'TEST_WC_DIR', dirname( TEST_WP_PLUGIN_STARTER_DIR, 1 ) . '/woocommerce' );
define( 'TEST_DOKAN_DIR', dirname( TEST_WP_PLUGIN_STARTER_DIR, 1 ) . '/dokan-lite' );

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once TEST_WP_PLUGIN_STARTER_DIR . '/vendor/autoload.php';

// Define WordPress test environment path
$_tests_dir = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : getenv( 'WP_PHPUNIT__DIR' );

// If the environment variable is not set, try some common locations
if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';

	// Try other common locations if the default one doesn't exist
	if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
		$possible_dirs = array(
			// Linux & Travis CI
			'/tmp/wordpress-tests-lib',
			// Varying directory depths
			dirname( __DIR__, 1 ) . '/wordpress-tests-lib',
			dirname( __DIR__, 2 ) . '/wordpress-tests-lib',
			dirname( __DIR__, 3 ) . '/wordpress-tests-lib',
			// For WordPress developers
			dirname( __DIR__, 4 ) . '/tests/phpunit',
		);

		foreach ( $possible_dirs as $dir ) {
			if ( file_exists( $dir . '/includes/functions.php' ) ) {
				$_tests_dir = $dir;
				break;
			}
		}
	}
}

// Exit if tests directory not found
if ( ! $_tests_dir || ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
	exit( 1 );
}

/**
 * Truncate WP Plugin Starter tables for clean test runs
 */
function wp_plugin_starter_truncate_table_data(): void {
	$tables = array(
		'wp_plugin_starter_features',
		'wp_plugin_starter_settings',
		// Add other tables as needed
	);

	global $wpdb;
	foreach ( $tables as $table_name ) {
		$wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}{$table_name}" );
	}
}

// Give access to tests_add_filter() function
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugins being tested
 */
function _manually_load_plugin() {
	// Define constants needed by WooCommerce
	define( 'WC_TAX_ROUNDING_MODE', 'auto' );
	define( 'WC_USE_TRANSACTIONS', false );

	// Load WooCommerce if the directory exists
	if ( file_exists( TEST_WC_DIR . '/woocommerce.php' ) ) {
		require TEST_WC_DIR . '/woocommerce.php';
	}

	// Load Dokan Lite if the directory exists
	if ( file_exists( TEST_DOKAN_DIR . '/dokan.php' ) ) {
		require TEST_DOKAN_DIR . '/dokan.php';
	}

	// Load our plugin
	require TEST_WP_PLUGIN_STARTER_DIR . '/wp-plugin-starter.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/**
 * Install WooCommerce for testing
 */
function install_wc() {
	// Skip if WooCommerce doesn't exist
	if ( ! file_exists( TEST_WC_DIR . '/woocommerce.php' ) ) {
		return;
	}

	// Install WooCommerce
	define( 'WP_UNINSTALL_PLUGIN', true );
	define( 'WC_REMOVE_ALL_DATA', true );

	include TEST_WC_DIR . '/uninstall.php';

	WC_Install::install();

	// Reload capabilities after install
	if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
		$GLOBALS['wp_roles']->reinit();
	} else {
		$GLOBALS['wp_roles'] = null;
		wp_roles();
	}

	echo esc_html( 'Installing WooCommerce...' . PHP_EOL );
}

/**
 * Install WP Plugin Starter for testing
 */
function install_wp_plugin_starter() {
	echo 'Installing WP Plugin Starter...' . PHP_EOL;

	// Clean up existing tables
	wp_plugin_starter_truncate_table_data();

	// Activate the plugin
	if ( class_exists( 'WP_Plugin_Starter' ) ) {
		wp_plugin_starter()->activate();
	}
}

// Install dependencies and our plugin
tests_add_filter( 'setup_theme', 'install_wc' );
tests_add_filter( 'setup_theme', 'install_wp_plugin_starter' );

// Start up the WP testing environment
require $_tests_dir . '/includes/bootstrap.php';
