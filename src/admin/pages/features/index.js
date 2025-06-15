/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { Button, Card, CardBody, CardFooter, CardHeader, TabPanel } from '@wordpress/components';
import { useCallback, useEffect, useState } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import FormNotices from '@admin/components/common/form-notices';
import { FullPageSpinner } from '@admin/components/common/spinner';
import DependencyDebug from '@admin/components/debug/DependencyDebug';
import StructureDebug from '@admin/components/debug/StructureDebug';
import DynamicTab from '@admin/components/features/dynamic-tab';
import useForm from '@admin/hooks/use-form';
import { useStructure } from '@admin/hooks/use-structure';

// import CustomProductTab from './tabs/custom-product';
// import CustomVendorTab from './tabs/custom-vendor';

import './style.scss';

// Define any custom tab components here
const CUSTOM_TABS = {
	// vendor: CustomVendorTab,
	// product: CustomProductTab,
	// Other tabs will use the dynamic tab component
	// cart: DynamicTab,
	// shipping: DynamicTab,
	// display: DynamicTab,
};

/**
 * Debug toolbar component
 *
 * @param  {Object}      props              Component props
 * @param  {boolean}     props.showDebug    Whether debug mode is enabled
 * @param  {Function}    props.setShowDebug Function to toggle debug mode
 * @param  {string}      props.debugMode    Current debug mode
 * @param  {Function}    props.setDebugMode Function to set debug mode
 * @return {JSX.Element}                    The debug toolbar component
 */
const DebugToolbar = ( { showDebug, setShowDebug, debugMode, setDebugMode } ) => (
	<div className="dk-flex dk-justify-end dk-mb-wp-4 dk-gap-2">
		<Button variant="secondary" onClick={ () => setShowDebug( ! showDebug ) }>
			{ showDebug ? 'Hide Debug' : 'Show Debug' }
		</Button>

		{ showDebug && (
			<>
				<Button
					variant={ debugMode === 'dependency' ? 'primary' : 'secondary' }
					onClick={ () => setDebugMode( 'dependency' ) }
				>
					Dependencies
				</Button>
				<Button
					variant={ debugMode === 'structure' ? 'primary' : 'secondary' }
					onClick={ () => setDebugMode( 'structure' ) }
				>
					Structure
				</Button>
			</>
		) }
	</div>
);

/**
 * Debug content component
 *
 * @param  {Object}           props                 Component props
 * @param  {string}           props.debugMode       Current debug mode
 * @param  {Object}           props.structure       Structure data
 * @param  {Object}           props.settings        SettingsModel data
 * @param  {Function}         props.onSettingChange Function to update settings
 * @return {JSX.Element|null}                       The debug content or null if not shown
 */
const DebugContent = ( { debugMode, structure, settings, onSettingChange } ) => {
	switch ( debugMode ) {
		case 'structure':
			return <StructureDebug structure={ structure } settings={ settings } />;
		case 'dependency':
			return <DependencyDebug structure={ structure } settings={ settings } onSettingChange={ onSettingChange } />;
		default:
			return null;
	}
};

/**
 * Tab renderer component
 *
 * @param  {Object}      props                  Component props
 * @param  {Object}      props.tab              Tab data
 * @param  {Object}      props.structure        Structure data for the tab
 * @param  {Object}      props.settings         SettingsModel data
 * @param  {Object}      props.validationErrors Validation errors
 * @param  {Function}    props.onSettingChange  Function to update settings
 * @return {JSX.Element}                        The rendered tab
 */
const TabRenderer = ( { tab, structure, settings, validationErrors, onSettingChange } ) => {
	const tabId = tab.name;

	// Check if there's a custom tab component
	if ( CUSTOM_TABS[ tabId ] ) {
		const CustomTabComponent = CUSTOM_TABS[ tabId ];
		return (
			<CustomTabComponent
				structure={ structure[ tabId ] }
				settings={ settings }
				validationErrors={ validationErrors }
				onSettingChange={ onSettingChange }
			/>
		);
	}

	// Otherwise use the dynamic tab component
	return (
		<DynamicTab
			tabId={ tabId }
			structure={ structure[ tabId ] }
			settings={ settings }
			validationErrors={ validationErrors }
			onSettingChange={ onSettingChange }
		/>
	);
};

/**
 * SettingsModel page component that loads structure from global variable
 * but still uses API for saving features
 *
 * @return {JSX.Element} The SettingsModel page component
 */
