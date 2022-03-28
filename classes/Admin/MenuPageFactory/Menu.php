<?php

namespace AC\Admin\MenuPageFactory;

use AC\Admin\Admin;
use AC\Admin\MenuPageFactory;
use AC\Capabilities;

class Menu implements MenuPageFactory {

	public function create( array $args = [] ) {
		return add_menu_page(
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			Admin::NAME,
			null,
			isset( $args['icon'] ) ? $args['icon'] : null,
			isset( $args['position'] ) ? $args['position'] : null
		);
	}

}