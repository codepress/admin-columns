<?php

namespace AC\Admin;

use AC;
use AC\Request;

// TODO redundant. Maybe use Handler.
class PageRequestHandler implements RequestHandlerInterface {

	/**
	 * @var PageFactoryAggregate
	 */
	private $factory;

	/**
	 * @var string
	 */
	private $default;

	public function __construct( PageFactoryAggregateInterface $factory, $default = '' ) {
		$this->factory = $factory;
		$this->default = (string) $default;
	}

	public function handle( Request $request ) {
		$page = $this->factory->create(
			$request->get_query()->get( self::PARAM_TAB ) ?: $this->default
		);

		return apply_filters( 'ac/admin/request/page', $page, $request );
	}

}