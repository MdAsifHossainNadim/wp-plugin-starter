/**
 * WordPress dependencies
 */
import { Card, CardHeader, CardBody, Flex, FlexItem } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import SettingsSection from '../../../components/features/section';
import { useStructure } from '../../../hooks/use-structure';

/**
 * Custom Product Tab with specialized layout and functionality
 *
 * @param {Object} props           Component props
 * @param {Object} props.structure The tab structure
 */
const CustomProductTab = ( { structure } ) => {
	const { settings, updateSetting } = useStructure();

	if ( ! structure || ! structure.sections ) {
		return <p>{ __( 'No product features available.', 'wp-plugin-starter' ) }</p>;
	}

	return (
		<div className="wp-plugin-starter-custom-product-tab">
			{ /* Render features sections dynamically */ }
			{ Object.keys( structure.sections ).map( ( sectionId ) => {
				const section = structure.sections[ sectionId ];

				return (
					<Card key={ sectionId } className="wp-plugin-starter-settings-section dk-mb-wp-6">
						<CardHeader>
							<h3 className="dk-text-lg dk-font-medium">{ section.title }</h3>
							{ section.description && <p className="dk-text-sm dk-text-gray-600 dk-mt-1">{ section.description }</p> }
						</CardHeader>

						<CardBody>
							<SettingsSection
								tabId="product" // Hard-coded for this example
								sectionId={ sectionId }
								fields={ section.fields }
								settings={ settings }
								onChange={ updateSetting }
							/>
						</CardBody>
					</Card>
				);
			} ) }

			{ /* Product Statistics */ }
			<Card className="wp-plugin-starter-product-stats">
				<CardHeader>
					<h3 className="dk-text-lg dk-font-medium">{ __( 'Product Statistics', 'wp-plugin-starter' ) }</h3>
				</CardHeader>

				<CardBody>
					<Flex>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card dk-p-wp-4 dk-border dk-border-gray-200 dk-rounded-md">
								<h4 className="dk-text-sm dk-font-medium dk-mb-wp-2">{ __( 'Total Products', 'wp-plugin-starter' ) }</h4>
								<div className="dk-text-2xl dk-font-bold">1250</div>
							</div>
						</FlexItem>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card dk-p-wp-4 dk-border dk-border-gray-200 dk-rounded-md">
								<h4 className="dk-text-sm dk-font-medium dk-mb-wp-2">{ __( 'Published Products', 'wp-plugin-starter' ) }</h4>
								<div className="dk-text-2xl dk-font-bold">980</div>
							</div>
						</FlexItem>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card dk-p-wp-4 dk-border dk-border-gray-200 dk-rounded-md">
								<h4 className="dk-text-sm dk-font-medium dk-mb-wp-2">{ __( 'Pending Products', 'wp-plugin-starter' ) }</h4>
								<div className="dk-text-2xl dk-font-bold">270</div>
							</div>
						</FlexItem>
					</Flex>
				</CardBody>
			</Card>
		</div>
	);
};

export default CustomProductTab;
