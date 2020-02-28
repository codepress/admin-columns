<?php

namespace AC;

use AC\_Admin\Controller;
use AC\Asset\Location;
use AC\ListScreenRepository\Aggregate;

class NetworkAdminFactory {

	/**
	 * @var Aggregate
	 */
	private $list_screen_repository;

	/**
	 * @var Location\Absolute
	 */
	private $location;

	public function __construct( Aggregate $list_screen_repository, Location\Absolute $location ) {
		$this->list_screen_repository = $list_screen_repository;
		$this->location = $location;
	}

	/**
	 * @return AdminLoader
	 */
	public function create() {
		return new AdminLoader( 'settings.php', 'network_admin_menu', new Controller( new Request(), $pages ), $network_pages, $location );
	}

}