<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

use WPPluginStarter\Admin\Dashboard\Components\Fields\Checkbox;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Multi_Check;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Currency;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Password;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Radio;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Radio_Box;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Select;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Switcher;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Tel;
use WPPluginStarter\Admin\Dashboard\Components\Tab;
use WPPluginStarter\Admin\Dashboard\Components\Fields\Text;

/**
 * Component Factory Class
 *
 * Factory for creating dashboard UI components
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class Component_Factory {
	/**
	 * Get a new Page object.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 *
	 * @return Page
	 */
	public static function page( string $id ): Page {
		/**
		 * Filter the page component before creation
		 *
		 * @since 1.0.0
		 * @param string $id The page ID
		 */
		$id = apply_filters( 'WPPluginStarter_dashboard_page_id', $id );

		$page = new Page( $id );

		/**
		 * Filter the page component after creation
		 *
		 * @since 1.0.0
		 * @param Page $page The page component
		 * @param string $id The page ID
		 */
		return apply_filters( 'WPPluginStarter_dashboard_page', $page, $id );
	}

	/**
	 * Get a new tab object.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 *
	 * @return Tab
	 */
	public static function tab( string $id ): Tab {
		/**
		 * Filter the tab component before creation
		 *
		 * @since 1.0.0
		 * @param string $id The tab ID
		 */
		$id = apply_filters( 'WPPluginStarter_dashboard_tab_id', $id );

		$tab = new Tab( $id );

		/**
		 * Filter the tab component after creation
		 *
		 * @since 1.0.0
		 * @param Tab $tab The tab component
		 * @param string $id The tab ID
		 */
		return apply_filters( 'WPPluginStarter_dashboard_tab', $tab, $id );
	}

	/**
	 * Get a new Section object.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 *
	 * @return Section
	 */
	public static function section( string $id ): Section {
		/**
		 * Filter the section component before creation
		 *
		 * @since 1.0.0
		 * @param string $id The section ID
		 */
		$id = apply_filters( 'WPPluginStarter_dashboard_section_id', $id );

		$section = new Section( $id );

		/**
		 * Filter the section component after creation
		 *
		 * @since 1.0.0
		 * @param Section $section The section component
		 * @param string $id The section ID
		 */
		return apply_filters( 'WPPluginStarter_dashboard_section', $section, $id );
	}

	/**
	 * Get a new SubSection object.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 *
	 * @return SubSection
	 */
	public static function sub_section( string $id ): SubSection {
		/**
		 * Filter the subsection component before creation
		 *
		 * @since 1.0.0
		 * @param string $id The subsection ID
		 */
		$id = apply_filters( 'WPPluginStarter_dashboard_subsection_id', $id );

		$subsection = new SubSection( $id );

		/**
		 * Filter the subsection component after creation
		 *
		 * @since 1.0.0
		 * @param SubSection $subsection The subsection component
		 * @param string $id The subsection ID
		 */
		return apply_filters( 'WPPluginStarter_dashboard_subsection', $subsection, $id );
	}

	/**
	 * Get a new Field object.
	 *
	 * @since 1.0.0
	 * @param string $id ID.
	 * @param string $type Field Type.
	 *
	 * @return Text|Number|Checkbox|Radio|Select|Tel|Password|Radio_Box|Switcher|Multi_Check|Currency
	 */
	public static function field( string $id, string $type = 'text' ): Settings_Element {
		/**
		 * Filter the field component before creation
		 *
		 * @since 1.0.0
		 * @param string $id The field ID
		 * @param string $type The field type
		 */
		$id = apply_filters( 'WPPluginStarter_dashboard_field_id', $id, $type );

		/**
		 * Filter the field type before creation
		 *
		 * @since 1.0.0
		 * @param string $type The field type
		 * @param string $id The field ID
		 */
		$type = apply_filters( 'WPPluginStarter_dashboard_field_type', $type, $id );

		$field = ( new Field( $id, $type ) )->get_input();

		/**
		 * Filter the field component after creation
		 *
		 * @since 1.0.0
		 * @param Settings_Element $field The field component
		 * @param string $id The field ID
		 * @param string $type The field type
		 */
		return apply_filters( 'WPPluginStarter_dashboard_field', $field, $id, $type );
	}
}
