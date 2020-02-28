<?php

namespace AC;

use AC\_Admin\Controller;
use AC\_Admin\Page;
use AC\_Admin\PageCollection;
use AC\_Admin\Section;
use AC\_Admin\SectionCollection;
use AC\Asset\Location;
use AC\Controller\ListScreenRequest;
use AC\ListScreenRepository\Aggregate;

class AdminFactory {

	/**
	 * @var Aggregate
	 */
	protected $list_screen_repository;

	/**
	 * @var Location\Absolute
	 */
	protected $location;

	public function __construct( Aggregate $list_screen_repository, Location\Absolute $location ) {
		$this->list_screen_repository = $list_screen_repository;
		$this->location = $location;
	}

	/**
	 * @return Page\Columns
	 */
	protected function create_columns_page() {
		return new Page\Columns(
			new ListScreenRequest( new Request(), $this->list_screen_repository, new Preferences\Site( 'settings' ) ),
			$this->location,
			new UnitializedListScreens( new DefaultColumns() )
		);
	}

	/**
	 * @return Page\Settings
	 */
	protected function create_settings_page() {
		$sections = new SectionCollection();
		$sections->add( new Section\General( [ new Section\Partial\ShowEditButton( new Settings\General() ) ] ) );
		$sections->add( new Section\Restore() );

		return new Page\Settings( $sections );
	}

	/**
	 * @return AdminLoader
	 */
	public function create() {
		$pages = new PageCollection();
		$pages->add( $this->create_columns_page() )
		      ->add( $this->create_settings_page() )
		      ->add( new Page\Addons( $this->location ) )
		      ->add( new Page\Tools() );

		return new AdminLoader( 'options-general.php', 'admin_menu', new Controller( new Request(), $pages ), $pages, $this->location );
	}

}