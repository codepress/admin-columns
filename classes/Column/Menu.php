<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Column displaying the menus the item is used in. Supported by all object types that
 * can be referenced in menus (i.e. posts).
 * @since 2.2.5
 */
abstract class Menu extends Column {

	public function __construct() {
		$this->set_type( 'column-used_by_menu' );
		$this->set_label( __( 'Menu', 'codepress-admin-columns' ) );
	}

	/**
	 * @since 2.2.5
	 *
	 * @param $object_id
	 *
	 * @return array
	 */
	public function get_raw_value( $object_id ) {
		return $this->get_menus( $object_id, array( 'fields' => 'ids' ) );
	}

	/**
	 * @return string
	 */
	public abstract function get_object_type();

	/**
	 * @return string
	 */
	public abstract function get_item_type();

	/**
	 * @param int $object_id
	 *
	 * @return array
	 */
	public function get_menu_item_ids( $object_id ) {
		$menu_item_ids = get_posts( array(
			'post_type'      => 'nav_menu_item',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_menu_item_object_id',
					'value' => $object_id,
				),
				array(
					'key'   => '_menu_item_object',
					'value' => $this->get_object_type(),
				),
			),
		) );

		return $menu_item_ids;
	}

	/**
	 * @param int   $object_id
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_menus( $object_id, $args = array() ) {

		$menu_item_ids = $this->get_menu_item_ids( $object_id );

		if ( ! $menu_item_ids ) {
			return array();
		}

		$menu_ids = wp_get_object_terms( $menu_item_ids, 'nav_menu', $args );

		if ( ! $menu_ids || is_wp_error( $menu_ids ) ) {
			return array();
		}

		return $menu_ids;
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\LinkToMenu( $this ) );
	}

}