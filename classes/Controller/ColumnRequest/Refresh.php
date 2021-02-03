<?php

namespace AC\Controller\ColumnRequest;

use AC;

class Refresh extends AC\Controller\ColumnRequest {

	protected function get_column( AC\Request $request, AC\ListScreen $list_screen ) {
		$settings = json_decode( $request->get( 'data' ), true );
		$settings['name'] = $request->get( 'column_name' );

		return $list_screen->create_column( $settings );
	}

}