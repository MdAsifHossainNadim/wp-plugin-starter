/**
 * WordPress dependencies
 */
import { Notice } from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { useNotices } from '@admin/context/notices-context';
import { cn } from '@admin/utils/tailwind-utils';

import './style.scss';

/**
 * Notices component - Displays admin notices
 *
 * @return {JSX.Element} The Notices component
 */
const Notices = () => {
	const { notices, removeNotice } = useNotices();

	// Auto-dismiss notices after 5 seconds
	// useEffect( () => {
	// 	const timers = notices.map( ( notice ) => {
	// 		return setTimeout( () => {
	// 			removeNotice( notice.id );
	// 		}, 5000 );
	// 	} );

	// 	return () => {
	// 		timers.forEach( clearTimeout );
	// 	};
	// }, [ notices, removeNotice ] );

	if ( ! notices.length ) {
		return null;
	}

	return (
		<div className="dk-admin-notices">
			{ notices.map( ( notice ) => (
				<Notice key={ notice.id } status={ notice.type } isDismissible={ true } onRemove={ () => removeNotice( notice.id ) } className={ cn( 'dk-admin-notice', `dk-admin-notice-${ notice.type }`, 'dk-mb-wp-4', 'last:dk-mb-wp-0' ) }>
					{ notice.message }
				</Notice>
			) ) }
		</div>
	);
};

export default Notices;
