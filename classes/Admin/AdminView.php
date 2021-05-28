<?php

namespace AC\Admin;

use AC\Renderable;
use AC\Request;

class AdminView implements Renderable {

	/**
	 * @var PageRequestHandler
	 */
	private $request_handler;

	/**
	 * @var AdminMenu
	 */
	private $admin_menu;

	public function __construct( PageRequestHandler $request_handler, AdminMenu $admin_menu ) {
		$this->request_handler = $request_handler;
		$this->admin_menu = $admin_menu;
	}

	public function render() {
		$page = $this->request_handler->handle( new Request() );
		?>
		<div id="cpac" class="wrap">

			<?= $this->admin_menu->render( $page->get_slug() ) ?>
			<?= $page->render() ?>

		</div>
		<?php
	}

}