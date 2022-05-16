<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class AlternateText extends Column\Meta {

	public function __construct() {
		$this->set_type( 'column-alternate_text' )
		     ->set_group( 'media-image' )
		     ->set_label( __( 'Alternative Text', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_wp_attachment_image_alt';
	}

	public function get_value( $id ) {
		$value = ac_helper()->string->strip_trim( $this->get_raw_value( $id ) );

		if ( ac_helper()->string->is_empty( $value ) ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return $this->get_meta_value( $id, $this->get_meta_key() );
	}

}