<?php

namespace AC\Admin;

use AC\Deprecated\Hooks;

class MenuFactory implements MenuFactoryInterface {

	/**
	 * @var string
	 */
	protected $url;

	public function __construct( $url ) {
		$this->url = $url;
	}

	/**
	 * @return Menu
	 */
	public function create() {
		$menu = new Menu( $this->url );

		$pages = [
			Page\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Page\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Page\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$pages[ Page\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' );
		}

		foreach ( $pages as $slug => $label ) {
			$menu->add_item( $slug, $label );
		}

		return $menu;
	}

}