const SettingsPage = () => {
	// Fetch API settings
	const [ apiSettings, setApiSettings ] = useState( null );
	const [ isApiLoading, setIsApiLoading ] = useState( true );
	const [ settingValue, setSettingValue ] = useState( {} );

	// Fetch settings from API
	useEffect( () => {
		const fetchSettings = async () => {
			try {
				const response = await apiFetch( {
					path: '/wp-plugin-starter/v1/settings',
				} );

				if ( response.data ) {
					setApiSettings( response.data );
				}
				setIsApiLoading( false );
			} catch ( error ) {
				// Log error without using console directly
				setIsApiLoading( false );
			}
		};

		void fetchSettings();
	}, [] );

	// Update setting value in state
	const updateContextSetting = useCallback( ( key, value ) => {
		setSettingValue( ( prev ) => ( {
			...prev,
			[ key ]: value,
		} ) );
	}, [] );

	// Save settings to API
	const saveSettings = useCallback( async () => {
		try {
			return await apiFetch( {
				path: '/wp-plugin-starter/v1/settings',
				method: 'POST',
				data: {
					settings: settingValue,
				},
			} );
		} catch ( error ) {
			throw error;
		}
	}, [ settingValue ] );

	// Load structure and settings from useStructure hook, passing the API settings
	const { structure, settings, isLoading, updateSetting } = useStructure( apiSettings, updateContextSetting );

	// Debug state
	// const [ showDebug, setShowDebug ] = useState( false );
	// const [ debugMode, setDebugMode ] = useState( 'dependency' );

	// Use our form hook for handling form state
	const {
		isSaving,
		message,
		isError,
		validationErrors,
		saveForm,
		clearNotifications,
	} = useForm( structure, settings, saveSettings );

	// If still loading, show spinner
	if ( isLoading || isApiLoading ) {
		return <FullPageSpinner message={ __( 'Loading features…', 'wp-plugin-starter' ) } />;
	}

	// Create tabs from the features structure
	const tabs = Object.keys( structure ).map( ( tabId ) => ( {
		name: tabId,
		title: decodeEntities( structure[ tabId ].title ),
		className: `wp-plugin-starter-tab-${ tabId }`,
	} ) );

	return (
		<div className="dk-max-w-full dk-mx-auto dk-py-wp-5">
			{ /*<DebugToolbar*/ }
			{ /*	showDebug={ showDebug }*/ }
			{ /*	setShowDebug={ setShowDebug }*/ }
			{ /*	debugMode={ debugMode }*/ }
			{ /*	setDebugMode={ setDebugMode }*/ }
			{ /*/>*/ }

			{ /*{ showDebug &&*/ }
			{ /*	<DebugContent*/ }
			{ /*		debugMode={ debugMode }*/ }
			{ /*		structure={ structure }*/ }
			{ /*		settings={ settings }*/ }
			{ /*		onSettingChange={ updateSetting }*/ }
			{ /*	/>*/ }
			{ /*}*/ }

			<Card className="dk-shadow-sm">
				<CardHeader className="dk-border-b dk-border-gray-200">
					<h2 className="dk-text-xl dk-font-semibold dk-m-0">{ __( 'Manage Features', 'wp-plugin-starter' ) }</h2>
				</CardHeader>

				<CardBody>
					<FormNotices
						message={ message }
						isError={ isError }
						validationErrors={ validationErrors }
						onDismiss={ clearNotifications }
					/>

					<TabPanel
						activeClass="dk-text-primary-600 dk-border dk-border-solid dk-border-b-2 dk-border-primary-500 dk-bg-gray-50"
						tabs={ tabs }
					>
						{ ( tab ) => (
							<TabRenderer
								tab={ tab }
								structure={ structure }
								settings={ settings }
								validationErrors={ validationErrors }
								onSettingChange={ updateSetting }
							/>
						) }
					</TabPanel>
				</CardBody>

				<CardFooter className="dk-sticky dk-bottom-0 dk-border-t dk-border-gray-200 dk-bg-gray-50">
					<Button
						variant="primary"
						isBusy={ isSaving }
						onClick={ saveForm }
						disabled={ isLoading || isSaving }
						className="dk-w-full dk-justify-center hover:!dk-bg-primary-800 focus-visible:!dk-bg-primary-800 focus:!dk-ring-primary-800"
					>
						{ __( 'Save Changes', 'wp-plugin-starter' ) }
					</Button>
				</CardFooter>
			</Card>
		</div>
	);
};

export default SettingsPage;
