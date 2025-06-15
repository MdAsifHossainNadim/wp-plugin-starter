/**
 * Image Validation Module
 *
 * This module validates product images for minimum and maximum dimensions and file size
 */

/**
 * Internal dependencies
 */
import './style.scss';

class ImageValidation {
	/**
	 * Initialize the image validation
	 *
	 * @return {void}
	 */
	init() {
		this.setupImageValidationEvents();
	}

	/**
	 * Set up image validation events
	 *
	 * @return {void}
	 */
	setupImageValidationEvents() {
		// Target all product image upload fields in Dokan vendor dashboard
		const imageFields = document.querySelectorAll( '.dokan-product-image, .dokan-product-gallery' );

		if ( ! imageFields.length ) {
			return;
		}

		// Add validation to each image field
		imageFields.forEach( ( field ) => {
			const input = field.querySelector( 'input[type="file"]' );

			if ( ! input ) {
				return;
			}

			// Get validation features from the field data attributes
			const settings = {
				minWidth: parseInt( field.dataset.minWidth, 10 ) || 0,
				minHeight: parseInt( field.dataset.minHeight, 10 ) || 0,
				maxWidth: parseInt( field.dataset.maxWidth, 10 ) || 0,
				maxHeight: parseInt( field.dataset.maxHeight, 10 ) || 0,
				maxFileSize: parseInt( field.dataset.maxFileSize, 10 ) || 0,
				allowedFileTypes: ( field.dataset.allowedFileTypes || 'image/jpeg,image/png,image/gif' ).split( ',' ),
			};

			// Create a validation message container
			const messageContainer = document.createElement( 'div' );
			messageContainer.className = 'wp-plugin-starter-image-validation-message';
			field.appendChild( messageContainer );

			// Add change event listener to validate images
			input.addEventListener( 'change', ( event ) => {
				this.validateImages( event.target.files, settings, messageContainer );
			} );
		} );
	}

	/**
	 * Validate images based on features
	 *
	 * @param  {FileList}    files            List of files to validate
	 * @param  {Object}      settings         Validation features
	 * @param  {HTMLElement} messageContainer Container to show validation messages
	 * @return {void}
	 */
	validateImages( files, settings, messageContainer ) {
		messageContainer.innerHTML = '';
		messageContainer.classList.remove( 'has-error' );

		if ( ! files || ! files.length ) {
			return;
		}

		const errorMessages = [];

		// Validate each file
		Array.from( files ).forEach( ( file ) => {
			// Validate file type
			if ( settings.allowedFileTypes.length && ! settings.allowedFileTypes.includes( file.type ) ) {
				errorMessages.push( `File type ${ file.type } is not allowed. Allowed types: ${ settings.allowedFileTypes.join( ', ' ) }.` );
				return;
			}

			// Validate file size
			if ( settings.maxFileSize && file.size > settings.maxFileSize * 1024 * 1024 ) {
				errorMessages.push( `File "${ file.name }" exceeds maximum size of ${ settings.maxFileSize }MB.` );
				return;
			}

			// Validate image dimensions
			if ( file.type.startsWith( 'image/' ) ) {
				const img = new Image();
				const objectUrl = URL.createObjectURL( file );

				img.onload = () => {
					URL.revokeObjectURL( objectUrl );

					const dimensionErrors = [];

					if ( settings.minWidth && img.width < settings.minWidth ) {
						dimensionErrors.push( `Minimum width: ${ settings.minWidth }px` );
					}

					if ( settings.minHeight && img.height < settings.minHeight ) {
						dimensionErrors.push( `Minimum height: ${ settings.minHeight }px` );
					}

					if ( settings.maxWidth && img.width > settings.maxWidth ) {
						dimensionErrors.push( `Maximum width: ${ settings.maxWidth }px` );
					}

					if ( settings.maxHeight && img.height > settings.maxHeight ) {
						dimensionErrors.push( `Maximum height: ${ settings.maxHeight }px` );
					}

					if ( dimensionErrors.length ) {
						const errorMsg = `Image "${ file.name }" (${ img.width }x${ img.height }) does not meet dimension requirements: ${ dimensionErrors.join( ', ' ) }.`;
						errorMessages.push( errorMsg );
						this.showValidationErrors( errorMessages, messageContainer );
					}
				};

				img.src = objectUrl;
			}
		} );

		// Show any immediate errors (file type, size)
		if ( errorMessages.length ) {
			this.showValidationErrors( errorMessages, messageContainer );
		}
	}

	/**
	 * Display validation error messages
	 *
	 * @param  {Array}       errors    List of error messages
	 * @param  {HTMLElement} container Container to show error messages
	 * @return {void}
	 */
	showValidationErrors( errors, container ) {
		if ( ! errors.length ) {
			return;
		}

		container.classList.add( 'has-error' );

		const errorList = document.createElement( 'ul' );
		errors.forEach( ( error ) => {
			const li = document.createElement( 'li' );
			li.textContent = error;
			errorList.appendChild( li );
		} );

		container.innerHTML = '';
		container.appendChild( errorList );

		// Disable submit button if there are errors
		const form = container.closest( 'form' );
		if ( form ) {
			const submitBtn = form.querySelector( 'input[type="submit"], button[type="submit"]' );
			if ( submitBtn ) {
				submitBtn.disabled = true;

				// Enable submit button when errors are fixed
				const fileInputs = form.querySelectorAll( 'input[type="file"]' );
				fileInputs.forEach( ( input ) => {
					input.addEventListener( 'change', () => {
						// Check if there are any remaining error messages
						const errorContainers = form.querySelectorAll( '.wp-plugin-starter-image-validation-message.has-error' );
						submitBtn.disabled = errorContainers.length > 0;
					} );
				} );
			}
		}
	}
}

// Initialize the module
document.addEventListener( 'DOMContentLoaded', () => {
	const imageValidation = new ImageValidation();
	imageValidation.init();
} );

export default ImageValidation;
