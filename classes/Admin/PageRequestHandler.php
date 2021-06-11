<?php

namespace AC\Admin;

use AC;
use AC\Request;

class PageRequestHandler implements RequestHandlerInterface {

	/**
	 * @var PageFactory
	 */
	private $page_factory;

	/**
	 * @var string
	 */
	private $default;

	public function __construct( PageFactory $page_factory, $default = '' ) {
		$this->page_factory = $page_factory;
		$this->default = $default;
	}

	public function handle( Request $request ) {
		return $this->page_factory->create( $request->get_query()->get( self::PARAM_TAB ) ?: $this->default );
	}

}