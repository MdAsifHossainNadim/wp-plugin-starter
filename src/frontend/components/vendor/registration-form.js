/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Vendor registration form component
 *
 * This component enhances the vendor registration form with additional functionality
 */
class RegistrationForm {
	/**
	 * Constructor
	 *
	 * @param {Object} options Form options
	 */
	constructor( options = {} ) {
		this.options = {
			defaultVendor: false,
			additionalFields: [],
			validation: true,
			...options,
		};

		this.init();
	}

	/**
	 * Initialize the form
	 */
	init() {
		// Find the registration form
		const form = document.querySelector( '.woocommerce-form-register' );

		if ( ! form ) {
			return;
		}

		// Find the vendor checkbox
		const vendorCheckbox = form.querySelector( '#seller-area' );

		if ( ! vendorCheckbox ) {
			return;
		}

		// Set default vendor status if needed
		if ( this.options.defaultVendor ) {
			vendorCheckbox.checked = true;
			this.showVendorFields( vendorCheckbox );
		}

		// Toggle vendor fields when checkbox is clicked
		vendorCheckbox.addEventListener( 'change', () => {
			this.showVendorFields( vendorCheckbox );
		} );

		// Add additional fields if needed
		if ( this.options.additionalFields.length ) {
			this.addAdditionalFields( form );
		}

		// Add form validation if enabled
		if ( this.options.validation ) {
			this.addFormValidation( form );
		}
	}

	/**
	 * Show/hide vendor fields based on checkbox
	 *
	 * @param {HTMLElement} checkbox Vendor checkbox
	 */
	showVendorFields( checkbox ) {
		const extraFields = document.querySelector( '.show_if_seller' );

		if ( ! extraFields ) {
			return;
		}

		// Toggle extra fields visibility
		extraFields.style.display = checkbox.checked ? 'block' : 'none';
	}

	/**
	 * Add additional fields to the form
	 *
	 * @param {HTMLElement} form Registration form
	 */
	addAdditionalFields( form ) {
		// Find the place to insert additional fields
		const extraFields = form.querySelector( '.show_if_seller' );

		if ( ! extraFields ) {
			return;
		}

		// Create fields container
		const fieldsContainer = document.createElement( 'div' );
		fieldsContainer.className = 'wp-plugin-starter-additional-fields';

		// Add each field
		this.options.additionalFields.forEach( ( field ) => {
			const fieldWrapper = document.createElement( 'p' );
			fieldWrapper.className = 'woocommerce-form-row form-row';

			// Create label
			const label = document.createElement( 'label' );
			label.htmlFor = `wp-plugin-starter-${ field.id }`;
			label.innerHTML = field.label;

			if ( field.required ) {
				label.className = 'required';

				const requiredSpan = document.createElement( 'span' );
				requiredSpan.className = 'required';
				requiredSpan.textContent = '*';

				label.appendChild( requiredSpan );
			}

			// Create input
			let input;

			switch ( field.type ) {
				case 'textarea':
					input = document.createElement( 'textarea' );
					input.rows = field.rows || 4;
					break;

				case 'select':
					input = document.createElement( 'select' );

					if ( field.options ) {
						// Add default option
						const defaultOption = document.createElement( 'option' );
						defaultOption.value = '';
						defaultOption.textContent = __( 'Select an option', 'wp-plugin-starter' );
						input.appendChild( defaultOption );

						// Add field options
						field.options.forEach( ( option ) => {
							const optionEl = document.createElement( 'option' );
							optionEl.value = option.value;
							optionEl.textContent = option.label;
							input.appendChild( optionEl );
						} );
					}
					break;

				case 'checkbox':
					input = document.createElement( 'input' );
					input.type = 'checkbox';
					input.value = '1';

					// Special styling for checkbox
					fieldWrapper.className += ' form-row-wide';
					break;

				case 'radio':
					// Container for radio options
					input = document.createElement( 'div' );
					input.className = 'wp-plugin-starter-radio-options';

					if ( field.options ) {
						field.options.forEach( ( option ) => {
							const radioWrapper = document.createElement( 'label' );
							radioWrapper.className = 'wp-plugin-starter-radio-label';

							const radioInput = document.createElement( 'input' );
							radioInput.type = 'radio';
							radioInput.name = `wp-plugin-starter-${ field.id }`;
							radioInput.value = option.value;

							radioWrapper.appendChild( radioInput );
							radioWrapper.appendChild( document.createTextNode( ` ${ option.label }` ) );

							input.appendChild( radioWrapper );
						} );
					}
					break;

				default:
					input = document.createElement( 'input' );
					input.type = field.type || 'text';
			}

			// Set common attributes
			if ( input.tagName !== 'DIV' ) {
				input.id = `wp-plugin-starter-${ field.id }`;
				input.name = `wp-plugin-starter-${ field.id }`;
				input.className = 'input-text';

				if ( field.placeholder ) {
					input.placeholder = field.placeholder;
				}

				if ( field.required ) {
					input.required = true;
				}
			}

			// Add field elements to wrapper
			fieldWrapper.appendChild( label );
			fieldWrapper.appendChild( input );

			// Add help text if provided
			if ( field.description ) {
				const helpText = document.createElement( 'span' );
				helpText.className = 'description';
				helpText.textContent = field.description;
				fieldWrapper.appendChild( helpText );
			}

			// Add wrapper to container
			fieldsContainer.appendChild( fieldWrapper );
		} );

		// Insert fields into the form
		extraFields.appendChild( fieldsContainer );
	}

