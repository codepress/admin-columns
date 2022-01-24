<?php

namespace AC\Admin;

use AC\Registrable;

class AdminNetwork implements Registrable {

	/**
	 * @var RequestHandlerInterface
	 */
	private $request_handler;

	/**
	 * @var WpMenuFactory
	 */
	private $wp_menu_factory;

	/**
	 * @var AdminScripts
	 */
	private $scripts;

	public function __construct( RequestHandlerInterface $request_handler, WpMenuFactory $wp_menu_factory, AdminScripts $scripts ) {
		$this->request_handler = $request_handler;
		$this->wp_menu_factory = $wp_menu_factory;
		$this->scripts = $scripts;
	}

	public function register() {
		add_action( 'network_admin_menu', [ $this, 'init' ] );
	}

	public function init() {
		$hook = $this->wp_menu_factory->create_sub_menu( 'settings.php' );

		$loader = new AdminLoader( $hook, $this->request_handler, $this->scripts );
		$loader->register();
	}

}