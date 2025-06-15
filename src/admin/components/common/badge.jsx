/**
 * WordPress dependencies
 */
import { forwardRef } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { cva } from '../../utils/tailwind-utils';

/**
 * Badge styles using cva for variant handling
 */
const badgeStyles = cva( {
	base: 'dk-inline-flex dk-items-center dk-rounded-full dk-font-medium',
	variants: {
		variant: {
			default: 'dk-bg-primary-100 dk-text-primary-800',
			secondary: 'dk-bg-gray-100 dk-text-gray-800',
			success: 'dk-bg-green-100 dk-text-green-800',
			danger: 'dk-bg-red-100 dk-text-red-800',
			warning: 'dk-bg-yellow-100 dk-text-yellow-800',
			info: 'dk-bg-blue-100 dk-text-blue-800',
			outline: 'dk-bg-transparent dk-border dk-border-gray-200 dk-text-gray-800',
		},
		size: {
			default: 'dk-px-wp-2 dk-py-wp-1 dk-text-xs',
			sm: 'dk-px-wp-1.5 dk-py-wp-0.5 dk-text-xs',
			lg: 'dk-px-wp-3 dk-py-wp-1.5 dk-text-sm',
		},
	},
	defaults: {
		variant: 'default',
		size: 'default',
	},
} );

/**
 * Badge component using cva
 *
 * @param  {Object}      props           Component props
 * @param  {string}      props.variant   Badge variant (default, secondary, success, danger, warning, info, outline)
 * @param  {string}      props.size      Badge size (default, sm, lg)
 * @param  {JSX.Element} props.children  Badge content
 * @param  {string}      props.className Additional CSS classes
 * @return {JSX.Element}                 The Badge component
 */
const Badge = forwardRef( ( { variant = 'default', size = 'default', children, className, ...props }, ref ) => {
	return (
		<span className={ badgeStyles( { variant, size, className } ) } ref={ ref } { ...props }>
			{ children }
		</span>
	);
} );

// Export the badgeStyles for reuse in other components
export { Badge, badgeStyles };
