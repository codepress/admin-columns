<?php

class AC_Column_Default extends AC_Column {

	public function __construct() {
		$this->set_group( 'default' );
		$this->set_original( true );
	}

	public function get_value( $id ) {
		return false;
	}

}