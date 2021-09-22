<?php

namespace AC\Admin;

use AC;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\Deprecated\Hooks;
use AC\IntegrationRepository;
use AC\ListScreenRepository\Storage;
use AC\Renderable;

class PageFactory implements PageFactoryInterface {

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

	/**
	 * @param string $slug
	 *
	 * @return Renderable|null
	 */
	public function create( $slug ) {

		switch ( $slug ) {
			case Page\Help::NAME :
				return new Page\Help( new Hooks(), $this->location, new View\Menu( $this->menu_factory->create( $slug ) ) );
			case Page\Settings::NAME :
				$page = new Page\Settings( new View\Menu( $this->menu_factory->create( $slug ) ), $this->location );
				$page->add_section( new Section\General( [ new Section\Partial\ShowEditButton() ] ) )
				     ->add_section( new Section\Restore(), 40 );

				if ( ! ac_is_pro_active() ) {
					$page->add_section( new Section\ProCta(), 50 );
				}

				return $page;
			case Page\Addons::NAME :
				return new Page\Addons(
					$this->location,
					new IntegrationRepository(),
					new View\Menu( $this->menu_factory->create( $slug ) )
				);
			case Page\Columns::NAME :
				return new Page\Columns(
					$this->location,
					new DefaultColumnsRepository(),
					new Section\Partial\Menu(),
					$this->storage,
					new View\Menu( $this->menu_factory->create( $slug ) ),
					new Preference\ListScreen()
				);
		}

		return null;
	}

}