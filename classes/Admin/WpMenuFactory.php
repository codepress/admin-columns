<?php

namespace AC\Admin;

use AC\Admin;

class WpMenuFactory {

	/**
	 * @param string $parent_menu
	 * @param Admin  $admin
	 * @param int    $position
	 */
	public function create_sub_menu( $parent_menu, Admin $admin, $position = null ) {
		$hook = add_submenu_page(
			$parent_menu,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			$admin->get_capability(),
			$admin->get_slug(),
			[ $admin, 'render' ],
			$position
		);

		add_action( "load-" . $hook, [ $admin, 'load' ] );
	}

	/**
	 * @param Admin  $admin
	 * @param string $icon
	 * @param int    $position
	 */
	public function create_menu( Admin $admin, $icon = null, $position = null ) {
		$hook = add_menu_page(
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			$admin->get_capability(),
			$admin->get_slug(),
			[ $admin, 'render' ],
			$icon,
			$position
		);

		add_action( "load-" . $hook, [ $admin, 'load' ] );
	}

}