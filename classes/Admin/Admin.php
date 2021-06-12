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

	/**
	 * @var AdminScripts
	 */
	private $scripts;

	/**
	 * @var string
	 */
	private $hook;

	public function __construct( RequestHandlerInterface $request_handler, WpMenuFactory $wp_menu_factory, AdminScripts $scripts ) {
		$this->request_handler = $request_handler;
		$this->wp_menu_factory = $wp_menu_factory;
		$this->scripts = $scripts;
	}

	public function register() {
		add_action( 'admin_menu', [ $this, 'init' ] );
	}

	public function init() {
		$this->hook = $this->wp_menu_factory->create_sub_menu( 'options-general.php' );

		add_action( 'in_admin_header', [ $this, 'head' ] );
		add_action( $this->hook, [ $this, 'body' ] );
		add_action( 'load-' . $this->hook, [ $this, 'load' ] );
	}

	public function head() {
		global $plugin_page;

		if ( $plugin_page !== self::NAME ) {
			return;
		}

		$page = $this->request_handler->handle( new Request() );

		if ( $page ) {
			echo $page->get_head()->render();
		}
	}

	public function body() {
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

		foreach ( $this->scripts->get_assets()->all() as $asset ) {
			$asset->enqueue();
		}
	}

}