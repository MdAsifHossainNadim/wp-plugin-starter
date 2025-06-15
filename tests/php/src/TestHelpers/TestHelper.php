<?php

namespace WPPluginStarter\Tests\TestHelpers;

/**
 * Base test helper class with common functionality
 */
class TestHelper {
	/**
	 * Generate a random string
	 *
	 * @param int $length The length of the random string
	 * @return string
	 */
	public static function random_string( int $length = 10 ): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string     = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$string .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
		}

		return $string;
	}

	/**
	 * Generate a random feature slug
	 *
	 * @return string
	 */
	public static function random_slug(): string {
		return 'test_' . strtolower( self::random_string( 8 ) );
	}

	/**
	 * Generate a random array of features
	 *
	 * @param int $count Number of features to generate
	 * @return array
	 */
	public static function random_settings( int $count = 3 ): array {
		$settings = array();

		for ( $i = 0; $i < $count; $i++ ) {
			$key = 'key_' . self::random_string( 5 );

			// Randomly choose type of value
			$type = rand( 0, 2 );

			switch ( $type ) {
				case 0:
					$settings[ $key ] = self::random_string();
					break;
				case 1:
					$settings[ $key ] = (bool) rand( 0, 1 );
					break;
				case 2:
					$settings[ $key ] = rand( 1, 100 );
					break;
			}
		}

		return $settings;
	}
}
