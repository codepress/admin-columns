<?php

namespace AC\ListScreen;

use AC\ListScreenPost;
use WP_Posts_List_Table;

class Post extends ListScreenPost {

	public function __construct( $post_type ) {
		parent::__construct( $post_type );

		$this->set_screen_base( 'edit' )
		     ->set_group( 'post' )
		     ->set_key( $post_type )
		     ->set_screen_id( $this->get_screen_base() . '-' . $post_type );
	}

	/**
	 * @see WP_Posts_List_Table::column_default
	 */
	public function set_manage_value_callback() {
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @return WP_Posts_List_Table
	 */
	protected function get_list_table() {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );

		return new WP_Posts_List_Table( array( 'screen' => $this->get_screen_id() ) );
	}

	/**
	 * @since 2.0
	 */
	public function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), parent::get_screen_link() );
	}

	/**
	 * @return string|false
	 */
	public function get_label() {
		return $this->get_post_type_label_var( 'name' );
	}

	/**
	 * @return false|string
	 */
	public function get_singular_label() {
		return $this->get_post_type_label_var( 'singular_name' );
	}

	/**
	 * @since 2.4.7
	 *
	 * @param $column_name
	 * @param $id
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

	/**
	 * @throws \ReflectionException
	 */
	protected function register_column_types() {
		parent::register_column_types();

		$this->register_column_types_from_dir( 'AC\Column\Post' );
	}

}