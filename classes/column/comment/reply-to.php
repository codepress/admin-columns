<?php
/**
 * CPAC_Column_Comment_Reply_To
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Reply_To extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-reply_to';
		$this->properties['label']	 = __( 'In Reply To', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {
		$value = '';
		$parent = $this->get_raw_value( $id );
		if ( $parent ) {
			$parent = get_comment( $parent );
			$value 	= sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_comment_link( $parent ) ), get_comment_author( $parent->comment_ID ) );
		}

		return $value;
	}

	/**
	 * @since 2.4.2
	 */
	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->comment_parent;
	}
}