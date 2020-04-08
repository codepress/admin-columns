<?php

namespace AC\Admin;

use AC\Collection;

class PageCollection extends Collection {

	/**
	 * @return Page[]
	 */
	public function all() {
		return parent::all();
	}

	public function add( Page $page ) {
		$this->put( $page->get_slug(), $page );

		return $this;
	}

	/**
	 * @return Page
	 */
	public function first() {
		return parent::first();
	}

}