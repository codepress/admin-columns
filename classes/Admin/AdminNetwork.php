<?php

namespace AC\Admin;

use AC\Asset\Location\Absolute;
use AC\Registrable;

class AdminNetwork implements Registrable {

	/**
	 * @var RequestHandlerInterface
	 */
	private $request_handler;

	/**
	 * @var Absolute
	 */
	private $location_core;

	/**
	 * @var AdminScripts
	 */
	private $scripts;

	public function __construct( RequestHandlerInterface $request_handler, Absolute $location_core, AdminScripts $scripts ) {
		$this->request_handler = $request_handler;
		$this->location_core = $location_core;
		$this->scripts = $scripts;
	}

	public function register() {
		add_action( 'network_admin_menu', [ $this, 'init' ] );
	}

	private function get_menu_page_factory() {
		return apply_filters(
			'acp/menu_network_page_factory',
			new MenuPageFactory\SubMenu()
		);
	}

	public function init() {
		$hook = $this->get_menu_page_factory()->create( [
			'parent' => 'settings.php',
			'icon'   => $this->location_core->with_suffix( 'assets/images/page-menu-icon.svg' )->get_url(),
		] );

		$loader = new AdminLoader( $hook, $this->request_handler, $this->scripts );
		$loader->register();
	}

}