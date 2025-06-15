<?php

/**
 * Tests for the main WP_Plugin_Starter plugin class
 *
 * @package WPPluginStarter\Tests
 */

namespace WPPluginStarter\Tests;

use WP_Plugin_Starter;
use WPPluginStarter\Core\DI\Container;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;
use WPPluginStarter\Core\Interfaces\Hookable;
use WPPluginStarter\Setup\System_Check;

/**
 * Test class for the main WP_Plugin_Starter plugin
 */
class WPPluginStarterTest extends WPPluginStarterUnitTestCase {

	private WP_Plugin_Starter $plugin;

	public function setUp(): void {
		parent::setUp();
		$this->plugin = wp_plugin_starter();
	}

	public function tearDown(): void {
		parent::tearDown();

		// Clear the plugin instance
		unset( $this->plugin );
	}

	public function test_instance(): void {
		// Check if instance returns the correct class
		$this->assertInstanceOf( WP_Plugin_Starter::class, WP_Plugin_Starter::instance() );

		// Check if instance returns the same object
		$this->assertSame( WP_Plugin_Starter::instance(), WP_Plugin_Starter::instance() );
	}

	public function test_version(): void {
		$this->assertEquals( WP_PLUGIN_STARTER_VERSION, $this->plugin->version );
	}

	public function test_slug(): void {
		$this->assertEquals( 'wp-plugin-starter', $this->plugin->slug );
	}

	public function test_init(): void {
		// Call init method
		$this->plugin->init();

		$file = plugin_basename( WP_PLUGIN_STARTER_FILE );

		// Verify hooks were added
		$this->assertEquals( 10, has_action( 'before_woocommerce_init', array( $this->plugin, 'declare_compatibility' ) ) );
		$this->assertEquals( 10, has_action( 'init', array( $this->plugin, 'load_hookable_services' ) ) );
		$this->assertEquals( 10, has_filter( 'woocommerce_data_stores', array( $this->plugin, 'load_data_stores' ) ) );
		$this->assertEquals( 10, has_action( 'init', array( $this->plugin, 'boot' ) ) );
		$this->assertEquals( 10, has_action( 'activate_' . $file, array( $this->plugin, 'activate' ) ) );
		$this->assertEquals( 10, has_action( 'deactivate_' . $file, array( $this->plugin, 'deactivate' ) ) );
	}

	public function test_get_container(): void {
		// Get container
		$container = $this->plugin->get_container();

		// Verify container instance
		$this->assertInstanceOf( Container::class, $container );

		// Test filter
		$test_container = new Container();
		add_filter(
			'wp_plugin_starter_get_container',
			function ( $container, $plugin ) use ( $test_container ) {
				$this->assertInstanceOf( Container::class, $container );
				$this->assertInstanceOf( WP_Plugin_Starter::class, $plugin );
				return $test_container;
			},
			10,
			2
		);

		// Check if filter works
		$filtered_container = $this->plugin->get_container();
		$this->assertSame( $test_container, $filtered_container );
	}

	public function test_load_hookable_classes(): void {
		$hooks = wp_plugin_starter_get_container()->get( Hookable::class );
		foreach ( $hooks as $hook ) {
			$this->assertInstanceOf( Hookable::class, $hook );
			$this->assertTrue( method_exists( $hook, 'register_hooks' ) );
		}
	}

	public function test_load_data_stores(): void {
		// Initial data stores
		$stores = array(
			'product' => 'WC_Product_Data_Store_CPT',
		);

		// Call load_data_stores method
		$result = $this->plugin->load_data_stores( $stores );

		// Verify our data stores were added
		$this->assertArrayHasKey( 'wp_plugin_starter_settings', $result );
		$this->assertEquals( Settings_Data_Store::class, $result['wp_plugin_starter_settings'] );

		// Verify original stores are preserved
		$this->assertArrayHasKey( 'product', $result );
		$this->assertEquals( 'WC_Product_Data_Store_CPT', $result['product'] );
	}

	public function test_boot(): void {
		// Mock the initialize_data_stores method to verify it's called
		$plugin_mock = $this->getMockBuilder( WP_Plugin_Starter::class )
		                    ->setMethods( array( 'initialize_data_stores' ) )
						->disableOriginalConstructor()
						->getMock();

		// Set expectations
		$plugin_mock->expects( $this->once() )
		            ->method( 'initialize_data_stores' );

		// Add a system check that passes
		$system_check = $this->createMock( System_Check::class );
		$system_check->method( 'check' )->willReturn( true );

		// Use reflection to set container property
		$reflection         = new \ReflectionClass( $plugin_mock );
		$container_property = $reflection->getProperty( 'container' );
		$container_property->setAccessible( true );

		// Create container with system check mock
		$container = new Container();
		$container->add( System_Check::class, $system_check );

		// Set container property
		$container_property->setValue( $plugin_mock, $container );

		// Call boot method
		$plugin_mock->boot();
	}

	public function test_initialize_data_stores(): void {
		// Create mock data stores
		$settings_store = $this->createMock( Settings_Data_Store::class );
		$settings_store->expects( $this->once() )->method( 'initialize' );

		// Get container and replace data stores with mocks
		$container = wp_plugin_starter_get_container();
		$container->add( Settings_Data_Store::class, $settings_store );
		$container->addTag( Settings_Data_Store::class, 'data-store-service' );

		// Call the initialize_data_stores method
		$reflection = new \ReflectionClass( $this->plugin );
		$method     = $reflection->getMethod( 'initialize_data_stores' );
		$method->setAccessible( true );
		$method->invoke( $this->plugin );
	}
}
