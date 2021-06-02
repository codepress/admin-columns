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
	protected $plugin_information;

	public function __construct( Storage $storage, Location\Absolute $location, AC\PluginInformation $plugin_information ) {
		$this->storage = $storage;
		$this->location = $location;
		$this->plugin_information = $plugin_information;
	}

	/**
	 * @return Admin
	 */
	public function create() {
		return new Admin(
			new AdminScripts( $this->location ),
			new PageRequestHandler( new PageFactory( $this->storage, $this->location, $this->plugin_information->is_network_active() ) ),
			new MenuFactory()
		);
	}

}