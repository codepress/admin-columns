<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_ID extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-mediaid';
		$this->properties['label'] = __( 'ID', 'codepress-admin-columns' );
	}

	public function get_value( $media_id ) {
		return $media_id;
	}

}