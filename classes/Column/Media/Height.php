<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Height extends AC_Column_Media_MetaValue {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-height' );
		$this->set_label( __( 'Height', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'height';
	}

	public function get_value( $id ) {
		$value = $this->get_raw_value( $id );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value . 'px';
	}

}