/**
 * WordPress dependencies
 */
import { Card, CardBody, CardHeader } from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';

/**
 * External dependencies
 */

/**
 * Internal dependencies
 */
import SettingsSection from '@admin/components/features/section';
import { cn } from '@admin/utils/tailwind-utils';

/**
 * Dynamic Tab component that renders sections based on the structure
 * Uses props for settings data and change handlers
 *
 * Renders a tab with dynamic fields based on structure
 * Uses existing Tailwind styles
 *
 * @param  {Object}      props                  Component properties
 * @param  {string}      props.tabId            The ID of the tab
 * @param  {Object}      props.structure        The structure of the tab
 * @param  {Object}      props.settings         The settings values
 * @param  {Object}      props.validationErrors Validation errors object
 * @param  {Function}    props.onSettingChange  Callback when settings are changed
 * @param  {string}      props.className        Additional CSS classes
 * @param  {JSX.Element} props.emptyMessage     Custom message when no sections are available
 * @return {JSX.Element}                        The tab component
 */
const DynamicTab = ( {
	tabId,
	structure,
	settings,
	validationErrors = {},
	onSettingChange,
	className = '',
	emptyMessage = null,
} ) => {
	// Validate structure exists and has sections
	if ( ! structure ) {
		return (
			<Card className="dk-bg-gray-50">
				<CardBody>
					<p className="dk-text-center dk-text-gray-500">
						{ __( 'Tab structure not found.', 'wp-plugin-starter' ) }
					</p>
				</CardBody>
			</Card>
		);
	}

	// Check if there are any sections to render
	const hasSections = structure.sections && Object.keys( structure.sections ).length > 0;

	if ( ! hasSections ) {
		return (
			<Card className="dk-bg-gray-50">
				<CardBody>
					<p className="dk-text-center dk-text-gray-500">
						{ emptyMessage || __( 'No features available for this tab.', 'wp-plugin-starter' ) }
					</p>
				</CardBody>
			</Card>
		);
	}

	return (
		<div className={ cn( `wp-plugin-starter-tab-content wp-plugin-starter-tab-${ tabId }`, className, 'dk-mt-1' ) }>
			{ Object.keys( structure.sections ).map( ( sectionId ) => {
				const section = structure.sections[ sectionId ];

				// Check if this section has any validation errors
				const sectionHasErrors = Object.keys( validationErrors ).some(
					( key ) => key.startsWith( `${ tabId }.${ sectionId }.` ) ||
						( Object.values( section.fields ).some(
							( field ) => field.dependency_key && validationErrors[ field.dependency_key ]
						) )
				);

				console.log( 'section', section );

				return (
					<Card
						key={ sectionId }
						className={ cn( 'wp-plugin-starter-settings-section dk-mb-wp-6 last:dk-mb-0', {
							'dk-border-red-300': sectionHasErrors,
						} ) }
					>
						<CardHeader className="dk-block dk-p-4 dk-bg-white dk-border-b dk-border-gray-200">
							<div className="dk-flex dk-items-center dk-gap-2">
								<h3 className="dk-text-base dk-font-medium">{ decodeEntities( section.title ) }</h3>
								{ section.badge && (
									<span className={ `dk-text-xs dk-px-2 dk-py-0.5 dk-rounded-full dk-font-medium ${
										section.badge.type === 'primary' ? 'dk-bg-blue-100 dk-text-blue-800'
											: section.badge.type === 'success' ? 'dk-bg-green-100 dk-text-green-800'
												: section.badge.type === 'warning' ? 'dk-bg-yellow-100 dk-text-yellow-800'
													: section.badge.type === 'danger' ? 'dk-bg-red-100 dk-text-red-800'
														: section.badge.type === 'info' ? 'dk-bg-indigo-100 dk-text-indigo-800'
															: 'dk-bg-gray-100 dk-text-gray-800'
									}` }>
										{ decodeEntities( section.badge.text ) }
									</span>
								) }
							</div>
							{ section.description && (
								<p className="dk-text-sm dk-text-gray-600 dk-mt-1">
									{ decodeEntities( section.description ) }
								</p>
							) }
						</CardHeader>

						<CardBody>
							<SettingsSection
								tabId={ tabId }
								sectionId={ sectionId }
								fields={ section.fields }
								settings={ settings }
								validationErrors={ validationErrors }
								onSettingChange={ onSettingChange }
							/>
						</CardBody>
					</Card>
				);
			} ) }
		</div>
	);
};

export default DynamicTab;
