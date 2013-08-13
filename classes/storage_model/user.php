<?php

class CPAC_Storage_Model_User extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {

		$this->key 		= 'wp-users';
		$this->label 	= __( 'Users' );
		$this->type 	= 'user';
		$this->page 	= 'users';

		$this->set_custom_columns();

		// Populate columns variable.
		// This is used for manage_value. By storing these columns we greatly improve performance.
		add_action( 'admin_init', array( $this, 'set_columns' ) );

		// headings
		add_filter( "manage_{$this->page}_columns",  array( $this, 'add_headings' ) );

		// values
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value' ), 10, 3 );

		//@todo: remove parent::__construct();
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		// You can use this filter to add third_party columns by hooking into this.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table 		= _get_list_table( 'WP_Users_List_Table', array( 'screen' => 'users' ) );
		$columns 	= $table->get_columns();

		return $columns;
	}

	/**
	 * Manage value
	 *
	 * @since 2.0.0
	 *
	 * @param string $value
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function manage_value( $value, $column_name, $user_id ) {

		// get column instance
		$column = $this->get_column_by_name( $column_name );

		if ( ! $column )
			return $value;

		// get value
		$custom_value = $column->get_value( $user_id );

		// get value
		// make sure it absolutely empty and check for (string) 0
		if ( ! empty( $custom_value ) || $custom_value === '0' ) {
			$value = $custom_value;
		}

		return apply_filters( "cac/column/value/type={$this->key}", $value, $column );
	}

	/**
     * Get Meta
     *
	 * @see CPAC_Columns::get_meta_keys()
	 * @since 2.0.0
	 *
	 * @return array
     */
    public function get_meta() {
        global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
    }
}