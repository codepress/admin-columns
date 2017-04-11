<?php

/**
 * @since 2.0
 */
class AC_Column_Link_Image extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-image' );
		$this->set_label( __( 'Image', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $bookmark->link_image;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Image( $this ) );
	}

}