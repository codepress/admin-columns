<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Asset\Location;
use AC\Deprecated\Hooks;

class Help implements PageFactoryInterface {

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var MenuFactoryInterface
	 */
	protected $menu_factory;

	public function __construct( Location\Absolute $location, MenuFactoryInterface $menu_factory ) {
		$this->location = $location;
		$this->menu_factory = $menu_factory;
	}

	public function create() {
		return new Page\Help(
			new Hooks(),
			$this->location,
			new Admin\View\Menu( $this->menu_factory->create( 'help' ) )
		);
	}

}