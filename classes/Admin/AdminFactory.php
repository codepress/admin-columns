<?php

namespace AC\Admin;

use AC;
use AC\Admin;
use AC\Asset\Location;
use AC\ListScreenRepository\Storage;

class AdminFactory implements AC\AdminFactory {

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
	 * @return Admin
	 */
	public function create() {

		// TODO
		return new Admin(
			'options-general.php',
			'admin_menu',
			new AdminScripts( $this->location ),
			new PageRequestHandler( $this->storage, $this->location ),
			new AdminMenuFactory()
		);
	}

}