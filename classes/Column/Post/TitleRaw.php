<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.2.4
 */
class AC_Column_Post_TitleRaw extends AC_Column_PostAbstract {

	public function __construct() {
		$this->set_type( 'column-title_raw' );
		$this->set_label( __( 'Title without actions', 'codepress-admin-columns' ) );
	}

	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'post_title', $post_id );
	}

}