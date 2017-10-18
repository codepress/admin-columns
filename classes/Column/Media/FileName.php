<?php

/**
 * @since 2.0
 */
class AC_Column_Media_FileName extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-file_name' );
		$this->set_label( __( 'Filename', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_wp_attached_file';
	}

	public function get_value( $id ) {
		return ac_helper()->html->link( wp_get_attachment_url( $id ), $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return ac_helper()->image->get_file_name( $id );
	}

}