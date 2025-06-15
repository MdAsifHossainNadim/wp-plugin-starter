<?php

namespace WPPluginStarter\Core;

/**
 * Template Manager
 *
 * Manages plugin templates
 *
 * @since   1.0.0
 * @package WPPluginStarter\Core
 */
class Template_Manager {

	/**
	 * Template paths
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $template_paths = array();

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init_paths();
	}

	/**
	 * Initialize template paths
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function init_paths(): void {
		// Default template path
		$this->add_path( WPPluginStarter_TEMPLATE_PATH, 10 );
	}

	/**
	 * Add template path
	 *
	 * @since 1.0.0
	 *
	 * @param string $path     Template path
	 * @param int    $priority Path priority (lower is higher)
	 *
	 * @return self
	 */
	public function add_path( string $path, int $priority = 10 ): Template_Manager {
		/**
		 * Filter the template path before adding.
		 *
		 * @since 1.0.0
		 *
		 * @param string $path     The template path.
		 * @param int    $priority The path priority.
		 */
		$path = apply_filters( 'WPPluginStarter_template_path', $path, $priority );

		$this->template_paths[ $path ] = $priority;

		// Sort paths by priority
		asort( $this->template_paths );

		/**
		 * Action fired after adding a template path.
		 *
		 * @since 1.0.0
		 *
		 * @param string $path     The template path.
		 * @param int    $priority The path priority.
		 */
		do_action( 'WPPluginStarter_template_path_added', $path, $priority );

		return $this;
	}

