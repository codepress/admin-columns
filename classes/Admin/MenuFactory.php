<?php

namespace AC\Admin;

use AC\Admin\Menu\Item;
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
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				RequestHandler::PARAM_PAGE => Admin::NAME,
				RequestHandler::PARAM_TAB  => $slug,
			],
			$this->url
		);
	}

	public function create( $current ) {
		$menu = new Menu();

		$items = [
			Main\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Main\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Main\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$items[ Main\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), '<span class="ac-badge">' . $hooks->get_count() . '</span>' );
		}

		foreach ( $items as $slug => $label ) {
			$menu->add_item( new Item( $this->create_menu_link( $slug ), $label, $current === $slug ? '-active' : '' ) );
		}

		return $menu;
	}

}