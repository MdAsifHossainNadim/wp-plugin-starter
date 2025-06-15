<?php
namespace WPPluginStarter\Utils;

/**
 * General utility functions for WP Plugin Starter.
 */
class Helpers {
    /**
     * Example: safely get an array value with a default.
     */
    public static function array_get(array $array, $key, $default = null) {
        return $array[$key] ?? $default;
    }

    /**
     * Check if a string starts with a given substring.
     */
    public static function starts_with(string $haystack, string $needle): bool {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    /**
     * Check if a string ends with a given substring.
     */
    public static function ends_with(string $haystack, string $needle): bool {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * Sanitize a value for safe output (HTML context).
     */
    public static function esc_html($value): string {
        return esc_html((string) $value);
    }

    /**
     * Convert a value to a boolean (strict type casting).
     */
    public static function to_bool($value): bool {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }

    // Add more utility methods as needed.
}
