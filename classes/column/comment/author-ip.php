<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_Author_Ip
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Author_Ip extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_ip';
		$this->properties['label'] = __( 'Author IP', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_author_IP;
	}
}