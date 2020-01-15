<?php

namespace AC\Admin\Section;

use AC\Controller\ListScreenRequest;
use AC\ListScreenGroups;
use AC\ListScreenTypes;
use AC\View;

class ListScreenMenu {

	/** @var ListScreenRequest */
	private $controller;

	public function __construct( ListScreenRequest $controller ) {
		$this->controller = $controller;
	}

	public function display() {
		$list_screen = $this->controller->get_list_screen();

		$menu = new View( [
			'items'       => $this->get_grouped_list_screens(),
			'current'     => $list_screen->get_key(),
			'screen_link' => $list_screen->get_screen_link(),
		] );

		$menu->set_template( 'admin/edit-menu' );

		echo $menu->render();
	}

	/**
	 * @return array
	 */
	private function get_grouped_list_screens() {
		$list_screens = [];

		foreach ( ListScreenTypes::instance()->get_list_screens() as $list_screen ) {
			$list_screens[ $list_screen->get_group() ][ $list_screen->get_key() ] = $list_screen->get_label();
		}

		$grouped = [];

		foreach ( ListScreenGroups::get_groups()->get_groups_sorted() as $group ) {
			$slug = $group['slug'];

			if ( empty( $list_screens[ $slug ] ) ) {
				continue;
			}

			if ( ! isset( $grouped[ $slug ] ) ) {
				$grouped[ $slug ]['title'] = $group['label'];
			}

			natcasesort( $list_screens[ $slug ] );

			$grouped[ $slug ]['options'] = $list_screens[ $slug ];

			unset( $list_screens[ $slug ] );
		}

		return $grouped;
	}

}