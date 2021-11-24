<?php

namespace AC\Admin;

use AC;
use AC\Request;

class PageNetworkRequestHandler implements RequestHandlerInterface {

	/**
	 * @var PageFactoryInterface[]
	 */
	private $factories;

	/**
	 * @param string               $slug
	 * @param PageFactoryInterface $factory
	 *
	 * @return $this
	 */
	public function add( $slug, PageFactoryInterface $factory ) {
		$this->factories[ $slug ] = $factory;

		return $this;
	}

	public function handle( Request $request ) {
		$slug = $request->get_query()->get( self::PARAM_TAB ) ?: 'columns';

		$page = isset( $this->factories[ $slug ] )
			? $this->factories[ $slug ]->create()
			: null;

		return apply_filters( 'ac/admin/network/request/page', $page, $request );
	}

}