<?php
/**
 * Admin Update Notice Template
 *
 * @since   1.0.0
 * @package WP_Plugin_Starter
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get notice parameters
$title       = isset( $title ) ? $title : __( 'WP Plugin Starter Updated', 'wp-plugin-starter' );
$message     = isset( $message ) ? $message : __( 'Thanks for updating WP Plugin Starter! Check out the new features and improvements.', 'wp-plugin-starter' );
$type        = isset( $type ) ? $type : 'info'; // info, success, warning, error
$dismissible = isset( $dismissible ) ? (bool) $dismissible : true;
$notice_id   = isset( $notice_id ) ? $notice_id : 'wp-plugin-starter-update';
$button_text = isset( $button_text ) ? $button_text : '';
$button_url  = isset( $button_url ) ? $button_url : '';

// Determine color classes based on notice type
switch ( $type ) {
	case 'success':
		$border_class = 'dk-border-green-500';
		$bg_class     = 'dk-bg-green-50';
		$text_class   = 'dk-text-green-800';
		$icon_class   = 'dk-text-green-500';
		$icon         = 'fa-check-circle';
		break;
	case 'warning':
		$border_class = 'dk-border-yellow-500';
		$bg_class     = 'dk-bg-yellow-50';
		$text_class   = 'dk-text-yellow-800';
		$icon_class   = 'dk-text-yellow-500';
		$icon         = 'fa-exclamation-triangle';
		break;
	case 'error':
		$border_class = 'dk-border-red-500';
		$bg_class     = 'dk-bg-red-50';
		$text_class   = 'dk-text-red-800';
		$icon_class   = 'dk-text-red-500';
		$icon         = 'fa-times-circle';
		break;
	default: // info
		$border_class = 'dk-border-primary-500';
		$bg_class     = 'dk-bg-primary-50';
		$text_class   = 'dk-text-primary-800';
		$icon_class   = 'dk-text-primary-500';
		$icon         = 'fa-info-circle';
		break;
}
?>

<div id="<?php echo esc_attr( $notice_id ); ?>" class="dk-my-wp-4 dk-border-l-4 <?php echo esc_attr( $border_class ); ?> <?php echo esc_attr( $bg_class ); ?> dk-p-wp-4 dk-rounded-r-md <?php echo $dismissible ? 'is-dismissible' : ''; ?>">
	<div class="dk-flex">
		<div class="dk-flex-shrink-0">
			<i class="fas <?php echo esc_attr( $icon ); ?> dk-h-5 dk-w-5 <?php echo esc_attr( $icon_class ); ?>"></i>
		</div>
		<div class="dk-ml-wp-3 dk-flex-1">
			<?php if ( $title ) : ?>
				<h3 class="dk-text-md dk-font-medium <?php echo esc_attr( $text_class ); ?>"><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>
			<div class="dk-mt-wp-2 dk-text-sm <?php echo esc_attr( $text_class ); ?>">
				<p><?php echo wp_kses_post( $message ); ?></p>

				<?php if ( $button_text && $button_url ) : ?>
					<div class="dk-mt-wp-3">
						<a href="<?php echo esc_url( $button_url ); ?>" class="dk-inline-flex dk-items-center dk-px-wp-4 dk-py-wp-2 dk-border dk-border-transparent dk-text-sm dk-font-medium dk-rounded-md dk-text-white dk-bg-primary-600 hover:dk-bg-primary-700 focus:dk-outline-none focus:dk-ring-2 focus:dk-ring-offset-2 focus:dk-ring-primary-500">
							<?php echo esc_html( $button_text ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $dismissible ) : ?>
			<div class="dk-ml-auto">
				<button type="button" class="dk-notice-dismiss dk-text-gray-400 hover:dk-text-gray-500">
					<span class="dk-sr-only"><?php esc_html_e( 'Dismiss notice', 'wp-plugin-starter' ); ?></span>
					<i class="fas fa-times"></i>
				</button>
			</div>
		<?php endif; ?>
	</div>
</div>
