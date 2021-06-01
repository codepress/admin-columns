<?php

namespace AC\Admin;

use AC\Admin;
use AC\Deprecated\Hooks;

class AdminPageMenuFactory {

	const QUERY_ARG_PAGE = 'page';
	const QUERY_ARG_TAB = 'tab';

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
		$menu = new Menu();

		$pages = [
			Page\Columns::NAME  => __( 'Columns', 'codepress-admin-columns' ),
			Page\Settings::NAME => __( 'Settings', 'codepress-admin-columns' ),
			Page\Addons::NAME   => __( 'Add-ons', 'codepress-admin-columns' ),
		];

		$hooks = new Hooks();

		if ( $hooks->get_count() > 0 ) {
			$pages[ Page\Help::NAME ] = sprintf( '%s %s', __( 'Help', 'codepress-admin-columns' ), $hooks->get_count() );
		}

		foreach ( $pages as $slug => $label ) {
			$menu->add( $this->create_menu_item( $slug, $label ) );
		}

		return $menu;
	}

	protected function create_menu_item( $slug, $label ) {
		return new Menu\Item( $slug, $label, $this->create_menu_link( $slug ) );
	}

	/**
	 * @param string $slug
	 *
	 * @return string
	 */
	protected function create_menu_link( $slug ) {
		return add_query_arg(
			[
				self::QUERY_ARG_PAGE => Admin::NAME,
				self::QUERY_ARG_TAB  => $slug,
			],
			$this->url
		);
	}

}