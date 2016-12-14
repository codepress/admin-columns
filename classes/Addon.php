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

	abstract public function is_addon_active();
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
	public function set_title( $title ) {
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
	public function set_slug( $slug ) {
		$this->slug = $slug;

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
	public function set_description( $description ) {
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
	public function set_group( $group ) {
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
	public function set_image_url( $image_url ) {
		$this->image_url = $image_url;

		return $this;
	}

	/**
	 * Show notice
	 *
	 * @return bool
	 */
	/*public function show_notice() {
		return $this->is_plugin_active() && ! $this->is_addon_active();
	}*/

}