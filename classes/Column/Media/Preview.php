<?php

namespace AC\Column\Media;

use AC\ApplyFilter\ValidAudioMimetypes;
use AC\ApplyFilter\ValidVideoMimetypes;
use AC\Column;
use AC\View\Embed\Video;

class Preview extends Column implements Column\AjaxValue {

	public function __construct() {
		$this->set_type( 'column-preview' )
		     ->set_label( __( 'Preview', 'codepress-admin-columns' ) );
	}

	private function get_mime_type( $id ) {
		return get_post_field( 'post_mime_type', $id );
	}

	public function get_raw_value( $id ) {
		return wp_get_attachment_url( $id );
	}

	private function get_media_type( $id ): ?string {
		$mime_type = $this->get_mime_type( $id );

		switch ( true ) {
			case in_array( $mime_type, ( new ValidVideoMimetypes( $this ) )->apply_filters(), true ):
				return 'video';

			case in_array( $mime_type, ( new ValidAudioMimetypes( $this ) )->apply_filters(), true ):
				return 'audio';

			case file_is_valid_image( $this->get_raw_value( $id ) ):
				return 'image';

			default:
				return null;
		}
	}

	public function get_value( $id ) {
		if ( ! $this->get_media_type( $id ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->html->get_ajax_modal_link(
			__( 'View', 'codepress-admin-columns' ),
			[
				'title'         => get_the_title( $id ),
				'edit_link'     => get_edit_post_link( $id ),
				'download_link' => $this->get_raw_value( $id ) ?: null,
				'id'            => $id,
				'class'         => "-nopadding -preview",
			]
		);
	}

	public function get_ajax_value( $id ) {
		switch ( $this->get_media_type( $id ) ) {
			case 'audio':
				return sprintf( '<audio controls autoplay="autoplay" preload="none" src="%s">%s</audio>', esc_url( $this->get_raw_value( $id ) ), __( 'No support for audio player', 'codepress-admin-columns' ) );
			case 'video':
				return ( new Video( [] ) )
					->set_src( $this->get_raw_value( $id ) )
					->render();

			case 'image':
				return sprintf( '<img src="%s" alt="">', esc_url( $this->get_raw_value( $id ) ) );
		}

		return __( 'Preview not available', 'codepress-admin-columns' );
	}

}