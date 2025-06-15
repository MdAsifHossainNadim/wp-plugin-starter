/**
 * WordPress dependencies
 */
import { Button } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * Button field component
 *
 * @param {Object} props       Component props
 * @param {Object} props.field The field definition
 */
const ButtonField = ( { field } ) => {
	const [ isLoading, setIsLoading ] = useState( false );

	// Determine button variant based on button_type
	const getButtonVariant = () => {
		switch ( field.button_type ) {
			case 'primary':
				return 'primary';
			case 'secondary':
				return 'secondary';
			case 'tertiary':
				return 'tertiary';
			case 'link':
				return 'link';
			default:
				return 'primary';
		}
	};

	// Handle different button actions
	const handleClick = () => {
		// If confirmation is required, show confirmation dialog
		if ( field.confirm_message ) {
			// Use a safer approach than direct window.confirm
			if ( ! field.skipConfirmation ) {
				// eslint-disable-next-line no-alert
				const userConfirmed = window.confirm( field.confirm_message );
				if ( ! userConfirmed ) {
					return;
				}
			}
		}

		switch ( field.action ) {
			case 'ajax':
				handleAjaxAction();
				break;
			case 'link':
				handleLinkAction();
				break;
			default:
				// Default action - could trigger an event
				if ( typeof field.onClick === 'function' ) {
					field.onClick();
				}
		}
	};

	// Handle AJAX action type
	const handleAjaxAction = () => {
		if ( ! field.ajax_action ) {
			return;
		}

		setIsLoading( true );

		// Use WordPress AJAX
		window.jQuery
			.ajax( {
				url: window.ajaxurl,
				method: 'POST',
				data: {
					action: field.ajax_action,
					nonce: window.WP_Plugin_Starter?.nonce || '',
					button_id: field.id,
				},
			} )
			.done( ( response ) => {
				if ( response.success && typeof window.WP_Plugin_Starter?.notify === 'function' ) {
					window.WP_Plugin_Starter.notify( {
						type: 'success',
						message: response.data?.message || 'Action completed successfully',
						isDismissible: true,
					} );
				} else if ( ! response.success && typeof window.WP_Plugin_Starter?.notify === 'function' ) {
					window.WP_Plugin_Starter.notify( {
						type: 'error',
						message: response.data?.message || 'Action failed',
						isDismissible: true,
					} );
				}
			} )
			.fail( () => {
				if ( typeof window.WP_Plugin_Starter?.notify === 'function' ) {
					window.WP_Plugin_Starter.notify( {
						type: 'error',
						message: 'Failed to complete the action',
						isDismissible: true,
					} );
				}
			} )
			.always( () => {
				setIsLoading( false );
			} );
	};

	// Handle link action type
	const handleLinkAction = () => {
		if ( field.url ) {
			window.location.href = field.url;
		}
	};

	return (
		<Button
			variant={ getButtonVariant() }
			size={ field.button_size }
			className={ cn( 'dk-admin-button dk-w-full dk-justify-center', field.classes ) }
			icon={ field.icon || null }
			isDestructive={ field.button_type === 'destructive' }
			isBusy={ isLoading }
			disabled={ field.disabled || isLoading }
			onClick={ handleClick }
		>
			{ field.button_text || field.label || 'Button' }
		</Button>
	);
};

export default ButtonField;
