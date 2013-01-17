<?php

/**
 * CPAC_Values Class
 *
 * @since 1.4.4
 *
 */
class CPAC_Values {

	/**
     * Storage key
	 *
	 * Key by which the settings are saved to the Database. Every WordPress Column type has it's unique key.
	 * Supported types are comments, links, users, media and posts. The first 4 get prefixed with 'wp-', except
	 * for posts. They have their posttype as storage key.
	 *
	 * @since 1.4.4
	 *
     * @var string Storage Key.
     */
	protected $storage_key;

	/**
     * Meta type
     *
	 * Used for retrieving custom metadata.
	 *
	 * @since 2.0.0
	 *
     * @var string Column Meta Type.
     */
	protected $meta_type;

	/**
     * Excerpt length
     *
	 * @since 1.4.4
	 *
     * @var int Excerpt Length
     */
	protected $excerpt_length;

	/**
     * Thumbnail Size ( Width, Height )
     *
	 * @since 1.4.4
	 *
     * @var array Thumbnail Size.
     */
	protected $thumbnail_size;


	/**
	 * Constructor
	 *
	 * @since 1.4.4
	 */
	function __construct() {
		// number of words
		$this->excerpt_length	= 20;
		$this->thumbnail_size	= apply_filters( 'cpac_thumbnail_size', array(80,80) );
	}

	/**
	 * Returns excerpt
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id )	{
		global $post;

		$save_post 	= $post;
		$post 		= get_post( $post_id );
		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;

		$output = $this->get_shortened_string( $excerpt, $this->excerpt_length );

		return $output;
	}

	/**
	 * Returns shortened string
	 *
	 * @see wp_trim_words();
	 * @since 1.0.0
	 *
	 * @return string Trimmed text.
	 */
	protected function get_shortened_string( $text = '', $num_words = 55, $more = null ) {
		if ( ! $text )
			return false;

		return wp_trim_words( $text, $num_words, $more );
	}

	/**
	 * Get image from assets folder
	 *
	 * @since 1.3.1
	 *
	 * @param string $name
	 * @param string $title
	 * @return string HTML img element
	 */
	protected function get_asset_image( $name = '', $title = '' ) {
		if ( ! $name )
			return false;

		return sprintf( "<img alt='' src='%s' title='%s'/>", CPAC_URL."/assets/images/{$name}", $title );
	}

	/**
	 * Shorten URL
	 *
	 * @since 1.3.1
	 *
	 * @param string $url
	 * @return string HTML anchor element
	 */
	protected function get_shorten_url( $url = '' ) {
		if ( !$url )
			return false;

		// shorten url
		$short_url 	= url_shorten( $url );

		return "<a title='{$url}' href='{$url}'>{$short_url}</a>";
	}

	/**
	 * Get column value of post attachments
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id
	 * @return string HTML images
	 */
	protected function get_column_value_attachments( $post_id ) {
		$result = '';

		if ( $attachment_ids = CPAC_Utility::get_attachment_ids( $post_id ) ) {
			foreach ( $attachment_ids as $attach_id ) {
				if ( $image = wp_get_attachment_image( $attach_id, $this->thumbnail_size, true ) ) {
					$result .= $image;
				}
			}
		}
		return $result;
	}

	/**
	 * Get Image Sizes by name
	 *
	 * @since 1.5.0
	 *
	 * @param string $name
	 * @return array Image Sizes
	 */
	function get_image_size_by_name( $name = '' ) {
		if ( ! $name )
			return false;

		global $_wp_additional_image_sizes;

		if ( ! isset( $_wp_additional_image_sizes[$name] ) )
			return false;

		return $_wp_additional_image_sizes[$name];
	}

