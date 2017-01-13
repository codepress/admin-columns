<?php

/**
 * @since 2.0
 */
class AC_Column_Media_FileSize extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-file_size' );
		$this->set_label( __( 'File size', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
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
	 * @return string Readable file size
	 */
	public function get_readable_filesize( $size ) {
		$filesize_units = array( 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );

		$i = (int) floor( log( $size, 1024 ) );

		return $size ? round( $size / pow( 1024, $i ), 2 ) . ' ' . $filesize_units[ $i ] : '0 Bytes';
	}

}