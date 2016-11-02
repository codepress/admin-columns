<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_AuthorAvatar extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-author_avatar' );
		$this->set_label( __( 'Avatar', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$comment = get_comment( $id );

		return get_avatar( $comment, 80 );
	}

}