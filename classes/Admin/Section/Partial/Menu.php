<?php

namespace AC\Admin\Section\Partial;

use AC\Controller\ListScreenRequest;
use AC\ListScreenGroups;
use AC\ListScreenTypes;
use AC\View;

class Menu {

	/** @var ListScreenRequest */
	private $controller;

	/** @var bool */
	private $is_network;

	public function __construct( ListScreenRequest $controller, $is_network = false ) {
		$this->controller = $controller;
		$this->is_network = (bool) $is_network;
	}

	public function render( $is_hidden = false ) {
		$list_screen = $this->controller->get_list_screen();

		$menu = new View( [
			'items'       => $this->get_grouped_list_screens(),
			'current'     => $list_screen->get_key(),
			'screen_link' => $list_screen->get_screen_link(),
			'class'       => $is_hidden ? 'hidden' : '',
		] );

		$menu->set_template( 'admin/edit-menu' );

		return $menu->render();
	}

	private function get_network_list_screens() {
		return ListScreenTypes::instance()->get_list_screens( [ 'network_only' => true ] );
	}

	private function get_site_list_screens() {
		return ListScreenTypes::instance()->get_list_screens( [ 'site_only' => true ] );
	}

	/**
	 * @return array
	 */
	private function get_grouped_list_screens() {

		$list_screens = $this->is_network
			? $this->get_network_list_screens()
			: $this->get_site_list_screens();

		$list_screens_grouped = [];
		foreach ( $list_screens as $list_screen ) {
			$list_screens_grouped[ $list_screen->get_group() ][ $list_screen->get_key() ] = $list_screen->get_label();
		}

		$grouped = [];

		foreach ( ListScreenGroups::get_groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			if ( empty( $list_screens_grouped[ $slug ] ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			natcasesort( $list_screens_grouped[ $slug ] );

			$grouped[ $slug ]['options'] = $list_screens_grouped[ $slug ];

			unset( $list_screens_grouped[ $slug ] );
		}

		return $grouped;
	}

}