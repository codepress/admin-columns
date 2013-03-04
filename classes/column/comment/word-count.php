<?php
/**
 * CPAC_Column_Comment_Wordcount
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Word_Count extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-word_count';
		$this->properties['label']	 = __( 'Word count', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return str_word_count( $this->strip_trim( $comment->comment_content ) );
	}
}