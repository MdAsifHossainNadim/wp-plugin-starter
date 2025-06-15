<?php

namespace WPPluginStarter\Tests\TestHelpers;

use WPPluginStarter\Core\FeatureRegistry;
use WPPluginStarter\Features\Feature_Interface;

/**
 * Feature Test Helper
 *
 * Helper methods for testing features
 */
class FeatureTestHelper {
	/**
	 * Create a basic feature implementation class
	 *
	 * @param string $name Feature name
	 *
	 * @return string The class name
	 */
	public static function create_feature_class( string $name = 'TestFeature' ): string {
		$class_name = "WP_Plugin_Starter\\Tests\\TestFeatures\\{$name}";

		if ( ! class_exists( $class_name ) ) {
			eval(
				"
				namespace WP_Plugin_Starter\\Tests\\TestFeatures;

				use WP_Plugin_Starter\\Features\\FeatureInterface;

				class {$name} implements FeatureInterface {
					public function init() {
						// Test initialization
					}
				}
			"
			);
		}

		return $class_name;
	}

	/**
	 * Create a test feature registry with mock features
	 *
	 * @param array $feature_ids Feature IDs to register
	 * @param bool  $enabled     Whether to enable features by default
	 *
	 * @return FeatureRegistry
	 */
	public static function create_registry_with_features( array $feature_ids = array( 'test_feature' ), bool $enabled = true ): FeatureRegistry {
		$registry = new FeatureRegistry();

		foreach ( $feature_ids as $id ) {
			$class = self::create_feature_class( $id );
			$registry->register( $id, $class, array(), $enabled );
		}

		return $registry;
	}

	/**
	 * Create a mock feature with initialization tracking
	 *
	 * @param string $id Feature ID
	 *
	 * @return object Mock feature instance
	 */
	public static function create_mock_feature( string $id = 'test_feature' ): object {
		return new class() implements Feature_Interface {
			public bool $initialized = false;

			public function init(): void {
				$this->initialized = true;
			}

			public function is_enabled(): bool {
				return true;
			}

			public function get_name(): string {
				return 'Test Feature';
			}

			public function get_description(): string {
				return 'Test feature description';
			}
		};
	}
}
