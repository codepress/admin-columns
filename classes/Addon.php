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
	 * Is integration addon installed?
	 *
	 * @return bool
	 */
	abstract public function is_addon_active();

	/**
	 * Is original plugin installed?
	 *
	 * @return bool
	 */
	abstract public function is_plugin_active();

	/**
	 * @return AC_Column_Placeholder
	 */
	abstract public function get_placeholder_column();

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
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @param string $slug
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

}