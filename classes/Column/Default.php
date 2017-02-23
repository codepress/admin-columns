<?php

// TODO: maybe remove?
class AC_Column_Default extends AC_Column {

	public function __construct() {
		$this->set_original( true );
	}

	public function get_value( $id ) {
		return false;
	}

}