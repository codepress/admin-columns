<?php
defined( 'ABSPATH' ) or die();

class CPAC_Storage_Model_User extends CPAC_Storage_Model {

	/**
	 * @since 2.0
	 */
	public function __construct() {

		$this->key = 'wp-users';
		$this->label = __( 'Users' );
		$this->singular_label = __( 'User' );
		$this->type = 'user';
		$this->meta_type = 'user';
		$this->page = 'users';
		$this->table_classname = 'WP_Users_List_Table';

		parent::__construct();
	}

	/**
	 * @since NEWVERSION
	 */
	protected function get_object_by_id( $id ) {
		return get_userdata( $id );
	}

	/**
	 * @since 2.4.9
	 */
	public function init_column_values() {
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value_callback' ), 100, 3 );
	}

	/**
	 * @since 2.4.10
	 */
	public function is_current_screen() {
		return ! is_network_admin() && parent::is_current_screen() && ( 'delete' !== filter_input( INPUT_GET, 'action' ) );
	}

	/**
	 * @since 2.4.7
	 */
	public function get_original_column_value( $column, $id ) {

		// Remove Admin Columns action for this column's value
		remove_action( "manage_users_custom_column", array( $this, 'manage_value_callback' ), 100, 3 );
		ob_start();
		do_action( "manage_users_custom_column", $column, $id );
		$contents = ob_get_clean();
		add_action( "manage_users_custom_column", array( $this, 'manage_value_callback' ), 100, 3 );

		return $contents;
	}

	/**
	 * @since 2.4.4
	 */
	public function get_default_column_names() {
		return array( 'cb', 'username', 'name', 'email', 'role', 'posts' );
	}

	/**
	 * @since 2.5
	 */
	protected function get_default_column_widths() {
		return array(
			'role'  => array( 'width' => 15 ),
			'posts' => array( 'width' => 74, 'unit' => 'px' ),
		);
	}

	/**
	 * @since 2.0.2
	 */
	public function manage_value( $column_name, $user_id, $value = '' ) {
		return $this->get_manage_value( $column_name, $user_id, $value );
	}

	public function manage_value_callback( $value, $column_name, $user_id ) {
		return $this->manage_value( $column_name, $user_id, $value );
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
	}
}