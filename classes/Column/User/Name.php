<?php

namespace AC\Column\User;

use AC\Column;

class Name extends Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'name' );
	}

}