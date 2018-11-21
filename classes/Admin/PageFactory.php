<?php
namespace AC\Admin;

class PageFactory {

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public static function create( $slug ) {
		$pages = new Pages();

		foreach ( $pages->get_copy() as $page ) {
			if ( $page->get_slug() === $slug ) {
				return $page;
			}
		}

		return false;
	}

}