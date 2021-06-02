<?php

namespace AC\Admin;

use AC;
use AC\Request;

class PageRequestHandler implements AC\PageRequestHandler {

	const PARAM_TAB = 'tab';

	/**
	 * @var PageFactory
	 */
	private $page_factory;

	public function __construct( PageFactoryInterface $page_factory ) {
		$this->page_factory = $page_factory;
	}

	/**
	 * @param Request $request
	 *
	 * @return Page
	 */
	public function handle( Request $request ) {
		return $this->page_factory->create( $request->get_query()->get( self::PARAM_TAB ) );
	}

}