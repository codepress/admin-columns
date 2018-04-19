<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 2.0
 */
class CommentCount extends Column {

	public function __construct() {
		$this->set_type( 'column-user_commentcount' );
		$this->set_label( __( 'Comment Count', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $user_id ) {
		return get_comments( array(
			'user_id' => $user_id,
			'count'   => true,
			'orderby' => false,
		) );
	}

}