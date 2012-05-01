<?php

/**
 * CPAC_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Values
{	
	private $excerpt_length;
	
	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{	
		// number of words
		$this->excerpt_length	= 20;		
	}
	
	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public function get_stored_columns($type)
	{ 
		// get plugin options
		$options 		= get_option('cpac_options');

		// get saved columns
		if ( isset($options['columns'][$type]) )
			return $options['columns'][$type];
		
		return false;
	}
	
	/**
	 * Checks if column-meta key exists
	 *
	 * @since     1.0
	 */
	protected function is_column_meta( $id = '' ) 
	{
		if ( strpos($id, 'column-meta-') !== false )
			return true;
		
		return false;
	}
	
	/**
	 * Returns excerpt
	 *
	 * @since     1.0
	 */
	protected function get_post_excerpt($post_id) 
	{
		global $post;
			
		$save_post 	= $post;
		$post 		= get_post($post_id);
		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;
		
		$output = $this->get_shortened_string($excerpt, $this->excerpt_length );	
		
		return $output;
	}
	
	/**
	 * Returns shortened string
	 *
	 * @since     1.0
	 */
	protected function get_shortened_string($string = '', $num_words = 55, $more = null) 
	{
		if (!$string)
			return false;
		
		return wp_trim_words( $string, $num_words, $more );
	}

	/**
	 *	Get image from assets folder
	 *
	 * 	@since     1.3.1
	 */
	protected function get_asset_image($name = '', $title = '')
	{
		if ( $name )
			return sprintf("<img alt='' src='%s' title='%s'/>", CPAC_URL."/assets/images/{$name}", $title);
	}	
	
	/**
	 *	Shorten URL
	 *
	 * 	@since     1.3.1
	 */
	protected function get_shorten_url($url = '')
	{
		if ( !$url )
			return false;
			
		// shorten url
		$short_url 	= url_shorten( $url );
		
		return "<a title='{$url}' href='{$url}'>{$short_url}</a>";		
	}
	
	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.0
	 */
	protected function get_column_value_attachments( $post_id ) 
	{
		$result 	 	= '';
		$attachment_ids = $this->get_attachment_ids($post_id);
		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attach_id ) {
				if ( wp_get_attachment_image($attach_id) )
					$result .= wp_get_attachment_image( $attach_id, array(80,80), true );
			}
		}
		return $result;
	}
	
	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.2.1
	 */
	protected function get_attachment_ids( $post_id ) 
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
	 * Get the posttype from columnname
	 *
	 * @since     1.3.1
	 */
	protected function get_posttype_by_postcount_column( $id = '' ) 
	{
		if ( strpos($id, 'column-user_postcount-') !== false )			
			return str_replace('column-user_postcount-', '', $id);
				
		return false;
	}
	
	/**
	 * Get a thumbnail
	 *
	 * @since     1.0
	 */
	protected function get_thumbnail( $image = '' ) 
	{		
		if ( empty($image) )
			return false;
		
		// get correct image path
		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $image);
		
		// resize image		
		if ( file_exists($image_path) && $this->is_image($image_path) ) {
			$resized = image_resize( $image_path, 80, 80, true);
			
			if ( ! is_wp_error( $resized ) ) {
				$image  = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized);
				
				return "<img src='{$image}' alt='' width='80' height='80' />";
			}
			
			return $resized->get_error_message();
		}
		
		return false;
	}
	
	/**
	 * Checks an URL for image extension
	 *
	 * @since     1.2
	 */
	protected function is_image($url) 
	{
		$validExt  	= array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
		$ext    	= strrchr($url, '.');
		
		return in_array($ext, $validExt);
	}	
	
	/**
	 * Get a thumbnail
	 *
	 * @since     1.3.1
	 */
	protected function get_media_thumbnails($meta) 
	{
		$meta = $this->strip_trim( str_replace(' ','', $meta) );
		
		// split media ids
		$media_ids = array($meta);
		if ( strpos($meta, ',') !== false )			
			$media_ids = explode(',', $meta);
		
		// check if media exists
		$thumbs = '';
		foreach ( $media_ids as $media_id )
			if ( is_numeric($media_id) )
				$thumbs .= wp_get_attachment_url($media_id) ? "<span class='cpac-column-value-image'>".wp_get_attachment_image( $media_id, array(80,80), true )."</span>" : '';
		
		return $thumbs;		
	}
	
	/**
	 *	Get post count
	 *
	 * 	@since     1.3.1
	 */
	protected function get_post_count( $post_type, $user_id )
	{
		if ( ! post_type_exists($post_type) || ! get_userdata($user_id) )
			return false;
			
		$user_posts = get_posts(array(
			'post_type'		=> $post_type,
			'numberposts' 	=> -1,
			'author' 		=> $user_id,
			'post_status' 	=> 'publish'
		));
		return count($user_posts);
	}
	
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.0
	 */	
	protected function get_column_value_custom_field($object_id, $column_name, $meta_type = 'post') 
	{
		/** Users */
		if ( $meta_type == 'user' ) {
			$type = 'wp-users';
		}
		
		/** Posts */
		else {
			$type 	= get_post_type($object_id);
		}
		
		// get column
		$columns 	= $this->get_stored_columns($type);
		
		// inputs
		$field	 	= isset($columns[$column_name]['field']) 	  ? $columns[$column_name]['field'] 		: '';
		$fieldtype	= isset($columns[$column_name]['field_type']) ? $columns[$column_name]['field_type'] 	: '';
		$before 	= isset($columns[$column_name]['before']) 	  ? $columns[$column_name]['before'] 		: '';
		$after 		= isset($columns[$column_name]['after']) 	  ? $columns[$column_name]['after'] 		: '';
		
		// Get meta field value
		$meta 	 	= get_metadata($meta_type, $object_id, $field, true);

		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array($meta) ) || is_array($meta) ) {			
			$meta 	= get_metadata($meta_type, $object_id, $field, true);
			$meta 	= $this->recursive_implode(', ', $meta);
		}
		
		// make sure there are no serialized arrays or empty meta data
		if ( empty($meta) || !is_string($meta) )	
			return false;
					
		// handles each field type differently..
		switch ($fieldtype) :			
		
			// Image
			case "image" :				
				$meta = $this->get_thumbnail($meta);
				break;
				
			// Media Library ID
			case "library_id" :
				$meta = $this->get_media_thumbnails($meta);				
				break;
			
			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string($meta, $this->excerpt_length);
				break;
			
			// Date
			case "date" :
				$meta = $this->get_date($meta);
				break;
			
			// Title
			case "title_by_id" :
				$titles = $this->get_custom_field_value_title($meta);
				if ( $titles )
					$meta = $titles;
				break;
			
		endswitch;		
		
		// add before and after string
		$meta = "{$before}{$meta}{$after}";
		
		return $meta;
	}
	
	/**
	 *	Get custom field value 'Title by ID'
	 *
	 * 	@since     1.3
	 */
	protected function get_custom_field_value_title($meta) 
	{
		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace(' ','', $meta) );
		
		// var
		$ids = $titles = array();
		
		// check for multiple id's
		if ( strpos($meta, ',') !== false )
			$ids = explode(',',$meta);			
		elseif ( is_numeric($meta) )
			$ids[] = $meta;			
		
		// display title with link
		if ( $ids && is_array($ids) ) {
			foreach ( $ids as $id ) {				
				$title = is_numeric($id) ? get_the_title($id) : '';
				$link  = get_edit_post_link($id);
				if ( $title )
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
			}
		}
		
		return implode('<span class="cpac-divider"></span>', $titles);
	}
	
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.2
	 */
	protected function get_user_column_value_custom_field($user_id, $id) 
	{		
		$columns 	= $this->get_stored_columns('wp-users');
		
		// inputs
		$field	 	= isset($columns[$id]['field']) 	 ? $columns[$id]['field'] 		: '';
		$fieldtype	= isset($columns[$id]['field_type']) ? $columns[$id]['field_type'] 	: '';
		$before 	= isset($columns[$id]['before']) 	 ? $columns[$id]['before'] 		: '';
		$after 		= isset($columns[$id]['after']) 	 ? $columns[$id]['after'] 		: '';
		
		// Get meta field value
		$meta 	 	= get_user_meta($user_id, $field, true);
		
		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array($meta) ) || is_array($meta) ) {			
			$meta 	= get_user_meta($user_id, $field);
			$meta 	= $this->recursive_implode(', ', $meta);
		}
		
		// make sure there are no serialized arrays or empty meta data
		if ( empty($meta) || !is_string($meta) )	
			return false;
					
		// handles each field type differently..
		switch ($fieldtype) :			
		
			// Image
			case "image" :				
				$meta = $this->get_thumbnail($meta);
				break;
				
			// Media Library ID
			case "library_id" :
				$meta = $this->get_media_thumbnails($meta);
				break;
			
			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string($meta, $this->excerpt_length);
				break;
								
		endswitch;		
		
		// add before and after string
		$meta = "{$before}{$meta}{$after}";
		
		return $meta;
	}

	/**
	 *	Implode for multi dimensional array
	 *
	 * 	@since     1.0
	 */
	protected function recursive_implode( $glue, $pieces ) 
	{
		foreach( $pieces as $r_pieces )	{
			if( is_array( $r_pieces ) ) {
				$retVal[] = $this->recursive_implode( $glue, $r_pieces );
			}
			else {
				$retVal[] = $r_pieces;
			}
		}
		if ( isset($retVal) && is_array($retVal) )
			return implode( $glue, $retVal );
		
		return false;
	}
	
	/**
	 * Strip tags and trim
	 *
	 * @since     1.3
	 */
	protected function strip_trim($string) 
	{
		return trim(strip_tags($string));
	}
	
	/**
	 * Get date
	 *
	 * @since     1.3.1
	 */
	protected function get_date($date) 
	{
		if ( ! $date )
			return false;
			
		if ( ! is_numeric($date) )
			$date = strtotime($date);
			
		return date_i18n( get_option('date_format'), $date );
	}
	
	/**
	 * Get time
	 *
	 * @since     1.3.1
	 */
	protected function get_time($date) 
	{
		if ( ! $date )
			return false;
			
		if ( ! is_numeric($date) )
			$date = strtotime($date);
		
		return date_i18n( get_option('time_format'), $date );
	}
	
	/**
	 *	Get column value of post actions
	 *
	 *	This part is copied from the Posts List Table class
	 *
	 * 	@since     1.4.2
	 */
	protected function get_column_value_actions( $id, $type = 'posts' ) 
	{	
		$actions = array();
		
		/** Posts */
		if ( $type == 'posts') {
			$post_id			= $id;
			$post 				= get_post($post_id);
			$title 				= _draft_or_post_title();
			$post_type_object 	= get_post_type_object( $post->post_type );
			$can_edit_post 		= current_user_can( $post_type_object->cap->edit_post, $post->ID );
			
			if ( $can_edit_post && 'trash' != $post->post_status ) {
				$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
			}
			if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
				if ( 'trash' == $post->post_status )
					$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
				elseif ( EMPTY_TRASH_DAYS )
					$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
				if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
					$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
			}
			if ( $post_type_object->public ) {
				if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
					if ( $can_edit_post )
						$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
				} elseif ( 'trash' != $post->post_status ) {
					$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
				}
			}
		}
		
		/** Users */
		elseif ( $type == 'users' ) {
			
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
		}
		
		return implode(' | ', $actions);
	}
}

?>