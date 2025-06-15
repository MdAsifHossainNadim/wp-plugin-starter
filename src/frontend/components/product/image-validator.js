/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Image validator component for product images
 *
 * This component validates product images against dimension and size restrictions
 */
class ImageValidator {
	/**
	 * Constructor
	 *
	 * @param {Object} options Validator options
	 */
	constructor( options = {} ) {
		this.options = {
			minWidth: 0,
			minHeight: 0,
			maxWidth: 0,
			maxHeight: 0,
			maxFileSize: 0, // in MB
			allowedFileTypes: [ 'image/jpeg', 'image/png', 'image/gif' ],
			...options,
		};

		this.init();
	}

	/**
	 * Initialize the validator
	 */
	init() {
		// Find all product image fields
		const imageFields = document.querySelectorAll( '.dokan-product-image, .dokan-product-gallery' );

		if ( ! imageFields.length ) {
			return;
		}

		// Attach validation to each field
		imageFields.forEach( ( field ) => {
			this.attachValidator( field );
		} );
	}

	/**
	 * Attach validator to a field
	 *
	 * @param {HTMLElement} field Field to attach validator to
	 */
	attachValidator( field ) {
		const input = field.querySelector( 'input[type="file"]' );

		if ( ! input ) {
			return;
		}

		// Create validation message element
		const messageEl = document.createElement( 'div' );
		messageEl.className = 'wp-plugin-starter-image-validation-message';
		field.appendChild( messageEl );

		// Listen for file selection
		input.addEventListener( 'change', () => {
			this.validateFiles( input.files, messageEl, field );
		} );
	}

	/**
	 * Validate files
	 *
	 * @param {FileList}    files     Files to validate
	 * @param {HTMLElement} messageEl Element to show validation messages
	 * @param {HTMLElement} field     Field element
	 */
	validateFiles( files, messageEl, field ) {
		// Reset validation state
		messageEl.innerHTML = '';
		messageEl.classList.remove( 'has-error' );
		field.classList.remove( 'has-validation-error' );

		if ( ! files || ! files.length ) {
			return;
		}

		const errors = [];
		let pendingValidations = files.length;

		// Validate each file
		Array.from( files ).forEach( ( file ) => {
			// Validate file type
			if ( ! this.validateFileType( file, errors ) ) {
				pendingValidations--;

				if ( pendingValidations === 0 ) {
					this.showValidationErrors( errors, messageEl, field );
				}
				return;
			}

			// Validate file size
			if ( ! this.validateFileSize( file, errors ) ) {
				pendingValidations--;

				if ( pendingValidations === 0 ) {
					this.showValidationErrors( errors, messageEl, field );
				}
				return;
			}

			// Validate image dimensions
			this.validateDimensions( file, errors ).finally( () => {
				pendingValidations--;

				if ( pendingValidations === 0 ) {
					this.showValidationErrors( errors, messageEl, field );
				}
			} );
		} );
	}

	/**
	 * Validate file type
	 *
	 * @param  {File}    file   File to validate
	 * @param  {Array}   errors Array to add errors to
	 * @return {boolean}        Whether validation passed
	 */
	validateFileType( file, errors ) {
		if ( this.options.allowedFileTypes.length && ! this.options.allowedFileTypes.includes( file.type ) ) {
			errors.push( `${ __( 'File type not allowed:', 'wp-plugin-starter' ) } ${ file.type }. ${ __( 'Allowed types:', 'wp-plugin-starter' ) } ${ this.options.allowedFileTypes.join( ', ' ) }` );
			return false;
		}

		return true;
	}

	/**
	 * Validate file size
	 *
	 * @param  {File}    file   File to validate
	 * @param  {Array}   errors Array to add errors to
	 * @return {boolean}        Whether validation passed
	 */
	validateFileSize( file, errors ) {
		if ( this.options.maxFileSize && file.size > this.options.maxFileSize * 1024 * 1024 ) {
			errors.push( `${ __( 'File too large:', 'wp-plugin-starter' ) } ${ file.name }. ${ __( 'Maximum size:', 'wp-plugin-starter' ) } ${ this.options.maxFileSize }MB` );
			return false;
		}

		return true;
	}

	/**
	 * Validate image dimensions
	 *
	 * @param  {File}    file   File to validate
	 * @param  {Array}   errors Array to add errors to
	 * @return {Promise}        Promise that resolves when validation is complete
	 */
	async validateDimensions( file, errors ) {
		if ( ! file.type.startsWith( 'image/' ) ) {
			return;
		}

		return new Promise( ( resolve ) => {
			const img = new Image();
			const objectUrl = URL.createObjectURL( file );

			img.onload = () => {
				URL.revokeObjectURL( objectUrl );

				const dimensionErrors = [];

				if ( this.options.minWidth && img.width < this.options.minWidth ) {
					dimensionErrors.push( `${ __( 'Minimum width:', 'wp-plugin-starter' ) } ${ this.options.minWidth }px` );
				}

				if ( this.options.minHeight && img.height < this.options.minHeight ) {
					dimensionErrors.push( `${ __( 'Minimum height:', 'wp-plugin-starter' ) } ${ this.options.minHeight }px` );
				}

				if ( this.options.maxWidth && img.width > this.options.maxWidth ) {
					dimensionErrors.push( `${ __( 'Maximum width:', 'wp-plugin-starter' ) } ${ this.options.maxWidth }px` );
				}

				if ( this.options.maxHeight && img.height > this.options.maxHeight ) {
					dimensionErrors.push( `${ __( 'Maximum height:', 'wp-plugin-starter' ) } ${ this.options.maxHeight }px` );
				}

				if ( dimensionErrors.length ) {
					errors.push( `${ __( 'Image dimensions invalid:', 'wp-plugin-starter' ) } ${ file.name } (${ img.width }x${ img.height }). ${ dimensionErrors.join( ', ' ) }` );
				}

				resolve();
			};

			img.onerror = () => {
				URL.revokeObjectURL( objectUrl );
				errors.push( `${ __( 'Failed to load image:', 'wp-plugin-starter' ) } ${ file.name }` );
				resolve();
			};

			img.src = objectUrl;
		} );
	}

	/**
	 * Show validation errors
	 *
	 * @param {Array}       errors    Error messages
	 * @param {HTMLElement} messageEl Element to show validation messages
	 * @param {HTMLElement} field     Field element
	 */
	showValidationErrors( errors, messageEl, field ) {
		if ( ! errors.length ) {
			return;
		}

		// Add error classes
		messageEl.classList.add( 'has-error' );
		field.classList.add( 'has-validation-error' );

		// Create error list
		const errorList = document.createElement( 'ul' );

		errors.forEach( ( error ) => {
			const li = document.createElement( 'li' );
			li.textContent = error;
			errorList.appendChild( li );
		} );

		messageEl.appendChild( errorList );

		// Disable submit button
		const form = field.closest( 'form' );

		if ( form ) {
			const submitBtn = form.querySelector( 'input[type="submit"], button[type="submit"]' );

			if ( submitBtn ) {
				submitBtn.disabled = true;

				// Enable submit button when input changes
				input.addEventListener( 'change', () => {
					const errorMessages = form.querySelectorAll( '.wp-plugin-starter-image-validation-message.has-error' );
					submitBtn.disabled = errorMessages.length > 0;
				} );
			}
		}
	}
}

export default ImageValidator;
