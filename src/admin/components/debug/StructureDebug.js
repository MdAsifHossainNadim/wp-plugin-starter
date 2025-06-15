/**
 * WordPress dependencies
 */
import { Button, Card, CardBody, CardHeader } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */

/**
 * Debug component to inspect the structure
 *
 * @param  {Object}      props           Component props
 * @param  {Object}      props.structure The structure data
 * @param  {Object}      props.settings  The settings data
 * @return {JSX.Element}                 The debug component
 */
const StructureDebug = ( { structure, settings } ) => {
	const [ showRaw, setShowRaw ] = useState( false );

	// Find all number fields in the structure
	const numberFields = [];

	Object.keys( structure ).forEach( ( tabId ) => {
		const tab = structure[ tabId ];
		Object.keys( tab.sections ).forEach( ( sectionId ) => {
			const section = tab.sections[ sectionId ];
			Object.keys( section.fields ).forEach( ( fieldId ) => {
				const field = section.fields[ fieldId ];
				if ( field.variant === 'number' ) {
					const key = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;
					numberFields.push( {
						key,
						field,
						value: settings[ key ],
						path: `${ tabId }.${ sectionId }.${ fieldId }`,
					} );
				}
			} );
		} );
	} );

	return (
		<Card className="dk-mb-wp-6">
			<CardHeader>
				<h3 className="dk-text-lg dk-font-medium">Structure Debug</h3>
				<Button isSecondary onClick={ () => setShowRaw( ! showRaw ) }>
					{ showRaw ? 'Hide Raw Data' : 'Show Raw Data' }
				</Button>
			</CardHeader>
			<CardBody>
				<h4 className="dk-text-md dk-font-medium dk-mb-wp-3">Number Fields Found: { numberFields.length }</h4>

				{ numberFields.length > 0 ? (
					<div className="dk-bg-gray-50 dk-p-wp-4 dk-rounded dk-border dk-border-gray-200 dk-mb-wp-4">
						<ul className="dk-list-disc dk-pl-wp-5">
							{ numberFields.map( ( item, index ) => (
								<li key={ index } className="dk-mb-wp-2">
									<strong>Path:</strong> { item.path }
									<br />
									<strong>Key:</strong> { item.key }
									<br />
									<strong>Value:</strong> { JSON.stringify( item.value ) }
									<br />
									<strong>Type:</strong> { typeof item.value }
									<br />
									<strong>Min:</strong> { item.field.minimum }, <strong>Max:</strong> { item.field.maximum }, <strong>Step:</strong> { item.field.step }
								</li>
							) ) }
						</ul>
					</div>
				) : (
					<p>No number fields found in the structure.</p>
				) }

				{ showRaw && (
					<>
						<h4 className="dk-text-md dk-font-medium dk-mb-wp-3 dk-mt-wp-4">Raw Structure</h4>
						<pre className="dk-bg-gray-50 dk-p-wp-4 dk-rounded dk-border dk-border-gray-200 dk-overflow-auto dk-max-h-96">{ JSON.stringify( structure, null, 2 ) }</pre>

						<h4 className="dk-text-md dk-font-medium dk-mb-wp-3 dk-mt-wp-4">Raw Settings</h4>
						<pre className="dk-bg-gray-50 dk-p-wp-4 dk-rounded dk-border dk-border-gray-200 dk-overflow-auto dk-max-h-96">{ JSON.stringify( settings, null, 2 ) }</pre>
					</>
				) }
			</CardBody>
		</Card>
	);
};

export default StructureDebug;
