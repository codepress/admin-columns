<?php

class AC_ListScreen_Post extends AC_ListScreenPost {

	public function __construct( $post_type ) {
		parent::__construct( $post_type );

		$this->set_screen_base( 'edit' );
		$this->set_list_table_class( 'WP_Posts_List_Table' );
		$this->set_group( 'post' );

		$this->set_key( $post_type );
		$this->set_screen_id( $this->get_screen_base() . '-' . $post_type );
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}

	public function set_manage_value_callback() {
		/* @see WP_Posts_List_Table::column_default */
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
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
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}