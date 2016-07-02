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

		$this->options['image_size'] = '';
		$this->options['image_size_w'] = 80;
		$this->options['image_size_h'] = 80;
	}

	function get_value( $id ) {
		$bookmark = get_bookmark( $id );

		return $this->get_image_formatted( $bookmark->link_image );
	}

	function display_settings() {
		$this->display_field_preview_size();
	}

}