<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_ID
 *
 * @since 2.0
 */
class CPAC_Column_Comment_ID extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-comment_id';
		$this->properties['label'] = __( 'ID', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $id;
	}
}