<?php

namespace AC;

use AC\Admin\Page;
use AC\Admin\PageCollection;
use AC\Admin\Section;
use AC\Admin\SectionCollection;
use AC\Asset\Location;
use AC\Controller\ListScreenRequest;
use AC\Deprecated\Hooks;
use AC\ListScreenRepository\Storage;

class AdminFactory {

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
	 * @return Page\Columns
	 */
	protected function create_columns_page() {
		$list_screen_controller = new ListScreenRequest(
			new Request(),
			$this->storage,
			new Preferences\Site( 'settings' )
		);

		return new Page\Columns(
			$list_screen_controller,
			$this->location,
			new DefaultColumnsRepository(),
			new Section\Partial\Menu( $list_screen_controller, false )
		);
	}

	protected function create_section_general() {
		return new Section\General( [
			new Section\Partial\ShowEditButton(),
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
		      ->add( new Page\Addons( $this->location, new Integrations() ) );

		$hooks = new Hooks();
		if ( $hooks->get_count() > 0 ) {
			$pages->add( new Page\Help( new Hooks(), $this->location ) );
		}

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
			$pages,
			$this->location
		);
	}

}