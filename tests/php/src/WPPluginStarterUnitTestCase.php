<?php

namespace WPPluginStarter\Tests;

use WP_Error;
use WP_UnitTestCase;
use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Tests\TestHelpers\Data\Fixtures\FixtureFactory;
use WPPluginStarter\Tests\TestHelpers\FeatureTestHelper;
use WPPluginStarter\Tests\TestHelpers\HooksTestHelper;
use WPPluginStarter\Tests\TestHelpers\WooCommerceTestHelper;
use WPPluginStarter\Tests\TestHelpers\DokanTestHelper;

/**
 * Base test case for all WP Plugin Starter tests
 */
abstract class WPPluginStarterUnitTestCase extends WP_UnitTestCase {
	/**
	 * WooCommerce test helper
	 *
	 * @var WooCommerceTestHelper
	 */
	protected WooCommerceTestHelper $wc;

	/**
	 * Dokan test helper
	 *
	 * @var DokanTestHelper
	 */
	protected DokanTestHelper $dokan;

	/**
	 * Feature test helper
	 *
	 * @var FeatureTestHelper
	 */
	protected FeatureTestHelper $feature_helper;

	/**
	 * Fixtures factory
	 *
	 * @var FixtureFactory
	 */
	protected FixtureFactory $fixtures;

	/**
	 * Set up before each test
	 */
	public function setUp(): void {
		parent::setUp();

		// Initialize helpers
		$this->wc             = new WooCommerceTestHelper();
		$this->dokan          = new DokanTestHelper();
		$this->feature_helper = new FeatureTestHelper();
		$this->fixtures       = new FixtureFactory();

		// Additional setup
		$this->additional_setup();
	}

	/**
	 * Additional setup - can be overridden by child classes
	 *
	 * @return void
	 */
	protected function additional_setup(): void {
		// To be overridden by child classes if needed
	}

	/**
	 * Create a test settings object
	 *
	 * @param array $overrides
	 * @param bool  $save
	 *
	 * @return Settings_Model
	 */
	protected function create_settings( array $overrides = array(), bool $save = true ): Settings_Model {
		return FixtureFactory::create_settings( $overrides, $save );
	}

	/**
	 * Create multiple test settings
	 *
	 * @param int   $count
	 * @param array $overrides
	 * @param bool  $save
	 *
	 * @return Settings_Model[]
	 */
	protected function create_settings_multiple( int $count = 3, array $overrides = array(), bool $save = true ): array {
		return FixtureFactory::create_multiple_settings( $count, $overrides, $save );
	}

	/**
	 * Replace real data stores with mocks
	 *
	 * This method can be used to replace the container bindings in tests
	 *
	 * @return void
	 */
	protected function use_mock_data_stores(): void {
		// This method is now a placeholder.
		// Individual tests should implement their own mocking as needed
	}

	/**
	 * Create a test feature registry with mock features
	 *
	 * @param array $features Feature IDs to register
	 * @param bool  $enabled  Whether to enable features by default
	 *
	 * @return \WPPluginStarter\Core\FeatureRegistry
	 */
	protected function create_feature_registry( array $features = array( 'test_feature' ), bool $enabled = true ): \WPPluginStarter\Core\FeatureRegistry {
		return FeatureTestHelper::create_registry_with_features( $features, $enabled );
	}

	/**
	 * Create a hooks spy for testing actions
	 *
	 * @param string $hook_name Hook name to spy on
	 *
	 * @return array [callback, calls]
	 */
	protected function spy_on_action( string $hook_name ): array {
		[ $callback, $calls ] = HooksTestHelper::create_action_spy();
		HooksTestHelper::spy_on_action( $hook_name, $callback );

		return array( $callback, $calls );
	}

	/**
	 * Create a hooks spy for testing filters
	 *
	 * @param string $hook_name   Hook name to spy on
	 * @param mixed  $return_value Value to return from filter
	 *
	 * @return array [callback, calls, return_value]
	 */
	protected function spy_on_filter( string $hook_name, $return_value = null ): array {
		[ $callback, $calls, $return ] = HooksTestHelper::create_filter_spy( $return_value );
		HooksTestHelper::spy_on_filter( $hook_name, $callback );

		return array( $callback, $calls, $return );
	}

	/**
	 * Create a WooCommerce product for testing
	 *
	 * @param array $args Product arguments
	 *
	 * @return \WC_Product|null
	 */
	protected function create_wc_product( array $args = array() ): ?\WC_Product {
		return WooCommerceTestHelper::create_product( $args );
	}

	/**
	 * Create a Dokan vendor for testing
	 *
	 * @param array $args Vendor arguments
	 *
	 * @return int|WP_Error Vendor ID or WP_Error
	 */
	protected function create_vendor( array $args = array() ) {
		return DokanTestHelper::create_vendor( $args );
	}
}
