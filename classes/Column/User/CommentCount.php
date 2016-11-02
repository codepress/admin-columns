<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_CommentCount extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_commentcount';
		$this->properties['label'] = __( 'Comment Count' );
	}

	function get_value( $user_id ) {
		return $this->get_raw_value( $user_id );
	}

	function get_raw_value( $user_id ) {
		return get_comments( array(
			'user_id' => $user_id,
			'count'   => true,
			'orderby' => false,
		) );
	}

}