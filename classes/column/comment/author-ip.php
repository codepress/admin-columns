<?php
/**
 * CPAC_Column_Comment_Author_Ip
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Author_Ip extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-author_ip';
		$this->properties['label']	 = __( 'Author IP', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return $comment->comment_author_IP;
	}
}