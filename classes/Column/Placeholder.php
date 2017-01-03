<?php

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_Placeholder extends AC_Column
	implements AC_Column_PlaceholderInterface {

	/**
	 * @var AC_Addon
	 */
	private $addon;

	/**
	 * @param AC_Addon $addon
	 */
	public function set_addon( AC_Addon $addon ) {
		$this->addon = $addon;
	}

	public function get_group() {
		return $this->addon->get_slug();
	}

	public function get_label() {
		return $this->addon->get_title();
	}

	public function get_type() {
		return 'placeholder-' . $this->addon->get_slug();
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->addon->get_link();
	}

}