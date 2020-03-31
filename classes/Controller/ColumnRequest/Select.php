<?php

namespace AC\Controller\ColumnRequest;

use AC;

class Select extends AC\Controller\ColumnRequest {

	protected function get_column( AC\Request $request, AC\ListScreen $list_screen ) {
		return $list_screen->get_column_by_type( $request->get( 'type' ) );
	}

}