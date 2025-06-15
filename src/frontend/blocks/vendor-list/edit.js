/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, ToggleControl, SelectControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Edit component for the Vendor List block
 *
 * @param  {Object}      props Block properties
 * @return {JSX.Element}       The edit component
 */
const VendorListEdit = ( props ) => {
	const { attributes, setAttributes } = props;
	const { numberOfVendors, orderBy, order, showAvatar, showRating, columnsPerRow } = attributes;

	const [ isLoading, setIsLoading ] = useState( false );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Vendor List SettingsModel', 'wp-plugin-starter' ) }>
					<RangeControl label={ __( 'Number of Vendors', 'wp-plugin-starter' ) } value={ numberOfVendors } onChange={ ( value ) => setAttributes( { numberOfVendors: value } ) } min={ 1 } max={ 50 } />
					<SelectControl
						label={ __( 'Order By', 'wp-plugin-starter' ) }
						value={ orderBy }
						options={ [
							{
								label: __( 'Registration Date', 'wp-plugin-starter' ),
								value: 'registered',
							},
							{ label: __( 'Name', 'wp-plugin-starter' ), value: 'name' },
							{
								label: __( 'Products Count', 'wp-plugin-starter' ),
								value: 'products_count',
							},
							{
								label: __( 'Rating', 'wp-plugin-starter' ),
								value: 'rating',
							},
						] }
						onChange={ ( value ) => setAttributes( { orderBy: value } ) }
					/>
					<SelectControl
						label={ __( 'Order', 'wp-plugin-starter' ) }
						value={ order }
						options={ [
							{
								label: __( 'Descending', 'wp-plugin-starter' ),
								value: 'desc',
							},
							{
								label: __( 'Ascending', 'wp-plugin-starter' ),
								value: 'asc',
							},
						] }
						onChange={ ( value ) => setAttributes( { order: value } ) }
					/>
					<RangeControl label={ __( 'Columns Per Row', 'wp-plugin-starter' ) } value={ columnsPerRow } onChange={ ( value ) => setAttributes( { columnsPerRow: value } ) } min={ 1 } max={ 6 } />
					<ToggleControl label={ __( 'Show Avatar', 'wp-plugin-starter' ) } checked={ showAvatar } onChange={ ( value ) => setAttributes( { showAvatar: value } ) } />
					<ToggleControl label={ __( 'Show Rating', 'wp-plugin-starter' ) } checked={ showRating } onChange={ ( value ) => setAttributes( { showRating: value } ) } />
				</PanelBody>
			</InspectorControls>

			<div className={ isLoading ? 'wp-plugin-starter-block-loading' : '' }>
				<ServerSideRender
					block="wp-plugin-starter/vendor-list"
					attributes={ attributes }
					EmptyResponsePlaceholder={ () => <p>{ __( 'No vendors found with the current criteria.', 'wp-plugin-starter' ) }</p> }
					LoadingResponsePlaceholder={ () => {
						setIsLoading( true );
						return <p>{ __( 'Loading vendors…', 'wp-plugin-starter' ) }</p>;
					} }
					ErrorResponsePlaceholder={ () => <p>{ __( 'Error loading vendors.', 'wp-plugin-starter' ) }</p> }
				/>
			</div>
		</>
	);
};

export default VendorListEdit;