	/**
	 * Add form validation
	 *
	 * @param {HTMLElement} form Registration form
	 */
	addFormValidation( form ) {
		form.addEventListener( 'submit', ( e ) => {
			const sellerCheckbox = form.querySelector( '#seller-area' );

			// Only validate if seller checkbox is checked
			if ( ! sellerCheckbox || ! sellerCheckbox.checked ) {
				return;
			}

			// Check required fields
			const requiredFields = form.querySelectorAll( '.show_if_seller [required]' );
			let hasError = false;

			requiredFields.forEach( ( field ) => {
				if ( ! field.value.trim() ) {
					hasError = true;
					this.showFieldError( field, __( 'This field is required', 'wp-plugin-starter' ) );
				} else {
					this.clearFieldError( field );
				}
			} );

			// Validate email format
			const emailField = form.querySelector( '.show_if_seller [type="email"]' );

			if ( emailField && emailField.value && ! this.isValidEmail( emailField.value ) ) {
				hasError = true;
				this.showFieldError( emailField, __( 'Please enter a valid email address', 'wp-plugin-starter' ) );
			}

			// Validate URL format
			const urlField = form.querySelector( '.show_if_seller [type="url"]' );

			if ( urlField && urlField.value && ! this.isValidUrl( urlField.value ) ) {
				hasError = true;
				this.showFieldError( urlField, __( 'Please enter a valid URL', 'wp-plugin-starter' ) );
			}

			// Stop form submission if there are errors
			if ( hasError ) {
				e.preventDefault();

				// Scroll to first error
				const firstError = form.querySelector( '.wp-plugin-starter-field-error' );

				if ( firstError ) {
					firstError.scrollIntoView( {
						behavior: 'smooth',
						block: 'center',
					} );
				}
			}
		} );
	}

	/**
	 * Show field error
	 *
	 * @param {HTMLElement} field   Field element
	 * @param {string}      message Error message
	 */
	showFieldError( field, message ) {
		// Clear existing error
		this.clearFieldError( field );

		// Add error class to field
		field.classList.add( 'wp-plugin-starter-error' );

		// Create error message element
		const errorEl = document.createElement( 'div' );
		errorEl.className = 'wp-plugin-starter-field-error';
		errorEl.textContent = message;

		// Insert error message after field
		field.parentNode.insertBefore( errorEl, field.nextSibling );
	}

	/**
	 * Clear field error
	 *
	 * @param {HTMLElement} field Field element
	 */
	clearFieldError( field ) {
		// Remove error class
		field.classList.remove( 'wp-plugin-starter-error' );

		// Remove error message
		const errorEl = field.parentNode.querySelector( '.wp-plugin-starter-field-error' );

		if ( errorEl ) {
			errorEl.parentNode.removeChild( errorEl );
		}
	}

	/**
	 * Validate email format
	 *
	 * @param  {string}  email Email to validate
	 * @return {boolean}       Whether email is valid
	 */
	isValidEmail( email ) {
		const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test( String( email ).toLowerCase() );
	}

	/**
	 * Validate URL format
	 *
	 * @param  {string}  url URL to validate
	 * @return {boolean}     Whether URL is valid
	 */
	isValidUrl( url ) {
		try {
			new URL( url );
			return true;
		} catch ( e ) {
			return false;
		}
	}
}

export default RegistrationForm;
