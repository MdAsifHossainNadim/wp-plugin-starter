/**
 * Cart Buttons Module
 *
 * This module enhances the cart buttons with additional functionality
 */

/**
 * Internal dependencies
 */
import './style.scss';

class CartButtons {
	/**
	 * Initialize the cart buttons
	 *
	 * @return {void}
	 */
	init() {
		this.enhanceCartButtons();
		this.setupQuantityHandlers();
	}

	/**
	 * Enhance cart buttons with additional features
	 *
	 * @return {void}
	 */
	enhanceCartButtons() {
		// Target all add to cart buttons
		const cartButtons = document.querySelectorAll( '.add_to_cart_button' );

		if ( ! cartButtons.length ) {
			return;
		}

		// Check if we have the features from PHP
		const settings = window.WP_Plugin_Starter?.cartButtons || {
			enableAjax: true,
			showQuantity: true,
			redirectToCart: false,
			buttonText: 'Add to Cart',
			addedText: 'Added to Cart',
			loadingText: 'Adding...',
		};

		// Apply features to each button
		cartButtons.forEach( ( button ) => {
			// Skip if already enhanced
			if ( button.classList.contains( 'wp-plugin-starter-enhanced' ) ) {
				return;
			}

			// Add our class for styling
			button.classList.add( 'wp-plugin-starter-enhanced' );

			// Apply custom text if needed
			if ( settings.buttonText && button.textContent.trim() === 'Add to cart' ) {
				button.textContent = settings.buttonText;
			}

			// Add loading state to button
			button.addEventListener( 'click', ( e ) => {
				if ( ! button.classList.contains( 'ajax_add_to_cart' ) || ! settings.enableAjax ) {
					return;
				}

				// Prevent default only for AJAX buttons
				e.preventDefault();

				// Get product data
				const productId = button.dataset.product_id;
				const quantity = this.getQuantityForProduct( productId ) || 1;

				// Add loading state
				button.classList.add( 'loading' );
				button.textContent = settings.loadingText;

				// Send AJAX request
				this.addToCartAjax( productId, quantity, button, settings );
			} );
		} );

		// Add quantity inputs if enabled
		if ( settings.showQuantity ) {
			this.addQuantityInputs( cartButtons );
		}
	}

	/**
	 * Add quantity inputs before cart buttons
	 *
	 * @param  {NodeList} buttons Cart buttons
	 * @return {void}
	 */
	addQuantityInputs( buttons ) {
		buttons.forEach( ( button ) => {
			// Skip if not a single product button or already has quantity
			if ( ! button.dataset.product_id || button.closest( '.wp-plugin-starter-quantity-wrapper' ) ) {
				return;
			}

			const productId = button.dataset.product_id;
			const isVariable = button.classList.contains( 'product_type_variable' );

			// Don't add quantity for variable products
			if ( isVariable ) {
				return;
			}

			// Create quantity wrapper
			const wrapper = document.createElement( 'div' );
			wrapper.className = 'wp-plugin-starter-quantity-wrapper';

			// Create quantity input
			const quantityContainer = document.createElement( 'div' );
			quantityContainer.className = 'wp-plugin-starter-quantity-input';

			const minusBtn = document.createElement( 'button' );
			minusBtn.className = 'wp-plugin-starter-quantity-minus';
			minusBtn.type = 'button';
			minusBtn.textContent = '-';

			const input = document.createElement( 'input' );
			input.type = 'number';
			input.className = 'wp-plugin-starter-quantity';
			input.min = '1';
			input.value = '1';
			input.dataset.product_id = productId;

			const plusBtn = document.createElement( 'button' );
			plusBtn.className = 'wp-plugin-starter-quantity-plus';
			plusBtn.type = 'button';
			plusBtn.textContent = '+';

			quantityContainer.appendChild( minusBtn );
			quantityContainer.appendChild( input );
			quantityContainer.appendChild( plusBtn );

			wrapper.appendChild( quantityContainer );

			// Insert the quantity wrapper before the button
			button.parentNode.insertBefore( wrapper, button );

			// Add the button to the wrapper as well
			wrapper.appendChild( button );
		} );
	}

	/**
	 * Set up quantity button handlers
	 *
	 * @return {void}
	 */
	setupQuantityHandlers() {
		// Listen for clicks on quantity buttons
		document.addEventListener( 'click', ( e ) => {
			if ( e.target.matches( '.wp-plugin-starter-quantity-plus' ) ) {
				const input = e.target.previousElementSibling;
				input.value = Math.max( 1, parseInt( input.value, 10 ) + 1 );
				input.dispatchEvent( new Event( 'change' ) );
			} else if ( e.target.matches( '.wp-plugin-starter-quantity-minus' ) ) {
				const input = e.target.nextElementSibling;
				input.value = Math.max( 1, parseInt( input.value, 10 ) - 1 );
				input.dispatchEvent( new Event( 'change' ) );
			}
		} );
	}

