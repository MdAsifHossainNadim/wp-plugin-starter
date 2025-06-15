<?php

namespace WPPluginStarter\Admin\Dashboard\Components;

/**
 * Subsection Class.
 *
 * Represents a subsection component in the admin dashboard.
 *
 * @since 1.0.0
 * @package WPPluginStarter\Admin\Dashboard\Components
 */
class SubSection extends Settings_Element {

	/**
	 * SettingsModel Element type.
	 *
	 * @since 1.0.0
	 * @var string $type SettingsModel Element type.
	 */
	protected string $type = 'subsection';

	/**
	 * Badge text for the subsection.
	 *
	 * @since 1.0.0
	 * @var string|null $badge Badge text.
	 */
	protected ?string $badge = null;

	/**
	 * Badge type/style for the subsection.
	 *
	 * @since 1.0.0
	 * @var string $badge_type Badge type (default, primary, success, warning, danger, info).
	 */
	protected string $badge_type = 'default';

	/**
	 * Data validation.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for validation.
	 *
	 * @return bool
	 */
	public function data_validation( $data ): bool {
		/**
		 * Filter subsection data validation
		 *
		 * @since 1.0.0
		 * @param bool $valid Whether the data is valid
		 * @param mixed $data The data being validated
		 * @param SubSection $this SubSection instance
		 */
		return apply_filters( 'WPPluginStarter_subsection_data_validation', is_array( $data ), $data, $this );
	}

	/**
	 * Set badge for the subsection.
	 *
	 * @since 1.0.0
	 * @param string $badge Badge text.
	 * @param string $type Badge type (default, primary, success, warning, danger, info).
	 *
	 * @return self
	 */
	public function set_badge( string $badge, string $type = 'default' ): self {
		$this->badge      = $badge;
		$this->badge_type = $type;

		return $this;
	}

	/**
	 * Get badge text.
	 *
	 * @since 1.0.0
	 * @return string|null
	 */
	public function get_badge(): ?string {
		return $this->badge;
	}

	/**
	 * Get badge type.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_badge_type(): string {
		return $this->badge_type;
	}

	/**
	 * Sanitize data for storage.
	 *
	 * @since 1.0.0
	 * @param mixed $data Data for sanitization.
	 *
	 * @return mixed
	 */
	public function sanitize_element( $data ) {
		/**
		 * Filter subsection data before sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to sanitize
		 * @param SubSection $this SubSection instance
		 */
		$data = apply_filters( 'WPPluginStarter_subsection_before_sanitize', $data, $this );

		$sanitized = wp_unslash( $data );

		/**
		 * Filter subsection data after sanitization
		 *
		 * @since 1.0.0
		 * @param mixed $sanitized The sanitized data
		 * @param mixed $data Original data before sanitization
		 * @param SubSection $this SubSection instance
		 */
		return apply_filters( 'WPPluginStarter_subsection_after_sanitize', $sanitized, $data, $this );
	}

	/**
	 * Escape data for display.
	 *
	 * @since 1.0.0
	 * @param array $data Data for display.
	 *
	 * @return array
	 */
	public function escape_element( $data ): array {
		/**
		 * Filter subsection data before escaping
		 *
		 * @since 1.0.0
		 * @param mixed $data The data to escape
		 * @param SubSection $this SubSection instance
		 */
		$data = apply_filters( 'WPPluginStarter_subsection_before_escape', $data, $this );

		/**
		 * Filter subsection data after escaping
		 *
		 * @since 1.0.0
		 * @param array $data The escaped data
		 * @param SubSection $this SubSection instance
		 */
		return apply_filters( 'WPPluginStarter_subsection_after_escape', $data, $this );
	}

	/**
	 * Populate the subsection object.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function populate(): array {
		/**
		 * Action before subsection population
		 *
		 * @since 1.0.0
		 * @param SubSection $this SubSection instance
		 */
		do_action( 'WPPluginStarter_before_subsection_populate', $this );

		$data = parent::populate();

		// Add badge information if badge is set
		if ( ! is_null( $this->badge ) ) {
			$data['badge'] = array(
				'text' => $this->get_badge(),
				'type' => $this->get_badge_type(),
			);
		}

		/**
		 * Filter populated subsection data
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param SubSection $this SubSection instance
		 */
		$data = apply_filters( 'WPPluginStarter_subsection_populated_data', $data, $this );

		/**
		 * Action after subsection population
		 *
		 * @since 1.0.0
		 * @param array $data The populated data
		 * @param SubSection $this SubSection instance
		 */
		do_action( 'WPPluginStarter_after_subsection_populate', $data, $this );

		return $data;
	}
}
