<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Author extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author';
		$this->properties['label'] = __( 'Author', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author;
	}
}