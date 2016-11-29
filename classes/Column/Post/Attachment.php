<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Attachment extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-attachment' );
		$this->set_label( __( 'Attachments', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_setting( 'image' )->format( $this->get_attachment_ids( $post_id ) );

		// TODO make images be wrapped in cacie-item to make them editable

		/*foreach ( $ids as $id ) {
			if ( $image = $this->format->images( $id ) ) {
				$values[] = '<div class="cacie-item" data-cacie-id="' . esc_attr( $id ) . '">' . $image . '</div>';
			}
		}

		return implode( $values );*/
	}

	public function get_raw_value( $post_id ) {
		return $this->get_attachment_ids( $post_id );
	}

	private function get_attachment_ids( $post_id ) {
		$attachments = get_posts( array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'post_status'    => null,
			'post_parent'    => $post_id,
			'fields'         => 'ids',
		) );

		return $attachments ? $attachments : array();
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Image( $this ) );
	}

}