<?php

namespace AC\Settings\Column\Media;

use AC\Column;
use AC\Settings\Column\MediaMeta;

class AudioMeta extends MediaMeta {

	public function __construct( Column $column ) {
		$types = [
			'created_timestamp' => __( 'Created Timestamp', 'codepress-admin-columns' ),
			'dataformat'        => __( 'Data Format', 'codepress-admin-columns' ),
			'channels'          => __( 'Channels', 'codepress-admin-columns' ),
			'sample_rate'       => __( 'Sample Rate', 'codepress-admin-columns' ),
			'bitrate'           => __( 'Bitrate', 'codepress-admin-columns' ),
			'channelmode'       => __( 'Channelmode', 'codepress-admin-columns' ),
			'bitrate_mode'      => __( 'Bitrate Mode', 'codepress-admin-columns' ),
			'lossless'          => __( 'Losless', 'codepress-admin-columns' ),
			'encoder_options'   => __( 'Encoder Options', 'codepress-admin-columns' ),
			'compression_ratio' => __( 'Compression Ratio', 'codepress-admin-columns' ),
			'fileformat'        => __( 'Fileformat', 'codepress-admin-columns' ),
			'filesize'          => __( 'Filesize', 'codepress-admin-columns' ),
			'mime_type'         => __( 'Mime Type', 'codepress-admin-columns' ),
			'length'            => __( 'Length', 'codepress-admin-columns' ),
			'length_formatted'  => __( 'Length Formatted', 'codepress-admin-columns' ),
		];

		natcasesort( $types );

		parent::__construct( $column, $types, 'dataformat' );
	}

}