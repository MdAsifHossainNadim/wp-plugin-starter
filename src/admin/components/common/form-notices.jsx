/**
 * WordPress dependencies
 */
import { Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * FormNotices component - Displays form notices and validation errors
 *
 * @param  {Object}           props                  Component props
 * @param  {string}           props.message          Message to display
 * @param  {boolean}          props.isError          Whether the message is an error
 * @param  {Object}           props.validationErrors Validation errors object
 * @param  {Function}         props.onDismiss        Callback when notice is dismissed
 * @param  {string}           props.className        Additional CSS classes
 * @return {JSX.Element|null}                        The FormNotices component or null if no notices
 */
const FormNotices = ( {
	message,
	isError = false,
	validationErrors = {},
	onDismiss,
	className = 'dk-mb-wp-5',
} ) => {
	const hasValidationErrors = Object.keys( validationErrors ).length > 0;

	if ( ! message && ! hasValidationErrors ) {
		return null;
	}

	return (
		<>
			{ message && (
				<Notice
					status={ isError ? 'error' : 'success' }
					isDismissible={ true }
					onRemove={ onDismiss }
					className={ className }
				>
					{ message }
				</Notice>
			) }

			{ hasValidationErrors && (
				<Notice status="error" isDismissible={ false } className={ className }>
					<p>{ __( 'Please fix the following errors:', 'wp-plugin-starter' ) }</p>
					<ul className="dk-list-disc dk-pl-wp-5 dk-mt-wp-2">
						{ Object.values( validationErrors ).map( ( error, index ) => (
							<li key={ index }>{ error }</li>
						) ) }
					</ul>
				</Notice>
			) }
		</>
	);
};

export default FormNotices;
