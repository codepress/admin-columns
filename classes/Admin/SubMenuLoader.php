<?php

namespace AC\Admin;

use AC\AdminFactory;
use AC\Registrable;

class SubMenuLoader implements Registrable {

	/**
	 * @var string
	 */
	private $menu_hook;

	/**
	 * @var WpMenuFactory
	 */
	private $menu_factory;

	public function __construct( $menu_hook, WpMenuFactory $menu_factory ) {
		$this->menu_hook = $menu_hook;
		$this->menu_factory = $menu_factory;
	}

	public function register() {
		add_action( $this->menu_hook, [ $this, 'load' ] );
	}

	public function load() {
		$this->menu_factory->create_sub_menu(
			'options-general.php',
			AdminFactory::get_factory()->create()
		);
	}

}