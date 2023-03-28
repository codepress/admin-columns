<?php

namespace AC\Admin\Section\Partial;

use AC\Admin\ListMenuFactory;
use AC\Admin\TableScreen;
use AC\View;

class Menu {

	private $is_network;

	public function __construct( bool $is_network = false ) {
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

	//	private function get_network_list_screens() {
	//		return ListScreenTypes::instance()->get_list_screens( [ 'network_only' => true ] );
	//	}

	//	private function get_site_list_screens() {
	//		return ListScreenTypes::instance()->get_list_screens( [ 'site_only' => true ] );
	//	}

	private function get_menu_items(): array {
		$groups = [];

		$menu_factory = ( new ListMenuFactory() )->create( false );

		// TODO
		$groups['post']['title'] = 'Post Type';

		foreach ( $menu_factory->get_items() as $key => $label ) {
			$groups['post']['options'][ $key ] = $label;
		}

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