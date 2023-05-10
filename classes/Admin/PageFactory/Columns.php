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
use AC\Controller\Middleware;
use AC\DefaultColumnsRepository;
use AC\ListScreenFactory;
use AC\ListScreenRepository\Storage;
use AC\Request;
use AC\Table\ListKeysFactoryInterface;
use InvalidArgumentException;

class Columns implements PageFactoryInterface {

	protected $storage;

	protected $location;

	protected $menu_factory;

	protected $list_screen_factory;

	protected $list_screen_uninitialized;

	private $menu_list_factory;

	private $list_keys_factory;

	public function __construct(
		Storage $storage,
		Location\Absolute $location,
		MenuFactoryInterface $menu_factory,
		ListScreenFactory $list_screen_factory,
		Admin\ListScreenUninitialized $list_screen_uninitialized,
		Admin\MenuListFactory $menu_list_factory,
		ListKeysFactoryInterface $list_keys_factory
	) {
		$this->storage = $storage;
		$this->location = $location;
		$this->menu_factory = $menu_factory;
		$this->list_screen_factory = $list_screen_factory;
		$this->list_screen_uninitialized = $list_screen_uninitialized;
		$this->menu_list_factory = $menu_list_factory;
		$this->list_keys_factory = $list_keys_factory;
	}

	public function create() {
		$request = new Request();

		$request->add_middleware(
			new Middleware\ListScreenAdmin(
				$this->storage,
				new Preference\ListScreen(),
				$this->list_screen_factory,
				$this->list_keys_factory
			)
		);

		$list_screen = $request->get( 'list_screen' );

		if ( ! $list_screen ) {
			throw new InvalidArgumentException( 'Invalid screen.' );
		}

		return new Page\Columns(
			$this->location,
			$list_screen,
			new DefaultColumnsRepository(),
			$this->list_screen_uninitialized,
			new Section\Partial\Menu( $this->menu_list_factory ),
			new Admin\View\Menu( $this->menu_factory->create( 'columns' ) )
		);
	}

}