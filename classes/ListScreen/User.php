<?php

class AC_ListScreen_User extends AC_ListScreenWP {

	public function __construct() {
		$this->label = __( 'Users' );
		$this->singular_label = __( 'User' );
		$this->type = 'user';
		$this->meta_type = 'user';
		$this->base = 'users';
		$this->list_table = 'WP_Users_List_Table';

		$this->set_screen_id( 'users' );
		$this->set_key( 'wp-users' );
		$this->set_group( 'user' );
	}

	/**
	 * @see set_manage_value_callback()
	 */
	public function set_manage_value_callback() {
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value' ), 100, 3 );
	}

	/**
	 * @since NEWVERSION
	 * @return string HTML
	 */
	public function get_single_row( $user_id ) {

		/* @var WP_Users_List_Table $table */
		$table = $this->get_list_table();

		return $table->single_row( get_userdata( $user_id ) );
	}

	/**
	 * @since 2.4.10
	 */
	public function is_current_screen( $wp_screen ) {
		return parent::is_current_screen( $wp_screen ) && 'delete' !== filter_input( INPUT_GET, 'action' );
	}

	/**
	 * @since 2.0.2
	 *
	 * @param string $value
	 * @param string $column_name
	 * @param int $user_id
	 */
	public function manage_value( $value, $column_name, $user_id ) {
		return $this->get_display_value_by_column_name( $column_name, $user_id, $value );
	}

}