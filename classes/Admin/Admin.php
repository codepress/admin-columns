<?php

namespace AC\Admin;

use AC\Registrable;
use AC\Request;

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

		add_action( $hook, [ $this, 'render' ] );
		add_action( 'load-' . $hook, [ $this, 'load' ] );
	}

	public function render() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			echo $page->render();
		}
	}

	public function load() {
		$page = $this->request_handler->handle( new Request() );

		if ( $page instanceof Registrable ) {
			$page->register();
		}
	}

}