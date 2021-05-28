<?php

namespace AC\Admin;

use AC;
use AC\Asset\Location;
use AC\DefaultColumnsRepository;
use AC\Deprecated\Hooks;
use AC\Integrations;
use AC\ListScreenRepository\Storage;
use AC\Request;

class PageRequestHandler implements AC\PageRequestHandler {

	const PARAM_TAB = 'tab';

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
	 * @param Request $request
	 *
	 * @return Page
	 */
	public function handle( Request $request ) {

		switch ( $request->get_query()->get( self::PARAM_TAB ) ) {

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