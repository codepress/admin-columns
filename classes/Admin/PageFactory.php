<?php
namespace AC\Admin;

class PageFactory {

	/**
	 * @param string $slug
	 *
	 * @return Page|false
	 */
	public static function create( $slug ) {
		foreach ( Pages::get_pages() as $page ) {
			if ( $page->get_slug() === $slug ) {
				return $page;
			}
		}

		return false;
	}

}