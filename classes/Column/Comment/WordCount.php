<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_WordCount extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-word_count';
		$this->properties['label'] = __( 'Word count', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$comment = get_comment( $id );

		return str_word_count( ac_helper()->string->strip_trim( $comment->comment_content ) );
	}

}