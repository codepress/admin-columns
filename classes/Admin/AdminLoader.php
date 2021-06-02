<?php

namespace AC\Admin;

use AC\AdminFactory;
use AC\Registrable;

class AdminLoader implements Registrable {

	/**
	 * @var WpMenuFactory
	 */
	private $menu_factory;

	public function __construct( WpMenuFactory $menu_factory ) {
		$this->menu_factory = $menu_factory;
	}

	public function register() {
		add_action( 'admin_menu', [ $this, 'load' ] );
	}

	public function load() {
		$this->menu_factory->create_sub_menu(
			'options-general.php',
			AdminFactory::get_factory()->create()
		);
	}

}