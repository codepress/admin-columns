<?php
namespace AC\Admin;

class Pages {

	/** @var Page[] */
	private $pages;

	/**
	 * @param Page $page
	 *
	 * @return $this
	 */
	public function register_page( Page $page  ) {
		$this->pages[] = $page;

		return $this;
	}

	public function get_pages() {
		return $this->pages;
	}

}