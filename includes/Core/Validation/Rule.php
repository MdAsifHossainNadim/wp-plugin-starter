<?php

namespace WPPluginStarter\Core\Validation;

/**
 * Validation Rule
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core\Validation
 */
class Rule {
	/**
	 * Required rule
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function required(): string {
		return 'required';
	}

	/**
	 * Email rule
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function email(): string {
		return 'email';
	}

	/**
	 * Numeric rule
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function numeric(): string {
		return 'numeric';
	}

	/**
	 * Integer rule
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function integer(): string {
		return 'integer';
	}

	/**
	 * Minimum value rule
	 *
	 * @since 1.0.0
	 *
	 * @param float $min Minimum value
	 *
	 * @return string
	 */
	public static function min( float $min ): string {
		return 'min:' . $min;
	}

	/**
	 * Maximum value rule
	 *
	 * @since 1.0.0
	 *
	 * @param float $max Maximum value
	 *
	 * @return string
	 */
	public static function max( float $max ): string {
		return 'max:' . $max;
	}

	/**
	 * Minimum length rule
	 *
	 * @since 1.0.0
	 *
	 * @param int $min Minimum length
	 *
	 * @return string
	 */
	public static function min_length( int $min ): string {
		return 'min_length:' . $min;
	}

	/**
	 * Maximum length rule
	 *
	 * @since 1.0.0
	 *
	 * @param int $max Maximum length
	 *
	 * @return string
	 */
	public static function max_length( int $max ): string {
		return 'max_length:' . $max;
	}

	/**
	 * URL rule
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function url(): string {
		return 'url';
	}

	/**
	 * In list rule
	 *
	 * @since 1.0.0
	 *
	 * @param array $values Valid values
	 *
	 * @return string
	 */
	public static function in( array $values ): string {
		return 'in:' . implode( ',', (array) $values );
	}

	/**
	 * Not in list rule
	 *
	 * @since 1.0.0
	 *
	 * @param array $values Invalid values
	 *
	 * @return string
	 */
	public static function not_in( array $values ): string {
		return 'not_in:' . implode( ',', (array) $values );
	}

	/**
	 * Regex pattern rule
	 *
	 * @since 1.0.0
	 *
	 * @param string $pattern Regular expression pattern
	 *
	 * @return string
	 */
	public static function regex( string $pattern ): string {
		return 'regex:' . $pattern;
	}

	/**
	 * Date rule
	 *
	 * @since 1.0.0
	 *
	 * @param string $format Date format
	 *
	 * @return string
	 */
	public static function date( string $format = 'Y-m-d' ): string {
		return 'date:' . $format;
	}

	/**
	 * Make rule chain
	 *
	 * @since 1.0.0
	 *
	 * @param array $rules Rules
	 *
	 * @return string
	 */
	public static function make( array $rules ): string {
		return implode( '|', (array) $rules );
	}
}
