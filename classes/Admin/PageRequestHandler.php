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
		// TODO check current screeen id: 'settings_page_codepress-admin-columns'
		if ( Admin::NAME !== $request->get( 'page' ) ) {
			return null;
		}

		return $this->page_factory->create( $request->get( self::PARAM_TAB ) ?: $this->default );
	}

}