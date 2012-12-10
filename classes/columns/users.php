<?php

class cpac_columns_users extends cpac_columns
{
	public function __construct()
    {
		$this->type = 'wp-users';
    }
	
	/**
	 * 	Get WP default users columns per post type.
	 *
	 * 	@since     1.1
	 */
	function get_default_columns()
	{
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php');
		
		// turn off site users
		$this->is_site_users = false;
		
		// get users columns
		$columns = WP_Users_List_Table::get_columns();
		
		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		return apply_filters('cpac-default-users-columns', $columns);
	}
	
	/**
	 * Custom users columns
	 *
	 * @since     1.1
	 */
	function get_custom_columns() 
	{
		$custom_columns = array(
			'column-user_id' => array(
				'label'	=> __('User ID', CPAC_TEXTDOMAIN)
			),
			'column-nickname' => array(
				'label'	=> __('Nickname', CPAC_TEXTDOMAIN)
			),
			'column-first_name' => array(
				'label'	=> __('First name', CPAC_TEXTDOMAIN)
			),
			'column-last_name' => array(
				'label'	=> __('Last name', CPAC_TEXTDOMAIN)
			),
			'column-user_url' => array(
				'label'	=> __('Url', CPAC_TEXTDOMAIN)
			),
			'column-user_registered' => array(
				'label'	=> __('Registered', CPAC_TEXTDOMAIN)
			),
			'column-user_description' => array(
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
		);
		
		// User total number of posts
		foreach ( cpac_utility::get_post_types() as $post_type ) {
			$label = '';
			
			// get plural label
			$posttype_obj = get_post_type_object( $post_type );
			if ( $posttype_obj ) {
				$label = $posttype_obj->labels->name;
			}			

			$custom_columns['column-user_postcount-'.$post_type] = array(
				'label'			=> __( sprintf('No. of %s',$label), CPAC_TEXTDOMAIN),
				'options'		=> array(
					'type_label'	=> __('Postcount', CPAC_TEXTDOMAIN)
				)
			);
		}
		
		// Custom Field support
		$custom_columns['column-meta-1'] = array(
			'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __('Field', CPAC_TEXTDOMAIN),
				'class'			=> 'cpac-box-metafield'
			)
		);	
		
		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);
		
		return apply_filters('cpac-custom-users-columns', $custom_columns);
	}
	
	/**
     * Get Meta Keys
     * 
	 * @since 1.5
     */
    public function get_meta_keys()
    {
        global $wpdb;
        
		$fields = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );
		
		return $this->maybe_add_hidden_meta($fields);
    }
	
	/**
	 * Get label
	 *
	 * @since 1.5
	 */
	public function get_label() 
	{
		return __('Users');
	}
}