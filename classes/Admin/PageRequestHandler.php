<?php

namespace AC\Admin;

use AC;
use AC\Request;

class PageRequestHandler implements AC\PageRequestHandler {

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
	 * @return Page|null
	 */
	public function handle( Request $request ) {
		$page = $this->page_factory->create( $request->get_query()->get( self::PARAM_TAB ) ?: Page\Columns::NAME );

		return apply_filters( 'ac/admin/request/page', $page, $request );
	}

}