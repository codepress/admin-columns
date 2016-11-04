<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying number of attachment for an item.
 *
 * @since 2.0
 */
class AC_Column_Post_AttachmentCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-attachment_count' );
		$this->set_label( __( 'Attachment Count', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$count = $this->get_raw_value( $post_id );

		return $count ? $count : $this->get_empty_char();
	}

	public function get_raw_value( $post_id ) {
		$attachment_ids = get_posts( array(
			'post_type'   => 'attachment',
			'numberposts' => - 1,
			'post_status' => null,
			'post_parent' => $post_id,
			'fields'      => 'ids'
		) );

		return count( $attachment_ids );
	}

}