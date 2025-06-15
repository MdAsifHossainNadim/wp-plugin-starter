<?php

namespace WPPluginStarter\Frontend;

/**
 * Frontend Class
 *
 * @package WPPluginStarter\Frontend
 */
class Frontend {

	/**
	 * Initialize frontend
	 *
	 * @return void
	 */
	public function __construct() {
		//      // Initialize frontend assets
		//      try {
		//          $assets = $this->container->get( 'frontend.assets' );
		//          $assets->init();
		//      } catch ( \Exception $e ) {
		//          // Log error or handle gracefully
		//      }

		/**
		 * Action after frontend initialization
		 *
		 * @param Frontend $this Frontend instance
		 */
		do_action( 'WPPluginStarter_frontend_init', $this );
	}
}
