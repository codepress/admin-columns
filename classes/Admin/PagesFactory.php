<?php
namespace AC\Admin;

class PagesFactory {

	/** @var Pages[] */
	private $factories = array();

	/**
	 * @param Pages $factory
	 */
	public function register_factory( Pages $factory ) {
		$this->factories[] = $factory;
	}

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public function get( $slug ) {
		foreach ( $this->factories as $factory ) {
			$page = $factory->get( $slug );

			if ( $page ) {
				return $page;
			}
		}

		return false;
	}

	/**
	 * @return Page[]
	 */
	public function get_pages() {
		$pages = array();

		foreach ( $this->factories as $factory ) {
			foreach ( $factory->get_pages() as $page ) {
				$pages[ $page->get_slug() ] = $page;
			}
		}

		return $pages;
	}

}