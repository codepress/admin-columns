<?php

namespace AC;

use AC\_Admin\Assets;
use AC\_Admin\Controller;
use AC\_Admin\Menu;
use AC\_Admin\PageCollection;
use AC\Asset\Localizable;
use AC\Asset\Location\Absolute;
use AC\Asset\Script;
use AC\Asset\Style;

class AdminLoader implements Registrable {

	const MENU_SLUG = 'codepress-admin-columns';
	const QUERY_ARG_PAGE = 'page';
	const QUERY_ARG_TAB = 'tab';

	/**
	 * @var string
	 */
	private $parent_slug;

	/**
	 * @var string
	 */
	private $menu_hook;

	/**
	 * @var Controller
	 */
	private $controller;

	/**
	 * @var PageCollection
	 */
	private $pages;

	/**
	 * @var Absolute
	 */
	private $location;

	public function __construct( $parent_slug, $menu_hook, Controller $controller, PageCollection $pages, Absolute $location ) {
		$this->parent_slug = $parent_slug;
		$this->menu_hook = $menu_hook;
		$this->pages = $pages;
		$this->controller = $controller;
		$this->location = $location;
	}

	/**
	 * @return PageCollection
	 */
	public function get_pages() {
		return $this->pages;
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				self::QUERY_ARG_PAGE => self::MENU_SLUG,
				self::QUERY_ARG_TAB  => $slug,
			],
			$this->parent_slug
		);
	}

	/**
	 * @return Menu
	 */
	public function get_menu() {
		$menu = new Menu();

		$current_slug = $this->controller->get_page()->get_slug();

		foreach ( $this->pages->all() as $page ) {
			$class = $current_slug === $page->get_slug()
				? 'nav-tab-active'
				: null;

			$menu->add( new Menu\Item( $this->create_menu_link( $page->get_slug() ), $page->get_title(), $class ) );
		}

		return $menu;
	}

	public function register() {
		add_action( $this->menu_hook, [ $this, 'register_menu' ] );
	}

	public function register_menu() {
		$hook = add_submenu_page(
			$this->parent_slug,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns Test', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::MENU_SLUG,
			[ $this, 'render' ],
			1
		);

		add_action( 'admin_print_scripts-' . $hook, [ $this, 'scripts' ] );
	}

	public function render() {
		?>
		<div id="cpac" class="wrap">
			<?= $this->get_menu()->render(); ?>
			<?= $this->controller->get_page()->render(); ?>
		</div>
		<?php
	}

	public function scripts() {
		$page = $this->controller->get_page();

		if ( $page instanceof Assets ) {
			foreach ( $page->get_assets() as $asset ) {
				$asset->enqueue();
			}
		}

		if ( $page instanceof Localizable ) {
			$page->localize();
		}

		wp_enqueue_style( 'wp-pointer' );

		$assets = [
			new Style( 'jquery-qtip2', $this->location->with_suffix( 'external/qtip2/jquery.qtip.min.css' ) ),
			new Script( 'jquery-qtip2', $this->location->with_suffix( 'external/qtip2/jquery.qtip.min.js' ), [ 'jquery' ] ),
			new Script( 'ac-admin-general', $this->location->with_suffix( 'assets/js/admin-general.js' ), [ 'jquery', 'wp-pointer', 'jquery-qtip2' ] ),
			new Style( 'ac-admin', $this->location->with_suffix( 'assets/css/admin-general.css' ) ),
		];

		foreach ( $assets as $asset ) {
			$asset->enqueue();
		}

		do_action( 'ac/admin_scripts', $page );
		do_action( 'ac/admin_scripts/' . $page->get_slug() );
	}

}