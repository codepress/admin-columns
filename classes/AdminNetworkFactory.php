<?php

namespace AC;

use AC\Admin\Controller;
use AC\Admin\Page;
use AC\Admin\PageCollection;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Location;
use AC\Controller\ListScreenRequest;
use AC\ListScreenRepository\Aggregate;

class AdminNetworkFactory {

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

		// todo: only network sites and users
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

		// todo: add license

		return new Page\Settings( $sections );
	}

	/**
	 * @return PageCollection
	 */
	protected function get_pages() {
		$pages = new PageCollection();
		$pages->add( $this->create_columns_page() )
		      ->add( $this->create_settings_page() )
		      ->add( new Page\Addons( $this->location ) );

		return $pages;
	}

	/**
	 * @return Controller
	 */
	protected function get_controller() {
		return new Controller( new Request(), $this->get_pages() );
	}

	/**
	 * @return Admin
	 */
	public function create() {
		return new Admin(
			'settings.php',
			'network_admin_menu',
			$this->get_controller(),
			$this->get_pages(),
			$this->location
		);
	}

}