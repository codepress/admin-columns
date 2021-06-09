<?php

namespace AC\Admin;

use AC;

class PageFactory implements PageFactoryInterface {

	/**
	 * @var MenuFactoryInterface
	 */
	protected $menu_factory;

	/**
	 * @var MainFactory
	 */
	private $main_factory;

	public function __construct( MenuFactoryInterface $menu_factory, MainFactoryInterface $main_factory ) {
		$this->menu_factory = $menu_factory;
		$this->main_factory = $main_factory;
	}

	/**
	 * @param string $slug
	 *
	 * @return Page|null
	 */
	public function create( $slug ) {
		$main = $this->main_factory->create( $slug );

		$main = apply_filters( 'ac/admin/page/main', $main, $slug );

		if ( ! $main ) {
			return null;
		}

		$menu = $this->menu_factory->create( $slug );

		do_action( 'ac/admin/page/menu', $menu );

		return new Page(
			$main,
			$menu
		);
	}

}