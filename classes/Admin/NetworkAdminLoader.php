<?php

namespace AC\Admin;

use AC\AdminFactoryInterface;
use AC\Registrable;

class NetworkAdminLoader implements Registrable {

	/**
	 * @var WpMenuFactory
	 */
	private $menu_factory;

	/**
	 * @var AdminFactoryInterface
	 */
	private $admin_factory;

	public function __construct( WpMenuFactory $menu_factory, AdminFactoryInterface $admin_factory ) {
		$this->menu_factory = $menu_factory;
		$this->admin_factory = $admin_factory;
	}

	public function register() {
		add_action( 'network_admin_menu', [ $this, 'load' ] );
	}

	public function load() {
		$this->menu_factory->create_sub_menu(
			'settings.php',
			$this->admin_factory->create()
		);
	}

}