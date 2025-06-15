/**
 * WordPress dependencies
 */
import { forwardRef } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * Card variants
 */
const cardVariants = {
	default: 'dk-bg-white dk-border dk-border-gray-200 dk-rounded-lg dk-shadow-sm',
	flat: 'dk-bg-white dk-border dk-border-gray-200 dk-rounded-lg',
	elevated: 'dk-bg-white dk-border dk-border-gray-200 dk-rounded-lg dk-shadow-md',
	outline: 'dk-bg-transparent dk-border dk-border-gray-200 dk-rounded-lg',
};

/**
 * Card component
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.variant   Card variant (default, flat, elevated, outline)
 * @param  {JSX.Element} props.children  Card content
 * @param  {string}      props.className Additional CSS classes
 * @param  {JSX.Element} props.header    Card header content
 * @param  {JSX.Element} props.footer    Card footer content
 * @return {JSX.Element}                 The Card component
 */
const Card = forwardRef( ( { variant = 'default', children, className, header, footer, ...props }, ref ) => {
	return (
		<div className={ cn( cardVariants[ variant ] || cardVariants.default, className ) } ref={ ref } { ...props }>
			{ header && <div className="dk-border-b dk-border-gray-200 dk-p-wp-4">{ header }</div> }

			<div className="dk-p-wp-4">{ children }</div>

			{ footer && <div className="dk-border-t dk-border-gray-200 dk-p-wp-4">{ footer }</div> }
		</div>
	);
} );

/**
 * Card Header component
 *
 * @param  {Object}      props           Component props
 * @param  {JSX.Element} props.children  Header content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The CardHeader component
 */
const CardHeader = forwardRef( ( { children, className, ...props }, ref ) => {
	return (
		<div className={ cn( 'dk-flex dk-flex-col dk-space-y-wp-1.5', className ) } ref={ ref } { ...props }>
			{ children }
		</div>
	);
} );

/**
 * Card Title component
 *
 * @param  {Object}      props           Component props
 * @param  {JSX.Element} props.children  Title content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The CardTitle component
 */
const CardTitle = forwardRef( ( { children, className, ...props }, ref ) => {
	return (
		<h3 className={ cn( 'dk-text-lg dk-font-medium dk-text-gray-900', className ) } ref={ ref } { ...props }>
			{ children }
		</h3>
	);
} );

/**
 * Card Description component
 *
 * @param  {Object}      props           Component props
 * @param  {JSX.Element} props.children  Description content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The CardDescription component
 */
const CardDescription = forwardRef( ( { children, className, ...props }, ref ) => {
	return (
		<p className={ cn( 'dk-text-sm dk-text-gray-500', className ) } ref={ ref } { ...props }>
			{ children }
		</p>
	);
} );

/**
 * Card Content component
 *
 * @param  {Object}      props           Component props
 * @param  {JSX.Element} props.children  Content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The CardContent component
 */
const CardContent = forwardRef( ( { children, className, ...props }, ref ) => {
	return (
		<div className={ cn( 'dk-py-wp-2', className ) } ref={ ref } { ...props }>
			{ children }
		</div>
	);
} );

/**
 * Card Footer component
 *
 * @param  {Object}      props           Component props
 * @param  {JSX.Element} props.children  Footer content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The CardFooter component
 */
const CardFooter = forwardRef( ( { children, className, ...props }, ref ) => {
	return (
		<div className={ cn( 'dk-flex dk-items-center dk-justify-end dk-space-x-wp-2', className ) } ref={ ref } { ...props }>
			{ children }
		</div>
	);
} );

export { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter };
