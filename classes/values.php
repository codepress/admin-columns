<?php

/**
 * CPAC_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Values
{
	protected $excerpt_length, $thumbnail_size;

	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{
		// number of words
		$this->excerpt_length	= 20;
		$this->thumbnail_size	= apply_filters( 'cpac_thumbnail_size', array(80,80) );
	}

	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public function get_stored_columns($type)
	{
		return Codepress_Admin_Columns::get_stored_columns($type);
	}

	/**
	 * Checks if column-meta key exists
	 *
	 * @since     1.0
	 */
	public static function is_column_meta( $id = '' )
	{
		return Codepress_Admin_Columns::is_column_meta( $id );
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
					$result .= wp_get_attachment_image( $attach_id, $this->thumbnail_size, true );
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
		return Codepress_Admin_Columns::get_attachment_ids( $post_id );
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

		// get thumbnail image size
		$image_size = $this->thumbnail_size; // w, h

		// incase the thumbnail dimension is set by name
		if ( !is_array($image_size) ) {
			global $_wp_additional_image_sizes;
			if ( isset($_wp_additional_image_sizes[$image_size]) ) {
				$_size 		= $_wp_additional_image_sizes[$image_size];
				$image_size = array( $_size['width'], $_size['height'] );
			}
		}

		// fallback for image size incase the passed size name does not exists
		if ( !isset($image_size[0]) || !isset($image_size[1]) ) {
			$image_size = array(80, 80);
		}

		// get correct image path
		$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $image);

		// resize image
		if ( file_exists($image_path) && $this->is_image($image_path) ) {
			$resized = image_resize( $image_path, $image_size[0], $image_size[1], true);

			// resize worked
			if ( ! is_wp_error( $resized ) ) {
				$image  = str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized);

				return "<img src='{$image}' alt='' width='{$image_size[0]}' height='{$image_size[1]}' />";
			}

			// resizing failed so let's return full image with maxed dimensions
			else {
				return "<img src='{$image}' alt='' style='max-width:{$image_size[0]}px;max-height:{$image_size[1]}px' />";
			}
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
	 *	Convert file size to readable format
	 *
	 * 	@since     1.4.5
	 */
	function get_readable_filesize($size)
    {
		$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';
    }

	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.0
	 */
	protected function get_column_value_custom_field($object_id, $column_name, $meta_type = 'post')
	{
		/** Users */
		if ( 'user' == $meta_type ) {
			$type = 'wp-users';
		}

		/** Media */
		elseif ( 'media' == $meta_type ) {
			$type = 'wp-media';
			$meta_type = 'post';
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

		// rename hidden custom fields to their original name
		$field = substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field;

		// Get meta field value
		$meta 	 	= get_metadata($meta_type, $object_id, $field, true);

		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array($meta) ) || is_array($meta) ) {
			$meta 	= get_metadata($meta_type, $object_id, $field, true);
			$meta 	= $this->recursive_implode(', ', $meta);
		}

		// make sure there are no serialized arrays or null data
		if ( !is_string($meta) )
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

			// Post Title
			case "title_by_id" :
				$titles = $this->get_custom_field_value_title($meta);
				if ( $titles )
					$meta = $titles;
				break;

			// User Name
			case "user_by_id" :
				$names = $this->get_custom_field_value_user($meta);
				if ( $names )
					$meta = $names;
				break;

			// Checkmark
			case "checkmark" :
				$checkmark = $this->get_asset_image('checkmark.png');

				if ( empty($meta) || 'false' === $meta || '0' === $meta ) {
					$checkmark = '';
				}

				$meta = $checkmark;
				break;

			// Color
			case "color" :
				if ( !empty($meta) ) {
					$meta = "<div class='cpac-color'><span style='background-color:{$meta}'></span>{$meta}</div>";
				}
				break;

		endswitch;

		// filter for customization
		$meta = apply_filters('cpac_get_column_value_custom_field', $meta, $fieldtype, $field, $type, $object_id );

		// add before and after string
		if ( $meta ) {
			$meta = "{$before}{$meta}{$after}";
		}

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
	 *	Get custom field value 'User by ID'
	 *
	 * 	@since     1.4.6.3
	 */
	protected function get_custom_field_value_user($meta)
	{
		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace(' ','', $meta) );

		// var
		$ids = $names = array();

		// check for multiple id's
		if ( strpos($meta, ',') !== false )
			$ids = explode(',',$meta);
		elseif ( is_numeric($meta) )
			$ids[] = $meta;

		// display username
		if ( $ids && is_array($ids) ) {
			foreach ( $ids as $id ) {
				if ( !is_numeric($id) )
					continue;

				$userdata = get_userdata($id);
				if ( is_object($userdata) && !empty( $userdata->display_name ) ) {
					$names[] = $userdata->display_name;
				}
			}
		}

		return implode('<span class="cpac-divider"></span>', $names);
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

		// filter for customization
		$meta = apply_filters('cpac_get_user_column_value_custom_field', $meta, $fieldtype, $field );

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
		return Codepress_Admin_Columns::strip_trim($string);
	}

	/**
	 * Get date
	 *
	 * @since     1.3.1
	 */
	protected function get_date( $date )
	{		
		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) )
			return false;
		
		// Parse with strtotime if it's:
		// - not numeric ( like a unixtimestamp )
		// - date format: yyyymmdd ( format used by ACF ) must start with 19xx or 20xx and is 8 long
		
		// @todo: in theory a numeric string of 8 can also be a unixtimestamp. 
		// we need to replace this with an option to mark a date as unixtimestamp.
		if ( ! is_numeric($date) || ( is_numeric( $date ) && strlen( trim($date) ) == 8 && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0  ) ) )
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
}

?>