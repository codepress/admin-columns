<?php

class AC_Helper_File {

	/**
	 * Convert file size to readable format
	 *
	 * @since 1.4.5
	 *
	 * @param string $size Size in bytes
	 * @param int    $decimals
	 *
	 * @return string|false Readable file size
	 */
	public function get_readable_filesize( $bytes, $decimals = 2, $empty_text = false ) {
		if ( ! $bytes ) {
			return $empty_text;
		}

		$filesize_units = array( 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' );

		$i = (int) floor( log( $bytes, 1024 ) );

		return round( $bytes / pow( 1024, $i ), $decimals ) . ' ' . $filesize_units[ $i ];
	}

}