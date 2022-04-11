<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since NEWVERSION
 */
class AudioPlayer extends Column {

	public function __construct() {
		$this->set_type( 'column-audio_player' )
		     ->set_group( 'media-audio' )
		     ->set_label( __( 'Audio Player', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$url = $this->get_raw_value( $id );

		if ( ! $url ) {
			return $this->get_empty_char();
		}

		return '<audio controls class="wp-audio-shortcode" preload="none" src="' . esc_url( $url ) . '">No support for audio player</audio>';
	}

	private function is_valid_mime_type( $id ) {
		$valid_mime_types = apply_filters( 'ac/column/player/valid_audio_mime_types', [ 'audio/mpeg', 'audio/flac', 'audio/wav' ], $this );

		return in_array( $this->get_mime_type( $id ), $valid_mime_types );
	}

	private function get_mime_type( $id ) {
		return get_post_field( 'post_mime_type', $id );
	}

	public function get_raw_value( $id ) {
		return $this->is_valid_mime_type( $id )
			? wp_get_attachment_url( $id )
			: false;
	}

}