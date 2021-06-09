<?php

namespace AC\Admin;

use AC;
use AC\Asset\Location;

class PageFactory implements PageFactoryInterface {

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var MenuFactoryInterface
	 */
	protected $menu_factory;

	/**
	 * @var MainFactory
	 */
	private $main_factory;

	public function __construct( Location\Absolute $location, MenuFactoryInterface $menu_factory, MainFactoryInterface $main_factory ) {
		$this->location = $location;
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

		if ( ! $main ) {
			return null;
		}

		return new Page(
			$main,
			new AdminScripts( $this->location ),
			$this->menu_factory->create( $slug )
		);
	}

}