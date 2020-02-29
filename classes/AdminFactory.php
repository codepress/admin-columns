<?php

namespace AC;

use AC\Admin\Page;
use AC\Admin\PageCollection;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
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

		$list_screen_controller = new ListScreenRequest( new Request(), $this->list_screen_repository, new Preferences\Site( 'settings' ) );

		// todo: check dependencies. maybe inject ListScreen instead of controller
		return new Page\Columns(
			$list_screen_controller,
			$this->location,
			new UnitializedListScreens( new DefaultColumns() ),
			new Section\Partial\Menu( $list_screen_controller, false )
		);
	}

	protected function create_section_general() {
		return new Section\General( [
			new Section\Partial\ShowEditButton( new Settings\General() ),
		] );
	}

	/**
	 * @return Page\Settings
	 */
	protected function create_settings_page() {
		$sections = new SectionCollection();
		$sections->add( $this->create_section_general() )
		         ->add( new Section\Restore() );

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
	 * @return Admin
	 */
	public function create() {
		$pages = $this->get_pages();

		return new Admin(
			'options-general.php',
			'admin_menu',
			new Request(),
			$pages,
			$this->location
		);
	}

}