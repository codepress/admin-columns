<?php

abstract class AC_Addon {

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $group;

	/**
	 * @var string
	 */
	private $logo;

	/**
	 * Plugin folder name
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * External website link
	 *
	 * @var string
	 */
	private $link;

	/**
	 * Is original plugin installed?
	 *
	 * @return bool
	 */
	abstract public function is_plugin_active();

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	protected function set_title( $title ) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Plugin folder name
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $slug Plugin folder name
	 */
	protected function set_slug( $slug ) {
		$this->slug = sanitize_key( $slug );

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_link() {
		if ( null === $this->link ) {
			$this->set_link( ac_get_site_utm_url( 'pricing-purchase', 'addon' ) );
		}

		return $this->link;
	}

	/**
	 * @param string $link
	 */
	protected function set_link( $url ) {
		if ( ac_helper()->string->is_valid_url( $url ) ) {
			$this->link = $url;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	protected function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_group() {
		return $this->group ? $this->group : 'integration';
	}

	/**
	 * @param string $group
	 */
	protected function set_group( $group ) {
		$this->group = $group;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_logo() {
		return $this->logo;
	}

	/**
	 * @param string $logo
	 */
	protected function set_logo( $logo ) {
		$this->logo = $logo;

		return $this;
	}

	/**
	 * @return AC_Column_Placeholder
	 */
	public function get_placeholder_column() {
		$column = new AC_Column_Placeholder();
		$column->set_addon( $this );

		return $column;
	}

	/**
	 * @param string $title
	 *
	 * @return string
	 */
	protected function get_fields_description( $title ) {
		return sprintf( __( 'Display and edit %s fields in the posts overview in seconds!', 'codepress-admin-columns' ), $title );
	}

	/**
	 * Is integration addon installed AND active
	 *
	 * @return bool
	 */
	public function is_addon_active() {
		return is_plugin_active( $this->get_addon_basename() );
	}

	/**
	 * Is integration addon installed, but NOT active.
	 *
	 * @return bool
	 */
	public function is_addon_installed() {
		return $this->get_addon_basename() ? true : false;
	}

	/**
	 * Plugin header information. Version,
	 *
	 * @return array|false
	 */
	public function get_addon_plugin() {
		$basename = $this->get_addon_basename();

		if ( ! $basename ) {
			return false;
		}

		$plugins = get_plugins();

		return $plugins[ $basename ];
	}

	/**
	 * Get the plugin basename (see plugin_basename()) from a plugin, for example "my-plugin/my-plugin.php"
	 *
	 * @return string|false Returns the plugin basename if the plugin is installed, false otherwise
	 */
	public function get_addon_basename() {
		$plugins = (array) get_plugins();

		foreach ( $plugins as $basename => $plugin ) {
			if ( $this->get_slug() === dirname( $basename ) ) {
				return $basename;
			}
		}

		return false;
	}

	/**
	 * @return string|false Returns the plugin version if the plugin is installed, false otherwise
	 */
	public function get_addon_version() {
		$plugin = $this->get_addon_plugin();

		if ( ! isset( $plugin['Version'] ) ) {
			return false;
		}

		return $plugin['Version'];
	}

}