<?php
namespace AC\Admin;

class PageFactory {

	/**
	 * @param $slug
	 *
	 * @return Page
	 */
	public static function create( $slug ) {
		foreach ( Pages::get_pages() as $page ) {
			if ( $page->get_slug() === $slug ) {
				return $page;
			}
		}

		return current( Pages::get_pages() );
	}

}