<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Attachment extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-attachment' );
		$this->set_label( __( 'Attachments', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$attachment_ids = (array) $this->get_raw_value( $id );

		switch ( $this->get_setting( 'attachment_display' )->get_value() ) {
			case 'thumbnail':
				$collection = new AC_Collection( $attachment_ids );
				$removed = $collection->limit( $this->get_setting( 'number_of_items' )->get_value() );

				$value = ac_helper()->html->images( $this->get_formatted_value( $collection->all() ), $removed );
				break;
			default:
				$value = count( $attachment_ids );
		}

		if ( ! $value ) {
			$value = $this->get_empty_char();
		}

		return $value;
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
		$this->add_setting( new AC_Settings_Column_AttachmentDisplay( $this ) );
	}

}