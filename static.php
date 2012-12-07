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
}