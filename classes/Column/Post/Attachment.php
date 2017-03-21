<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Attachment extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-attachment' );
		$this->set_label( __( 'Attachments', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return $this->get_attachment_ids( $post_id );
	}

	/**
	 * @param $post_id
	 *
	 * @return int[] Attachment ID's
	 */
	private function get_attachment_ids( $post_id ) {
		$attachment_ids = get_posts( array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'post_status'    => null,
			'post_parent'    => $post_id,
			'fields'         => 'ids',
		) );

		if ( ! $attachment_ids ) {
			return array();
		}

		return $attachment_ids;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Image( $this ) );
	}

}