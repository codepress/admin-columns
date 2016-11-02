<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_FileSize extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-file_size';
		$this->properties['label'] = __( 'File size', 'codepress-admin-columns' );
	}

	function get_value( $id ) {
		$value = '';

		$file = wp_get_attachment_url( $id );
		$abs = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file );

		if ( file_exists( $abs ) ) {
			$value = $this->get_readable_filesize( filesize( $abs ) );
		}

		return $value;
	}

	/**
	 * Convert file size to readable format
	 *
	 * @since 1.4.5
	 *
	 * @param string $size
	 *
	 * @return string Readable filesize
	 */
	function get_readable_filesize( $size ) {
		$filesizename = array( "Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" );

		return $size ? round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $filesizename[ $i ] : '0 Bytes';
	}

}