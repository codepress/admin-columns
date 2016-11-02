<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Image extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-image';
		$this->properties['label'] = __( 'Image', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->format->images( $bookmark->link_image );
	}

	function display_settings() {
		$this->field_settings->image();
	}

}