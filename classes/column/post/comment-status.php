<?php

/**
 * Column displaying whether an item is open for comments, i.e. whether users can
 * comment on this item.
 *
 * @since 2.0
 */
class CPAC_Column_Post_Comment_Status extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		// Properties
		$this->properties['type'] = 'column-comment_status';
		$this->properties['label'] = __( 'Comment status', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'comment_status';
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.2
	 */
	function apply_conditional() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {
		$comment_status = $this->get_raw_value( $post_id );
		$value = '<span class="dashicons dashicons-no cpac_status_no" title="' . $comment_status . '"></span>';
		if ( 'open' == $comment_status ) {
			$value = '<span class="dashicons dashicons-yes cpac_status_yes" title="' . $comment_status . '"></span>';
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {
		return get_post_field( 'comment_status', $post_id, 'raw' );
	}
}