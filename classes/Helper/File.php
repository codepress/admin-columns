<?php

namespace AC\Helper;

class File {

	/**
	 * Convert file size to readable format
	 *
	 * @param      $bytes
	 * @param int  $decimals
	 * @param bool $empty_text
	 *
	 * @return string|false Readable file size
	 * @since 1.4.5
	 */
	public function get_readable_filesize( $bytes, $decimals = 2, $empty_text = false ) {

		$filesize = $this->get_readable_filesize_as_array( $bytes, $decimals );

		if ( ! $filesize ) {
			return $empty_text;
		}

		return implode( ' ', $filesize );
	}

	/**
	 * @param string $bytes
	 * @param int    $decimals
	 *
	 * @return array [ string $size, string $unit ]
	 */
	public function get_readable_filesize_as_array( $bytes, $decimals = 2 ) {
		if ( ! $bytes ) {
			return [];
		}

		$filesize_units = [ 'Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];

		$i = (int) floor( log( $bytes, 1024 ) );

		return [
			round( $bytes / pow( 1024, $i ), $decimals ),
			$filesize_units[ $i ],
		];
	}

}