<?php
/**
 * CPAC_Column_Comment_Author_Avatar
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Author_Avatar extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-author_avatar';
		$this->properties['label']	 = __( 'Avatar', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return get_avatar( $comment, 80 );
	}
}