	/**
	 * Get a thumbnail
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta
	 * @param array $args
	 * @return string HTML img elements
	 */
	protected function get_thumbnails( $meta, $args = '' ) {
		if ( empty( $meta ) || 'false' == $meta )
			return false;

		$output = '';

		// Image size
		$defaults = array(
			'image_size'	=> 'thumbnail',
			'width'			=> 80,
			'height'		=> 80,
		);
		$args = wp_parse_args( $args, $defaults );

		// Image
		if ( $this->is_image( $meta ) ) {

			if ( $sizes = $this->get_image_size_by_name( $args['image_size'] ) ) {
				$args['width'] 	= $sizes['width'];
				$args['height'] = $sizes['width'];
			}

			// get correct image path
			$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $meta );

			// resize image
			if ( file_exists( $image_path ) ) {

				// try to resize image
				if ( $resized = $this->image_resize( $image_path, $args['width'], $args['height'], true ) ) {
					$output = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) .  "' alt='' width='{$args['width']}' height='{$args['height']}' />";
				}

				// resizing failed so let's return full image with maxed dimensions
				else {
					$output = "<img src='{$meta}' alt='' style='max-width:{$args['width']}px;max-height:{$args['height']}px' />";
				}
			}
		}

		// Media Attachment
		else {

			$meta = CPAC_Utility::strip_trim( str_replace( ' ', '', $meta ) );

			$media_ids = array($meta);

			// split media ids
			if ( strpos( $meta, ',' ) !== false ) {
				$media_ids = array_filter( explode( ',', $meta ) );
			}

			foreach ( $media_ids as $media_id ) {

				// valid image?
				if ( ! is_numeric($media_id) || ! wp_get_attachment_url( $media_id ) )
					continue;

				// image attributes
				$attributes = wp_get_attachment_image_src( $media_id, $args['image_size'] );
				$src 		= $attributes[0];
				$width		= $attributes[1];
				$height		= $attributes[2];

				// image size by name
				if ( $sizes = $this->get_image_size_by_name( $args['image_size'] ) ) {
					$width 	= $sizes['width'];
					$height	= $sizes['height'];
				}

				// custom image size
				if ( 'cpac-custom' == $args['image_size'] ) {
					$width 	= $args['width'];
					$height = $args['height'];
				}

				// maximum dimensions
				$max = max( array( $width, $height ) );

				$output .= "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$attributes[0]}' alt=''/></span>";
			}
		}

		return $output;
	}

	/**
	 * Image Resize
	 *
	 * @see image_resize()
	 * @since 1.5
	 *
	 * @return string Image URL
	 */
	function image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$resized = false;

		// WP 3.5 or higher
		if ( function_exists( 'wp_get_image_editor' ) ) {

			$editor = wp_get_image_editor( $file );

			if ( is_wp_error( $editor ) )
				return false;

			$editor->set_quality( $jpeg_quality );

			$resized = $editor->resize( $max_w, $max_h, $crop );
			if ( is_wp_error( $resized ) )
				return false;

			$dest_file = $editor->generate_filename( $suffix, $dest_path );

			$saved = $editor->save( $dest_file );

			if ( is_wp_error( $saved ) )
				return false;

			$resized = $dest_file;
		}

		// WP 3.4 or lower
		else {
			$result = image_resize( $file, $max_w, $max_h, $crop, $suffix, $dest_path, $jpeg_quality );

			if ( ! is_wp_error( $result ) && $result ) {
				$resized = $result;
			}
		}

		return $resized;
	}

	/**
	 * Checks an URL for image extension
	 *
	 * @since 1.2.0
	 *
	 * @param string $url
	 * @return bool
	 */
	protected function is_image( $url ) {
		$validExt  	= array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
		$ext    	= strrchr( $url, '.' );

		return in_array( $ext, $validExt );
	}

	/**
	 * Convert file size to readable format
	 *
	 * @since 1.4.5
	 *
	 * @param string $size
	 * @return string Readable filesize
	 */
	function get_readable_filesize( $size ) {
		$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		return $size ? round( $size/pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2) . $filesizename[$i] : '0 Bytes';
    }

	/**
	 * Get column value of Custom Field
	 *
	 * @since 1.0.0
	 *
	 * @param int $object_id
	 * @param string $column_name
	 * @return string Customfield value
	 */
	protected function get_column_value_custom_field( $column_name, $object_id ) {
		// get column
		$columns = CPAC_Utility::get_stored_columns( $this->storage_key );

		if ( ! isset( $columns[$column_name] ) )
			return false;

		// values
		$defaults = array(
			'id'			=> $column_name,
			'storage_key'	=> $this->storage_key,
			'label'			=> '',
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'image_size'	=> 'cpac-custom',
			'image_size_w'	=> 80,
			'image_size_h'	=> 80,
		);

		$settings = (object) wp_parse_args( $columns[$column_name], $defaults );

		// rename hidden custom fields to their original name
		if ( 'cpachidden' == substr( $settings->field, 0, 10 ) ) {
			$settings->field = str_replace( 'cpachidden', '', $settings->field );
		}

		// Get meta field value
		$meta = get_metadata( $this->meta_type, $object_id, $settings->field, true );

		// multiple meta values
		if ( ( 'array' == $settings->field_type && is_array( $meta ) ) || is_array( $meta ) ) {
			$meta = $this->recursive_implode( ', ', $meta );
		}

		// make sure there are no serialized arrays or null data
		if ( ! is_string( $meta ) )
			return false;

		// handles each field type differently..
		switch ( $settings->field_type ) :

			// Image
			case "image" :
				$meta = $this->get_thumbnails( $meta, array(
					'image_size'	=> $settings->image_size,
					'width' 		=> $settings->image_size_w,
					'height' 		=> $settings->image_size_h,
				));
				break;

			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string( $meta, $this->excerpt_length );
				break;

			// Date
			case "date" :
				$meta = $this->get_date( $meta );
				break;

			// Post Title
			case "title_by_id" :
				if ( $titles = $this->get_custom_field_value_title( $meta ) ) {
					$meta = $titles;
				}
				break;

			// User Name
			case "user_by_id" :
				if ( $names = $this->get_custom_field_value_user( $meta ) ) {
					$meta = $names;
				}
				break;

			// Checkmark
			case "checkmark" :
				$checkmark = $this->get_asset_image( 'checkmark.png' );

				if ( empty( $meta ) || 'false' === $meta || '0' === $meta ) {
					$checkmark = '';
				}

				$meta = $checkmark;
				break;

			// Color
			case "color" :
				if ( ! empty( $meta ) ) {
					$meta = "<div class='cpac-color'><span style='background-color:{$meta}'></span>{$meta}</div>";
				}
				break;

		endswitch;

		// add before and after string
		if ( $meta ) {
			$meta = "{$settings->before}{$meta}{$settings->after}";
		}

		/**
		 * Filter
		 *
		 * @since 2.0.0
		 *
		 * @param string $meta Column value
		 * @param string $object_id Object ID
		 * @param object $settings All custom field settings
		 * @return string Column value
		 */
		return apply_filters( 'cpac_get_column_value_custom_field', $meta, $object_id, $settings );
	}

	/**
	 * Get custom field value 'Title by ID'
	 *
	 * @since 1.3.0
	 *
	 * @param string $meta
	 * @return string Titles
	 */
	protected function get_custom_field_value_title( $meta ) {
		//remove white spaces and strip tags
		$meta = CPAC_Utility::strip_trim( str_replace( ' ','', $meta ) );
		// var
		$ids = $titles = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false )
			$ids = explode( ',',$meta );
		elseif ( is_numeric( $meta ) )
			$ids[] = $meta;

		// display title with link
		if ( $ids && is_array( $ids ) ) {
			foreach ( $ids as $id ) {
				$title = is_numeric( $id ) ? get_the_title( $id ) : '';
				$link  = get_edit_post_link( $id );
				if ( $title )
					$titles[] = $link ? "<a href='{$link}'>{$title}</a>" : $title;
			}
		}

		return implode('<span class="cpac-divider"></span>', $titles);
	}

	/**
	 * Get custom field value 'User by ID'
	 *
	 * @since 1.4.6.3
	 *
	 * @param string $meta
	 * @return string Users
	 */
	protected function get_custom_field_value_user( $meta )
	{
		//remove white spaces and strip tags
		$meta = CPAC_Utility::strip_trim( str_replace( ' ','', $meta ) );

		// var
		$ids = $names = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false ) {
			$ids = explode( ',',$meta );
		}
		elseif ( is_numeric( $meta ) ) {
			$ids[] = $meta;
		}

		// display username
		if ( $ids && is_array( $ids ) ) {
			foreach ( $ids as $id ) {
				if ( ! is_numeric( $id ) )
					continue;

				$userdata = get_userdata( $id );
				if ( is_object( $userdata ) && ! empty( $userdata->display_name ) ) {
					$names[] = $userdata->display_name;
				}
			}
		}

		return implode( '<span class="cpac-divider"></span>', $names );
	}

	/**
	 * Get column value of Custom Field
	 *
	 * @since 1.2.0
	 *
	 * @param int $user_id
	 * @param string $id Column Type
	 * @return string User Customfield value
	 */
	protected function get_user_column_value_custom_field( $user_id, $id ) {
		$columns 	= CPAC_Utility::get_stored_columns( 'wp-users' );

		// inputs
		$field	 	= isset( $columns[$id]['field'] )		? $columns[$id]['field'] 		: '';
		$fieldtype	= isset( $columns[$id]['field_type'] )	? $columns[$id]['field_type'] 	: '';
		$before 	= isset( $columns[$id]['before'] )		? $columns[$id]['before'] 		: '';
		$after 		= isset( $columns[$id]['after'] )		? $columns[$id]['after'] 		: '';

		// Get meta field value
		$meta = get_user_meta( $user_id, $field, true );

		// multiple meta values
		if ( ( $fieldtype == 'array' && is_array( $meta ) ) || is_array( $meta ) ) {
			$meta 	= get_user_meta( $user_id, $field );
			$meta 	= $this->recursive_implode( ', ', $meta );
		}

		// make sure there are no serialized arrays or empty meta data
		if ( empty( $meta ) || ! is_string( $meta ) )
			return false;

		// handles each field type differently..
		switch ( $fieldtype ) :

			// Image
			case "image" :
				$meta = $this->get_thumbnails( $meta );
				break;

			// Excerpt
			case "excerpt" :
				$meta = $this->get_shortened_string( $meta, $this->excerpt_length );
				break;

		endswitch;

		// filter for customization
		$meta = apply_filters( 'cpac_get_user_column_value_custom_field', $meta, $fieldtype, $field );

		// add before and after string
		$meta = "{$before}{$meta}{$after}";

		return $meta;
	}

	/**
	 * Implode for multi dimensional array
	 *
	 * @since 1.0.0
	 *
	 * @param string $glue
	 * @param array $pieces
	 * @return string Imploded array
	 */
	protected function recursive_implode( $glue, $pieces )
	{
		foreach( $pieces as $r_pieces )	{
			if ( is_array( $r_pieces ) ) {
				$retVal[] = $this->recursive_implode( $glue, $r_pieces );
			}
			else {
				$retVal[] = $r_pieces;
			}
		}
		if ( isset($retVal) && is_array( $retVal ) ) {
			return implode( $glue, $retVal );
		}

		return false;
	}

	/**
	 * Get date
	 *
	 * @since 1.3.1
	 *
	 * @param string $date
	 * @return string Formatted date
	 */
	protected function get_date( $date ) {
		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) )
			return false;

		// Parse with strtotime if it's:
		// - not numeric ( like a unixtimestamp )
		// - date format: yyyymmdd ( format used by ACF ) must start with 19xx or 20xx and is 8 long

		// @todo: in theory a numeric string of 8 can also be a unixtimestamp.
		// we need to replace this with an option to mark a date as unixtimestamp.
		if ( ! is_numeric( $date ) || ( is_numeric( $date ) && strlen( trim( $date ) ) == 8 && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0  ) ) ) {
			$date = strtotime( $date );
		}

		return date_i18n( get_option( 'date_format' ), $date );
	}

	/**
	 * Get time
	 *
	 * @since 1.3.1
	 *
	 * @param string $date
	 * @return string Formatted time
	 */
	protected function get_time( $date ) {
		if ( ! $date )
			return false;

		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}

		return date_i18n( get_option( 'time_format' ), $date );
	}
}