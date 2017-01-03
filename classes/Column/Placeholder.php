<?php

/**
 * ACF Placeholder column, holding a CTA for Admin Columns Pro.
 *
 * @since 2.2
 */
class AC_Column_Placeholder extends AC_Column
	implements AC_Column_PlaceholderInterface {

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @param AC_Addon $addon
	 */
	public function set_addon( AC_Addon $addon ) {
		$this->set_type( 'placeholder-' . $addon->get_slug() );
		$this->set_group( $addon->get_slug() );
		$this->set_label( $addon->get_title() );

		$this->url = $addon->get_link();
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}