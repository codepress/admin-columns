<?php
/**
 * CPAC_Column_Comment_Author_Avatar
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Author_Avatar extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-author_avatar';
		$this->properties['label']	 = __( 'Avatar', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return get_avatar( $comment, 80 );
	}
}