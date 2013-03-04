<?php
/**
 * CPAC_Column_Media_File_Size
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_File_Size extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-file_size';
		$this->properties['label']	 = __( 'File size', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$value = '';

		$file 	= wp_get_attachment_url( $id );
		$abs	= str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file );

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
	 * @return string Readable filesize
	 */
	function get_readable_filesize( $size ) {
		$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		return $size ? round( $size/pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2) . $filesizename[$i] : '0 Bytes';
    }
}