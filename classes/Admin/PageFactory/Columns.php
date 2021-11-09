<?php

namespace AC\Admin\PageFactory;

use AC;
use AC\Admin;
use AC\Admin\MenuFactoryInterface;
use AC\Admin\Page;
use AC\Admin\PageFactoryInterface;
use AC\Admin\Preference;
use AC\Admin\Section;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\ListScreenRepository\Storage;

class Columns implements PageFactoryInterface {

	/**
	 * @var Storage
	 */
	protected $storage;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var MenuFactoryInterface
	 */
	protected $menu_factory;

	public function __construct( Storage $storage, Location\Absolute $location, MenuFactoryInterface $menu_factory ) {
		$this->storage = $storage;
		$this->location = $location;
		$this->menu_factory = $menu_factory;
	}

	public function create() {
		return new Page\Columns(
			$this->location,
			new DefaultColumnsRepository(),
			new Section\Partial\Menu(),
			$this->storage,
			new Admin\View\Menu( $this->menu_factory->create( 'columns' ) ),
			new Preference\ListScreen()
		);
	}

}