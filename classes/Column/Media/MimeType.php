<?php

/**
 * @since 2.0
 */
class AC_Column_Media_MimeType extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-mime_type' );
		$this->set_label( __( 'Mime Type', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		return get_post_field( 'post_mime_type', $id );
	}

}