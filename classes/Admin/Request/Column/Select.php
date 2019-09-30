<?php

namespace AC\Admin\Request\Column;

use AC;
use AC\Admin\Request\Column;

class Select extends Column {

	public function __construct() {
		parent::__construct( 'select' );
	}

	public function get_column( AC\Request $request ) {
		return $this->list_screen->get_column_by_type( $request->get( 'type' ) );
	}

}