<?php

namespace AC\Admin;

use AC\AdminFactoryInterface;
use AC\Registrable;

class SubMenuNetworkLoader implements Registrable {

	/**
	 * @var string
	 */
	private $menu_hook;

	/**
	 * @var WpMenuFactory
	 */
	private $menu_factory;

	/**
	 * @var AdminFactoryInterface
	 */
	private $admin_factory;

	public function __construct( $menu_hook, WpMenuFactory $menu_factory, AdminFactoryInterface $admin_factory ) {
		$this->menu_hook = $menu_hook;
		$this->menu_factory = $menu_factory;
		$this->admin_factory = $admin_factory;
	}

	public function register() {
		add_action( $this->menu_hook, [ $this, 'load' ] );
	}

	public function load() {
		$this->menu_factory->create_sub_menu(
			'settings.php',
			$this->admin_factory->create()
		);
	}

}