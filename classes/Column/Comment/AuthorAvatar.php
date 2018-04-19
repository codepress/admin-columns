<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.0
 */
class AuthorAvatar extends Column {

	public function __construct() {
		$this->set_type( 'column-author_avatar' );
		$this->set_label( __( 'Avatar', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$comment = get_comment( $id );

		return get_avatar( $comment, 60 );
	}

}