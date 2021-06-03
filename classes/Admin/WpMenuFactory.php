<?php

namespace AC\Admin;

use AC\Admin;
use AC\Capabilities;
use AC\Registrable;
use AC\Renderable;

class WpMenuFactory {

	/**
	 * @param string     $parent_menu
	 * @param Renderable $page
	 * @param int        $position
	 */
	public function create_sub_menu( $parent_menu, Renderable $page, $position = null ) {
		$hook = add_submenu_page(
			$parent_menu,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			Admin::NAME,
			[ $page, 'render' ],
			$position
		);

		if ( $page instanceof Registrable ) {
			add_action( "load-" . $hook, [ $page, 'register' ] );
		}
	}

	/**
	 * @param Renderable $page
	 * @param string     $icon
	 * @param int        $position
	 */
	public function create_menu( Renderable $page, $icon = null, $position = null ) {
		$hook = add_menu_page(
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			Admin::NAME,
			[ $page, 'render' ],
			$icon,
			$position
		);

		if ( $page instanceof Registrable ) {
			add_action( "load-" . $hook, [ $page, 'register' ] );
		}
	}

}