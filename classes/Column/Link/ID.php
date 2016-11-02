<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Link_ID extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 	= 'column-link_id';
		$this->properties['label']	 	= __( 'ID', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		return $id;
	}

}