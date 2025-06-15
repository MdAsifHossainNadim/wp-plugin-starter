/**
 * WordPress dependencies
 */
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
import { useId } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * A media field component
 *
 * @param {Object}   props          Component props
 * @param {Object}   props.field    The field definition
 * @param {number}   props.value    The field value (media ID)
 * @param {Function} props.onChange Callback for when field value changes
 */
const MediaField = ( { field, value, onChange } ) => {
	const ALLOWED_MEDIA_TYPES = field.allowedTypes || [ 'image' ];

	// Generate a unique ID for this field
	const fieldId = useId();
	const mediaId = `wp-plugin-starter-media-${ field.id || fieldId }`;
	const labelId = `${ mediaId }-label`;

	return (
		<div className="wp-plugin-starter-media-field">
			<div className="wp-plugin-starter-media-field-label">
				{ field.label && <label id={ labelId } htmlFor={ mediaId }>{ field.label }</label> }
			</div>

			<MediaUploadCheck>
				<MediaUpload
					onSelect={ ( media ) => {
						onChange( media.id );
					} }
					allowedTypes={ ALLOWED_MEDIA_TYPES }
					value={ value }
					render={ ( { open } ) => (
						<div className="wp-plugin-starter-media-upload" id={ mediaId }>
							{ ! value ? (
								<Button
									onClick={ open }
									variant="secondary"
									aria-labelledby={ labelId }
								>
									{ field.buttonText || __( 'Choose Media', 'wp-plugin-starter' ) }
								</Button>
							) : (
								<div className="wp-plugin-starter-media-preview">
									{ ALLOWED_MEDIA_TYPES.includes( 'image' ) && <img src={ `${ field.mediaBaseUrl || '' }?id=${ value }&size=medium` } alt="" /> }
									<div className="wp-plugin-starter-media-actions">
										<Button onClick={ open } variant="secondary" isSmall>
											{ __( 'Replace', 'wp-plugin-starter' ) }
										</Button>
										<Button onClick={ () => onChange( '' ) } variant="link" isDestructive isSmall>
											{ __( 'Remove', 'wp-plugin-starter' ) }
										</Button>
									</div>
								</div>
							) }
						</div>
					) }
				/>
			</MediaUploadCheck>

			{ field.description && <p className="wp-plugin-starter-media-field-description">{ field.description }</p> }
		</div>
	);
};

export default MediaField;
