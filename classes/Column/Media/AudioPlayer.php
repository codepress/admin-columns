<?php

namespace AC\Column\Media;

use AC\Column;

class AudioPlayer extends Column {

	public function __construct() {
		$this->set_type( 'column-audio_player' )
		     ->set_group( 'media-audio' )
		     ->set_label( __( 'Player', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$url = $this->get_raw_value( $id );

		if ( ! $url ) {
			return $this->get_empty_char();
		}

		return sprintf( '<audio controls preload="none" src="%s">%s</audio>', esc_url( $url ), __( 'No support for audio player', 'codepress-admin-columns' ) );
	}

	private function get_valid_mime_types() {
		return (array) apply_filters( 'ac/column/audio_player/valid_mime_types', [ 'audio/mpeg', 'audio/flac', 'audio/wav' ], $this );
	}

	private function is_valid_mime_type( $id ) {
		return in_array( $this->get_mime_type( $id ), $this->get_valid_mime_types() );
	}

	private function get_mime_type( $id ) {
		return (string) get_post_field( 'post_mime_type', $id );
	}

	public function get_raw_value( $id ) {
		return $this->is_valid_mime_type( $id )
			? wp_get_attachment_url( $id )
			: false;
	}

}