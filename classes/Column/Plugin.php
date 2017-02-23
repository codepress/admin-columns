<?php

class AC_Column_Plugin extends AC_Column {

	public function __construct() {
		$this->set_group( 'plugin' );
		$this->set_original( true );
	}

}