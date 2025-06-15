/**
 * WordPress dependencies
 */
import { useEffect, useState, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
/**
 * External dependencies
 */
import { useAnimations } from '@admin/hooks/use-animations';

/**
 * Animated component wrapper
 *
 * @param  {Object}      props                  Component props
 * @param  {JSX.Element} props.children         Child content
 * @param  {string}      props.component        Component type
 * @param  {string}      props.state            Animation state
 * @param  {boolean}     props.isError          Whether there is an error
 * @param  {boolean}     props.isActive         Whether the component is active
 * @param  {string}      props.animationClass   Custom animation class
 * @param  {string}      props.className        Additional CSS classes
 * @param  {Function}    props.onAnimationStart Animation start callback
 * @param  {Function}    props.onAnimationEnd   Animation end callback
 * @return {JSX.Element}                        Component with animation
 */
const Animated = ( {
	children,
	component = 'element',
	state = 'default',
	isError = false,
	isActive = false,
	animationClass = '',
	className = '',
	onAnimationStart = () => {},
	onAnimationEnd = () => {},
	...props
} ) => {
	const { getAnimationClasses } = useAnimations();
	const [ animation, setAnimation ] = useState( null );
	const elementRef = useRef( null );

	// Get animation classes based on component type and state
	const animClasses = animationClass || getAnimationClasses( component, { state, isError, isActive } );

	// Apply animation when component mounts or animation changes
	useEffect( () => {
		if ( ! elementRef.current ) {
			return;
		}

		const handleAnimationStart = ( e ) => {
			if ( e.target === elementRef.current ) {
				onAnimationStart( e );
			}
		};

		const handleAnimationEnd = ( e ) => {
			if ( e.target === elementRef.current ) {
				onAnimationEnd( e );
			}
		};

		const element = elementRef.current;
		element.addEventListener( 'animationstart', handleAnimationStart );
		element.addEventListener( 'animationend', handleAnimationEnd );

		return () => {
			element.removeEventListener( 'animationstart', handleAnimationStart );
			element.removeEventListener( 'animationend', handleAnimationEnd );
		};
	}, [ onAnimationStart, onAnimationEnd ] );

	// Apply manual animation
	useEffect( () => {
		if ( animation && elementRef.current ) {
			const element = elementRef.current;
			element.classList.add( animation );

			const timeout = setTimeout( () => {
				element.classList.remove( animation );
				setAnimation( null );
			}, 1000 ); // Default animation duration

			return () => clearTimeout( timeout );
		}
	}, [ animation ] );

	// Method to trigger animation programmatically
	const animate = ( animationName ) => {
		setAnimation( animationName );
	};

	// Add animate method to children if they're a function
	const childrenWithProps = typeof children === 'function' ? children( { animate, ref: elementRef } ) : children;

	const combinedClassName = `${ animClasses } ${ className }`.trim();

	return (
		<div ref={ elementRef } className={ combinedClassName } { ...props }>
			{ childrenWithProps }
		</div>
	);
};

export default Animated;
