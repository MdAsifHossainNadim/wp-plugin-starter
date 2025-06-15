/* global WP_Plugin_Starter */

/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { Button, Modal } from '@wordpress/components';
import { useState, useEffect, useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Notices from '@admin/components/common/notices';
import { FullPageSpinner } from '@admin/components/common/spinner';
import { useNotices } from '@admin/context/notices-context';

import logoImage from '../../../../assets/images/wp-plugin-starter-logo.png';

import './style.scss';

/**
 * Dashboard page component - Matches the original PHP template
 *
 * @return {JSX.Element} The Dashboard page component
 */
const DashboardPage = () => {
	const [ isLoading, setIsLoading ] = useState( true );
	const [ isResetting, setIsResetting ] = useState( false );
	const [ isImporting, setIsImporting ] = useState( false );
	const [ isExporting, setIsExporting ] = useState( false );
	const [ showResetModal, setShowResetModal ] = useState( false );
	const [ showImportModal, setShowImportModal ] = useState( false );
	const [ resetScope, setResetScope ] = useState( 'all' );
	const [ importFile, setImportFile ] = useState( null );
	const fileInputRef = useRef( null );
	const [ dashboardData, setDashboardData ] = useState( {
		setting_names: [],
		active_features_count: 0,
		total_settings_count: 0,
		dokan_version: '',
		url: '',
	} );
	const { addNotice } = useNotices();

	// Fetch dashboard data on mount
	useEffect( () => {
		const fetchDashboardData = async () => {
			try {
				setIsLoading( true );
				const response = await apiFetch( {
					path: '/wp-plugin-starter/v1/dashboard',
				} );

				// Check if response has the expected structure
				if ( ! response.success ) {
					throw new Error( response.message || __( 'Invalid response from the server', 'wp-plugin-starter' ) );
				}

				// Extract statistics data from the updated API response structure
				const statisticsData = response.data?.statistics || {};

				setDashboardData( {
					active_features_count: statisticsData.active_features?.value || 0,
					total_settings_count: statisticsData.total_features?.value || 0,
					dokan_version: statisticsData.dokan_version?.value || __( 'Not Detected', 'wp-plugin-starter' ),
					url: window.location.href,
					setting_names: response.data?.setting_names || [],
				} );
			} catch ( error ) {
				addNotice(
					error.message || __( 'Failed to load dashboard data. Please try again.', 'wp-plugin-starter' ),
					'error'
				);
			} finally {
				setIsLoading( false );
			}
		};

		void fetchDashboardData();
	}, [] );

	// Handle import modal toggling
	const handleOpenImportModal = () => {
		setShowImportModal( true );
	};

	const handleCloseImportModal = () => {
		setShowImportModal( false );
		setImportFile( null );
		if ( fileInputRef.current ) {
			fileInputRef.current.value = '';
		}
	};

	const handleImportFileChange = ( event ) => {
		const file = event.target.files[ 0 ];
		setImportFile( file );
	};

	const handleImportSettings = async () => {
		if ( ! importFile ) {
			addNotice( __( 'Please select a file to import.', 'wp-plugin-starter' ), 'error' );
			return;
		}

		if ( ! importFile.name.endsWith( '.json' ) ) {
			addNotice( __( 'Please select a valid JSON file.', 'wp-plugin-starter' ), 'error' );
			return;
		}

		try {
			setIsImporting( true );

			// Read the file content
			const fileReader = new window.FileReader();
			fileReader.onload = async ( e ) => {
				try {
					const settingsData = JSON.parse( e.target.result );

					// Send the settings data to the API
					const response = await apiFetch( {
						path: '/wp-plugin-starter/v1/features/import',
						method: 'POST',
						data: {
							settings: settingsData,
						},
					} );

					if ( response.success ) {
						addNotice( response.message || __( 'SettingsModel imported successfully.', 'wp-plugin-starter' ), 'success' );

						// Refresh dashboard data to reflect the changes
						const dashboardResponse = await apiFetch( {
							path: '/wp-plugin-starter/v1/dashboard',
						} );

						if ( dashboardResponse.success ) {
							const statisticsData = dashboardResponse.data?.statistics || {};

							setDashboardData( {
								active_features_count: statisticsData.active_features?.value || 0,
								total_settings_count: statisticsData.total_features?.value || 0,
								dokan_version: statisticsData.dokan_version?.value || __( 'Not Detected', 'wp-plugin-starter' ),
								url: window.location.href,
								setting_names: response.data?.setting_names || [],
							} );
						}
					} else {
						throw new Error( response.message || __( 'Failed to import settings.', 'wp-plugin-starter' ) );
					}

					setIsImporting( false );
					handleCloseImportModal();
				} catch ( error ) {
					addNotice(
						error.message || __( 'Failed to import settings. Please try again.', 'wp-plugin-starter' ),
						'error'
					);
					setIsImporting( false );
				}
			};

			fileReader.onerror = () => {
				addNotice( __( 'Failed to read the file. Please try again.', 'wp-plugin-starter' ), 'error' );
				setIsImporting( false );
			};

			fileReader.readAsText( importFile );
		} catch ( error ) {
			addNotice(
				error.message || __( 'Failed to import settings. Please try again.', 'wp-plugin-starter' ),
				'error'
			);
			setIsImporting( false );
		}
	};

	// Handle reset settings
	const handleOpenResetModal = () => {
		setShowResetModal( true );
	};

	const handleCloseResetModal = () => {
		setShowResetModal( false );
	};

	const handleResetSettings = async () => {
		try {
			setIsResetting( true );

			const response = await apiFetch( {
				path: '/wp-plugin-starter/v1/features/reset',
				method: 'POST',
				data: {
					scope: resetScope,
					confirm: true,
				},
			} );

			if ( response.success ) {
				addNotice( response.message || __( 'SettingsModel reset successfully.', 'wp-plugin-starter' ), 'success' );

				// Refresh dashboard data to reflect the changes
				const dashboardResponse = await apiFetch( {
					path: '/wp-plugin-starter/v1/dashboard',
				} );

				if ( dashboardResponse.success ) {
					const statisticsData = dashboardResponse.data?.statistics || {};

					setDashboardData( {
						setting_names: response.data?.setting_names || [],
						active_features_count: statisticsData.active_features?.value || 0,
						total_settings_count: statisticsData.total_features?.value || 0,
						dokan_version: statisticsData.dokan_version?.value || __( 'Not Detected', 'wp-plugin-starter' ),
						url: window.location.href,
					} );
				}
			} else {
				throw new Error( response.message || __( 'Failed to reset settings.', 'wp-plugin-starter' ) );
			}
		} catch ( error ) {
			addNotice(
				error.message || __( 'Failed to reset settings. Please try again.', 'wp-plugin-starter' ),
				'error'
			);
		} finally {
			setIsResetting( false );
			handleCloseResetModal();
		}
	};

	// Handle export settings
	const handleExportSettings = async () => {
		try {
			setIsExporting( true );
			addNotice( __( 'Preparing export file…', 'wp-plugin-starter' ), 'info' );

			const response = await apiFetch( {
				path: '/wp-plugin-starter/v1/features/export',
				method: 'GET',
			} );

			if ( response && response.data ) {
				// Create a Blob with the JSON data
				const blob = new Blob( [ JSON.stringify( response.data.data, null, 2 ) ], { type: 'application/json' } );

				// Create a temporary download link
				const url = window.URL.createObjectURL( blob );
				const downloadLink = document.createElement( 'a' );
				downloadLink.href = url;

				// Set the filename with current date
				const date = new Date();
				const dateString = date.toISOString().split( 'T' )[ 0 ];
				downloadLink.download = `wp-plugin-starter-settings-${ dateString }.json`;

				// Append to body, click, and clean up
				document.body.appendChild( downloadLink );
				downloadLink.click();
				window.URL.revokeObjectURL( url );
				document.body.removeChild( downloadLink );

				addNotice( __( 'SettingsModel exported successfully.', 'wp-plugin-starter' ), 'success' );
			} else {
				throw new Error( __( 'No data received from the server.', 'wp-plugin-starter' ) );
			}
		} catch ( error ) {
			addNotice(
				error.message || __( 'Failed to export settings. Please try again.', 'wp-plugin-starter' ),
				'error',
			);
		} finally {
			setIsExporting( false );
		}
	};

	// If still loading, show loading spinner
	if ( isLoading ) {
		return <FullPageSpinner message={ __( 'Loading dashboard…', 'wp-plugin-starter' ) } />;
	}

	return (
		<div className="wp-plugin-starter-admin-page">
			<Notices />

			{ /* Welcome Panel */ }
			<div className="dk-admin-card dk-bg-gradient-to-r dk-from-primary-50 dk-to-secondary-50 dk-border-l-4 dk-border-primary-500 dk-mb-wp-6">
				<div className="dk-flex dk-items-center">
					<div className="dk-flex-shrink-0 dk-mr-wp-4">
						<img src={ logoImage } alt="WP Plugin Starter" className="dk-w-16 dk-h-16" />
					</div>
					<div>
						<h2 className="dk-text-xl dk-font-medium dk-text-gray-900">{ __( 'Welcome to WP Plugin Starter', 'wp-plugin-starter' ) }</h2>
						<p className="dk-mt-1 dk-text-sm dk-text-gray-600">
							{ __( 'You are running version', 'wp-plugin-starter' ) } <span className="dk-font-medium">{ WP_Plugin_Starter.version }</span>.
							{ __( 'Enhance your Dokan-powered marketplace with powerful tools and customizations.', 'wp-plugin-starter' ) }
						</p>
					</div>
				</div>
			</div>

			<div className="dk-grid dk-grid-cols-1 dk-gap-wp-6">
				{ /* Quick Stats */ }
				<div className="dk-grid dk-grid-cols-1 md:dk-grid-cols-3 dk-gap-wp-4">
					<div className="dk-admin-card dk-border-t-4 dk-border-primary-500 dk-bg-primary-50">
						<div className="dk-flex dk-justify-between dk-items-center">
							<div>
								<h3 className="dk-text-lg dk-font-medium">{ __( 'Total Features', 'wp-plugin-starter' ) }</h3>
								<p className="dk-text-2xl dk-font-bold dk-mt-1">{ dashboardData.total_settings_count }</p>
							</div>
							<div className="dk-rounded-full dk-w-12 dk-h-12 dk-flex dk-items-center dk-justify-center dk-bg-white dk-border dk-border-gray-200">
								<span className="dashicons dashicons-admin-plugins dk-text-primary-500"></span>
							</div>
						</div>
					</div>

					<div className="dk-admin-card dk-border-t-4 dk-border-secondary-500 dk-bg-secondary-50">
						<div className="dk-flex dk-justify-between dk-items-center">
							<div>
								<h3 className="dk-text-lg dk-font-medium">{ __( 'Total Active Features', 'wp-plugin-starter' ) }</h3>
								<p className="dk-text-2xl dk-font-bold dk-mt-1">{ dashboardData.active_features_count }</p>
							</div>
							<div className="dk-rounded-full dk-w-12 dk-h-12 dk-flex dk-items-center dk-justify-center dk-bg-white dk-border dk-border-gray-200">
								<span className="dashicons dashicons-admin-generic dk-text-secondary-500"></span>
							</div>
						</div>
					</div>

					<div className="dk-admin-card dk-border-t-4 dk-border-green-500 dk-bg-green-50">
						<div className="dk-flex dk-justify-between dk-items-center">
							<div>
								<h3 className="dk-text-lg dk-font-medium">{ __( 'Dokan Version', 'wp-plugin-starter' ) }</h3>
								<p className="dk-text-2xl dk-font-bold dk-mt-1">{ dashboardData.dokan_version }</p>
							</div>
							<div className="dk-rounded-full dk-w-12 dk-h-12 dk-flex dk-items-center dk-justify-center dk-bg-white dk-border dk-border-gray-200">
								<span className="dashicons dashicons-admin-tools dk-text-green-500"></span>
							</div>
						</div>
					</div>
				</div>

				{ /* Main Content Area */ }
				<div className="dk-grid dk-grid-cols-1 lg:dk-grid-cols-3 dk-gap-wp-6">
					{ /* SettingsModel Management Card */ }
					<div className="dk-admin-card">
						<h2 className="dk-text-lg dk-font-medium dk-border-b dk-border-gray-200 dk-pb-wp-3 dk-mb-wp-4">{ __( 'SettingsModel Management', 'wp-plugin-starter' ) }</h2>
						<div>
							<p className="dk-text-sm dk-text-gray-600 dk-mb-wp-4">{ __( 'Manage your WP Plugin Starter features with these options:', 'wp-plugin-starter' ) }</p>

							<div className="dk-space-y-wp-3">
								<Button
									href={ `${ WP_Plugin_Starter.adminUrl }?page=wp-plugin-starter#/settings` }
									variant="primary"
									className="dk-admin-button dk-w-full dk-justify-center dk-h-9"
								>
									<span className="dashicons dashicons-admin-settings dk-mr-2"></span>
									{ __( 'Configure SettingsModel', 'wp-plugin-starter' ) }
								</Button>

								<Button
									variant="secondary"
									onClick={ handleExportSettings }
									disabled={ isExporting }
									className="dk-admin-button dk-admin-button-secondary dk-w-full dk-justify-center dk-h-9"
								>
									<span className="dashicons dashicons-download dk-mr-2"></span>
									{ isExporting ? __( 'Exporting…', 'wp-plugin-starter' ) : __( 'Export SettingsModel', 'wp-plugin-starter' ) }
								</Button>

								<Button
									variant="secondary"
									onClick={ handleOpenImportModal }
									className="dk-admin-button dk-admin-button-secondary dk-w-full dk-justify-center dk-h-9"
								>
									<span className="dashicons dashicons-upload dk-mr-2"></span>
									{ __( 'Import SettingsModel', 'wp-plugin-starter' ) }
								</Button>

								<Button
									variant="secondary"
									onClick={ handleOpenResetModal }
									className="dk-admin-button dk-admin-button-danger dk-w-full dk-justify-center dk-h-9"
								>
									<span className="dashicons dashicons-image-rotate dk-mr-2"></span>
									{ __( 'Reset SettingsModel', 'wp-plugin-starter' ) }
								</Button>
							</div>
						</div>
					</div>

					{ /* Documentation & Support Card */ }
					<div className="dk-admin-card">
						<h2 className="dk-text-lg dk-font-medium dk-border-b dk-border-gray-200 dk-pb-wp-3 dk-mb-wp-4">{ __( 'Documentation & Support', 'wp-plugin-starter' ) }</h2>
						<div>
							<p className="dk-text-sm dk-text-gray-600 dk-mb-wp-4">{ __( 'Need help with WP Plugin Starter? Check out these resources:', 'wp-plugin-starter' ) }</p>

							<ul className="dk-space-y-wp-3">
								<li>
									<a
										href="https://wordpress.org/support/plugin/wp-plugin-starter/"
										target="_blank"
										rel="noopener noreferrer"
										className="dk-flex dk-items-center dk-text-primary-600 dk-hover:text-primary-800"
									>
										<span className="dk-w-8 dk-h-8 dk-rounded-full dk-bg-primary-100 dk-flex dk-items-center dk-justify-center dk-mr-wp-2">
											<span className="dashicons dashicons-editor-help"></span>
										</span>
										{ __( 'Support Forum', 'wp-plugin-starter' ) }
									</a>
								</li>
								<li>
									<a
										href="https://wordpress.org/support/plugin/wp-plugin-starter/reviews/#new-post"
										target="_blank"
										rel="noopener noreferrer"
										className="dk-flex dk-items-center dk-text-primary-600 dk-hover:text-primary-800"
									>
										<span className="dk-w-8 dk-h-8 dk-rounded-full dk-bg-primary-100 dk-flex dk-items-center dk-justify-center dk-mr-wp-2">
											<span className="dashicons dashicons-admin-comments"></span>
										</span>
										{ __( 'Submit your feedback', 'wp-plugin-starter' ) }
									</a>
								</li>
								<li>
									<a
										href="https://github.com/wpintegrity/feedback/issues"
										target="_blank"
										rel="noopener noreferrer"
										className="dk-flex dk-items-center dk-text-primary-600 dk-hover:text-primary-800"
									>
										<span className="dk-w-8 dk-h-8 dk-rounded-full dk-bg-primary-100 dk-flex dk-items-center dk-justify-center dk-mr-wp-2">
											<span className="dashicons dashicons-feedback"></span>
										</span>
										{ __( 'Feature Idea or Bug Report', 'wp-plugin-starter' ) }
									</a>
								</li>
							</ul>

							<div className="dk-mt-wp-6 dk-p-wp-3 dk-bg-yellow-50 dk-rounded-md dk-border dk-border-yellow-200">
								<h3 className="dk-text-md dk-font-medium dk-text-yellow-800 dk-mb-wp-2">
									<span className="dashicons dashicons-warning dk-mr-1"></span>
									{ __( 'Need Help?', 'wp-plugin-starter' ) }
								</h3>
								<p className="dk-text-sm dk-text-yellow-700 dk-mb-wp-3">{ __( 'If you need support or have a feature request, please visit our support forum.', 'wp-plugin-starter' ) }</p>
								<Button
									href="https://wordpress.org/support/plugin/wp-plugin-starter/#new-topic-0"
									target="_blank"
									rel="noopener noreferrer"
									className="dk-admin-button dk-admin-button-warning dk-w-full dk-justify-center"
								>
									{ __( 'Get Support', 'wp-plugin-starter' ) }
								</Button>
							</div>
						</div>
					</div>
				</div>
			</div>

			{ /* Import SettingsModel Modal */ }
			{ showImportModal && (
				<Modal
					title={ __( 'Import SettingsModel', 'wp-plugin-starter' ) }
					onRequestClose={ handleCloseImportModal }
					className="wp-plugin-starter-admin-page wp-plugin-starter-admin-modal wp-plugin-starter-import-modal"
				>
					<div className="dk-modal-content">
						<div className="dk-modal-header">
							<h2 className="dk-modal-title">{ __( 'Import SettingsModel', 'wp-plugin-starter' ) }</h2>
							<button
								type="button"
								className="dk-modal-close"
								onClick={ handleCloseImportModal }
								aria-label={ __( 'Close modal', 'wp-plugin-starter' ) }
							>
								<span className="dashicons dashicons-no-alt"></span>
							</button>
						</div>
						<div className="dk-modal-body">
							<div className="dk-modal-icon dk-bg-primary-50 dk-text-primary-500">
								<span className="dashicons dashicons-upload"></span>
							</div>
							<p className="dk-modal-description">
								{ __( 'Select a WP Plugin Starter settings file to import. This will overwrite your current settings.', 'wp-plugin-starter' ) }
							</p>
							<div className="dk-form-field">
								<label htmlFor="import-file" className="dk-form-label">
									{ __( 'SettingsModel File', 'wp-plugin-starter' ) }
								</label>
								<div className="dk-file-input-wrapper">
									<input
										type="file"
										id="import-file"
										ref={ fileInputRef }
										accept=".json"
										onChange={ handleImportFileChange }
										className="dk-file-input"
									/>
									<div className="dk-file-input-info">
										{ importFile ? (
											<span className="dk-file-name">{ importFile.name }</span>
										) : (
											<span className="dk-file-placeholder">{ __( 'Choose a file or drag it here', 'wp-plugin-starter' ) }</span>
										) }
									</div>
								</div>
								<p className="dk-form-help">
									{ __( 'Only .json files exported from WP Plugin Starter are supported.', 'wp-plugin-starter' ) }
								</p>
							</div>
						</div>
						<div className="dk-modal-footer">
							<Button
								variant="secondary"
								onClick={ handleCloseImportModal }
								className="dk-admin-button dk-admin-button-secondary"
							>
								{ __( 'Cancel', 'wp-plugin-starter' ) }
							</Button>
							<Button
								variant="primary"
								isBusy={ isImporting }
								disabled={ isImporting || ! importFile }
								onClick={ handleImportSettings }
								className="dk-admin-button"
							>
								{ isImporting ? __( 'Importing…', 'wp-plugin-starter' ) : __( 'Import', 'wp-plugin-starter' ) }
							</Button>
						</div>
					</div>
				</Modal>
			) }

			{ /* Reset SettingsModel Modal */ }
			{ showResetModal && (
				<Modal
					title={ __( 'Reset SettingsModel', 'wp-plugin-starter' ) }
					onRequestClose={ handleCloseResetModal }
					className="wp-plugin-starter-admin-page wp-plugin-starter-admin-modal wp-plugin-starter-reset-modal"
				>
					<div className="dk-modal-content">
						<div className="dk-modal-header">
							<h2 className="dk-modal-title">{ __( 'Reset SettingsModel', 'wp-plugin-starter' ) }</h2>
							<button
								type="button"
								className="dk-modal-close"
								onClick={ handleCloseResetModal }
								aria-label={ __( 'Close modal', 'wp-plugin-starter' ) }
							>
								<span className="dashicons dashicons-no-alt"></span>
							</button>
						</div>
						<div className="dk-modal-body">
							<div className="dk-modal-icon dk-bg-red-50 dk-text-red-500">
								<span className="dashicons dashicons-warning"></span>
							</div>
							<p className="dk-modal-description dk-text-red-700">
								{ __( 'Are you sure you want to reset WP Plugin Starter settings? This action cannot be undone.', 'wp-plugin-starter' ) }
							</p>
							<div className="dk-form-field">
								<label htmlFor="reset-scope" className="dk-form-label">
									{ __( 'Reset Scope', 'wp-plugin-starter' ) }
								</label>
								<select
									id="reset-scope"
									className="dk-form-select"
									value={ resetScope }
									onChange={ ( e ) => setResetScope( e.target.value ) }
								>
									<option value="all">{ __( 'All SettingsModel', 'wp-plugin-starter' ) }</option>
									{ dashboardData.setting_names && dashboardData.setting_names.map( ( settingName, index ) => (
										<option key={ index } value={ settingName.toLowerCase() }>
											{ settingName }
										</option>
									) ) }
								</select>
							</div>

							{ /* Display settings names if available */ }
							{ dashboardData.setting_names && dashboardData.setting_names.length > 0 && (
								<div className="dk-mt-wp-4">
									<p className="dk-form-label dk-mb-wp-2">{ __( 'Available SettingsModel:', 'wp-plugin-starter' ) }</p>
									<div className="dk-p-wp-3 dk-bg-gray-50 dk-rounded-md dk-border dk-border-gray-200 dk-max-h-60 dk-overflow-y-auto">
										<div className="dk-flex dk-flex-wrap dk-gap-wp-2">
											{ dashboardData.setting_names.map( ( name, index ) => (
												<span key={ index } className="dk-text-sm dk-text-gray-700 dk-bg-white dk-px-wp-2 dk-py-wp-1 dk-rounded dk-border dk-border-gray-200">
													{ name }
												</span>
											) ) }
										</div>
									</div>
								</div>
							) }
						</div>
						<div className="dk-modal-footer">
							<Button
								variant="secondary"
								onClick={ handleCloseResetModal }
								className="dk-admin-button dk-admin-button-secondary"
							>
								{ __( 'Cancel', 'wp-plugin-starter' ) }
							</Button>
							<Button
								variant="primary"
								isBusy={ isResetting }
								disabled={ isResetting }
								onClick={ handleResetSettings }
								className="dk-admin-button dk-admin-button-danger"
							>
								{ isResetting ? __( 'Resetting…', 'wp-plugin-starter' ) : __( 'Reset', 'wp-plugin-starter' ) }
							</Button>
						</div>
					</div>
				</Modal>
			) }
		</div>
	);
};

export default DashboardPage;
