<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Height extends AC_Column_Media_Meta {

	public function __construct() {
		$this->set_type( 'column-height' );
		$this->set_label( __( 'Height', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		return $value ? $value . 'px' : ac_helper()->string->get_empty_char();
	}

	public function get_raw_value( $id ) {
		$value = parent::get_raw_value( $id );

		return ! empty( $value['height'] ) ? $value['height'] : false;
	}

}