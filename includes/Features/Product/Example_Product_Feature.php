<?php

namespace WPPluginStarter\Features\Product;

use WPPluginStarter\Features\Abstract_Feature;

/**
 * Code Editor Feature
 *
 * Provides code editing functionality for product fields
 * that require custom code input (CSS, JS, HTML, etc.)
 *
 * @since   1.0.0
 * @package WPPluginStarter\Features\Product
 */
class Example_Product_Feature extends Abstract_Feature {

	/**
	 * Initialize feature properties
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function init(): void {
		$this->name           = __( 'Code Editor', 'wp-plugin-starter' );
		$this->description    = __( 'Provides code editing functionality for custom fields that require code input.', 'wp-plugin-starter' );
		$this->settings_group = 'product';
	}

	/**
	 * Register hooks with WordPress
	 *
	 * @return void
	 */
	public function register_hooks(): void {
		if ( ! $this->is_enabled() ) {
			return;
		}

		// Register scripts and styles for code editor
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );

		// Add filter to enhance code editor fields
		add_filter( 'wp_plugin_starter_field_code_attributes', array( $this, 'enhance_code_editor' ), 10, 2 );
	}

	/**
	 * Register assets for code editor
	 *
	 * @return void
	 */
	public function register_assets(): void {
		if ( ! $this->is_dokan_admin_page() ) {
			return;
		}

		// Register WordPress code editor assets
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// Add inline script to initialize code editors
		wp_add_inline_script( 'code-editor', $this->get_initialization_script() );
	}

	/**
	 * Get initialization script for code editors
	 *
	 * @return string
	 */
	protected function get_initialization_script(): string {
		return "
			jQuery(document).ready(function($) {
				// Initialize code editors when they appear in the DOM
				function initializeCodeEditors() {
					$('.wp-plugin-starter-code-textarea').each(function() {
						if (!$(this).data('code-editor-initialized')) {
							var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
							editorSettings.codemirror = _.extend(
								{},
								editorSettings.codemirror,
								{
									indentUnit: 2,
									tabSize: 2,
									mode: $(this).data('mode') || 'htmlmixed'
								}
							);
							var editor = wp.codeEditor.initialize(this, editorSettings);
							$(this).data('code-editor-initialized', true);
						}
					});
				}

				// Initialize on page load
				initializeCodeEditors();

				// Initialize when new fields might be added dynamically
				$(document).on('wp_plugin_starter_fields_updated', initializeCodeEditors);
			});
		";
	}

	/**
	 * Enhance code editor field attributes
	 *
	 * Modifies the attributes for textarea fields that should use the code editor
	 *
	 * @param array  $attributes The existing field attributes
	 * @param string $field_type The type of field
	 *
	 * @return array Modified attributes
	 */
	public function enhance_code_editor( array $attributes, string $field_type ): array {
		// Add class and data attributes to enable code editor
		$attributes['class'] .= ' wp-plugin-starter-code-textarea';

		// Set the appropriate mode based on field type
		switch ( $field_type ) {
			case 'css':
				$attributes['data-mode'] = 'css';
				break;
			case 'javascript':
				$attributes['data-mode'] = 'javascript';
				break;
			case 'html':
				$attributes['data-mode'] = 'htmlmixed';
				break;
			case 'php':
				$attributes['data-mode'] = 'php';
				break;
			default:
				$attributes['data-mode'] = 'htmlmixed';
		}

		return $attributes;
	}

	/**
	 * Check if current page is a Dokan admin page
	 *
	 * @return bool
	 */
	private function is_dokan_admin_page(): bool {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return false;
		}

		return strpos( $screen->id, 'dokan' ) !== false ||
			   strpos( $screen->id, 'product' ) !== false;
	}

	/**
	 * Get settings for this feature
	 *
	 * @return array
	 */
	public function get_settings(): array {
		return [
			[
				'title' => __( 'Code Editor SettingsModel', 'wp-plugin-starter' ),
				'type'  => 'title',
				'desc'  => __( 'Configure the code editor behavior for product custom fields', 'wp-plugin-starter' ),
				'id'    => 'code_editor_settings',
			],
			[
				'title'   => __( 'Editor Theme', 'wp-plugin-starter' ),
				'desc'    => __( 'Select the code editor color theme', 'wp-plugin-starter' ),
				'id'      => 'code_editor_theme',
				'default' => 'default',
				'type'    => 'select',
				'options' => [
					'default' => __( 'Default', 'wp-plugin-starter' ),
					'dark'    => __( 'Dark', 'wp-plugin-starter' ),
					'light'   => __( 'Light', 'wp-plugin-starter' ),
				],
			],
			[
				'title'   => __( 'Line Numbers', 'wp-plugin-starter' ),
				'desc'    => __( 'Show line numbers in the code editor', 'wp-plugin-starter' ),
				'id'      => 'code_editor_line_numbers',
				'default' => 'yes',
				'type'    => 'checkbox',
			],
			[
				'type' => 'sectionend',
				'id'   => 'code_editor_settings',
			],
		];
	}
}
