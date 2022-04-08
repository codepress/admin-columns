<?php

namespace AC\Settings\Column\Media;

use AC\Column;
use AC\Settings\Column\MediaMeta;

class VideoMeta extends MediaMeta {

	public function __construct( Column $column ) {
		$video_types = [
			'created_timestamp' => __( 'Created Timestamp', 'codepress-admin-columns' ),
			'dataformat'        => __( 'Dataformat', 'codepress-admin-columns' ),
			'fileformat'        => __( 'Fileformat', 'codepress-admin-columns' ),
			'height'            => __( 'Height', 'codepress-admin-columns' ),
			'length'            => __( 'Length', 'codepress-admin-columns' ),
			'length_formatted'  => __( 'Length Formatted', 'codepress-admin-columns' ),
			'width'             => __( 'Width', 'codepress-admin-columns' ),
		];

		natcasesort( $video_types );

		$audio_types = [
			'audio/bits_per_sample' => __( 'Bits Per Sample', 'codepress-admin-columns' ),
			'audio/channelmode'     => __( 'Channelmode', 'codepress-admin-columns' ),
			'audio/channels'        => __( 'Channels', 'codepress-admin-columns' ),
			'audio/codec'           => __( 'Codec', 'codepress-admin-columns' ),
			'audio/dataformat'      => __( 'Dataformat', 'codepress-admin-columns' ),
			'audio/lossless'        => __( 'Losless', 'codepress-admin-columns' ),
			'audio/sample_rate'     => __( 'Sample Rate', 'codepress-admin-columns' ),
		];

		$audio_types = array_map( [ $this, 'wrap_audio_string' ], $audio_types );

		natcasesort( $audio_types );

		parent::__construct( $column, array_merge( $video_types, $audio_types ), 'dataformat' );
	}

	private function wrap_audio_string( $string ) {
		return sprintf( '%s (%s)', $string, __( 'audio', 'codepress-admin-columns' ) );
	}

}