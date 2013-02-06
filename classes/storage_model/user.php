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
		
		// headings; give higher priority, so it will load just before other plugins to prevent conflicts
		add_filter( "manage_users_columns",  array( $this, 'add_headings' ), 9 );	
		
		// values
		add_filter( 'manage_users_custom_column', array( $this, 'manage_value' ), 10, 3 );
		
		parent::__construct();
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
		do_action( "cpac_before_default_columns_{$this->key}" );

		// Dependencies
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );
		
		
		// @todo set 'screen'
		$table 		= new WP_Users_List_Table( array( 'screen' => 'users' ) );
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
				
		// get value
		if ( $column && $custom_value = $column->get_value( $user_id ) ) {
			$value = $custom_value;
		}
				
		return apply_filters( "cpac_value_{$this->key}", $value, $column );
	}
}