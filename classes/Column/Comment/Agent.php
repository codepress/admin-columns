<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Agent extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-agent';
		$this->properties['label'] = __( 'Agent', 'codepress-admin-columns' );
	}

	// Display
	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_agent;
	}

}