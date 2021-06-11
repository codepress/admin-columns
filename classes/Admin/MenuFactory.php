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

		$menu->add_item( Main\Columns::NAME, __( 'Columns', 'codepress-admin-columns' ) )
		     ->add_item( Main\Settings::NAME, __( 'Settings', 'codepress-admin-columns' ) )
		     ->add_item( Main\Addons::NAME, __( 'Add-ons', 'codepress-admin-columns' ) );

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$menu->add_item( Main\Help::NAME, sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' ) );
		}

		return $menu;
	}

}