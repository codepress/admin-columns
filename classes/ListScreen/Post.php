<?php

class AC_ListScreen_Post extends AC_ListScreenPost {

	public function __construct( $post_type ) {
		parent::__construct( $post_type );

		$this->set_screen_base( 'edit' );
		$this->set_group( 'post' );
		$this->set_key( $post_type );
		$this->set_screen_id( $this->get_screen_base() . '-' . $post_type );

		/* @see WP_Posts_List_Table */
		$this->set_list_table_class( 'WP_Posts_List_Table' );
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

	protected function register_column_types() {
		$this->register_column_type( new AC_Column_CustomField() );
		$this->register_column_type( new AC_Column_UsedByMenu() );
		$this->register_column_type( new AC_Column_Actions() );

		$this->register_column_types_from_dir( AC()->get_plugin_dir() . 'classes/Column/Post', 'AC_' );
	}

}