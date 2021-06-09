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

	public function create( $current ) {
		$menu = new Menu( $this->url, $current );

		$pages = [
			Main\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Main\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Main\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$pages[ Main\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' );
		}

		foreach ( $pages as $slug => $label ) {
			$menu->add_item( $slug, $label );
		}

		return $menu;
	}

}