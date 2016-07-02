<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_ReplyTo extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-reply_to';
		$this->properties['label'] = __( 'In Reply To', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$value = '';
		$parent = $this->get_raw_value( $id );
		if ( $parent ) {
			$parent = get_comment( $parent );
			$value = sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_comment_link( $parent ) ), get_comment_author( $parent->comment_ID ) );
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_parent;
	}
}