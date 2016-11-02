<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Image extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-image' );
		$this->set_label( __( 'Image', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->format->images( $bookmark->link_image );
	}

	public function display_settings() {
		$this->field_settings->image();
	}

}