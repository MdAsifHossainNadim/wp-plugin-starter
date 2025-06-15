/**
 * Internal dependencies
 */
import { cn } from '../../utils/tailwind-utils';

/**
 * SettingsModel Card Component
 *
 * @param  {Object}      props             Component props
 * @param  {string}      props.title       Card title
 * @param  {string}      props.description Card description
 * @param  {any}         props.children    Card content
 * @param  {any}         props.actions     Card actions
 * @param  {string}      props.className   Additional CSS classes
 * @return {JSX.Element}                   Card component
 */
const SettingsCard = ( { title, description, children, actions, className } ) => {
	return (
		<div className={ cn( 'dk-admin-card dk-mb-wp-6', className ) }>
			<div className="dk-border-b dk-border-gray-200 dk-pb-wp-4 dk-mb-wp-4">
				<h3 className="dk-text-admin-lg dk-font-medium dk-text-gray-900">{ title }</h3>
				{ description && <p className="dk-mt-wp-1 dk-text-admin-sm dk-text-gray-500">{ description }</p> }
			</div>

			<div className="dk-mb-wp-4">{ children }</div>

			{ actions && <div className="dk-flex dk-justify-end dk-space-x-wp-3 dk-border-t dk-border-gray-200 dk-pt-wp-4">{ actions }</div> }
		</div>
	);
};

export default SettingsCard;
