<?php

/**
 * @since 2.0
 */
class AC_Column_Media_FileSize extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-file_size' );
		$this->set_label( __( 'File Size', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = '';

		$file = wp_get_attachment_url( $id );
		$abs = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file );

		if ( file_exists( $abs ) ) {
			$value = ac_helper()->file->get_readable_filesize( filesize( $abs ) );
		}

		return $value;
	}

}