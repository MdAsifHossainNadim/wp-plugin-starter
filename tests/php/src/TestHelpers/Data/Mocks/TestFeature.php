<?php

namespace WPPluginStarter\Tests\TestHelpers\Data\Mocks;

use WPPluginStarter\Features\Feature_Interface;

/**
 * Mock feature implementation for testing
 */
class TestFeature implements Feature_Interface {
	/**
	 * Feature features
	 *
	 * @var array
	 */
	private array $settings = array();

	/**
	 * Constructor
	 *
	 * @param array $settings Optional features
	 */
	public function __construct( array $settings = array() ) {
		$this->settings = $settings;
	}

	/**
	 * Initialize the feature
	 *
	 * @return void
	 */
	public function init(): void {
		// Mock implementation
	}

	/**
	 * Get a setting
	 *
	 * @param string $key     Setting key
	 * @param mixed  $default Default value
	 *
	 * @return mixed
	 */
	public function get_setting( string $key, $default = null ) {
		return $this->settings[ $key ] ?? $default;
	}

	/**
	 * Set a setting
	 *
	 * @param string $key   Setting key
	 * @param mixed  $value Setting value
	 *
	 * @return void
	 */
	public function set_setting( string $key, $value ): void {
		$this->settings[ $key ] = $value;
	}

	/**
	 * Get all features
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return $this->settings;
	}

	public function is_enabled(): bool {
		// TODO: Implement is_enabled() method.
		return '';
	}

	public function get_name(): string {
		// TODO: Implement get_name() method.
		return '';
	}

	public function get_description(): string {
		// TODO: Implement get_description() method.
		return '';
	}
}
