<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying the menus the item is used in. Supported by all object types that
 * can be referenced in menus (i.e. posts).
 *
 * @since 2.2.5
 */
abstract class AC_Column_UsedByMenuAbstract extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-used_by_menu';
		$this->properties['label'] = __( 'Used by Menu', 'codepress-admin-columns' );

		$this->options['link_to_menu'] = false;
	}

	function apply_conditional() {
		return $this->get_meta_type() ? true : false;
	}

	function get_value( $object_id ) {
		$menus = array();

		if ( $menu_ids = $this->get_raw_value( $object_id ) ) {
			foreach ( $menu_ids as $menu_id ) {
				$term = get_term_by( 'id', $menu_id, 'nav_menu' );

				$title = $term->name;
				if ( 'on' == $this->get_option( 'link_to_menu' ) ) {
					$title = '<a href="' . esc_url( add_query_arg( array( 'menu' => $menu_id ), admin_url( 'nav-menus.php' ) ) ) . '">' . $term->name . '</a>';
				}

				$menus[] = $title;
			}
		}

		return implode( ', ', $menus );
	}

	function get_formatted_value( $object_id ){
		$menus = array();

		if ( $menu_ids = $this->get_raw_value( $object_id ) ) {
			foreach ( $menu_ids as $menu_id ) {
				$term = get_term_by( 'id', $menu_id, 'nav_menu' );
				$menus[] = $term->name;
			}
		}

		return implode( ', ', $menus );
	}

	/**
	 * Get object meta type of the storage model
	 *
	 * @since 2.2.5
	 */
	function get_meta_type() {
		$object_type = false;
		$model = $this->get_storage_model();
		if ( isset( $model->taxonomy ) ) {
			$object_type = $model->taxonomy;
		}
		elseif ( $post_type = $this->get_post_type() ) {
			$object_type = $post_type;
		}

		return $object_type;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.2.5
	 */
	function get_raw_value( $object_id ) {
		$object_type = $this->get_meta_type();

		$menu_item_ids = get_posts( array(
			'post_type'   => 'nav_menu_item',
			'numberposts' => -1,
			'post_status' => 'publish',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'key'   => '_menu_item_object_id',
					'value' => $object_id
				),
				array(
					'key'   => '_menu_item_object',
					'value' => $object_type
				),
			)
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

	public function display_settings() {
		$this->settings()->field( array(
			'type'        => 'radio',
			'name'        => 'link_to_menu',
			'label'       => __( 'Link to menu', 'codepress-admin-columns' ),
			'description' => __( 'This will make the title link to the menu.', 'codepress-admin-columns' ),
			'options'     => array(
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			),
			'default'     => 'off'
		) );
	}

}