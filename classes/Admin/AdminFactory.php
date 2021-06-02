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

	/**
	 * @var AC\PluginInformation
	 */
	protected $plugin;

	public function __construct( Storage $storage, Location\Absolute $location, AC\PluginInformation $plugin ) {
		$this->storage = $storage;
		$this->location = $location;
		$this->plugin = $plugin;
	}

	/**
	 * @return Admin
	 */
	public function create() {
		return new Admin(
			new AdminScripts( $this->location ),
			new PageRequestHandler( new PageFactory( $this->storage, $this->location ) ),
			new MenuFactory( admin_url( 'options-general.php' ) )
		);
	}

}