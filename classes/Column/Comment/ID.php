<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_ID extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-comment_id';
		$this->properties['label'] = __( 'ID', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $id;
	}
}