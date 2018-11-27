<?php
namespace AC\Admin;

use AC\Admin;
use WP_Screen;

class PageFactory {

	/** @var WP_Screen */
//	private $wp_screen;

	/**
	 * @var Pages
	 */
//	private $pages;
//
//	public function __construct( WP_Screen $wp_screen, Pages $pages ) {
//		$this->wp_screen = $wp_screen;
//		$this->pages = $pages;
//	}

	public static function create( $slug ) {
		switch ( $slug ) {

			case 'help':
				return new Admin\Page\Help();

			case 'settings' :
				return new Admin\Page\Settings();

			default :
				return new Admin\Page\Columns();
		}
	}

	/**
	 * @param string $url
	 *
	 * @return Page
	 */
	// todo
//	public function create_from_url( $url ) {
//		if ( false === strpos( parse_url( $url, PHP_URL_PATH ), 'options-general.php' ) ) {
//			return $this->create_from_slug();
//		}
//
//		parse_str( parse_url( $url, PHP_URL_QUERY ), $query );
//
//		if ( ! isset( $query['page'], $query['tab'] ) ) {
//			return $this->create_from_slug();
//		}
//
//		if ( Admin::MENU_SLUG !== $query['page'] ) {
//			return $this->create_from_slug();
//		}
//
//		return $this->create_from_slug( $query['tab'] );
//	}

	/**
	 * @param string $slug
	 *
	 * @return Page
	 */
//	public function create_from_slug( $slug = null ) {
//		foreach ( Pages::get_pages() as $page ) {
//			if ( $page->get_slug() === $slug ) {
//				return $page;
//			}
//		}
//
//		return current( Pages::get_pages() );
//	}

}