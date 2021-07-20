<?php

namespace AC\Column;

use AC;
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
	 * @param $object_id
	 *
	 * @return array
	 * @since 2.2.5
	 */
	public function get_raw_value( $object_id ) {
		return $this->get_menus( $object_id, [ 'fields' => 'ids', 'orderby' => 'name' ] );
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
		$helper = new AC\Helper\Menu();

		return $helper->get_ids( $object_id, $this->get_object_type() );
	}

	/**
	 * @param int   $object_id
	 * @param array $args
	 *
	 * @return array
	 */
	public function get_menus( $object_id, array $args = [] ) {
		$helper = new AC\Helper\Menu();

		return $helper->get_terms( $helper->get_ids( $object_id, $this->get_object_type() ), $args );
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\LinkToMenu( $this ) );
	}

}