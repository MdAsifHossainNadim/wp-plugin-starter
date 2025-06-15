/**
 * WordPress dependencies
 */
import { Button, Card, CardBody, CardHeader, ToggleControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { isFieldVisible, compareValues } from '@admin/utils/structure-helpers';

/**
 * Debug component to inspect field dependencies
 *
 * @param  {Object}      props                 Component props
 * @param  {Object}      props.structure       The structure data
 * @param  {Object}      props.settings        The settings data
 * @param  {Function}    props.onSettingChange Callback when settings change
 * @return {JSX.Element}                       The debug component
 */
const DependencyDebug = ( { structure, settings, onSettingChange } ) => {
	const [ showAll, setShowAll ] = useState( false );
	const [ expandedSections, setExpandedSections ] = useState( {} );

	// Toggle a section expanded/collapsed
	const toggleSection = ( sectionKey ) => {
		setExpandedSections( {
			...expandedSections,
			[ sectionKey ]: ! expandedSections[ sectionKey ],
		} );
	};

	// Find fields with dependencies in the structure
	const findDependencies = () => {
		const dependencies = [];

		Object.keys( structure ).forEach( ( tabId ) => {
			const tab = structure[ tabId ];
			Object.keys( tab.sections ).forEach( ( sectionId ) => {
				const section = tab.sections[ sectionId ];
				Object.keys( section.fields ).forEach( ( fieldId ) => {
					const field = section.fields[ fieldId ];
					const key = field.dependency_key || `${ tabId }.${ sectionId }.${ fieldId }`;

					// For fields with dependencies
					if ( field.dependencies && field.dependencies.length > 0 ) {
						const visible = isFieldVisible( field, settings );

						dependencies.push( {
							tabId,
							sectionId,
							fieldId,
							field,
							key,
							dependencies: field.dependencies,
							visible,
							value: settings[ key ],
						} );
					}

					// For fields that other fields depend on
					if ( showAll ) {
						const isDependedOn = dependencies.some( ( dep ) => dep.dependencies.some( ( d ) => d.key === key ) );

						if ( isDependedOn ) {
							// Only add if not already in the list
							const exists = dependencies.some( ( d ) => d.key === key );
							if ( ! exists ) {
								dependencies.push( {
									tabId,
									sectionId,
									fieldId,
									field,
									key,
									dependencies: [],
									visible: isFieldVisible( field, settings ),
									value: settings[ key ],
									isDependedOn: true,
								} );
							}
						}
					}
				} );
			} );
		} );

		return dependencies;
	};

	const dependencies = findDependencies();

	// Group fields by section
	const dependenciesBySection = dependencies.reduce( ( acc, dep ) => {
		const sectionKey = `${ dep.tabId }.${ dep.sectionId }`;
		if ( ! acc[ sectionKey ] ) {
			acc[ sectionKey ] = {
				tabId: dep.tabId,
				sectionId: dep.sectionId,
				tabTitle: structure[ dep.tabId ]?.title || dep.tabId,
				sectionTitle: structure[ dep.tabId ]?.sections[ dep.sectionId ]?.title || dep.sectionId,
				fields: [],
			};
		}
		acc[ sectionKey ].fields.push( dep );
		return acc;
	}, {} );

	if ( Object.keys( dependenciesBySection ).length === 0 ) {
		return (
			<Card size="small">
				<CardHeader>No fields with dependencies found</CardHeader>
			</Card>
		);
	}

	return (
		<Card className="dk-mb-wp-6">
			<CardHeader>
				Field Dependencies Debug
				<ToggleControl label="Show all related fields" checked={ showAll } onChange={ setShowAll } />
			</CardHeader>
			<CardBody>
				{ Object.keys( dependenciesBySection ).map( ( sectionKey ) => {
					const section = dependenciesBySection[ sectionKey ];
					const isExpanded = expandedSections[ sectionKey ];

					return (
						<Card key={ sectionKey } size="small" className="dk-mb-4">
							<CardHeader className="dk-cursor-pointer" onClick={ () => toggleSection( sectionKey ) }>
								{ section.tabTitle } &gt; { section.sectionTitle } ({ section.fields.length } fields)
								<span className="dk-ml-2">{ isExpanded ? '▼' : '►' }</span>
							</CardHeader>
							{ isExpanded && (
								<CardBody>
									<table className="dk-w-full dk-text-sm">
										<thead>
											<tr className="dk-border-b">
												<th className="dk-text-left dk-p-2">Field</th>
												<th className="dk-text-left dk-p-2">Value</th>
												<th className="dk-text-left dk-p-2">Dependencies</th>
												<th className="dk-text-left dk-p-2">Visible</th>
												<th className="dk-text-left dk-p-2">Actions</th>
											</tr>
										</thead>
										<tbody>
											{ section.fields.map( ( dep ) => {
												// Check if each dependency is met using compareValues
												const dependencyStatus = dep.dependencies.map( ( d ) => {
													const isMet = compareValues( settings[ d.key ], d.value, d.comparison || '=' );
													return {
														...d,
														isMet,
													};
												} );

												return (
													<tr key={ dep.key } className="dk-border-b">
														<td className="dk-p-2">
															<div className="dk-font-medium">{ dep.field.title }</div>
															<div className="dk-text-xs dk-text-gray-500">{ dep.key }</div>
														</td>
														<td className="dk-p-2">
															{ dep.isDependedOn ? (
																<ToggleControl
																	checked={ !! dep.value }
																	onChange={ () => {
																		onSettingChange( dep.key, ! dep.value );
																	} }
																/>
															) : (
																<code>{ JSON.stringify( dep.value ) }</code>
															) }
														</td>
														<td className="dk-p-2">
															{ dependencyStatus.map( ( d, i ) => (
																<div key={ i } className="dk-text-xs">
																	<code>
																		{ d.key } { d.comparison || '=' } { JSON.stringify( d.value ) }
																	</code>{ ' ' }
																	<span className={ d.isMet ? 'dk-text-green-500' : 'dk-text-red-500' }>({ d.isMet ? 'Met' : 'Not Met' })</span>
																	<div className="dk-text-xs dk-mt-1 dk-text-gray-500">
																		Current: <code>{ JSON.stringify( settings[ d.key ] ) }</code>
																	</div>
																</div>
															) ) }
														</td>
														<td className="dk-p-2">
															<span className={ dep.visible ? 'dk-text-green-500' : 'dk-text-red-500' }>{ dep.visible ? 'Yes' : 'No' }</span>
														</td>
														<td className="dk-p-2">
															{ dep.isDependedOn && (
																<Button
																	isSmall
																	variant="secondary"
																	onClick={ () => {
																		// Toggle between common values based on field type
																		let newValue;
																		if ( typeof dep.value === 'boolean' ) {
																			newValue = ! dep.value;
																		} else if ( typeof dep.value === 'number' ) {
																			// Cycle through some values: 0, 1, 10, 100
																			const values = [ 0, 1, 10, 100 ];
																			const currentIndex = values.indexOf( dep.value );
																			newValue = values[ ( currentIndex + 1 ) % values.length ];
																		} else if ( Array.isArray( dep.field.options ) && dep.field.options.length > 0 ) {
																			// For select fields, cycle through options
																			const values = dep.field.options.map( ( opt ) => opt.value );
																			const currentIndex = values.indexOf( dep.value );
																			newValue = values[ ( currentIndex + 1 ) % values.length ];
																		} else {
																			// For text fields, cycle through empty, "yes", "no"
																			const values = [ '', 'yes', 'no' ];
																			const currentIndex = values.indexOf( dep.value );
																			newValue = values[ ( currentIndex + 1 ) % values.length ];
																		}

																		onSettingChange( dep.key, newValue );
																	} }
																>
																	Cycle Value
																</Button>
															) }
														</td>
													</tr>
												);
											} ) }
										</tbody>
									</table>
								</CardBody>
							) }
						</Card>
					);
				} ) }
			</CardBody>
		</Card>
	);
};

export default DependencyDebug;
