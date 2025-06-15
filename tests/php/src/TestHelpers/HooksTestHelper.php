<?php

namespace WPPluginStarter\Tests\TestHelpers;

/**
 * Hooks Test Helper
 *
 * Helper methods for testing WordPress hooks
 */
class HooksTestHelper {
	/**
	 * Create an action spy
	 *
	 * @return array [callback, calls]
	 */
	public static function create_action_spy(): array {
		$calls = array();

		$callback = function ( ...$args ) use ( &$calls ) {
			$calls[] = $args;
		};

		return array( $callback, &$calls );
	}

	/**
	 * Create a filter spy
	 *
	 * @param mixed $return_value Value to return from filter
	 *
	 * @return array [callback, calls, return]
	 */
	public static function create_filter_spy( $return_value = null ): array {
		$calls  = array();
		$return = $return_value;

		$callback = function ( $value, ...$args ) use ( &$calls, &$return ) {
			$args    = array_merge( array( $value ), $args );
			$calls[] = $args;

			return $return;
		};

		return array( $callback, &$calls, &$return );
	}

	/**
	 * Add action spy
	 *
	 * @param string   $hook_name Hook name
	 * @param callable $callback  Callback function
	 * @param int      $priority  Priority
	 *
	 * @return bool Whether action was added
	 */
	public static function spy_on_action( string $hook_name, callable $callback, int $priority = 10 ): bool {
		return add_action( $hook_name, $callback, $priority, 99 );
	}

	/**
	 * Add filter spy
	 *
	 * @param string   $hook_name Hook name
	 * @param callable $callback  Callback function
	 * @param int      $priority  Priority
	 *
	 * @return bool Whether filter was added
	 */
	public static function spy_on_filter( string $hook_name, callable $callback, int $priority = 10 ): bool {
		return add_filter( $hook_name, $callback, $priority, 99 );
	}

	/**
	 * Remove all callbacks from a hook
	 *
	 * @param string $hook_name Hook name
	 */
	public static function clear_hook( string $hook_name ): void {
		global $wp_filter;

		if ( isset( $wp_filter[ $hook_name ] ) ) {
			unset( $wp_filter[ $hook_name ] );
		}
	}

	/**
	 * Check if a hook has been fired
	 *
	 * @param string $hook_name Hook name
	 * @param array  $calls     Array of calls from a spy
	 *
	 * @return bool Whether the hook was fired
	 */
	public static function has_hook_fired( string $hook_name, array $calls ): bool {
		return ! empty( $calls );
	}
}
