<?php

namespace AC\Settings\Column\Media;

use AC\Column;
use AC\Settings\Column\MediaMeta;
use AC\Settings\FormatValue;

class VideoMeta extends MediaMeta implements FormatValue {

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

	public function format( $value, $original_value ) {
		switch ( $this->get_media_meta_key() ) {
			case 'height':
			case 'width':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), __( 'px', 'codepress-admin-columns' ) );
				}

				return $value;
			case 'length':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), __( 'sec', 'codepress-admin-columns' ) );
				}

				return $value;
			case 'audio/channels':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), _n( 'channels', 'channels', $value, 'codepress-admin-columns' ) );
				}

				return $value;
			case 'audio/sample_rate':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), __( 'Hz', 'codepress-admin-columns' ) );
				}

				return $value;
			case 'created_timestamp':
				return $value
					? ac_helper()->date->format_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), $value )
					: '';
			default:
				return $value;
		}
	}

}