	/**
	 * Get template paths
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_paths(): array {
		/**
		 * Filter template paths.
		 *
		 * @since 1.0.0
		 *
		 * @param array $template_paths The template paths.
		 */
		return apply_filters( 'WPPluginStarter_template_paths', $this->template_paths );
	}

	/**
	 * Locate template
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name
	 *
	 * @return string Template path
	 */
	public function locate_template( string $template_name ): string {
		/**
		 * Filter the template name before locating.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_name The template name.
		 */
		$template_name = apply_filters( 'WPPluginStarter_template_name', $template_name );

		/**
		 * Action fired before locating a template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_name The template name.
		 */
		do_action( 'WPPluginStarter_before_locate_template', $template_name );

		// Default theme template locations
		$theme_templates = array(
			"wp-plugin-starter/{$template_name}", // Child theme
			$template_name, // Parent theme
		);

		/**
		 * Filter the theme template locations.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $theme_templates The theme template locations.
		 * @param string $template_name   The template name.
		 */
		$theme_templates = apply_filters( 'WPPluginStarter_theme_template_locations', $theme_templates, $template_name );

		// Check if template exists in theme
		$template = locate_template( $theme_templates );

		// If not found in theme, check in plugin paths
		if ( ! $template ) {
			foreach ( $this->template_paths as $path => $priority ) {
				$file = trailingslashit( $path ) . $template_name;

				if ( file_exists( $file ) ) {
					$template = $file;
					break;
				}
			}
		}

		/**
		 * Filter the located template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template      The located template.
		 * @param string $template_name The template name.
		 */
		$template = apply_filters( 'WPPluginStarter_locate_template', $template, $template_name );

		/**
		 * Action fired after locating a template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template      The located template.
		 * @param string $template_name The template name.
		 */
		do_action( 'WPPluginStarter_after_locate_template', $template, $template_name );

		return $template;
	}

	/**
	 * Get template part
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug Template slug
	 * @param string $name Template name
	 * @param array  $args Template arguments
	 *
	 * @return void
	 */
	public function get_template_part( string $slug, string $name = '', array $args = array() ): void {
		/**
		 * Filter the template slug.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug The template slug.
		 * @param string $name The template name.
		 */
		$slug = apply_filters( 'WPPluginStarter_template_part_slug', $slug, $name );

		/**
		 * Filter the template name.
		 *
		 * @since 1.0.0
		 *
		 * @param string $name The template name.
		 * @param string $slug The template slug.
		 */
		$name = apply_filters( 'WPPluginStarter_template_part_name', $name, $slug );

		/**
		 * Filter template arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $args The template arguments.
		 * @param string $slug The template slug.
		 * @param string $name The template name.
		 */
		$args = apply_filters( 'WPPluginStarter_template_part_args', $args, $slug, $name );

		$template = '';

		// Look in theme/child theme
		if ( $name ) {
			$template = $this->locate_template( "{$slug}-{$name}.php" );
		}

		// If template not found, look for slug.php
		if ( ! $template ) {
			$template = $this->locate_template( "{$slug}.php" );
		}

		/**
		 * Filter the template path.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template The template path.
		 * @param string $slug     The template slug.
		 * @param string $name     The template name.
		 * @param array  $args     The template arguments.
		 */
		$template = apply_filters( 'WPPluginStarter_get_template_part', $template, $slug, $name, $args );

		/**
		 * Action fired before including a template part.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template The template path.
		 * @param string $slug     The template slug.
		 * @param string $name     The template name.
		 * @param array  $args     The template arguments.
		 */
		do_action( 'WPPluginStarter_before_template_part', $template, $slug, $name, $args );

		if ( $template ) {
			// Extract args to make them available in template
			if ( is_array( $args ) && ! empty( $args ) ) {
				extract( $args );
			}

			include $template;
		}

		/**
		 * Action fired after including a template part.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template The template path.
		 * @param string $slug     The template slug.
		 * @param string $name     The template name.
		 * @param array  $args     The template arguments.
		 */
		do_action( 'WPPluginStarter_after_template_part', $template, $slug, $name, $args );
	}

	/**
	 * Get template
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name
	 * @param array  $args          Template arguments
	 *
	 * @return void
	 */
	public function get_template( string $template_name, array $args = array() ): void {
		/**
		 * Filter template arguments.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $args          The template arguments.
		 * @param string $template_name The template name.
		 */
		$args = apply_filters( 'WPPluginStarter_template_args', $args, $template_name );

		$template = $this->locate_template( $template_name );

		if ( ! $template ) {
			/* translators: %s template */
			_doing_it_wrong(
				__FUNCTION__,
				sprintf( __( 'Template %s not found.', 'wp-plugin-starter' ), '<code>' . $template_name . '</code>' ),
				'1.0.0'
			);

			return;
		}

		/**
		 * Action fired before including a template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template      The template file.
		 * @param string $template_name The template name.
		 * @param array  $args          The template arguments.
		 */
		do_action( 'WPPluginStarter_before_template', $template, $template_name, $args );

		// Extract args to make them available in template
		if ( is_array( $args ) && ! empty( $args ) ) {
			extract( $args );
		}

		include $template;

		/**
		 * Action fired after including a template.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template      The template file.
		 * @param string $template_name The template name.
		 * @param array  $args          The template arguments.
		 */
		do_action( 'WPPluginStarter_after_template', $template, $template_name, $args );
	}

	/**
	 * Get template HTML
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name Template name
	 * @param array  $args          Template arguments
	 *
	 * @return string Template HTML
	 */
	public function get_template_html( string $template_name, array $args = array() ): string {
		/**
		 * Action fired before getting template HTML.
		 *
		 * @since 1.0.0
		 *
		 * @param string $template_name The template name.
		 * @param array  $args          The template arguments.
		 */
		do_action( 'WPPluginStarter_before_template_html', $template_name, $args );

		ob_start();
		$this->get_template( $template_name, $args );
		$html = ob_get_clean();

		/**
		 * Filter the template HTML.
		 *
		 * @since 1.0.0
		 *
		 * @param string $html          The template HTML.
		 * @param string $template_name The template name.
		 * @param array  $args          The template arguments.
		 */
		$html = apply_filters( 'WPPluginStarter_template_html', $html, $template_name, $args );

		/**
		 * Action fired after getting template HTML.
		 *
		 * @since 1.0.0
		 *
		 * @param string $html          The template HTML.
		 * @param string $template_name The template name.
		 * @param array  $args          The template arguments.
		 */
		do_action( 'WPPluginStarter_after_template_html', $html, $template_name, $args );

		return $html;
	}
}
