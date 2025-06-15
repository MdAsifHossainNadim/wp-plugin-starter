<?php

namespace WPPluginStarter\Admin;

use WPPluginStarter\Core\Interfaces\Hookable;

/**
 * Notices Class
 *
 * Handles admin notices registration and display
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin
 */
class Notices implements Hookable {

	/**
	 * Notices
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $notices = array();

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->notices = get_option( 'WPPluginStarter_admin_notices', array() );

		// clean notice values
		$this->notices = $this->notices['value'] ?? $this->notices ?? array();

		/**
		 * Filter notices on initialization
		 *
		 * @since 1.0.0
		 * @param array $notices Notices array
		 */
		$this->notices = apply_filters( 'WPPluginStarter_admin_notices_init', $this->notices );
	}

	/**
	 * Initialize notices
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_hooks(): void {
		/**
		 * Action before notice hooks are registered
		 *
		 * @since 1.0.0
		 * @param Notices $this Notices instance
		 */
		do_action( 'WPPluginStarter_before_notice_hooks', $this );

		add_action( 'admin_notices', array( $this, 'display_notices' ) );
		add_action( 'wp_ajax_WPPluginStarter_dismiss_notice', array( $this, 'dismiss_notice' ) );

		/**
		 * Action after notice hooks are registered
		 *
		 * @since 1.0.0
		 * @param Notices $this Notices instance
		 */
		do_action( 'WPPluginStarter_after_notice_hooks', $this );
	}

	/**
	 * Add notice
	 *
	 * @since 1.0.0
	 * @param string $message     Notice message
	 * @param string $type        Notice type (success, error, warning, info)
	 * @param bool   $dismissible Whether the notice is dismissible
	 * @param string $id          Unique notice ID, auto-generated if not provided
	 * @param bool   $persistent  Whether the notice persists across page loads
	 *
	 * @return void
	 */
	public function add_notice( string $message, string $type = 'info', bool $dismissible = true, string $id = '', bool $persistent = false ): void {
		/**
		 * Action before notice is added
		 *
		 * @since 1.0.0
		 * @param string $message     Notice message
		 * @param string $type        Notice type
		 * @param bool   $dismissible Whether the notice is dismissible
		 * @param string $id          Notice ID
		 * @param bool   $persistent  Whether the notice persists across page loads
		 */
		do_action( 'WPPluginStarter_before_add_notice', $message, $type, $dismissible, $id, $persistent );

		if ( empty( $id ) ) {
			$id = 'WPPluginStarter_' . md5( $message );
		}

		$notice = array(
			'id'          => $id,
			'message'     => $message,
			'type'        => $type,
			'dismissible' => $dismissible,
			'persistent'  => $persistent,
		);

		/**
		 * Filter notice data before adding
		 *
		 * @since 1.0.0
		 * @param array $notice Notice data
		 */
		$notice = apply_filters( 'WPPluginStarter_notice_data', $notice );

		if ( $persistent ) {
			$this->notices[ $id ] = $notice;
			update_option( 'WPPluginStarter_admin_notices', $this->notices );
		} else {
			$transient_notices        = get_transient( 'WPPluginStarter_admin_transient_notices' ) ?? array();
			$transient_notices[ $id ] = $notice;
			set_transient( 'WPPluginStarter_admin_transient_notices', $transient_notices, DAY_IN_SECONDS );
		}

		/**
		 * Action after notice is added
		 *
		 * @since 1.0.0
		 * @param array $notice Notice data
		 * @param bool  $persistent Whether the notice persists across page loads
		 */
		do_action( 'WPPluginStarter_after_add_notice', $notice, $persistent );
	}

	/**
	 * Remove a notice by ID
	 *
	 * @param string $id Notice ID to remove
	 *
	 * @return void
	 */
	public function remove_notice( string $id ): void {
		/**
		 * Action before notice is removed
		 *
		 * @since 1.0.0
		 * @param string $id Notice ID
		 */
		do_action( 'WPPluginStarter_before_remove_notice', $id );

		if ( isset( $this->notices[ $id ] ) ) {
			unset( $this->notices[ $id ] );
			update_option( 'WPPluginStarter_admin_notices', $this->notices );
		}

		/**
		 * Action after notice is removed
		 *
		 * @since 1.0.0
		 * @param string $id Notice ID
		 */
		do_action( 'WPPluginStarter_after_remove_notice', $id );
	}

	/**
	 * Display admin notices
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function display_notices(): void {
		/**
		 * Action before notices are displayed
		 *
		 * @since 1.0.0
		 */
		do_action( 'WPPluginStarter_before_display_notices' );

		// Get transient notices
		$transient_notices = get_transient( 'WPPluginStarter_admin_transient_notices' );
		if ( ! is_array( $transient_notices ) ) {
			$transient_notices = array();
		}

		// Combine persistent and transient notices
		$all_notices = array_merge( $this->notices, $transient_notices );

		/**
		 * Filter all notices before display
		 *
		 * @since 1.0.0
		 * @param array $all_notices All notices to be displayed
		 */
		$all_notices = apply_filters( 'WPPluginStarter_all_notices', $all_notices );

		// Display notices
		foreach ( $all_notices as $notice ) {
			if ( isset( $notice['id'], $notice['message'] ) && $this->is_notice_dismissed( $notice['id'] ) ) {
				continue;
			}

			$this->render_notice( $notice );
		}

		// Clear transient notices after displaying
		delete_transient( 'WPPluginStarter_admin_transient_notices' );

		/**
		 * Action after notices are displayed
		 *
		 * @since 1.0.0
		 */
		do_action( 'WPPluginStarter_after_display_notices' );
	}

	/**
	 * Render a notice
	 *
	 * @since 1.0.0
	 * @param array $notice Notice data
	 *
	 * @return void
	 */
	protected function render_notice( array $notice ): void {
		/**
		 * Action before notice is rendered
		 *
		 * @since 1.0.0
		 * @param array $notice Notice data
		 */
		do_action( 'WPPluginStarter_before_render_notice', $notice );

		$type        = $notice['type'] ?? 'info';
		$dismissible = isset( $notice['dismissible'] ) && $notice['dismissible'] ? ' is-dismissible' : '';
		$id          = $notice['id'] ?? '';
		$message     = $notice['message'] ?? '';

		if ( '' === $id || '' === $message ) {
			return;
		}

		/**
		 * Filter notice type
		 *
		 * @since 1.0.0
		 * @param string $type Notice type
		 * @param array  $notice Notice data
		 */
		$type = apply_filters( 'WPPluginStarter_notice_type', $type, $notice );

		/**
		 * Filter notice message
		 *
		 * @since 1.0.0
		 * @param string $message Notice message
		 * @param array  $notice Notice data
		 */
		$message = apply_filters( 'WPPluginStarter_notice_message', $message, $notice );

		printf(
			'<div class="wp-plugin-starter-notice notice notice-%1$s%2$s" data-notice-id="%3$s"><p>%4$s</p></div>',
			esc_attr( $type ),
			esc_attr( $dismissible ),
			esc_attr( $id ),
			wp_kses_post( $message )
		);

		if ( ! empty( $dismissible ) && ! empty( $id ) ) {
			$this->add_dismissible_script( $id );
		}

		/**
		 * Action after notice is rendered
		 *
		 * @since 1.0.0
		 * @param array $notice Notice data
		 */
		do_action( 'WPPluginStarter_after_render_notice', $notice );
	}

	/**
	 * Add script for dismissible notices
	 *
	 * @since 1.0.0
	 * @param string $id Notice ID
	 *
	 * @return void
	 */
	protected function add_dismissible_script( string $id ): void {
		/**
		 * Filter dismissible notice nonce
		 *
		 * @since 1.0.0
		 * @param string $nonce Nonce
		 * @param string $id Notice ID
		 */
		$nonce = apply_filters( 'WPPluginStarter_dismiss_notice_nonce', wp_create_nonce( 'wp-plugin-starter-dismiss-notice' ), $id );

		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(document).on('click', '.notice[data-notice-id="<?php echo esc_js( $id ); ?>"] .notice-dismiss', function () {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'WPPluginStarter_dismiss_notice',
							id: '<?php echo esc_js( $id ); ?>',
							nonce: '<?php echo esc_js( $nonce ); ?>'
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Dismiss notice
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function dismiss_notice(): void {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wp-plugin-starter-dismiss-notice' ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'wp-plugin-starter' ) ) );
		}

		if ( ! isset( $_POST['id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Missing notice ID', 'wp-plugin-starter' ) ) );
		}

		$id = sanitize_text_field( $_POST['id'] );

		/**
		 * Action before notice is dismissed
		 *
		 * @since 1.0.0
		 * @param string $id Notice ID
		 */
		do_action( 'WPPluginStarter_before_dismiss_notice', $id );

		$dismissed_notices        = get_user_meta( get_current_user_id(), 'WPPluginStarter_dismissed_notices', true ) ?? array();
		$dismissed_notices[ $id ] = time();
		update_user_meta( get_current_user_id(), 'WPPluginStarter_dismissed_notices', $dismissed_notices );

		// If it's a persistent notice, remove it
		if ( isset( $this->notices[ $id ] ) ) {
			unset( $this->notices[ $id ] );
			update_option( 'WPPluginStarter_admin_notices', $this->notices );
		}

		/**
		 * Action after notice is dismissed
		 *
		 * @since 1.0.0
		 * @param string $id Notice ID
		 * @param array  $dismissed_notices All dismissed notices
		 */
		do_action( 'WPPluginStarter_after_dismiss_notice', $id, $dismissed_notices );

		wp_send_json_success( array( 'message' => __( 'Notice dismissed', 'wp-plugin-starter' ) ) );
	}

	/**
	 * Check if notice is dismissed
	 *
	 * @since 1.0.0
	 * @param string $id Notice ID
	 *
	 * @return bool
	 */
	protected function is_notice_dismissed( string $id ): bool {
		$dismissed_notices = get_user_meta( get_current_user_id(), 'WPPluginStarter_dismissed_notices', true ) ?? array();

		/**
		 * Filter whether a notice is dismissed
		 *
		 * @since 1.0.0
		 * @param bool   $is_dismissed Whether the notice is dismissed
		 * @param string $id Notice ID
		 * @param array  $dismissed_notices All dismissed notices
		 */
		return apply_filters( 'WPPluginStarter_is_notice_dismissed', isset( $dismissed_notices[ $id ] ), $id, $dismissed_notices );
	}
}
