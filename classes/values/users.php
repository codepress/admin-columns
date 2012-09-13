<?php

/**
 * CPAC_Users_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Users_Values extends CPAC_Values
{	
	/**
	 * Constructor
	 *
	 * @since     1.4.4
	 */
	function __construct()
	{
		parent::__construct();
		
		add_filter( 'manage_users_custom_column', array( $this, 'manage_users_column_value'), 10, 3 );
	}
	
	/**
	 * Manage custom column for Users.
	 *
	 * @since     1.1
	 */
	public function manage_users_column_value( $value, $column_name, $user_id )
	{
		$type = $column_name;
		
		$userdata = get_userdata( $user_id );

		if ( ! $userdata )
			return false;
		
		// Check for user custom fields: column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-user-meta';
			
		// Check for post count: column-user_postcount-[posttype]
		if ( Codepress_Admin_Columns::get_posttype_by_postcount_column($type) )
			$type = 'column-user_postcount';
		
		// Hook 
		do_action('cpac-manage-users-column', $type, $column_name, $user_id);
		
		$result = '';
		switch ($type) :			
			
			// user id
			case "column-user_id" :
				$result = $user_id;
				break;
			
			// first name
			case "column-nickname" :
				$result = $userdata->nickname;
				break;
			
			// first name
			case "column-first_name" :
				$result = $userdata->first_name;
				break;
				
			// last name
			case "column-last_name" :
				$result = $userdata->last_name;
				break;
			
			// user url
			case "column-user_url" :
				$result = $userdata->user_url;
				break;
				
			// user registration date
			case "column-user_registered" :
				$result = $userdata->user_registered;
				break;
				
			// user description
			case "column-user_description" :
				$result = $this->get_shortened_string( get_the_author_meta('user_description', $user_id), $this->excerpt_length );
				break;
				
			// user description
			case "column-user_postcount" :
				$post_type 	= Codepress_Admin_Columns::get_posttype_by_postcount_column($column_name);
				
				// get post count
				$count 		= Codepress_Admin_Columns::get_post_count( $post_type, $user_id );
				
				// set result
				$result 	= $count > 0 ? "<a href='edit.php?post_type={$post_type}&author={$user_id}'>{$count}</a>" : (string) $count;
				break; 
			
			// user actions
			case "column-actions" :
				$result = $this->get_column_value_actions($user_id, 'users');
				break;
			
			// user meta data ( custom field )
			case "column-user-meta" :
				$result = $this->get_column_value_custom_field($user_id, $column_name, 'user');
				break;
			
			default :
				$result = $value;
				
		endswitch;
		
		// Filter for customizing the result output
		apply_filters('cpac-users-column-result', $result, $type, $column_name, $user_id);
		
		return $result;
	}
	
	/**
	 *	Get column value of user actions
	 *
	 *	This part is copied from the Users List Table class
	 *
	 * 	@since     1.4.2
	 */
	private function get_column_value_actions( $id ) 
	{	
		$actions = array();
				
		$user_object = new WP_User( $id );
		$screen 	 = get_current_screen();
		
		if ( 'site-users-network' == $screen->id )
			$url = "site-users.php?id={$this->site_id}&amp;";
		else
			$url = 'users.php?';
		
		if ( get_current_user_id() == $user_object->ID ) {
			$edit_link = 'profile.php';
		} else {
			$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( stripslashes( $_SERVER['REQUEST_URI'] ) ), "user-edit.php?user_id=$user_object->ID" ) );
		}
		
		if ( current_user_can( 'edit_user',  $user_object->ID ) ) {
			$edit = "<strong><a href=\"$edit_link\">$user_object->user_login</a></strong><br />";
			$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
		} else {
			$edit = "<strong>$user_object->user_login</strong><br />";
		}

		if ( !is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) )
			$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . "</a>";
		if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) )
			$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url."action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . "</a>";
			
		return implode(' | ', $actions);
	}
}

?>