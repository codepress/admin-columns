<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class Attachment extends Column {

	public function __construct() {
		$this->set_type( 'column-attachment' );
		$this->set_label( __( 'Attachments', 'codepress-admin-columns' ) );
	}

	public function get_raw_value( $post_id ) {
		return $this->get_attachment_ids( (int) $post_id );
	}

	public function get_attachment_ids( int $post_id ): array {
		$attachment_ids = get_posts( [
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'post_status'    => null,
			'post_parent'    => $post_id,
			'fields'         => 'ids',
		] );

		if ( ! $attachment_ids ) {
			return [];
		}

		return $attachment_ids;
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\AttachmentDisplay( $this ) );
	}

}