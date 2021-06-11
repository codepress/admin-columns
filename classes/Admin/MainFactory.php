<?php

namespace AC\Admin;

use AC;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\Deprecated\Hooks;
use AC\Integrations;
use AC\ListScreenRepository\Storage;
use AC\Renderable;

class MainFactory implements MainFactoryInterface {

	/**
	 * @var Storage
	 */
	protected $storage;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	public function __construct( Storage $storage, Location\Absolute $location ) {
		$this->storage = $storage;
		$this->location = $location;
	}

	/**
	 * @param string $slug
	 *
	 * @return Renderable|null
	 */
	public function create( $slug ) {

		switch ( $slug ) {
			case Main\Help::NAME :
				return new Main\Help( new Hooks(), $this->location );
			case Main\Settings::NAME :
				$sections = new SectionCollection();
				$sections->add( new Section\General( [ new Section\Partial\ShowEditButton() ] ) )
				         ->add( new Section\Restore() );

				return new Main\Settings( $sections );
			case Main\Addons::NAME :
				return new Main\Addons( $this->location, new Integrations() );
			case Main\Columns::NAME :
				return new Main\Columns(
					$this->location,
					new DefaultColumnsRepository(),
					new Section\Partial\Menu(),
					$this->storage,
					new Preference\ListScreen()
				);
		}

		return null;
	}

}