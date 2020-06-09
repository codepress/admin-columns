<?php

namespace AC\Column\User;

use AC\Column;

/**
 * @since 2.0
 */
class CommentCount extends Column {

	public function __construct() {
		$this->set_type( 'column-user_commentcount' )
		     ->set_label( __( 'Comments', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $user_id ) {
		return get_comments( [
			'user_id' => $user_id,
			'count'   => true,
			'orderby' => false,
		] );
	}

	public function get_value( $user_id ) {
		$count = $this->get_raw_value( $user_id );

		if ( ! $count ) {
			return $this->get_empty_char();
		}

		return sprintf( '<a href="%s">%s</a>', add_query_arg( [ 'user_id' => $user_id ], admin_url( 'edit-comments.php' ) ), $count );
	}

}