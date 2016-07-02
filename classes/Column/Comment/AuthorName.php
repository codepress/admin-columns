<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.2
 */
class AC_Column_Comment_AuthorName extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_name';
		$this->properties['label'] = __( 'Author name', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author;
	}
}