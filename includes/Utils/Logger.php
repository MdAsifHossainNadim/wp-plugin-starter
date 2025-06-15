<?php

namespace WPPluginStarter\Utils;

/**
 * Logger
 *
 * Enhanced logging utility using WC_Logger
 *
 * @since   1.0.0
 * @package WPPluginStarter\Utils
 */
class Logger {
	/**
	 * Log levels
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $levels = array(
		'emergency' => 0,
		'alert'     => 1,
		'critical'  => 2,
		'error'     => 3,
		'warning'   => 4,
		'notice'    => 5,
		'info'      => 6,
		'debug'     => 7,
	);

	/**
	 * WC_Logger instance
	 *
	 * @since 1.0.0
	 * @var \WC_Logger|null
	 */
	protected $wc_logger = null;

	/**
	 * Source for WC_Logger
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected string $source;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @param string $source Optional. Source identifier for WC_Logger. Default 'wp-plugin-starter'.
	 */
	public function __construct( string $source = 'wp-plugin-starter' ) {
		$this->source = $source;

		// Initialize WC_Logger if WooCommerce is active
		if ( WPPluginStarter_is_woocommerce_active() && function_exists( 'wc_get_logger' ) ) {
			$this->wc_logger = wc_get_logger();
		}
	}

	/**
	 * Set the source for WC_Logger
	 *
	 * @since 1.0.0
	 *
	 * @param string $source Source identifier
	 *
	 * @return Logger
	 */
	public function set_source( string $source ): Logger {
		$this->source = $source;

		return $this;
	}

	/**
	 * Log a message with emergency level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function emergency( string $message, array $context = array() ): void {
		$this->log( 'emergency', $message, $context );
	}

	/**
	 * Log a message with alert level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function alert( string $message, array $context = array() ): void {
		$this->log( 'alert', $message, $context );
	}

	/**
	 * Log a message with critical level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function critical( string $message, array $context = array() ): void {
		$this->log( 'critical', $message, $context );
	}

	/**
	 * Log a message with error level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function error( string $message, array $context = array() ): void {
		$this->log( 'error', $message, $context );
	}

	/**
	 * Log a message with warning level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function warning( string $message, array $context = array() ): void {
		$this->log( 'warning', $message, $context );
	}

	/**
	 * Log a message with notice level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function notice( string $message, array $context = array() ): void {
		$this->log( 'notice', $message, $context );
	}

	/**
	 * Log a message with info level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function info( string $message, array $context = array() ): void {
		$this->log( 'info', $message, $context );
	}

	/**
	 * Log a message with debug level
	 *
	 * @since 1.0.0
	 *
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function debug( string $message, array $context = array() ): void {
		$this->log( 'debug', $message, $context );
	}

	/**
	 * Log a message with specified level
	 *
	 * @since 1.0.0
	 *
	 * @param string $level   Log level
	 * @param string $message Message to log
	 * @param array  $context Additional context data
	 *
	 * @return void
	 */
	public function log( string $level, string $message, array $context = array() ): void {
		// Bail if WooCommerce logger is not available
		if ( ! $this->wc_logger ) {
			return;
		}

		/**
		 * Filter to determine if logging should occur.
		 *
		 * @since 1.0.0
		 *
		 * @param bool   $should_log Whether or not the message should be logged.
		 * @param string $level      The log level.
		 * @param string $message    The log message.
		 * @param array  $context    Additional context data.
		 * @param string $source     The log source identifier.
		 *
		 * @return bool
		 */
		if ( ! apply_filters( 'WPPluginStarter_should_log', true, $level, $message, $context, $this->source ) ) {
			return;
		}

		// Normalize level
		$level = strtolower( $level );

		// Check if level is valid
		if ( ! array_key_exists( $level, $this->levels ) ) {
			$level = 'info';
		}

		/**
		 * Filter the log level before logging.
		 *
		 * @since 1.0.0
		 *
		 * @param string $level    The log level.
		 * @param string $message  The log message.
		 * @param array  $context  Additional context data.
		 * @param string $source   The log source identifier.
		 *
		 * @return string
		 */
		$level = apply_filters( 'WPPluginStarter_log_level', $level, $message, $context, $this->source );

		/**
		 * Filter the log message before logging.
		 *
		 * @since 1.0.0
		 *
		 * @param string $message  The log message.
		 * @param string $level    The log level.
		 * @param array  $context  Additional context data.
		 * @param string $source   The log source identifier.
		 *
		 * @return string
		 */
		$message = apply_filters( 'WPPluginStarter_log_message', $message, $level, $context, $this->source );

		// Add source to context
		$context = array_merge( $context, array( 'source' => $this->source ) );

		/**
		 * Filter the log context before logging.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $context  Additional context data.
		 * @param string $level    The log level.
		 * @param string $message  The log message.
		 * @param string $source   The log source identifier.
		 *
		 * @return array
		 */
		$context = apply_filters( 'WPPluginStarter_log_context', $context, $level, $message, $this->source );

		/**
		 * Action that fires before logging occurs.
		 *
		 * @since 1.0.0
		 *
		 * @param string $level    The log level.
		 * @param string $message  The log message.
		 * @param array  $context  Additional context data.
		 * @param string $source   The log source identifier.
		 */
		do_action( 'WPPluginStarter_before_log', $level, $message, $context, $this->source );

		// Log using WC_Logger
		$this->wc_logger->log( $level, $message, $context );

		/**
		 * Action that fires after logging occurs.
		 *
		 * @since 1.0.0
		 *
		 * @param string $level    The log level.
		 * @param string $message  The log message.
		 * @param array  $context  Additional context data.
		 * @param string $source   The log source identifier.
		 */
		do_action( 'WPPluginStarter_after_log', $level, $message, $context, $this->source );
	}
}
