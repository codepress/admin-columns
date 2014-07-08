<?php
/**
 * CPAC_Column_Comment_Wordcount
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Word_Count extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-word_count';
		$this->properties['label']	 = __( 'Word count', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return str_word_count( $this->strip_trim( $comment->comment_content ) );
	}
}