<?php

/**
 * @since 2.0
 */
class AC_Column_Media_AlternateText extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-alternate_text' );
		$this->set_label( __( 'Alt', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_wp_attachment_image_alt';
	}

	public function get_value( $id ) {
		return ac_helper()->string->strip_trim( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return $this->get_meta_value( $id, $this->get_meta_key() );
	}

}