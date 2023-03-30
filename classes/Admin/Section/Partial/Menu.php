<?php

namespace AC\Admin\Section\Partial;

use AC\Admin\MenuListFactory;
use AC\View;

class Menu {

	private $menu_factory;

	private $is_network;

	public function __construct( MenuListFactory $menu_factory, bool $is_network = false ) {
		$this->menu_factory = $menu_factory;
		$this->is_network = $is_network;
	}

	public function render( string $current, string $url, $is_hidden = false ): string {
		$menu = new View( [
			'items'       => $this->get_menu_items(),
			'current'     => $current,
			'screen_link' => $url,
			'class'       => $is_hidden ? 'hidden' : '',
		] );

		$menu->set_template( 'admin/edit-menu' );

		return $menu->render();
	}

	private function get_menu_items(): array {
		$groups = [];

		foreach ( $this->menu_factory->create()->all() as $menu_item ) {
			$group = $menu_item->get_group();

			if ( ! isset( $groups[ $group ] ) ) {
				$groups[ $group ]['title'] = $group;
			}

			$groups[ $group ]['options'][ $menu_item->get_key() ] = $menu_item->get_label();
		}

		// TODO sort

		return $groups;
	}

	/**
	 * @return array
	 */
	// TODO remove
	//	private function xget_menu_items() {
	//
	//		$list_screens = $this->is_network
	//			? $this->get_network_list_screens()
	//			: $this->get_site_list_screens();
	//
	//		$list_screens_grouped = [];
	//		foreach ( $list_screens as $list_screen ) {
	//			$list_screens_grouped[ $list_screen->get_group() ][ $list_screen->get_key() ] = $list_screen->get_label();
	//		}
	//
	//		$grouped = [];
	//
	//		foreach ( ListScreenGroups::get_groups()->get_groups_sorted() as $group ) {
	//			$slug = $group['slug'];
	//
	//			if ( empty( $list_screens_grouped[ $slug ] ) ) {
	//				continue;
	//			}
	//
	//			if ( ! isset( $grouped[ $slug ] ) ) {
	//				$grouped[ $slug ]['title'] = $group['label'];
	//			}
	//
	//			natcasesort( $list_screens_grouped[ $slug ] );
	//
	//			$grouped[ $slug ]['options'] = $list_screens_grouped[ $slug ];
	//
	//			unset( $list_screens_grouped[ $slug ] );
	//		}
	//		echo '<pre>';
	//		print_r( $grouped );
	//		echo '</pre>';
	//		exit;
	//
	//		return $grouped;
	//	}

}