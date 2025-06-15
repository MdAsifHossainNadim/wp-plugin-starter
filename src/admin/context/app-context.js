/**
 * WordPress dependencies
 */
import { createContext, useContext, useState } from '@wordpress/element';

// Create context
const AppContext = createContext( {} );

/**
 * App Provider Component
 * Provides global app state and functionality
 *
 * @param  {Object}          props          Component props
 * @param  {React.ReactNode} props.children Child components
 * @return {JSX.Element}                    Provider component
 */
export const AppProvider = ( { children } ) => {
	// Get the initial page from PHP data
	const initialPage = window.WP_Plugin_Starter?.adminData?.initialPage || 'dashboard';

	// State for file management
	const [ files, setFiles ] = useState( {} );
	const [ isLoadingFile, setIsLoadingFile ] = useState( false );

	// App state
	const [ appState, setAppState ] = useState( {
		sidebarOpen: window.innerWidth >= 768, // Open on desktop by default
		isLoading: false,
		currentUser: window.WP_Plugin_Starter?.adminData?.currentUser || {},
	} );

	// Toggle sidebar
	const toggleSidebar = () => {
		setAppState( ( prev ) => ( {
			...prev,
			sidebarOpen: ! prev.sidebarOpen,
		} ) );
	};

	// File management functions
	const loadFile = async ( fileId, fileContent = null ) => {
		setIsLoadingFile( true );

		try {
			// If content is provided, just store it
			if ( fileContent ) {
				setFiles( ( prev ) => ( {
					...prev,
					[ fileId ]: {
						id: fileId,
						content: fileContent,
						isDirty: false,
						lastModified: new Date(),
					},
				} ) );
				setIsLoadingFile( false );
				return;
			}

			// Otherwise fetch from API
			const response = await fetch( `${ window.WP_Plugin_Starter.restUrl }/files/${ fileId }` );
			const data = await response.json();

			if ( data.success ) {
				setFiles( ( prev ) => ( {
					...prev,
					[ fileId ]: {
						id: fileId,
						content: data.content,
						isDirty: false,
						lastModified: new Date( data.modified ),
					},
				} ) );
			}
		} catch ( error ) {
			console.error( 'Failed to load file:', error );
		} finally {
			setIsLoadingFile( false );
		}
	};

	const updateFile = ( fileId, content ) => {
		setFiles( ( prev ) => {
			// If file doesn't exist, create it
			if ( ! prev[ fileId ] ) {
				return {
					...prev,
					[ fileId ]: {
						id: fileId,
						content,
						isDirty: true,
						lastModified: new Date(),
					},
				};
			}

			// Otherwise update existing file
			return {
				...prev,
				[ fileId ]: {
					...prev[ fileId ],
					content,
					isDirty: true,
					lastModified: new Date(),
				},
			};
		} );
	};

	const saveFile = async ( fileId ) => {
		if ( ! files[ fileId ] || ! files[ fileId ].isDirty ) {
			return { success: false, message: 'No changes to save' };
		}

		setAppState( ( prev ) => ( { ...prev, isLoading: true } ) );

		try {
			const response = await fetch( `${ window.WP_Plugin_Starter.restUrl }/files/${ fileId }`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': window.WP_Plugin_Starter.restNonce,
				},
				body: JSON.stringify( {
					content: files[ fileId ].content,
					file_id: fileId,
				} ),
			} );

			const data = await response.json();

			if ( data.success ) {
				setFiles( ( prev ) => ( {
					...prev,
					[ fileId ]: {
						...prev[ fileId ],
						isDirty: false,
						lastModified: new Date(),
					},
				} ) );

				return {
					success: true,
					message: 'File saved successfully',
					data: data.data || null,
				};
			}
			return {
				success: false,
				message: data.message || 'Failed to save file',
				error: data.error || null,
			};
		} catch ( error ) {
			console.error( 'Failed to save file:', error );
			return {
				success: false,
				message: 'Error while saving file',
				error: error.message,
			};
		} finally {
			setAppState( ( prev ) => ( { ...prev, isLoading: false } ) );
		}
	};

	// Context value
	const value = {
		appState,
		setAppState,
		toggleSidebar,
		initialPage,
		files,
		loadFile,
		updateFile,
		saveFile,
		isLoadingFile,
	};

	return <AppContext.Provider value={ value }>{ children }</AppContext.Provider>;
};

/**
 * Hook to use app context
 *
 * @return {Object} App context value
 */
export const useApp = () => {
	const context = useContext( AppContext );

	if ( ! context ) {
		throw new Error( 'useApp must be used within an AppProvider' );
	}

	return context;
};
