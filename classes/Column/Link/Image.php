<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_Image extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-image';
		$this->properties['label'] = __( 'Image', 'codepress-admin-columns' );

		$this->default_options['image_size'] = '';
		$this->default_options['image_size_w'] = 80;
		$this->default_options['image_size_h'] = 80;
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->format->images( $bookmark->link_image );
	}

	function display_settings() {
		$this->field_settings->image();
	}

}