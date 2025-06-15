<?php

namespace WPPluginStarter\Tests\TestHelpers\Data\Fixtures;

use WPPluginStarter\Core\Data\Models\Settings_Model;
use WPPluginStarter\Core\Data\Stores\Settings_Data_Store;

/**
 * Fixture Factory
 *
 * Creates test fixtures for models
 */
class FixtureFactory {
	/**
	 * Create a test settings object
	 *
	 * @param array $overrides Properties to override defaults
	 * @param bool  $save      Whether to save to data store
	 *
	 * @return Settings_Model
	 */
	public static function create_settings( array $overrides = array(), bool $save = true ): Settings_Model {
		// Default properties
		$properties = array(
			'name'        => 'test-setting-' . uniqid(),
			'group'       => 'test',
			'description' => 'A test setting',
			'values'      => array( 'value' => 'test' ),
			'default'     => null,
		);

		// Apply overrides
		$properties = array_merge( $properties, $overrides );

		// Create settings
		$settings = new Settings_Model();

		foreach ( $properties as $key => $value ) {
			$setter = 'set_' . $key;
			if ( method_exists( $settings, $setter ) ) {
				if ( is_array( $value ) || ( 'set_values' !== $setter ) ) {
					$settings->$setter( $value );
				} else {
					$settings->$setter( array( 'value' => $value ) );
				}
			}
		}

		// Save to data store if requested
		if ( $save ) {
			// Get data store from container
			$data_store = null;
			if ( function_exists( 'WP_Plugin_Starter_get_container' ) ) {
				$data_store = WP_Plugin_Starter_get_container()->get( Settings_Data_Store::class );
			} else {
				$data_store = new Settings_Data_Store();
			}

			$data_store->create( $settings );
		}

		return $settings;
	}

	/**
	 * Create multiple test settings
	 *
	 * @param int   $count     Number of settings to create
	 * @param array $overrides Properties to override defaults
	 * @param bool  $save      Whether to save to data store
	 *
	 * @return Settings_Model[]
	 */
	public static function create_multiple_settings( int $count = 3, array $overrides = array(), bool $save = true ): array {
		$settings_array = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$setting_overrides = $overrides;

			// Add index to name if not provided in overrides
			if ( ! isset( $setting_overrides['name'] ) ) {
				$setting_overrides['name'] = 'test-setting-' . ( $i + 1 );
			}

			$settings_array[] = self::create_settings( $setting_overrides, $save );
		}

		return $settings_array;
	}
}
