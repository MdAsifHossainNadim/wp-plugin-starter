/**
 * WordPress dependencies
 */
import { Card, CardHeader, CardBody, Button, Flex, FlexItem } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import SettingsSection from '../../../components/features/section';
import { useStructure } from '../../../hooks/use-structure';

/**
 * Custom Vendor Tab with specialized layout and functionality
 *
 * @param {Object} props           Component props
 * @param {Object} props.structure The tab structure
 */
const CustomVendorTab = ( { structure } ) => {
	const { settings, updateSetting } = useStructure();

	// Handle specialized actions if needed
	const handleExportVendors = () => {
		// Specialized export functionality
		console.log( 'Exporting vendors...' );
	};

	if ( ! structure || ! structure.sections ) {
		return <p>{ __( 'No vendor features available.', 'wp-plugin-starter' ) }</p>;
	}

	return (
		<div className="wp-plugin-starter-custom-vendor-tab">
			{ /* Custom header with action buttons */ }
			<Card className="wp-plugin-starter-vendor-actions dk-mb-wp-6 last:dk-mb-0">
				<CardBody>
					<Flex justify="flex-start">
						<FlexItem>
							<Button variant="primary" onClick={ handleExportVendors }>
								{ __( 'Export Vendors', 'wp-plugin-starter' ) }
							</Button>
						</FlexItem>
						<FlexItem>
							<Button variant="secondary">{ __( 'Import Vendors', 'wp-plugin-starter' ) }</Button>
						</FlexItem>
					</Flex>
				</CardBody>
			</Card>

			{ /* Render features sections dynamically */ }
			{ Object.keys( structure.sections ).map( ( sectionId ) => {
				const section = structure.sections[ sectionId ];

				return (
					<Card key={ sectionId } className="wp-plugin-starter-settings-section dk-mb-wp-6 last:dk-mb-0">
						<CardHeader>
							<h3>{ section.title }</h3>
							{ section.description && <p>{ section.description }</p> }
						</CardHeader>

						<CardBody>
							<SettingsSection
								tabId="vendor" // Hard-coded for this example
								sectionId={ sectionId }
								fields={ section.fields }
								settings={ settings }
								onChange={ updateSetting }
							/>
						</CardBody>
					</Card>
				);
			} ) }

			{ /* Custom content at the bottom */ }
			<Card className="wp-plugin-starter-vendor-stats dk-mb-wp-6 last:dk-mb-0">
				<CardHeader>
					<h3>{ __( 'Vendor Statistics', 'wp-plugin-starter' ) }</h3>
				</CardHeader>

				<CardBody>
					{ /* Custom stats display */ }
					<Flex>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card">
								<h4>{ __( 'Total Vendors', 'wp-plugin-starter' ) }</h4>
								<div className="wp-plugin-starter-stat-value">125</div>
							</div>
						</FlexItem>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card">
								<h4>{ __( 'Active Vendors', 'wp-plugin-starter' ) }</h4>
								<div className="wp-plugin-starter-stat-value">98</div>
							</div>
						</FlexItem>
						<FlexItem>
							<div className="wp-plugin-starter-stat-card">
								<h4>{ __( 'Pending Vendors', 'wp-plugin-starter' ) }</h4>
								<div className="wp-plugin-starter-stat-value">12</div>
							</div>
						</FlexItem>
					</Flex>
				</CardBody>
			</Card>
		</div>
	);
};

export default CustomVendorTab;