	/**
	 * Get quantity for a specific product
	 *
	 * @param  {string} productId Product ID
	 * @return {number}           Quantity
	 */
	getQuantityForProduct( productId ) {
		const input = document.querySelector( `.wp-plugin-starter-quantity[data-product_id="${ productId }"]` );
		return input ? parseInt( input.value, 10 ) : 1;
	}

	/**
	 * Add product to cart via AJAX
	 *
	 * @param  {string}      productId Product ID
	 * @param  {number}      quantity  Quantity
	 * @param  {HTMLElement} button    Button element
	 * @param  {Object}      settings  Cart button features
	 * @return {void}
	 */
	addToCartAjax( productId, quantity, button, settings ) {
		// Create form data
		const formData = new FormData();
		formData.append( 'product_id', productId );
		formData.append( 'quantity', quantity );
		formData.append( 'add-to-cart', productId );

		// Add action for AJAX
		if ( window.wc_add_to_cart_params ) {
			formData.append( 'action', 'woocommerce_ajax_add_to_cart' );
		}

		// Send AJAX request
		fetch( window.wc_add_to_cart_params?.ajax_url || window.WP_Plugin_Starter?.ajaxUrl || '/wp-admin/admin-ajax.php', {
			method: 'POST',
			body: formData,
			credentials: 'same-origin',
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				button.classList.remove( 'loading' );

				if ( data.success ) {
					button.classList.add( 'added' );
					button.textContent = settings.addedText;

					// Update cart fragments if available
					if ( data.fragments ) {
						this.updateCartFragments( data.fragments );
					}

					// Redirect to cart if enabled
					if ( settings.redirectToCart && data.cart_url ) {
						window.location.href = data.cart_url;
					}
				} else {
					// Show error message
					this.showNotice( data.message || 'Error adding product to cart', 'error' );
					button.textContent = settings.buttonText;
				}
			} )
			.catch( ( error ) => {
				console.error( 'Error:', error );
				button.classList.remove( 'loading' );
				button.textContent = settings.buttonText;
				this.showNotice( 'Error adding product to cart', 'error' );
			} );
	}

	/**
	 * Update cart fragments
	 *
	 * @param  {Object} fragments Cart fragments
	 * @return {void}
	 */
	updateCartFragments( fragments ) {
		if ( typeof fragments !== 'object' ) {
			return;
		}

		// Update each fragment
		Object.keys( fragments ).forEach( ( key ) => {
			const fragment = fragments[ key ];
			const elements = document.querySelectorAll( key );

			elements.forEach( ( element ) => {
				const newElement = document.createElement( 'div' );
				newElement.innerHTML = fragment;

				// Replace each element with the updated fragment
				element.parentNode.replaceChild( newElement.firstChild, element );
			} );
		} );
	}

	/**
	 * Show notice message
	 *
	 * @param  {string} message Notice message
	 * @param  {string} type    Notice type (success, error, info)
	 * @return {void}
	 */
	showNotice( message, type = 'success' ) {
		// Check if WooCommerce notices container exists
		let noticesContainer = document.querySelector( '.woocommerce-notices-wrapper' );

		// Create container if not found
		if ( ! noticesContainer ) {
			noticesContainer = document.createElement( 'div' );
			noticesContainer.className = 'woocommerce-notices-wrapper';
			document.body.prepend( noticesContainer );
		}

		// Create notice element
		const notice = document.createElement( 'div' );
		notice.className = `woocommerce-${ type }`;
		notice.innerHTML = `<p>${ message }</p>`;

		// Add close button
		const closeBtn = document.createElement( 'button' );
		closeBtn.className = 'wp-plugin-starter-notice-close';
		closeBtn.type = 'button';
		closeBtn.innerHTML = '&times;';
		closeBtn.addEventListener( 'click', () => {
			notice.remove();
		} );

		notice.appendChild( closeBtn );

		// Add to container
		noticesContainer.appendChild( notice );

		// Auto-remove after 5 seconds
		setTimeout( () => {
			notice.remove();
		}, 5000 );
	}
}

// Initialize the module
document.addEventListener( 'DOMContentLoaded', () => {
	const cartButtons = new CartButtons();
	cartButtons.init();
} );

export default CartButtons;
