<?php

namespace AC\Admin;

use AC;
use AC\Admin;
use AC\Asset\Location;
use AC\ListScreenRepository\Storage;

class AdminFactory implements AC\AdminFactoryInterface {

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
		return new Admin(
			new AdminScripts( $this->location ),
			new PageRequestHandler( new PageFactory( $this->storage, $this->location ) ),
			new AdminPageMenuFactory( admin_url( 'options-general.php' ) )
		);
	}

}