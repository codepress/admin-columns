<?php

/**
 * @since 2.0
 */
class AC_Column_Media_Width extends AC_Column_Media_Height {

	public function __construct() {
		parent::__construct();

		$this->set_type( 'column-width' );
		$this->set_label( __( 'Width', 'codepress-admin-columns' ) );
	}

	protected function get_option_name() {
		return 'width';
	}

}