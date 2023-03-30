<?php

namespace AC\Admin;

use AC;
use AC\Request;

class PageRequestHandler implements RequestHandlerInterface {

	/**
	 * @var PageFactoryInterface[]
	 */
	private $factories;

	public function add( string $slug, PageFactoryInterface $factory ): self {
		$this->factories[ $slug ] = $factory;

		return $this;
	}

	public function handle( Request $request ) {
		$slug = $request->get_query()->get( self::PARAM_TAB ) ?: 'columns';

		$page = isset( $this->factories[ $slug ] )
			? $this->factories[ $slug ]->create()
			: null;

		return apply_filters( 'ac/admin/request/page', $page, $request );
	}

}