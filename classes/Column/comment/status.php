<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_Approved
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Status extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-status';
		$this->properties['label'] = __( 'Status', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_statuses() {
		return array(
			'trash'        => __( 'Trash' ),
			'post-trashed' => __( 'Trash' ),
			'spam'         => __( 'Spam' ),
			'1'            => __( 'Approved' ),
			'0'            => __( 'Pending' ),
		);
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		$statuses = $this->get_statuses();

		return isset( $statuses[ $comment->comment_approved ] ) ? $statuses[ $comment->comment_approved ] : '';
	}
}