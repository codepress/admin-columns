<?php
namespace AC\Admin;

class Pages {

	/** @var Page[] */
	private $pages = array();

	/**
	 * @param Page $page
	 *
	 * @return $this
	 */
	public function register_page( Page $page ) {
		$this->pages[ $page->get_slug() ] = $page;

		return $this;
	}

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function get( $slug ) {
		if ( ! array_key_exists( $slug, $this->pages ) ) {
			return false;
		}

		return $this->pages[ $slug ];
	}

	/**
	 * @return Page[]
	 */
	public function get_pages() {
		return $this->pages;
	}

}