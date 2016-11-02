<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_ID extends AC_Column_PostAbstract {

	public function __construct() {
		$this->set_type( 'column-postid' );
		$this->set_label( __( 'ID', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return $post_id;
	}

}