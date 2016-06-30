<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_Approved
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Approved extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-approved';
		$this->properties['label'] = __( 'Approved', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_icon_yes_or_no( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_approved;
	}
}