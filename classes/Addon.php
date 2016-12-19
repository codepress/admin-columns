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
	private $image_url;

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
		return $this->link ? $this->link : ac_get_site_url( 'pricing-purchase' );
	}

	/**
	 * @param string $link
	 */
	protected function set_link( $link ) {
		$this->link = $link;

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
	public function get_image_url() {
		return $this->image_url;
	}

	/**
	 * @param string $image_url
	 */
	protected function set_image_url( $image_url ) {
		$this->image_url = $image_url;

		return $this;
	}

}