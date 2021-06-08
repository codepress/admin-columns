<?php

namespace AC\Admin;

use AC\Registrable;
use AC\Request;
use AC\View;

class Admin implements Registrable {

	const NAME = 'codepress-admin-columns';

	/**
	 * @var RequestHandlerInterface
	 */
	private $request_handler;

	/**
	 * @var WpMenuFactory
	 */
	private $wp_menu_factory;

	public function __construct( RequestHandlerInterface $request_handler, WpMenuFactory $wp_menu_factory ) {
		$this->request_handler = $request_handler;
		$this->wp_menu_factory = $wp_menu_factory;
	}

	public function register() {
		add_action( 'admin_menu', [ $this, 'init' ] );
	}

	public function init() {
		$hook = $this->wp_menu_factory->create_sub_menu( 'options-general.php' );

		add_action( 'in_admin_header', [ $this, 'render_menu' ] );
		add_action( $hook, [ $this, 'render_body' ] );
		add_action( 'load-' . $hook, [ $this, 'load' ] );
	}

	public function render_menu() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			$view = new View( [
				'menu_items' => $page->get_menu()->get_items(),
				'current'    => $page->get_menu()->get_current(),
			] );

			echo $view->set_template( 'admin/menu' )->render();
		}
	}

	public function render_body() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			$view = new View( [
				'content' => $page->get_main()->render(),
			] );

			echo $view->set_template( 'admin/wrap' )->render();
		}
	}

	public function load() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Registrable ) {
			$page->register();
		}
	}

}