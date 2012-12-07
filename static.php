<?php 
class cpac_static
{
	/**
	 * Get post types
	 *
	 * @since     1.0
	 */
	public function get_post_types()
	{
		$post_types = get_post_types(array(
			'_builtin' => false
		));
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';
		
		return apply_filters('cpac-get-post-types', $post_types);
	}
	
	/**
	 * Filter preset columns. These columns apply either for every post or set by a plugin.
	 *
	 * @since     1.0
	 */
	public function filter_preset_columns( $type, $columns ) 
	{
		$options = get_option('cpac_options_default');
		
		if ( !$options )
			return $columns;
		
		// we use the wp default columns for filtering...
		$stored_wp_default_columns 	= $options[$type];

		// ... the ones that are set by plugins, theme functions and such.
		$dif_columns 	= array_diff(array_keys($columns), array_keys($stored_wp_default_columns));
			
		// we add those to the columns
		$pre_columns = array();
		if ( $dif_columns ) {
			foreach ( $dif_columns as $column ) {
				$pre_columns[$column] = $columns[$column];
			}
		}
		
		return $pre_columns;
	}

	/**
	 * Checks if column-meta key exists
	 *
	 * @since     1.0
	 */
	public function is_column_meta( $id = '' ) 
	{
		if ( strpos($id, 'column-meta-') !== false )
			return true;
		
		return false;
	}

	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since     1.0
	 */
	public function sanitize_string($string) 
	{	
		$string = esc_url($string);
		$string = str_replace('http://','', $string);
		$string = str_replace('https://','', $string);
		
		return $string;
	}
	
	/**
	 * Get singular name of post type
	 *
	 * @since     1.0
	 */
	public function get_singular_name( $type ) 
	{
		// Links
		if ( $type == 'wp-links' )
			$label = __('Links');
			
		// Comments
		elseif ( $type == 'wp-comments' )
			$label = __('Comments');
			
		// Users
		elseif ( $type == 'wp-users' )
			$label = __('Users');
		
		// Media
		elseif ( $type == 'wp-media' )
			$label = __('Media Library');
		
		// Posts
		else {
			$posttype_obj 	= get_post_type_object($type);
			$label 			= $posttype_obj->labels->singular_name;
		}
		
		return $label;
	}
	
	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public static function get_stored_columns( $type )
	{ 
		// get plugin options
		$options = get_option('cpac_options');

		// get saved columns
		if ( !empty($options['columns'][$type]) )
			return $options['columns'][$type];
		
		return false;
	}
	
	/**
	 *	Add managed columns by Type
	 *
	 * 	@since 1.4.6.5
	 */
	function get_comment_icon() 
	{
		return "<span class='vers'><img src='" . trailingslashit( get_admin_url() ) . 'images/comment-grey-bubble.png' . "' alt='Comments'></span>";
	}
	
	/**
	 * Get the posttype from columnname
	 *
	 * @since     1.3.1
	 */
	function get_posttype_by_postcount_column( $id = '' ) 
	{
		if ( strpos($id, 'column-user_postcount-') !== false )			
			return str_replace('column-user_postcount-', '', $id);
				
		return false;
	}
	
	/**
	 *	Get post count
	 *
	 * 	@since     1.3.1
	 */
	function get_post_count( $post_type, $user_id )
	{
		global $wpdb;
		
		if ( ! post_type_exists($post_type) || empty($user_id) )
			return false;
		
		$sql = "
			SELECT COUNT(ID)
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_author = %d
			AND post_type = %s
		";
		
		return $wpdb->get_var( $wpdb->prepare($sql, $user_id, $post_type) );
	}
	
	/**
	 * Strip tags and trim
	 *
	 * @since     1.3
	 */
	public static function strip_trim($string) 
	{
		return trim(strip_tags($string));
	}
	
	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.2.1
	 */
	public static function get_attachment_ids( $post_id ) 
	{
		return get_posts(array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
	}
	
	/**
	 * Get author field by nametype
	 *
	 * Used by posts and sortable
	 *
	 * @since     1.4.6.1
	 */
	public function get_author_field_by_nametype( $nametype, $user_id)
	{
		$userdata = get_userdata( $user_id );
	
		switch ( $nametype ) :
			
			case "display_name" :
				$name = $userdata->display_name;
				break;
				
			case "first_name" :
				$name = $userdata->first_name;
				break;		
				
			case "last_name" :
				$name = $userdata->last_name;
				break;
				
			case "first_last_name" :
				$first = !empty($userdata->first_name) ? $userdata->first_name : '';
				$last = !empty($userdata->last_name) ? " {$userdata->last_name}" : '';
				$name = $first.$last;
				break;
				
			case "nickname" :
				$name = $userdata->nickname;
				break;		
				
			case "username" :
				$name = $userdata->user_login;
				break;
			
			case "email" :
				$name = $userdata->user_email;
				break;
				
			case "userid" :
				$name = $userdata->ID;
				break;
				
			default :
				$name = $userdata->display_name;
			
		endswitch;
		
		return $name;
	}
}