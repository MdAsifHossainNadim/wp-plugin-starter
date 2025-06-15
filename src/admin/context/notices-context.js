/**
 * WordPress dependencies
 */
import { createContext, useCallback, useContext, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

// Notice types
export const NOTICE_TYPES = {
	SUCCESS: 'success',
	ERROR: 'error',
	WARNING: 'warning',
	INFO: 'info',
};

// Create the notices context
export const NoticesContext = createContext( {
	notices: [],
	addNotice: () => {},
	removeNotice: () => {},
	clearNotices: () => {},
} );

/**
 * Provider for the notices context
 *
 * @param {Object}          props          Component props
 * @param {React.ReactNode} props.children Child components
 */
export const NoticesProvider = ( { children } ) => {
	const [ notices, setNotices ] = useState( [] );

	/**
	 * Add a new notice
	 *
	 * @param {string}  message               The notice message
	 * @param {string}  type                  The notice type (success, error, warning, info)
	 * @param {Object}  options               Additional options for the notice
	 * @param {boolean} options.isDismissible Whether the notice can be dismissed (default: true)
	 * @param {number}  options.duration      Auto-dismiss duration in ms (0 for no auto-dismiss)
	 */
	const addNotice = useCallback( ( message, type = NOTICE_TYPES.INFO, options = {} ) => {
		const id = Date.now().toString();
		const { isDismissible = true, duration = 5000 } = options;

		const notice = {
			id,
			message,
			type,
			isDismissible,
		};

		setNotices( ( prevNotices ) => [ ...prevNotices, notice ] );

		// Auto-dismiss notice if duration is set
		if ( duration > 0 ) {
			setTimeout( () => {
				removeNotice( id );
			}, duration );
		}

		return id;
	}, [] );

	/**
	 * Remove a notice by ID
	 *
	 * @param {string} id The notice ID to remove
	 */
	const removeNotice = useCallback( ( id ) => {
		setNotices( ( prevNotices ) => prevNotices.filter( ( notice ) => notice.id !== id ) );
	}, [] );

	/**
	 * Clear all notices
	 */
	const clearNotices = useCallback( () => {
		setNotices( [] );
	}, [] );

	const value = {
		notices,
		addNotice,
		removeNotice,
		clearNotices,
	};

	return <NoticesContext.Provider value={ value }>{ children }</NoticesContext.Provider>;
};

/**
 * Custom hook to use the notices context
 *
 * @return {Object} The notices context value
 */
export const useNotices = () => {
	const context = useContext( NoticesContext );

	if ( context === undefined ) {
		throw new Error( 'useNotices must be used within a NoticesProvider' );
	}

	return context;
};
