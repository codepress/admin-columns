<?php

namespace AC\Controller\ColumnRequest;

use AC\Column;
use AC\Controller\ColumnRequest;
use AC\ListScreen;
use AC\Request;

class Refresh extends ColumnRequest {

	protected function get_column( Request $request, ListScreen $list_screen ): ?Column {
		$settings = json_decode( $request->get( 'data' ), true );
		$settings['name'] = $request->get( 'column_name' );

		return $list_screen->create_column( $settings ) ?: null;
	}

}