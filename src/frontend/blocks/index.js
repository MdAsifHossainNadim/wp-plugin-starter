/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import VendorListEdit from '@frontend/blocks/vendor-list/edit';
import VendorListSave from '@frontend/blocks/vendor-list/save';

/**
 * Register all blocks
 */
const registerBlocks = () => {
	// Register Vendor List block
	registerBlockType( 'wp-plugin-starter/vendor-list', {
		title: __( 'Dokan Vendor List', 'wp-plugin-starter' ),
		description: __( 'Display a customizable list of vendors', 'wp-plugin-starter' ),
		category: 'dokan',
		icon: 'groups',
		keywords: [ __( 'vendor', 'wp-plugin-starter' ), __( 'dokan', 'wp-plugin-starter' ), __( 'seller', 'wp-plugin-starter' ) ],
		supports: {
			html: false,
			align: [ 'wide', 'full' ],
		},
		attributes: {
			numberOfVendors: {
				type: 'number',
				default: 10,
			},
			orderBy: {
				type: 'string',
				default: 'registered',
			},
			order: {
				type: 'string',
				default: 'desc',
			},
			showAvatar: {
				type: 'boolean',
				default: true,
			},
			showRating: {
				type: 'boolean',
				default: true,
			},
			columnsPerRow: {
				type: 'number',
				default: 3,
			},
		},
		edit: VendorListEdit,
		save: VendorListSave,
	} );

	// Register additional blocks here
};

// Initialize the blocks
registerBlocks();
