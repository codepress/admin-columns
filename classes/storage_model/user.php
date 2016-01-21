<?php

class CPAC_Storage_Model_User extends CPAC_Storage_Model {

	/**
	 * @since 2.0
	 */
	public function __construct() {

		$this->key            = 'wp-users';
		$this->label          = __( 'Users' );
		$this->singular_label = __( 'User' );
		$this->type           = 'user';
		$this->meta_type      = 'user';
		$this->page           = 'users';
		$this->menu_type      = 'other';

		parent::__construct();
	}

	/**
	 * @since 2.4.9
	 */
	public function init_manage_columns() {

		add_filter( "manage_{$this->page}_columns", array( $this, 'add_headings' ), 100 );
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value_callback' ), 100, 3 );
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
	 * @see CPAC_Type::get_default_columns()
	 */
	public function get_default_columns() {

		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add third_party columns by hooking into this.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table   = _get_list_table( 'WP_Users_List_Table', array( 'screen' => 'users' ) );
		$columns = (array) $table->get_columns();

		if ( cac_is_setting_screen() ) {
			$columns = array_merge( get_column_headers( 'users' ), $columns );
		}

		return $columns;
	}

	/**
	 * @since 2.4.4
	 */
	public function get_default_column_names() {
		return array( 'cb', 'username', 'name', 'email', 'role', 'posts' );
	}

	/**
	 * @since 2.0.2
	 */
	public function manage_value( $column_name, $user_id, $value = '' ) {
		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return $value;
		}
		$custom_value = $column->get_display_value( $user_id );

		// make sure it absolutely empty and check for (string) 0
		if ( ! empty( $custom_value ) || '0' === $custom_value ) {
			$value = $custom_value;
		}

		// filters
		$value = apply_filters( "cac/column/value", $value, $user_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $user_id, $column, $this->key );

		return $value;
	}

	public function manage_value_callback( $value, $column_name, $user_id ) {
		return $this->manage_value( $column_name, $user_id, $value );
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
	}
}