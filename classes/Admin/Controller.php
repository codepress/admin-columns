<?php

namespace AC\Admin;

use AC\Request;

class Controller {

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var PageCollection
	 */
	private $pages;

	public function __construct( Request $request, PageCollection $pages ) {
		$this->request = $request;
		$this->pages = $pages;
	}

	/**
	 * @return string
	 */
	private function get_slug() {
		return $this->request->get( 'tab' );
	}

	/**
	 * @return Page
	 */
	public function get_page() {
		$requested_slug = $this->get_slug();

		foreach ( $this->pages->all() as $page ) {
			if ( $requested_slug === $page->get_slug() ) {
				return $page;
			}
		}

		return $this->pages->current();
	}

}