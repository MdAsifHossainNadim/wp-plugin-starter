/**
 * Animation utility functions for React components
 */

/**
 * Add transition class and temporarily disable transitions during state changes
 *
 * @param {HTMLElement} element    The DOM element
 * @param {string}      classToAdd Class to add after disabling transitions
 * @param {number}      duration   Duration in ms to wait before removing class
 */
export const animateTransition = ( element, classToAdd, duration = 500 ) => {
	if ( ! element ) {
		return;
	}

	// Store original transition
	const originalTransition = element.style.transition;

	// Disable transitions temporarily
	element.style.transition = 'none';

	// Force reflow
	// eslint-disable-next-line no-unused-expressions
	element.offsetHeight;

	// Add class and restore transition
	element.classList.add( classToAdd );
	element.style.transition = originalTransition;

	// Remove the class after animation completes
	setTimeout( () => {
		element.classList.remove( classToAdd );
	}, duration );
};

/**
 * Create CSS keyframes animation and apply it to element
 *
 * @param  {HTMLElement} element    Element to animate
 * @param  {string}      name       Animation name
 * @param  {Object}      keyframes  Keyframes object
 * @param  {number}      duration   Duration in ms
 * @param  {string}      timingFunc Timing function
 * @param  {number}      delay      Delay in ms
 * @param  {number}      iterations Iteration count
 * @param  {string}      fillMode   Fill mode
 * @return {string}                 Animation name for cleanup
 */
export const createAnimation = ( element, name, keyframes, duration = 300, timingFunc = 'ease', delay = 0, iterations = 1, fillMode = 'forwards' ) => {
	if ( ! element ) {
		return null;
	}

	// Create unique animation name
	const uniqueName = `${ name }_${ Date.now() }`;

	// Build keyframes CSS
	let keyframeCSS = `@keyframes ${ uniqueName } {`;

	for ( const percent in keyframes ) {
		keyframeCSS += `${ percent } {`;
		for ( const prop in keyframes[ percent ] ) {
			keyframeCSS += `${ prop }: ${ keyframes[ percent ][ prop ] };`;
		}
		keyframeCSS += '}';
	}

	keyframeCSS += '}';

	// Create style element
	const styleEl = document.createElement( 'style' );
	styleEl.innerHTML = keyframeCSS;
	document.head.appendChild( styleEl );

	// Apply animation
	element.style.animation = `${ uniqueName } ${ duration }ms ${ timingFunc } ${ delay }ms ${ iterations } ${ fillMode }`;

	// Cleanup function
	const cleanup = () => {
		document.head.removeChild( styleEl );
		element.style.animation = '';
	};

	// Auto cleanup after animation completes
	if ( iterations !== 'infinite' ) {
		setTimeout( cleanup, duration + delay );
	}

	return { name: uniqueName, cleanup };
};

/**
 * Common animation presets
 */
export const animationPresets = {
	fadeIn: {
		'0%': { opacity: '0' },
		'100%': { opacity: '1' },
	},
	fadeOut: {
		'0%': { opacity: '1' },
		'100%': { opacity: '0' },
	},
	slideInRight: {
		'0%': { transform: 'translateX(-20px)', opacity: '0' },
		'100%': { transform: 'translateX(0)', opacity: '1' },
	},
	slideInLeft: {
		'0%': { transform: 'translateX(20px)', opacity: '0' },
		'100%': { transform: 'translateX(0)', opacity: '1' },
	},
	slideInUp: {
		'0%': { transform: 'translateY(20px)', opacity: '0' },
		'100%': { transform: 'translateY(0)', opacity: '1' },
	},
	slideInDown: {
		'0%': { transform: 'translateY(-20px)', opacity: '0' },
		'100%': { transform: 'translateY(0)', opacity: '1' },
	},
	pulse: {
		'0%': { transform: 'scale(1)' },
		'50%': { transform: 'scale(1.05)' },
		'100%': { transform: 'scale(1)' },
	},
	bounce: {
		'0%, 100%': { transform: 'translateY(0)' },
		'50%': { transform: 'translateY(-10px)' },
	},
	shake: {
		'0%, 100%': { transform: 'translateX(0)' },
		'10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-5px)' },
		'20%, 40%, 60%, 80%': { transform: 'translateX(5px)' },
	},
};
