<?php

namespace WPPluginStarter\Core\DI;

use WPPluginStarter\Dependencies\League\Container\Container as LeagueContainer;

/**
 * Container class
 *
 * A dependency injection container extending League Container
 *
 * @package WPPluginStarter\Core\DI
 */
class Container extends LeagueContainer {
	/**
	 * Get all services with a specific tag
	 *
	 * This method resolves and returns all services tagged with the specified tag.
	 *
	 * @param string $tag The tag to search for
	 *
	 * @return array Array of resolved services with the specified tag
	 */
	public function getByTag( string $tag ): array {
		if ( $this->definitions->hasTag( $tag ) ) {
			return $this->definitions->resolveTagged( $tag );
		}

		return array();
	}
}
