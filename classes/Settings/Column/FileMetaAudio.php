<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Settings\FormatValue;

class FileMetaAudio extends FileMeta implements FormatValue {

	public function __construct( Column $column ) {
		$types = [
			'bitrate'           => __( 'Bitrate', 'codepress-admin-columns' ),
			'bitrate_mode'      => __( 'Bitrate Mode', 'codepress-admin-columns' ),
			'channelmode'       => __( 'Channelmode', 'codepress-admin-columns' ),
			'channels'          => __( 'Channels', 'codepress-admin-columns' ),
			'compression_ratio' => __( 'Compression Ratio', 'codepress-admin-columns' ),
			'created_timestamp' => __( 'Created Timestamp', 'codepress-admin-columns' ),
			'dataformat'        => __( 'Data Format', 'codepress-admin-columns' ),
			'encoder_options'   => __( 'Encoder Options', 'codepress-admin-columns' ),
			'fileformat'        => __( 'Fileformat', 'codepress-admin-columns' ),
			'filesize'          => __( 'Filesize', 'codepress-admin-columns' ),
			'length'            => __( 'Length', 'codepress-admin-columns' ),
			'length_formatted'  => __( 'Length Formatted', 'codepress-admin-columns' ),
			'lossless'          => __( 'Losless', 'codepress-admin-columns' ),
			'mime_type'         => __( 'Mime Type', 'codepress-admin-columns' ),
			'sample_rate'       => __( 'Sample Rate', 'codepress-admin-columns' ),
		];

		natcasesort( $types );

		parent::__construct( $column, $types, 'dataformat' );
	}

	public function format( $value, $original_value ) {
		switch ( $this->get_media_meta_key() ) {
			case 'bitrate':
				if ( $value > 1000 ) {
					$value = sprintf( '%s %s', number_format( $value / 1000 ), __( 'Kbps', 'codepress-admin-columns' ) );
				}

				return $value;
			case 'channels':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), _n( 'channels', 'channels', $value, 'codepress-admin-columns' ) );
				}

				return $value;
			case 'compression_ratio':
				if ( $value > 0 ) {
					$value = number_format( $value, 4 );
				}

				return $value;
			case 'created_timestamp':
				return $value
					? ac_helper()->date->format_date( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ), $value )
					: '';
			case 'filesize':
				return ac_helper()->file->get_readable_filesize( $value );
			case 'length':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), __( 'sec', 'codepress-admin-columns' ) );
				}

				return $value;
			case 'sample_rate':
				if ( $value > 0 ) {
					$value = sprintf( '%s %s', number_format( $value ), __( 'Hz', 'codepress-admin-columns' ) );
				}

				return $value;
			default:
				return $value;
		}
	}

}