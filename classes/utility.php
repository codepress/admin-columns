<?php
class cpac_utility
{
	/**
	 *	Get column types
	 *
	 * 	@since     1.1
	 */
	function get_types()
	{
		$types = array();

		foreach ( cpac_utility::get_post_types() as $post_type ) {
			$types[] = new cpac_columns_posttype( $post_type );
		}

		$types[] 	= new cpac_columns_users();
		$types[] 	= new cpac_columns_media();
		$types[] 	= new cpac_columns_links();
		$types[] 	= new cpac_columns_comments();

		return $types;
	}

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