<?php

namespace AC\Admin;

use AC;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\Deprecated\Hooks;
use AC\Integrations;
use AC\ListScreenRepository\Storage;

class PageFactory implements PageFactoryInterface {

	const PARAM_TAB = 'tab';

	/**
	 * @var Storage
	 */
	protected $storage;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	/**
	 * @var bool
	 */
	protected $network_active;

	public function __construct( Storage $storage, Location\Absolute $location, $network_active ) {
		$this->storage = $storage;
		$this->location = $location;
		$this->network_active = $network_active;
	}

	/**
	 * @param string $slug
	 *
	 * @return Page
	 */
	public function create( $slug ) {

		switch ( $slug ) {
			case Page\Help::NAME :
				return new Page\Help( new Hooks(), $this->location );
			case Page\Settings::NAME :
				$sections = new SectionCollection();
				$sections->add( new Section\General( [ new Section\Partial\ShowEditButton() ] ) )
				         ->add( new Section\Restore() );

				return new Page\Settings( $sections );
			case Page\Addons::NAME :
				return new Page\Addons( $this->location, new Integrations() );
			default:
				return new Page\Columns(
					$this->location,
					new DefaultColumnsRepository(),
					new Section\Partial\Menu(),
					$this->storage,
					new Preference\ListScreen()
				);
		}
	}

}