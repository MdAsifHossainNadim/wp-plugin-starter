/**
 * WordPress dependencies
 */
import { forwardRef } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * Button variants
 */
const buttonVariants = {
	default: 'dk-bg-primary-600 dk-text-white hover:dk-bg-primary-700 dk-border dk-border-primary-600',
	destructive: 'dk-bg-red-600 dk-text-white hover:dk-bg-red-700 dk-border dk-border-red-600',
	outline: 'dk-bg-transparent dk-text-gray-700 hover:dk-bg-gray-100 dk-border dk-border-gray-300',
	secondary: 'dk-bg-gray-100 dk-text-gray-700 hover:dk-bg-gray-200 dk-border dk-border-gray-200',
	ghost: 'dk-bg-transparent dk-text-gray-700 hover:dk-bg-gray-100 dk-border dk-border-transparent',
	link: 'dk-bg-transparent dk-text-primary-600 hover:dk-underline dk-border-none dk-p-0',
};

/**
 * Button sizes
 */
const buttonSizes = {
	default: 'dk-h-9 dk-px-4 dk-py-2',
	sm: 'dk-h-8 dk-px-3 dk-text-sm',
	lg: 'dk-h-10 dk-px-8',
	icon: 'dk-h-9 dk-w-9',
};

/**
 * Custom Button component
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.variant   Button variant (default, destructive, outline, secondary, ghost, link)
 * @param  {string}      props.size      Button size (default, sm, lg, icon)
 * @param  {boolean}     props.isLoading Whether the button is in loading state
 * @param  {JSX.Element} props.children  Button content
 * @param  {string}      props.className Additional CSS classes
 * @param  {Function}    props.onClick   Click handler
 * @return {JSX.Element}                 The Button component
 */
const Button = forwardRef( ( { variant = 'default', size = 'default', isLoading = false, disabled = false, className, children, ...props }, ref ) => {
	return (
		<button
			className={ cn(
				'dk-inline-flex dk-items-center dk-justify-center dk-rounded-md dk-font-medium dk-transition-colors dk-focus-visible:dk-outline-none dk-focus-visible:dk-ring-2 dk-focus-visible:dk-ring-primary-500 dk-focus-visible:dk-ring-offset-2 disabled:dk-opacity-50 disabled:dk-pointer-events-none',
				buttonVariants[ variant ],
				buttonSizes[ size ],
				isLoading && 'dk-opacity-70 dk-cursor-wait',
				className
			) }
			disabled={ disabled || isLoading }
			ref={ ref }
			{ ...props }
		>
			{ isLoading && (
				<svg className="dk-mr-2 dk-h-4 dk-w-4 dk-animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle className="dk-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
					<path className="dk-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
				</svg>
			) }
			{ children }
		</button>
	);
} );

export { Button, buttonVariants };
