<?php
declare( strict_types=1 );

namespace AC\Column\Media;

use AC\Column;

// TODO add support for video/pdf/audio
class Preview extends Column implements Column\AjaxValue {

	public function __construct() {
		$this->set_type( 'column-preview' )
		     ->set_label( __( 'Preview', 'codepress-admin-columns' ) );
	}

	private function has_image( $id ) {
		return null !== $this->get_image_url( $id );
	}

	private function get_download_url( $id ) {
		return wp_get_attachment_url( $id );
	}

	private function get_image_url( $id ) {
		$image = wp_get_attachment_image_src( $id, false );

		if ( empty( $image ) ) {
			return null;
		}

		return (string) $image[0];
	}

	public function get_value( $id ) {
		if ( ! $this->has_image( $id ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->html->get_ajax_modal_link(
			__( 'View', 'codepress-admin-columns' ),
			[
				'title'         => get_the_title( $id ),
				'edit_link'     => get_edit_post_link( $id ),
				'download_link' => $this->get_download_url( $id ) ?: null,
				'id'            => $id,
				'class'         => "-nopadding -preview",
			]
		);
	}

	public function get_ajax_value( $id ) {
		return sprintf( '<img src="%s" alt="">', esc_url( $this->get_image_url( $id ) ) );
	}

}