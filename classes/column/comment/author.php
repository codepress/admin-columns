<?php
/**
 * CPAC_Column_Comment_Author
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Author extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-author';
		$this->properties['label']	 = __( 'Author', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return $comment->comment_author;
	}
}