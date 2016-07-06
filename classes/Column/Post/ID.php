<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_ID extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 	= 'column-postid';
		$this->properties['label']	 	= __( 'ID', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return $post_id;
	}

}