<?php

namespace AC\Settings\Column;

use AC\Settings;

class LinkToMenu extends Settings\Column\Toggle
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $link_to_menu;

	protected function define_options() {
		return array(
			'link_to_menu' => 'on',
		);
	}

	public function create_view() {
		$view = parent::create_view();

		$view->set_data( array(
			'label'   => __( 'Link to menu', 'codepress-admin-columns' ),
			'tooltip' => __( 'This will make the title link to the menu.', 'codepress-admin-columns' ),
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_link_to_menu() {
		return $this->link_to_menu;
	}

	/**
	 * @param string $link_to_menu
	 *
	 * @return bool
	 */
	public function set_link_to_menu( $link_to_menu ) {
		$this->link_to_menu = $link_to_menu;

		return true;
	}

	/**
	 * @param int[] $menu_ids
	 * @param mixed $original_value
	 *
	 * @return false|string
	 */
	public function format( $menu_ids, $original_value ) {
		if ( ! $menu_ids ) {
			return $this->column->get_empty_char();
		}

		$values = array();

		foreach ( $menu_ids as $menu_id ) {
			$term = get_term_by( 'id', $menu_id, 'nav_menu' );

			if ( 'on' === $this->get_link_to_menu() ) {
				$term->name = ac_helper()->html->link( add_query_arg( array( 'menu' => $menu_id ), admin_url( 'nav-menus.php' ) ), $term->name );
			}

			$values[] = $term->name;
		}

		return wp_sprintf( '%l', $values );
	}

}