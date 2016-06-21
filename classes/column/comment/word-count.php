<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_Wordcount
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Word_Count extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-word_count';
		$this->properties['label'] = __( 'Word count', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$comment = get_comment( $id );

		return str_word_count( $this->strip_trim( $comment->comment_content ) );
	}
}