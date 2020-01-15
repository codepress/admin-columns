<?php

namespace AC\Admin\Section;

use AC\ListScreen;
use AC\ListScreenGroups;
use AC\ListScreenTypes;
use AC\View;

class ListScreenMenu {

	/**
	 * @var ListScreenTypes[];
	 */
	private $list_screens;

	/**
	 * @var ListScreen
	 */
	private $current_list_screen;

	public function __construct( ListScreenTypes $list_screens, ListScreen $current ) {
		$this->list_screens = $list_screens;
		$this->current_list_screen = $current;
	}

	public function display() {
		$menu = new View( [
			'items'       => $this->get_grouped_list_screens(),
			'current'     => $this->current_list_screen->get_key(),
			'screen_link' => $this->current_list_screen->get_screen_link(),
		] );

		$menu->set_template( 'admin/edit-menu' );

		echo $menu->render();
	}

	/**
	 * @return array
	 */
	private function get_grouped_list_screens() {
		$list_screens = array();

		foreach ( $this->list_screens->get_list_screens() as $list_screen ) {
			$list_screens[ $list_screen->get_group() ][ $list_screen->get_key() ] = $list_screen->get_label();
		}

		$grouped = array();

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