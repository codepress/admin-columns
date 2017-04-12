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
		$this->set_empty_char( true );
	}

	/**
	 * @see   AC_Column::get_raw_value()
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

	public function is_valid() {
		return in_array( $this->get_list_screen()->get_meta_type(), array( 'post', 'user', 'term', 'comment' ) );
	}

}