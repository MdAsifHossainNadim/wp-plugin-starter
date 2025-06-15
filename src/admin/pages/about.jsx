/* global WP_Plugin_Starter */

/**
 * WordPress dependencies
 */
import { Button, Card, CardBody, CardFooter, CardHeader } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

/**
 * About page component
 *
 * @return {JSX.Element} The About page component
 */
const AboutPage = () => {
	const contributors = [
		{
			name: 'Tanvir Hasan',
			role: __( 'Lead Developer', 'wp-plugin-starter' ),
			avatar: 'https://secure.gravatar.com/avatar/41d61b8c7c6f31d8a39874ba76a1d082',
			profile: 'https://profiles.wordpress.org/tanvirh/',
		},
		// Add more contributors as needed
	];

	const features = [
		{
			title: __( 'Product Management', 'wp-plugin-starter' ),
			description: __( 'Advanced product management tools including customizable attributes, bulk editing capabilities, inventory controls, and image validation. Allows vendors to set product restrictions, variable product enhancements, and custom fields for improved catalog management.', 'wp-plugin-starter' ),
			icon: 'products',
		},
		{
			title: __( 'Vendor Experience', 'wp-plugin-starter' ),
			description: __( 'Comprehensive vendor dashboard improvements with custom registration fields, profile verification, commission management, and analytics. Provides tools for vendors to manage their store presence, customer communications, and performance tracking in one place.', 'wp-plugin-starter' ),
			icon: 'groups',
		},
		{
			title: __( 'Shipping Controls', 'wp-plugin-starter' ),
			description: __( 'Powerful shipping management system with zone-based rates, real-time carrier integration, packaging options, and delivery time estimates. Enables both marketplace owners and vendors to configure flexible shipping rules tailored to their specific needs.', 'wp-plugin-starter' ),
			icon: 'car',
		},
		{
			title: __( 'Cart & Checkout', 'wp-plugin-starter' ),
			description: __( 'Enhanced shopping experience with multi-vendor cart optimization, one-page checkout, saved payment methods, and order splitting capabilities. Improves conversion rates with features like abandoned cart recovery, guest checkout, and custom checkout fields.', 'wp-plugin-starter' ),
			icon: 'cart',
		},
	];

	const HeroSection = () => (
		<Card className="dk-mb-wp-6">
			<CardBody className="dk-text-center dk-py-wp-8">
				<img src={ `${ WP_Plugin_Starter?.assetsUrl || '' }/images/wp-plugin-starter-logo.png` } alt="WP Plugin Starter" className="dk-mx-auto dk-h-24 dk-w-auto dk-mb-wp-4" />
				<h1 className="dk-text-3xl dk-font-bold dk-text-gray-900 dk-mb-wp-3">{ __( 'About WP Plugin Starter', 'wp-plugin-starter' ) }</h1>
				<p className="dk-max-w-2xl dk-mx-auto dk-text-lg dk-text-gray-600">
					{ __(
						'WP Plugin Starter is a modern, extensible WordPress plugin boilerplate designed to provide a robust foundation for building advanced plugins with enterprise-grade architecture.',
						'wp-plugin-starter'
					) }
				</p>
				<div className="dk-mt-wp-6 dk-flex dk-justify-center dk-space-x-wp-4">
					<span className="dk-inline-flex dk-items-center dk-px-wp-3 dk-py-wp-1 dk-rounded-full dk-text-sm dk-font-medium dk-bg-primary-100 dk-text-primary-800">
						{ __( 'Version', 'wp-plugin-starter' ) }: { WP_Plugin_Starter?.version || '3.0.0' }
					</span>
					<span className="dk-inline-flex dk-items-center dk-px-wp-3 dk-py-wp-1 dk-rounded-full dk-text-sm dk-font-medium dk-bg-green-100 dk-text-green-800">
						{ __( 'Active Installations', 'wp-plugin-starter' ) }: 500+
					</span>
				</div>
			</CardBody>
		</Card>
	);

	const FeaturesSection = () => (
		<>
			<h2 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-4">{ __( 'Advanced Features', 'wp-plugin-starter' ) }</h2>
			<div className="dk-grid dk-grid-cols-1 md:dk-grid-cols-2 dk-gap-wp-6 dk-mb-wp-8">
				{ features.map( ( feature, index ) => (
					<Card key={ index } className="dk-transition dk-duration-300 dk-ease-in-out hover:dk-shadow-lg">
						<CardBody className="w-full dk-p-6">
							<div className="dk-flex dk-flex-col md:dk-flex-row dk-items-start">
								<div className="dk-flex-shrink-0 dk-flex dk-items-center dk-justify-center dk-h-16 dk-w-16 dk-rounded-lg dk-bg-primary-100 dk-mr-wp-5 dk-mb-4 md:dk-mb-0">
									<span className={ `dashicons dashicons-${ feature.icon } dk-text-primary-600` }></span>
								</div>
								<div className="dk-flex-grow">
									<h3 className="dk-text-xl dk-font-semibold dk-text-gray-900 dk-mb-wp-3">{ feature.title }</h3>
									<p className="dk-text-gray-600 dk-leading-relaxed">{ feature.description }</p>
								</div>
							</div>
						</CardBody>
						<CardFooter className="dk-p-5 dk-bg-gray-50 dk-border-t dk-border-gray-200 dk-flex dk-flex-col md:dk-flex-row md:dk-justify-between md:dk-items-center">
							<div className="dk-text-sm dk-text-gray-500 dk-mb-3 md:dk-mb-0">
								<p className="dk-relative dk-inline-block dk-mr-wp-2">{ __( 'Status:', 'wp-plugin-starter' ) }</p>
								<span className="dk-inline-block dk-px-2.5 dk-py-0.5 dk-rounded-md dk-text-xs dk-font-medium dk-bg-yellow-100 dk-text-yellow-800">
									{ feature.status || __( 'Not Installed', 'wp-plugin-starter' ) }
								</span>
							</div>
							<Button
								variant="primary"
								onClick={ () => alert( __( 'Feature coming soon!', 'wp-plugin-starter' ) ) }
								className="dk-admin-button dk-w-full md:dk-w-auto"
							>
								{ __( 'Explore Now', 'wp-plugin-starter' ) }
							</Button>
						</CardFooter>
					</Card>
				) ) }
			</div>
		</>
	);

	const ContributorsSection = () => (
		<>
			<h2 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-4">{ __( 'Contributors', 'wp-plugin-starter' ) }</h2>
			<Card className="dk-mb-wp-8">
				<CardBody>
					<div className="dk-grid dk-grid-cols-1 md:dk-grid-cols-2 lg:dk-grid-cols-3 dk-gap-wp-6">
						{ contributors.map( ( contributor, index ) => (
							<div key={ index } className="dk-flex dk-items-center dk-space-x-wp-4">
								<img src={ contributor.avatar } alt={ contributor.name } className="dk-h-14 dk-w-14 dk-rounded-full" />
								<div>
									<h3 className="dk-text-lg dk-font-medium dk-text-gray-900">{ contributor.name }</h3>
									<p className="dk-text-sm dk-text-gray-600 dk-mb-wp-1">{ contributor.role }</p>
									<a href={ contributor.profile } target="_blank" rel="noopener noreferrer" className="dk-text-sm dk-text-primary-600 hover:dk-text-primary-800">
										{ __( 'WordPress.org Profile', 'wp-plugin-starter' ) }
									</a>
								</div>
							</div>
						) ) }
					</div>
				</CardBody>
			</Card>
		</>
	);

	const GettingHelpSection = () => (
		<>
			<h2 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-4">{ __( 'Getting Help', 'wp-plugin-starter' ) }</h2>
			<div className="dk-grid dk-grid-cols-1 md:dk-grid-cols-2 dk-gap-wp-6 dk-mb-wp-8">
				<Card>
					<CardHeader>
						<h3 className="dk-text-lg dk-font-medium dk-text-gray-900">{ __( 'Support', 'wp-plugin-starter' ) }</h3>
					</CardHeader>
					<CardBody>
						<p className="dk-text-gray-600 dk-mb-wp-4">{ __( 'Need help with WP Plugin Starter? Visit our support forum to get assistance from our team and community.', 'wp-plugin-starter' ) }</p>
						<a href="https://wordpress.org/support/plugin/wp-plugin-starter/" target="_blank" rel="noopener noreferrer" className="dk-admin-button">
							{ __( 'Visit Support Forum', 'wp-plugin-starter' ) }
						</a>
					</CardBody>
				</Card>

				<Card>
					<CardHeader>
						<h3 className="dk-text-lg dk-font-medium dk-text-gray-900">{ __( 'Documentation', 'wp-plugin-starter' ) }</h3>
					</CardHeader>
					<CardBody>
						<p className="dk-text-gray-600 dk-mb-wp-4">{ __( 'Check our documentation for detailed guides, tutorials, and reference materials.', 'wp-plugin-starter' ) }</p>
						<a href="https://wordpress.org/plugins/wp-plugin-starter/" target="_blank" rel="noopener noreferrer" className="dk-admin-button">
							{ __( 'View Documentation', 'wp-plugin-starter' ) }
						</a>
					</CardBody>
				</Card>
			</div>
		</>
	);

	const ChangelogSection = () => (
		<>
			<h2 className="dk-text-2xl dk-font-bold dk-text-gray-900 dk-mb-wp-4">{ __( 'Latest Changes', 'wp-plugin-starter' ) }</h2>
			<Card>
				<CardHeader>
					<h3 className="dk-text-lg dk-font-medium dk-text-gray-900">{ __( 'Changelog', 'wp-plugin-starter' ) }</h3>
				</CardHeader>
				<CardBody>
					<div className="dk-space-y-wp-4">
						<div>
							<h4 className="dk-text-md dk-font-medium dk-text-gray-900">
								{ __( 'Version 3.0.0', 'wp-plugin-starter' ) }
								<span className="dk-ml-wp-2 dk-text-sm dk-text-gray-500">- { __( 'Released on', 'wp-plugin-starter' ) } May 10, 2025</span>
							</h4>
							<ul className="dk-list-disc dk-list-inside dk-text-gray-600 dk-mt-wp-2 dk-space-y-wp-1">
								<li>{ __( 'Complete plugin restructuring with modern architecture', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'New React-based admin interface with Tailwind CSS', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'Improved REST API for features management', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'Enhanced extensibility with service providers', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'Added React Router for better admin navigation', 'wp-plugin-starter' ) }</li>
							</ul>
						</div>

						<div>
							<h4 className="dk-text-md dk-font-medium dk-text-gray-900">
								{ __( 'Version 2.5.0', 'wp-plugin-starter' ) }
								<span className="dk-ml-wp-2 dk-text-sm dk-text-gray-500">- { __( 'Released on', 'wp-plugin-starter' ) } March 15, 2025</span>
							</h4>
							<ul className="dk-list-disc dk-list-inside dk-text-gray-600 dk-mt-wp-2 dk-space-y-wp-1">
								<li>{ __( 'Added product image validation feature', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'Improved vendor registration options', 'wp-plugin-starter' ) }</li>
								<li>{ __( 'Fixed compatibility issues with latest WordPress and WooCommerce versions', 'wp-plugin-starter' ) }</li>
							</ul>
						</div>
					</div>

					<div className="dk-mt-wp-4 dk-pt-wp-4 dk-border-t dk-border-gray-200">
						<a
							href="https://wordpress.org/plugins/wp-plugin-starter/changelog/"
							target="_blank"
							rel="noopener noreferrer"
							className="dk-text-primary-600 hover:dk-text-primary-800 dk-inline-flex dk-items-center"
						>
							{ __( 'View full changelog', 'wp-plugin-starter' ) }
							<span className="dashicons dashicons-arrow-right-alt dk-ml-wp-1 dk-text-sm"></span>
						</a>
					</div>
				</CardBody>
			</Card>
		</>
	);

	return (
		<div className="wp-plugin-starter-about-page">
			{ /* Hero Section */ }
			<HeroSection />

			{ /* Features Grid */ }
			<FeaturesSection />

			{ /* Contributors Section */ }
			{ /*<ContributorsSection />*/ }

			{ /* Getting Help Section */ }
			<GettingHelpSection />

			{ /* Changelog Section */ }
			<ChangelogSection />
		</div>
	);
};

export default AboutPage;
