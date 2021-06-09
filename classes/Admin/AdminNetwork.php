<?php

namespace AC\Admin;

use AC\Registrable;
use AC\Request;
use AC\View;

class AdminNetwork implements Registrable {

	/**
	 * @var NetworkRequestHandler
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

	public function __construct( NetworkRequestHandler $request_handler, WpMenuFactory $wp_menu_factory, AdminScripts $scripts ) {
		$this->request_handler = $request_handler;
		$this->wp_menu_factory = $wp_menu_factory;
		$this->scripts = $scripts;
	}

	public function register() {
		add_action( 'network_admin_menu', [ $this, 'init' ] );
	}

	public function init() {
		$hook = $this->wp_menu_factory->create_sub_menu( 'settings.php' );

		add_action( $hook, [ $this, 'body' ] );
		add_action( 'load-' . $hook, [ $this, 'load' ] );
	}

	public function body() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			$view = new View( [
				'content' => $page->get_head()->render() . $page->get_main()->render(),
			] );

			echo $view->set_template( 'admin/wrap' )->render();
		}
	}

	public function load() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Registrable ) {
			$page->register();
		}

		foreach ( $this->scripts->get_assets()->all() as $asset ) {
			$asset->enqueue();
		}
	}

}