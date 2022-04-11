<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings\Column\VideoDisplay;

/**
 * @since NEWVERSION
 */
class VideoPlayer extends Column implements Column\DetailedValue {

	public function __construct() {
		$this->set_type( 'column-video_player' )
		     ->set_group( 'media-video' )
		     ->set_label( __( 'Video Player', 'codepress-admin-columns' ) );
	}

	protected function register_settings() {
		parent::register_settings();

		$this->add_setting( new VideoDisplay( $this ) );
	}

	private function get_display_type() {
		return $this->get_setting( 'video_display' )->get_value();
	}

	public function get_value( $id ) {
		$url = $this->get_raw_value( $id );

		if ( ! $url ) {
			return $this->get_empty_char();
		}

		return 'modal' === $this->get_display_type()
			? '<a data-modal-value href="#" data-modal-title="' . get_the_title( $id ) . '">' . __( 'View video', 'codepress-admin-columns' ) . '</a>'
			: $this->get_video_embed( $url, 300 );
	}

	private function is_valid_mime_type( $id ) {
		$valid_mime_types = apply_filters( 'ac/column/video_player/valid_mime_types', [ 'video/mp4', 'video/quicktime' ], $this );

		return in_array( $this->get_mime_type( $id ), $valid_mime_types );
	}

	private function get_mime_type( $id ) {
		return get_post_field( 'post_mime_type', $id );
	}

	private function get_video_embed( $url, $width, $attributes = [] ) {
		$attribute_markup = '';

		foreach ( $attributes as $key => $value ) {
			$attribute_markup .= ' ' . $key . '=' . esc_attr__( $value );
		}

		return '<video controls ' . $attribute_markup . ' width="' . $width . '" src="' . esc_url( $url ) . '"></video>';
	}

	public function get_raw_value( $id ) {
		return $this->is_valid_mime_type( $id )
			? wp_get_attachment_url( $id )
			: false;
	}

	public function get_detailed_value( $id ) {
		$url = $this->get_raw_value( $id );

		return $url
			? $this->get_video_embed( $url, 600, [ 'autoplay' => 'true' ] )
			: $this->get_empty_char();
	}

}