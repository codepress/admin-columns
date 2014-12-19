<?php

class CPAC_Storage_Model_User extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0
	 */
	function __construct() {

		$this->key 		 = 'wp-users';
		$this->label 	 = __( 'Users' );
		$this->type 	 = 'user';
		$this->meta_type = 'user';
		$this->page 	 = 'users';
		$this->menu_type = 'other';

		// headings
		add_filter( "manage_{$this->page}_columns",  array( $this, 'add_headings' ), 100 );

		// values
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value_callback' ), 100, 3 );

		parent::__construct();
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_default_columns() {

		if ( ! function_exists('_get_list_table') ) {
			return array();
		}

		// You can use this filter to add third_party columns by hooking into this.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		// get columns
		$table 		= _get_list_table( 'WP_Users_List_Table', array( 'screen' => 'users' ) );
		$columns 	= (array) $table->get_columns();

		if ( $this->is_settings_page() ) {
			$columns = array_merge( get_column_headers( 'users' ), $columns );
		}

		return $columns;
	}

	/**
	 * Manage value
	 *
	 * @since 2.0.2
	 *
	 * @param string $column_name
	 * @param int $user_id
	 * @param string $value
	 */
	function manage_value( $column_name, $user_id, $value = '' ) {

		// get column instance
		$column = $this->get_column_by_name( $column_name );

		if ( ! $column )
			return $value;

		// get value
		$custom_value = $column->get_value( $user_id );

		// make sure it absolutely empty and check for (string) 0
		if ( ! empty( $custom_value ) || '0' === $custom_value ) {
			$value = $custom_value;
		}

		// filters
		$value = apply_filters( "cac/column/value", $value, $user_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $user_id, $column, $this->key );

		return $value;
	}

	/**
	 * Callback Manage value
	 *
	 * @since 2.0.2
	 *
	 * @param string $value
	 * @param string $column_name
	 * @param int $user_id
	 */
	public function manage_value_callback( $value, $column_name, $user_id ) {

		return $this->manage_value( $column_name, $user_id, $value );
	}

	/**
     * Get Meta
     *
	 * @see CPAC_Columns::get_meta_keys()
	 * @since 2.0
	 *
	 * @return array
     */
    public function get_meta() {
        global $wpdb;

        if ( $cache = wp_cache_get( $this->key, 'cac_columns' ) ) {
        	$result = $cache;
        }
        else {
			$result = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1", ARRAY_N );
			wp_cache_add( $this->key, $result, 'cac_columns', 10 ); // 10 sec.
		}

		return $result;
    }
}