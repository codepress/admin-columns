<?php

namespace AC\Middleware;

use AC;
use AC\ListScreenFactory;
use AC\Middleware;
use AC\Request;

class ListScreen
	implements Middleware {

	const LIST_SCREEN = 'list_screen_instance';

	public function handle( Request $request ) {
		return;

		$list_screen = ListScreenFactory::create(
			$request->filter( 'list_screen', '', FILTER_SANITIZE_STRING ),
			$request->filter( 'layout', null, FILTER_SANITIZE_STRING )
		);

		if ( $list_screen instanceof AC\ListScreen ) {
			$request->get_parameters()->set( self::LIST_SCREEN, $list_screen );
		}
	}

}