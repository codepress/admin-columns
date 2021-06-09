<?php

namespace AC\Admin;

use AC\Admin;
use AC\Capabilities;

class WpMenuFactory {

	/**
	 * @param string $parent_menu
	 * @param int    $position
	 *
	 * @return string
	 */
	public function create_sub_menu( $parent_menu, $position = null ) {
		return add_submenu_page(
			$parent_menu,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			Admin\Admin::NAME,
			null,
			$position
		);
	}

	/**
	 * @param string $icon
	 * @param int    $position
	 */
	public function create_menu( $icon = null, $position = null ) {
		return add_menu_page(
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			Admin\Admin::NAME,
			null,
			$icon,
			$position
		);
	}

}