<?php

namespace AC\Admin\View;

use AC\Admin;
use AC\View;

class Menu extends View {

	public function __construct( Admin\Menu $menu ) {
		parent::__construct( [ 'menu_items' => $menu->get_items() ] );

		$this->set_template( 'admin/menu' );
	}

}