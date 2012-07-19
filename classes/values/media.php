<?php

/**
 * CPAC_Media_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Media_Values extends CPAC_Values
{	
	/**
	 * Constructor
	 *
	 * @since     1.4.4
	 */
	function __construct()
	{
		parent::__construct();
		
		add_action( 'manage_media_custom_column', array( $this, 'manage_media_column_value'), 10, 2 );		
	}
	
	/**
	 * Manage custom column for Media.
	 *
	 * @since     1.3
	 */
	public function manage_media_column_value( $column_name, $media_id )
	{
		$type 	= $column_name;
		
		//$meta 	= wp_get_attachment_metadata($media_id);
		$meta 	= get_post_meta( $media_id, '_wp_attachment_metadata', true );
		$p 		= get_post($media_id);
		
		// Check for custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-meta';
		
		// Hook 
		do_action('cpac-manage-media-column', $type, $column_name, $media_id);
		
		$result = '';
		switch ($type) :			
			
			// media id
			case "column-mediaid" :
				$result = $media_id;
				break;			
			
			// dimensions
			case "column-dimensions" :
				if ( !empty($meta['width']) &&  !empty($meta['height']) )
					$result = "{$meta['width']} x {$meta['height']}";
				break;
			
			// width
			case "column-width" :
				$result	= !empty($meta['width']) ? $meta['width'] : '';
				break;
			
			// height
			case "column-height" :
				$result	= !empty($meta['height']) ? $meta['height'] : '';
				break;
			
			// description
			case "column-description" :
				$result	= $p->post_content;
				break;
				
			// caption
			case "column-caption" :
				$result	= $p->post_excerpt;
				break;
				
			// alternate text
			case "column-alternate_text" :
				$alt 	= get_post_meta($media_id, '_wp_attachment_image_alt', true);
				$result = $this->strip_trim($alt);
				break;
				
			// mime type
			case "column-mime_type" :				
				$result = $p->post_mime_type;
				break;
			
			// file name
			case "column-file_name" :
				$file 		= wp_get_attachment_url($p->ID);
				$filename 	= basename($file);
				$result 	= "<a title='{$filename}' href='{$file}'>{$filename}</a>";
				break;
				
			// file paths
			case "column-file_paths" :
				$sizes 		= get_intermediate_image_sizes();
				$url 		= wp_get_attachment_url($p->ID);
				$filename 	= basename($url);				
				$paths[] 	= "<a title='{$filename}' href='{$url}'>" . __('original', CPAC_TEXTDOMAIN) . "</a>";
				if ( $sizes ) {
					foreach ( $sizes as $size ) {
						$src 	= wp_get_attachment_image_src( $media_id, $size );						
						if (!empty($src[0])) {
							$filename = basename($src[0]);
							$paths[] = "<a title='{$filename}' href='{$src[0]}'>{$size}</a>";
						}
					}
				}				
				$result = implode('<span class="cpac-divider"></span>', $paths);
				break;
			
			case "column-actions" :
				$result = $this->get_column_value_actions($media_id);
				break;
				
			case "column-filesize" :
				$file 	= wp_get_attachment_url($p->ID);
				$abs	= str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file);
				if ( file_exists($abs) ) {
					$result = $this->get_readable_filesize(filesize($abs));
				}
				break;
			
			// Custom Field
			case "column-meta" :
				$result = $this->get_column_value_custom_field($media_id, $column_name, 'post');		
				break;
			
			// Image metadata EXIF or IPTC data			
			case "column-image-aperture" :
				$result = !empty( $meta['image_meta']['aperture'] ) ? $meta['image_meta']['aperture'] : '';				
				break;
				
			case "column-image-credit" :
				$result = !empty( $meta['image_meta']['credit'] ) ? $meta['image_meta']['credit'] : '';				
				break;
				
			case "column-image-camera" :
				$result = !empty( $meta['image_meta']['camera'] ) ? $meta['image_meta']['camera'] : '';				
				break;
			
			case "column-image-caption" :
				$result = !empty( $meta['image_meta']['caption'] ) ? $meta['image_meta']['caption'] : '';				
				break;
			
			case "column-image-created_timestamp" :
				if ( !empty( $meta['image_meta']['created_timestamp'] ) ) {
					$result = date_i18n( get_option('date_format') . ' ' . get_option('time_format') , strtotime($meta['image_meta']['created_timestamp']) );
				}		
				break;
				
			case "column-image-copyright" :
				$result = !empty( $meta['image_meta']['copyright'] ) ? $meta['image_meta']['copyright'] : '';				
				break;
				
			case "column-image-focal_length" :
				$result = !empty( $meta['image_meta']['focal_length'] ) ? $meta['image_meta']['focal_length'] : '';				
				break;
			
			case "column-image-iso" :
				$result = !empty( $meta['image_meta']['iso'] ) ? $meta['image_meta']['iso'] : '';				
				break;
			
			case "column-image-shutter_speed" :
				$result = !empty( $meta['image_meta']['shutter_speed'] ) ? $meta['image_meta']['shutter_speed'] : '';				
				break;
			
			case "column-image-title" :
				$result = !empty( $meta['image_meta']['title'] ) ? $meta['image_meta']['title'] : '';				
				break;
				
			default :
				$result = '';
			
		endswitch;
		
		// Filter for customizing the result output
		apply_filters('cpac-media-column-result', $result, $type, $column_name, $media_id);
		
		echo $result;
	}
	
	/**
	 *	Get column value of media actions
	 *
	 *	This part is copied from the Media List Table class
	 *
	 * 	@since     1.4.2
	 */
	private function get_column_value_actions( $id ) 
	{	
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');
		
		// we need class to get the object actions
		$m = new WP_Media_List_Table;
		
		// prevent php notice
		$m->is_trash = isset( $_REQUEST['status'] ) && 'trash' == $_REQUEST['status'];
		
		// get media actions
		$media 		= get_post($id);
		$actions 	= $m->_get_row_actions( $media, _draft_or_post_title($id) );
		
		return implode(' | ', $actions);
	}
}

?>