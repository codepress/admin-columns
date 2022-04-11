<?php

namespace AC\Admin;

use AC\Asset\Location\Absolute;
use AC\Registrable;

class Admin implements Registrable {

	const NAME = 'codepress-admin-columns';

	/**
	 * @var RequestHandlerInterface
	 */
	private $request_handler;

	/**
	 * @var Absolute
	 */
	private $location;

	/**
	 * @var AdminScripts
	 */
	private $scripts;

	public function __construct( RequestHandlerInterface $request_handler, Absolute $location, AdminScripts $scripts ) {
		$this->request_handler = $request_handler;
		$this->location = $location;
		$this->scripts = $scripts;
	}

	public function register() {
		add_action( 'admin_menu', [ $this, 'init' ] );
	}

	private function get_menu_page_factory() {
		return apply_filters(
			'ac/menu_page_factory',
			new MenuPageFactory\SubMenu()
		);
	}

	public function init() {
		$hook = $this->get_menu_page_factory()->create( [
			'parent' => 'options-general.php',
			'icon'   => $this->location->with_suffix( 'assets/images/page-menu-icon.svg' )->get_url(),
		] );

		$loader = new AdminLoader( $hook, $this->request_handler, $this->scripts );
		$loader->register();
	}

}