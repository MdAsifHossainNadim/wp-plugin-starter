/**
 * WordPress dependencies
 */
import { CardHeader as WPCardHeader, CardBody } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Badge } from '../components/common/badge';
import { BadgeCVA } from '../components/common/badge-cva';
import { Button } from '../components/common/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '../components/common/card';
import { cn } from '../utils/tailwind-utils';

/**
 * TailwindMerge Demo Page
 *
 * @return {JSX.Element} The TailwindMerge Demo component
 */
const TailwindMergeDemo = () => {
	const [ isLoading, setIsLoading ] = useState( false );

	const handleButtonClick = () => {
		setIsLoading( true );
		setTimeout( () => {
			setIsLoading( false );
		}, 2000 );
	};

	// Example of dynamic class merging
	const dynamicClasses = {
		card: 'dk-bg-white',
		withBorder: 'dk-border dk-border-gray-200',
		withShadow: 'dk-shadow-md',
		rounded: 'dk-rounded-lg',
	};

	return (
		<div className="dk-p-wp-4">
			<h1 className="dk-text-2xl dk-font-bold dk-mb-wp-6">{ __( 'Tailwind Merge Demo', 'wp-plugin-starter' ) }</h1>

			<div className={ cn( dynamicClasses.card, dynamicClasses.withBorder, dynamicClasses.withShadow, dynamicClasses.rounded, 'dk-mb-wp-8 dk-p-wp-4' ) }>
				<WPCardHeader>
					<h2 className="dk-text-lg dk-font-medium">{ __( 'Button Variants with Tailwind Merge', 'wp-plugin-starter' ) }</h2>
				</WPCardHeader>
				<CardBody>
					<div className="dk-flex dk-flex-wrap dk-gap-wp-4">
						<Button onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Default Button', 'wp-plugin-starter' ) }
						</Button>

						<Button variant="destructive" onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Destructive', 'wp-plugin-starter' ) }
						</Button>

						<Button variant="outline" onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Outline', 'wp-plugin-starter' ) }
						</Button>

						<Button variant="secondary" onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Secondary', 'wp-plugin-starter' ) }
						</Button>

						<Button variant="ghost" onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Ghost', 'wp-plugin-starter' ) }
						</Button>

						<Button variant="link" onClick={ handleButtonClick } isLoading={ isLoading }>
							{ __( 'Link', 'wp-plugin-starter' ) }
						</Button>
					</div>

					<div className="dk-mt-wp-8">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-4">{ __( 'Button Sizes', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-items-center dk-gap-wp-4">
							<Button size="sm">{ __( 'Small', 'wp-plugin-starter' ) }</Button>

							<Button>{ __( 'Default', 'wp-plugin-starter' ) }</Button>

							<Button size="lg">{ __( 'Large', 'wp-plugin-starter' ) }</Button>
						</div>
					</div>

					<div className="dk-mt-wp-8">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-4">{ __( 'Class Overrides with Tailwind Merge', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-gap-wp-4">
							<Button className="dk-bg-purple-600 dk-border-purple-600 hover:dk-bg-purple-700">{ __( 'Custom Purple', 'wp-plugin-starter' ) }</Button>

							<Button variant="outline" className="dk-text-amber-600 dk-border-amber-300 hover:dk-bg-amber-50">
								{ __( 'Custom Amber', 'wp-plugin-starter' ) }
							</Button>
						</div>
					</div>
				</CardBody>
			</div>

			<div className="dk-mb-wp-8">
				<WPCardHeader>
					<h2 className="dk-text-lg dk-font-medium">{ __( 'Dynamic Class Composition', 'wp-plugin-starter' ) }</h2>
				</WPCardHeader>
				<CardBody>
					<div className={ cn( 'dk-p-wp-4', 'dk-border', 'dk-rounded-md', 'dk-bg-gray-50', 'dk-border-gray-200' ) }>
						<p className="dk-text-gray-700">{ __( 'This box uses tailwind-merge to combine multiple class strings.', 'wp-plugin-starter' ) }</p>
					</div>

					<div className="dk-mt-wp-4">
						<div
							className={ cn(
								'dk-p-wp-4',
								'dk-border',
								'dk-rounded-md',
								'dk-bg-blue-50', // This will be overridden
								'dk-bg-green-50', // This will take precedence
								'dk-border-green-200'
							) }
						>
							<p className="dk-text-green-700">{ __( 'This box demonstrates class conflicts being resolved (bg-blue-50 is overridden by bg-green-50).', 'wp-plugin-starter' ) }</p>
						</div>
					</div>
				</CardBody>
			</div>

			{ /* Custom Card Component Demo */ }
			<h2 className="dk-text-xl dk-font-bold dk-mb-wp-4 dk-mt-wp-8">{ __( 'Custom Card Component', 'wp-plugin-starter' ) }</h2>

			<div className="dk-grid dk-grid-cols-1 md:dk-grid-cols-2 dk-gap-wp-6 dk-mb-wp-8">
				<Card>
					<CardHeader>
						<CardTitle>{ __( 'Default Card', 'wp-plugin-starter' ) }</CardTitle>
						<CardDescription>{ __( 'This is a default card with header and content.', 'wp-plugin-starter' ) }</CardDescription>
					</CardHeader>
					<CardContent>
						<p className="dk-text-gray-700">{ __( 'Card content goes here. This card uses the default styling.', 'wp-plugin-starter' ) }</p>
					</CardContent>
					<CardFooter>
						<Button size="sm">{ __( 'Action', 'wp-plugin-starter' ) }</Button>
					</CardFooter>
				</Card>

				<Card variant="elevated" className="dk-border-primary-100">
					<CardHeader>
						<CardTitle className="dk-text-primary-700">{ __( 'Elevated Card', 'wp-plugin-starter' ) }</CardTitle>
						<CardDescription>{ __( 'This card has elevated styling with custom classes.', 'wp-plugin-starter' ) }</CardDescription>
					</CardHeader>
					<CardContent>
						<p className="dk-text-gray-700">{ __( 'This card demonstrates how to override styles with tailwind-merge.', 'wp-plugin-starter' ) }</p>
					</CardContent>
					<CardFooter>
						<Button size="sm" variant="outline">
							{ __( 'Cancel', 'wp-plugin-starter' ) }
						</Button>
						<Button size="sm">{ __( 'Save', 'wp-plugin-starter' ) }</Button>
					</CardFooter>
				</Card>

				<Card variant="outline" className="dk-border-dashed">
					<CardHeader>
						<CardTitle>{ __( 'Outline Card', 'wp-plugin-starter' ) }</CardTitle>
						<CardDescription>{ __( 'This card has outline styling with dashed border.', 'wp-plugin-starter' ) }</CardDescription>
					</CardHeader>
					<CardContent>
						<p className="dk-text-gray-700">{ __( 'The outline variant can be combined with other classes.', 'wp-plugin-starter' ) }</p>
					</CardContent>
				</Card>

				<Card variant="flat" className="dk-bg-gray-50">
					<CardHeader>
						<CardTitle>{ __( 'Flat Card', 'wp-plugin-starter' ) }</CardTitle>
						<CardDescription>{ __( 'This card has flat styling with gray background.', 'wp-plugin-starter' ) }</CardDescription>
					</CardHeader>
					<CardContent>
						<p className="dk-text-gray-700">{ __( 'The flat variant has no shadow and can have custom background.', 'wp-plugin-starter' ) }</p>
					</CardContent>
				</Card>
			</div>

			{ /* Badge Component Demo */ }
			<h2 className="dk-text-xl dk-font-bold dk-mb-wp-4 dk-mt-wp-8">{ __( 'Badge Component', 'wp-plugin-starter' ) }</h2>

			<Card className="dk-mb-wp-8">
				<CardHeader>
					<CardTitle>{ __( 'Badge Variants', 'wp-plugin-starter' ) }</CardTitle>
					<CardDescription>{ __( 'Different badge variants with tailwind-merge.', 'wp-plugin-starter' ) }</CardDescription>
				</CardHeader>
				<CardContent>
					<div className="dk-flex dk-flex-wrap dk-gap-wp-3">
						<Badge>{ __( 'Default', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="secondary">{ __( 'Secondary', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="success">{ __( 'Success', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="danger">{ __( 'Danger', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="warning">{ __( 'Warning', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="info">{ __( 'Info', 'wp-plugin-starter' ) }</Badge>
						<Badge variant="outline">{ __( 'Outline', 'wp-plugin-starter' ) }</Badge>
					</div>

					<div className="dk-mt-wp-6">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-3">{ __( 'Badge Sizes', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-items-center dk-gap-wp-4">
							<Badge size="sm">{ __( 'Small', 'wp-plugin-starter' ) }</Badge>
							<Badge>{ __( 'Default', 'wp-plugin-starter' ) }</Badge>
							<Badge size="lg">{ __( 'Large', 'wp-plugin-starter' ) }</Badge>
						</div>
					</div>

					<div className="dk-mt-wp-6">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-3">{ __( 'Custom Badge Styles', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-items-center dk-gap-wp-4">
							<Badge className="dk-bg-purple-100 dk-text-purple-800">{ __( 'Custom Purple', 'wp-plugin-starter' ) }</Badge>
							<Badge className="dk-bg-gradient-to-r dk-from-blue-500 dk-to-purple-500 dk-text-white">{ __( 'Gradient', 'wp-plugin-starter' ) }</Badge>
							<Badge className="dk-border-2 dk-border-dashed dk-border-amber-500 dk-bg-amber-50 dk-text-amber-700">{ __( 'Dashed Border', 'wp-plugin-starter' ) }</Badge>
						</div>
					</div>
				</CardContent>
			</Card>

			{ /* BadgeCVA Component Demo */ }
			<h2 className="dk-text-xl dk-font-bold dk-mb-wp-4 dk-mt-wp-8">{ __( 'BadgeCVA Component (with cva)', 'wp-plugin-starter' ) }</h2>

			<Card className="dk-mb-wp-8">
				<CardHeader>
					<CardTitle>{ __( 'BadgeCVA Variants', 'wp-plugin-starter' ) }</CardTitle>
					<CardDescription>{ __( 'Badge component using the cva utility for variant handling.', 'wp-plugin-starter' ) }</CardDescription>
				</CardHeader>
				<CardContent>
					<div className="dk-flex dk-flex-wrap dk-gap-wp-3">
						<BadgeCVA>{ __( 'Default', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="secondary">{ __( 'Secondary', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="success">{ __( 'Success', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="danger">{ __( 'Danger', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="warning">{ __( 'Warning', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="info">{ __( 'Info', 'wp-plugin-starter' ) }</BadgeCVA>
						<BadgeCVA variant="outline">{ __( 'Outline', 'wp-plugin-starter' ) }</BadgeCVA>
					</div>

					<div className="dk-mt-wp-6">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-3">{ __( 'BadgeCVA with Multiple Variants', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-items-center dk-gap-wp-4">
							<BadgeCVA variant="success" size="sm">
								{ __( 'Small Success', 'wp-plugin-starter' ) }
							</BadgeCVA>
							<BadgeCVA variant="danger">{ __( 'Default Danger', 'wp-plugin-starter' ) }</BadgeCVA>
							<BadgeCVA variant="warning" size="lg">
								{ __( 'Large Warning', 'wp-plugin-starter' ) }
							</BadgeCVA>
						</div>
					</div>

					<div className="dk-mt-wp-6">
						<h3 className="dk-text-md dk-font-medium dk-mb-wp-3">{ __( 'BadgeCVA with Custom Classes', 'wp-plugin-starter' ) }</h3>
						<div className="dk-flex dk-items-center dk-gap-wp-4">
							<BadgeCVA className="dk-bg-purple-100 dk-text-purple-800">{ __( 'Custom Purple', 'wp-plugin-starter' ) }</BadgeCVA>
							<BadgeCVA variant="outline" className="dk-border-dashed dk-border-blue-400 dk-text-blue-700">
								{ __( 'Custom Outline', 'wp-plugin-starter' ) }
							</BadgeCVA>
							<BadgeCVA className="dk-bg-gradient-to-r dk-from-pink-500 dk-to-purple-500 dk-text-white">{ __( 'Gradient', 'wp-plugin-starter' ) }</BadgeCVA>
						</div>
					</div>
				</CardContent>
			</Card>
		</div>
	);
};

export default TailwindMergeDemo;
