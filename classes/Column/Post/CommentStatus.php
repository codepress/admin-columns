<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying whether an item is open for comments, i.e. whether users can
 * comment on this item.
 *
 * @since 2.0
 */
class AC_Column_Post_CommentStatus extends CPAC_Column {

	public function init() {
		parent::init();

		// Properties
		$this->properties['type'] = 'column-comment_status';
		$this->properties['label'] = __( 'Comment status', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'comment_status';
	}

	function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	function get_value( $post_id ) {
		$comment_status = $this->get_raw_value( $post_id );

		return $this->get_icon_yes_or_no( ( 'open' == $comment_status ), $comment_status );
	}

	function get_raw_value( $post_id ) {
		return get_post_field( 'comment_status', $post_id, 'raw' );
	}

}