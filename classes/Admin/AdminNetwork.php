<?php

namespace AC\Admin;

use AC\Registrable;
use AC\Request;

class AdminNetwork implements Registrable {

	/**
	 * @var NetworkRequestHandler
	 */
	private $request_handler;

	/**
	 * @var WpMenuFactory
	 */
	private $wp_menu_factory;

	public function __construct( NetworkRequestHandler $request_handler, WpMenuFactory $wp_menu_factory ) {
		$this->request_handler = $request_handler;
		$this->wp_menu_factory = $wp_menu_factory;
	}

	public function register() {
		add_action( 'network_admin_menu', [ $this, 'load' ] );
	}

	public function load() {
		$hook = $this->wp_menu_factory->create_sub_menu( 'settings.php' );

		add_action( $hook, [ $this, 'render_page' ] );
		add_action( 'load-' . $hook, [ $this, 'register_page' ] );
	}

	public function render_page() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			$page->render();
		}
	}

	public function register_page() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Registrable ) {
			$page->register();
		}
	}

}