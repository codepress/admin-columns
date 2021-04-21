<?php

namespace AC\ListScreen;

use AC;
use AC\WpListTableFactory;
use ReflectionException;
use WP_User;
use WP_Users_List_Table;

class User extends AC\ListScreenWP {

	public function __construct() {

		$this->set_label( __( 'Users' ) )
		     ->set_singular_label( __( 'User' ) )
		     ->set_meta_type( AC\MetaType::USER )
		     ->set_screen_base( 'users' )
		     ->set_screen_id( 'users' )
		     ->set_key( 'wp-users' )
		     ->set_group( 'user' );
	}

	/**
	 * @see set_manage_value_callback()
	 */
	public function set_manage_value_callback() {
		add_filter( 'manage_users_custom_column', [ $this, 'manage_value' ], 100, 3 );
	}

	/**
	 * @param $wp_screen
	 *
	 * @return bool
	 * @since 2.4.10
	 */
	public function is_current_screen( $wp_screen ) {
		return parent::is_current_screen( $wp_screen ) && 'delete' !== filter_input( INPUT_GET, 'action' );
	}

	/**
	 * @param string $value
	 * @param string $column_name
	 * @param int    $user_id
	 *
	 * @return string
	 * @since 2.0.2
	 */
	public function manage_value( $value, $column_name, $user_id ) {
		return $this->get_display_value_by_column_name( $column_name, $user_id, $value );
	}

	/**
	 * @param int $id
	 *
	 * @return WP_User
	 */
	protected function get_object( $id ) {
		return get_userdata( $id );
	}

	/**
	 * @param int $id
	 *
	 * @return string HTML
	 * @since 3.0
	 */
	public function get_single_row( $id ) {
		return $this->get_list_table()->single_row( $this->get_object( $id ) );
	}

	/**
	 * @throws ReflectionException
	 */
	protected function register_column_types() {
		$this->register_column_type( new AC\Column\CustomField );
		$this->register_column_type( new AC\Column\Actions );

		$this->register_column_types_from_dir( 'AC\Column\User' );
	}

	/**
	 * @return WP_Users_List_Table
	 */
	protected function get_list_table() {
		return ( new WpListTableFactory() )->create_user_table( $this->get_screen_id() );
	}

}