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
	public function __construct( AC_Addon $addon ) {
		$this
			->set_label( $addon->get_title() )
			->set_group( $addon->get_slug() )
			->set_type( 'placeholder-' . $addon->get_slug() );
	}

	/**
	 * @param string $url
	 *
	 * @return $this
	 */
	public function set_url( $url ) {
		if ( ac_helper()->string->is_valid_url( $url ) ) {
			$this->url = $url;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}