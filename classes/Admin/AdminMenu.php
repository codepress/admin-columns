<?php

namespace AC\Admin;

use AC\Admin;
use AC\Deprecated\Hooks;

class AdminMenu {

	const QUERY_ARG_PAGE = 'page';
	const QUERY_ARG_TAB = 'tab';

	/**
	 * @var string
	 */
	private $parent_slug;

	public function __construct( $parent_slug ) {
		$this->parent_slug = $parent_slug;
	}

	public function render( $current ) {
		$menu = new Menu();

		// TODO
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
			$class = $current === $slug
				? 'nav-tab-active'
				: null;

			$menu->add( new Menu\Item( $this->create_menu_link( $slug ), $label, $class ) );
		}

		return $menu->render();
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
			$this->parent_slug
		);
	}

}