<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings\Column\VideoDisplay;

class VideoPlayer extends Column implements Column\AjaxValue {

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

	private function create_relative_path( $url ) {
		return str_replace( site_url(), '', $url );
	}

	public function get_value( $id ) {
		$url = $this->get_raw_value( $id );

		if ( ! $url ) {
			return $this->get_empty_char();
		}

		if ( 'modal' === $this->get_display_type() ) {
			$url = $this->get_raw_value( $id );

			return ac_helper()->html->get_ajax_modal_link(
				__( 'Play', 'codepress-admin-columns' ),
				[
					'title'         => get_the_title( $id ),
					'edit_link'     => get_edit_post_link( $id ),
					'download_link' => $this->create_relative_path( $url ) ?: null,
					'id'            => $id,
					'class'         => "-nopadding",
				]
			);
		}

		return $this->get_video_embed( $url );
	}

	private function is_valid_mime_type( $id ) {
		$valid_mime_types = apply_filters( 'ac/column/video_player/valid_mime_types', [ 'video/mp4', 'video/webm', 'video/quicktime' ], $this );

		return in_array( $this->get_mime_type( $id ), $valid_mime_types );
	}

	private function get_mime_type( $id ) {
		return get_post_field( 'post_mime_type', $id );
	}

	private function get_video_embed( $url, array $attributes = [] ) {
		$attribute_markup = [];

		foreach ( $attributes as $key => $value ) {
			$attribute_markup[] = sprintf( '%s="%s"', $key, esc_attr__( $value ) );
		}

		return sprintf( '<video controls %s src="%s" preload="metadata"></video>', implode( ' ', $attribute_markup ), esc_url( $url ) );
	}

	public function get_raw_value( $id ) {
		return $this->is_valid_mime_type( $id )
			? wp_get_attachment_url( $id )
			: false;
	}

	public function get_ajax_value( $id ) {
		$url = $this->get_raw_value( $id );

		return $url
			? $this->get_video_embed( $url, [ 'width' => 600, 'autoplay' => 'true' ] )
			: null;
	}

}