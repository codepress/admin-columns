<?php

namespace AC\Middleware;

use AC\ListScreenFactory;
use AC\Middleware;
use AC\Request;

class ListScreen
	implements Middleware {

	const LIST_SCREEN = 'list_screen';

	public function handle( Request $request ) {
		$list_screen = ListScreenFactory::create(
			$request->filter( 'list_screen', '', FILTER_SANITIZE_STRING ),
			$request->filter( 'layout', null, FILTER_SANITIZE_STRING )
		);

		$request->get_parameters()->set( 'list_screen', $list_screen );
		$request->get_parameters()->remove( 'layout' );
	}

}