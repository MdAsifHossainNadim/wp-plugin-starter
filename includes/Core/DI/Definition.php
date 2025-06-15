<?php

namespace WPPluginStarter\Core\DI;

use WPPluginStarter\Dependencies\League\Container\Definition\Definition as LeagueDefinition;

/**
 * Custom Definition class to support method injection
 *
 * This extends the League Container Definition class to add support for method injection
 * instead of constructor injection
 *
 * @package WPPluginStarter\Core\DI
 */
class Definition extends LeagueDefinition {
	/**
	 * The method that we use for dependency injection
	 */
	public const INJECTION_METHOD = 'init';

	/**
	 * Resolve a class using method injection instead of constructor injection
	 *
	 * @param string $concrete The concrete class to instantiate
	 *
	 * @return object
	 */
	protected function resolveClass( string $concrete ): object {
		$instance = new $concrete();
		$this->invokeInit( $instance );

		return $instance;
	}

	/**
	 * Invoke methods on resolved instance, including init
	 *
	 * @param object $instance The concrete instance
	 *
	 * @return object
	 */
	protected function invokeMethods( $instance ): object {
		$this->invokeInit( $instance );
		parent::invokeMethods( $instance );

		return $instance;
	}

	/**
	 * Invoke the init method on a resolved object
	 *
	 * Constructor injection causes backwards compatibility problems
	 * so we will rely on method injection via an init method
	 *
	 * @param object $instance The resolved object
	 *
	 * @return void
	 */
	private function invokeInit( $instance ): void {
		$resolved = $this->resolveArguments( $this->arguments );

		if ( method_exists( $instance, self::INJECTION_METHOD ) ) {
			call_user_func_array( array( $instance, self::INJECTION_METHOD ), $resolved );
		}
	}

	/**
	 * Forget the cached resolved object
	 *
	 * @return void
	 */
	public function forgetResolved(): void {
		$this->resolved = null;
	}
}
