<?php

/**
 * Column displaying the menus the item is used in. Supported by all object types that
 * can be referenced in menus (i.e. posts).
 *
 * @since 2.2.5
 */
class AC_Column_UsedByMenu extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-used_by_menu' );
		$this->set_label( __( 'Used by Menu', 'codepress-admin-columns' ) );
	}

	public function get_value( $object_id ) {
		$menus = new AC_Collection();

		if ( $menu_ids = $this->get_raw_value( $object_id ) ) {
			foreach ( $menu_ids as $menu_id ) {
				$term = get_term_by( 'id', $menu_id, 'nav_menu' );
				$title = $term->name;

				if ( 'on' === $this->get_option( 'link_to_menu' ) ) {
					$title = ac_helper()->html->link( add_query_arg( array( 'menu' => $menu_id ), admin_url( 'nav-menus.php' ) ), $term->name );
				}

				$menus->push(  new AC_Value( $title ) );
			}
		}

		return $this->format_value( $menus );
	}

	/**
	 * @see AC_Column::get_raw_value()
	 * @since 2.2.5
	 */
	function get_raw_value( $object_id ) {

		$object_type = $this->get_post_type();

		if ( ! $object_type ) {
			$object_type = $this->get_taxonomy();
		}

		if ( ! $object_type ) {
			$object_type = $this->get_list_screen()->get_meta_type();
		}

		$menu_item_ids = get_posts( array(
			'post_type'   => 'nav_menu_item',
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'key'   => '_menu_item_object_id',
					'value' => $object_id,
				),
				array(
					'key'   => '_menu_item_object',
					'value' => $object_type,
				),
			),
		) );

		if ( ! $menu_item_ids ) {
			return false;
		}

		$menu_ids = wp_get_object_terms( $menu_item_ids, 'nav_menu', array( 'fields' => 'ids' ) );
		if ( ! $menu_ids || is_wp_error( $menu_ids ) ) {
			return false;
		}

		return $menu_ids;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_LinkToMenu( $this ) );
	}

}