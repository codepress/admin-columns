<?php

namespace AC\Admin;

use AC;
use AC\Request;

class NetworkRequestHandler implements RequestHandlerInterface {

	/**
	 * @var RequestHandlerInterface[]
	 */
	public static $handlers;

	public static function add_handler( RequestHandlerInterface $handler ) {
		self::$handlers[] = $handler;
	}

	public function handle( Request $request ) {
		foreach ( array_reverse( self::$handlers ) as $handler ) {
			$page = $handler->handle( $request );

			if ( $page ) {
				break;
			}
		}

		return apply_filters( 'ac/admin/network/request/page', $page, $request );
	}

}