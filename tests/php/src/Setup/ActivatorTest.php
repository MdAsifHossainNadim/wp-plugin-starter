<?php

namespace WPPluginStarter\Tests\Setup;

use WPPluginStarter\Core\Data\Models\Feature;
use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Setup\Activator;
use WPPluginStarter\Tests\WPPluginStarterUnitTestCase;

/**
 * Test case for the Activator class.
 *
 * @covers \WPPluginStarter\Setup\Activator
 */
class ActivatorTest extends WPPluginStarterUnitTestCase {
	/**
	 * @var Activator
	 */
	private $activator;

	/**
	 * Set up before each test
	 */
	public function setUp(): void {
		parent::setUp();

		// Replace real data stores with mocks
		$this->use_mock_data_stores();

		// Create activator instance
		$this->activator = new Activator();
	}

	/**
	 * Test activate method creates tables and adds options
	 */
	public function test_activate_creates_tables_and_adds_options() {
		// Spy on wp_cache_flush action to verify it's called
		$cache_flush_spy = $this->spy_on_filter( 'wp_cache_flush', true );

		// Spy on WP_Plugin_Starter_activated action to verify it's called
		$activated_action_spy = $this->spy_on_action( 'WP_Plugin_Starter_activated' );

		// Clear any mock data
		$this->clear_mock_data();

		// Run activation
		$this->activator->run();

		// Verify tables were created
		$this->assertTrue( $this->mock_feature_store->tables_created, 'Feature tables should be created' );
		$this->assertTrue( $this->mock_settings_store->tables_created, 'SettingsModel tables should be created' );

		// Verify default features were created
		$all_settings = $this->mock_settings_store->get_all();
		$this->assertNotEmpty( $all_settings, 'SettingsModel should not be empty after activation' );

		// Check for version features
		$has_version_setting = false;
		foreach ( $all_settings as $setting ) {
			if ( $setting->get_name() === 'version' ) {
				$has_version_setting = true;
				$this->assertEquals( 'system', $setting->get_group() );
				$values = $setting->get_values();
				$this->assertEquals( WP_Plugin_Starter_VERSION, $values['version'] );
				$this->assertEquals( 'active', $values['status'] );
				break;
			}
		}
		$this->assertTrue( $has_version_setting, 'Version setting should be created' );

		// Verify cache was flushed
		$this->assertEquals( 1, count( $cache_flush_spy[1] ), 'wp_cache_flush should be called once' );

		// Verify WP_Plugin_Starter_activated action was fired
		$this->assertEquals( 1, count( $activated_action_spy[1] ), 'WP_Plugin_Starter_activated action should be fired' );

		// Verify scheduled events were set up
		$this->assertTrue( wp_next_scheduled( 'WP_Plugin_Starter_daily_scheduled_events' ) > 0 );
		$this->assertTrue( wp_next_scheduled( 'WP_Plugin_Starter_weekly_scheduled_events' ) > 0 );
		$this->assertTrue( wp_next_scheduled( 'WP_Plugin_Starter_monthly_scheduled_events' ) > 0 );
	}

	/**
	 * Test initialize_settings creates default features
	 */
	public function test_initialize_settings_creates_default_settings() {
		// Clear any mock data
		$this->clear_mock_data();

		// Use reflection to access private method
		$method = new \ReflectionMethod( Activator::class, 'initialize_settings' );
		$method->setAccessible( true );
		$method->invoke( $this->activator );

		// Verify default features were created
		$all_settings = $this->mock_settings_store->get_all();
		$this->assertNotEmpty( $all_settings, 'SettingsModel should not be empty after initialization' );

		// Verify specific setting exists with correct defaults
		$has_vendor_checkbox = false;
		foreach ( $all_settings as $setting ) {
			if ( $setting->get_name() === 'remove_vendor_checkbox' ) {
				$has_vendor_checkbox = true;
				$this->assertEquals( 'vendor', $setting->get_group() );
				$this->assertFalse( $setting->get_value() );
				break;
			}
		}
		$this->assertTrue( $has_vendor_checkbox, 'remove_vendor_checkbox setting should be created' );
	}

	/**
	 * Test initialize_settings doesn't recreate features if they exist
	 */
	public function test_initialize_settings_doesnt_recreate_existing_settings() {
		// Create a test setting
		$this->create_settings(
			array(
				'name'   => 'remove_vendor_checkbox',
				'group'  => 'test_group',
				'values' => true,
			)
		);

		// Use reflection to access private method
		$method = new \ReflectionMethod( Activator::class, 'initialize_settings' );
		$method->setAccessible( true );
		$method->invoke( $this->activator );

		// Verify the existing setting wasn't changed
		$setting = new Settings_Model( 'remove_vendor_checkbox' );
		$this->assertEquals( 'test_group', $setting->get_group() );
		$this->assertTrue( $setting->get_value() );
	}
}
