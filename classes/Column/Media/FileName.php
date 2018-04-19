<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class FileName extends Column\Meta {

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