<?php
/**
 * CPAC_Column class
 *
 * @since 2.0.0
 *
 * @param object $storage_model CPAC_Storage_Model
 */
class CPAC_Column {
	
	/**
	 * Storage Model
	 *
	 * $storage_model contains a CPAC_Storage_Model object which the column belongs too. 
	 * A Storage Model can be a Posttype, User, Comment, Link or Media storage type.
	 *
	 * @since 2.0.0
	 */
	protected $storage_model;
	
	/**
	 * Options
	 *
	 * $options contains the user set options for the CPAC_Column object.
	 *
	 * @since 2.0.0
	 */
	public $options = array();

	/**
	 * Properties
	 *
	 * $properties describes the fixed properties for the CPAC_Column object.
	 *
	 * @since 2.0.0
	 */
	public $properties = array();
	
	/**
	 * Get value
	 *
	 * Returns the value for the column.
	 * 
	 * @since 2.0.0	
	 * @param int $postid Post ID
	 * @return string Value
	 */
	function get_value( $postid ) {}
	
	/**
	 * Display_settings
	 *
	 * @since 2.0.0	
	 */
	function display_settings() {}
	
	/**
	 * Defaults
	 *
	 * @since 2.0.0
	 *
	 * @param object $storage_model CPAC_Storage_Model
	 */
	public function __construct( CPAC_Storage_Model $storage_model ) {

		$this->storage_model = $storage_model;
		
		// every column contains these default properties
		$default_properties = array(						
			'type'				=> null,  	// Unique type
			'name'				=> null,  	// Unique name
			'clone'				=> null,	// Unique clone ID
			'label'				=> null,  	// Label which describes this column.			
			'classes'			=> null,	// Custom CSS classes for this column.
			'hide_label'		=> false,	// Should the Label be hidden?
		);		

		// merge arguments with defaults. turn into object for easy handling
		$properties = array_merge( $default_properties, $this->properties );
				
		// set column name to column type
		$properties['name'] = $properties['type'];
		
		// add filters
		$properties = apply_filters( 'cpac_column_properties', $properties );
		$properties = apply_filters( "cpac_column_properties_{$this->storage_model->key}", $properties );
		
		// convert to object for easy handling
		$this->properties = (object) $properties;
		
		// every column contains these default options
		$default_options = array(
			'label'			=> $this->properties->label,	// Label for this column.
			'width'			=> null,						// Width for this column.
			'state'			=> 'off',						// Active state for this column.
		);
		
		// add filters
		$default_options = apply_filters( 'cpac_column_default_options', $default_options, $this );
		$default_options = apply_filters( "cpac_column_default_options_{$this->storage_model->key}", $default_options, $this );
		
		// merge arguments with defaults and stored options. turn into object for easy handling
		$this->options = (object) array_merge( $default_options, $this->options );
		
		$this->populate_options();
	}
	
	/**
	 * Populate Options
	 *
	 * @since 2.0.0
	 * @return object
	 */
	 function populate_options() {	

		$this->options = (object) array_merge( (array) $this->options, $this->read() );
	}
	
	/**
	 * Set Properties
	 *
	 * @param string $property
	 * @return mixed $value
	 */
	function set_properties( $property, $value ) {
				
		$this->properties->{$property} = $value;
		
		return $this;
	}
	
	/**
	 * Set Options
	 *
	 * @param string $option
	 * @return mixed $value
	 */
	function set_options( $option, $value ) {

		$this->options->{$option} = $value;
		
		return $this;
	}
		
	/**
	 * Set Clone
	 *
	 * @param int $id
	 * @return object
	 */
	function set_clone( $id = null ) {
		
		if ( $id !== null && $id > 0 ) {

			$this->properties->name = "{$this->properties->type}-{$id}";
			$this->properties->clone = $id;
		}	
				
		return $this;
	}
	
	/**
	 * Get Attribute Name
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function attr_name( $field_name ) {
		echo "columns[{$this->properties->name}][{$field_name}]";
	}
	
	/**
	 * Get Attribute ID
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function attr_id( $field_name ) {
		echo "cpac-{$this->storage_model->key}-{$this->properties->name}-{$field_name}";
	}
	
	/**
	 * Read options
	 *
	 * @since 2.0.0
	 *
	 * @return array Column options
	 */
	function read() {
		
		$options = (array) get_option( "cpac_options_{$this->storage_model->key}" );

		if ( empty( $options[ $this->properties->name ] ) )
			return array();

		return $options[ $this->properties->name ];
	}
	
	/**
	 * Returns excerpt
	 *
	 * @todo: options for excerpt length
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

		$output = $this->get_shortened_string( $excerpt, 20 );

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
	 * Get all image sizes
	 *
	 * @since 1.0.0
	 *
	 * @return array Image Sizes.
	 */
	function get_all_image_sizes() {
		$image_sizes = array(
			'thumbnail'	=>	__( "Thumbnail", CPAC_TEXTDOMAIN ),
			'medium'	=>	__( "Medium", CPAC_TEXTDOMAIN ),
			'large'		=>	__( "Large", CPAC_TEXTDOMAIN ),
			'full'		=>	__( "Full", CPAC_TEXTDOMAIN )
		);

		foreach( get_intermediate_image_sizes() as $size ) {
			if ( ! isset( $image_sizes[$size] ) ) {
				$image_sizes[$size] = ucwords( str_replace( '-', ' ', $size) );
			}
		}

		return $image_sizes;
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
	 * Get a thumbnail
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta
	 * @param array $args
	 * @return string HTML img elements
	 */
	protected function get_thumbnails( $meta, $args = array() ) {
		if ( empty( $meta ) || 'false' == $meta )
			return false;

		$output = '';

		// Image size
		$defaults = array(
			'image_size'	=> 'cpac-custom',
			'image_size_w'	=> 80,
			'image_size_h'	=> 80,
		);
		$args = wp_parse_args( $args, $defaults );

		// Image
		if ( $this->is_image( $meta ) ) {

			if ( $sizes = $this->get_image_size_by_name( $args['image_size'] ) ) {
				$args['image_size_w'] = $sizes['image_size_w'];
				$args['image_size_h'] = $sizes['image_size_w'];
			}

			// get correct image path
			$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $meta );

			// resize image
			if ( file_exists( $image_path ) ) {

				// try to resize image
				if ( $resized = $this->image_resize( $image_path, $args['image_size_w'], $args['image_size_h'], true ) ) {
					$output = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) .  "' alt='' width='{$args['image_size_w']}' height='{$args['image_size_h']}' />";
				}

				// resizing failed so let's return full image with maxed dimensions
				else {
					$output = "<img src='{$meta}' alt='' style='max-width:{$args['image_size_w']}px;max-height:{$args['image_size_h']}px' />";
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
					$width 	= $sizes['image_size_w'];
					$height	= $sizes['image_size_h'];
				}

				// custom image size
				if ( 'cpac-custom' == $args['image_size'] ) {
					$width 	= $args['image_size_w'];
					$height = $args['image_size_h'];
				}

				// maximum dimensions
				$max = max( array( $width, $height ) );

				$output .= "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$attributes[0]}' alt=''/></span>";
			}
		}

		return $output;
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
	
	/**
	 * Label view
	 *
	 * @since 2.0.0
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function label_view( $label, $description = '', $pointer = '' ) {		
		?>
		<td class="label">
			<label for="<?php $this->attr_id( $pointer ); ?>">
				<?php echo stripslashes( $label ); ?>
			</label>
			<?php if( $description ) : ?>
			<p class="description"><?php echo $description; ?></p>
			<?php endif; ?>
		</td>
		<?php
	}
	
	/**
	 * Display field Preview Size
	 *
	 * @todo function to create input fields
	 * @since 2.0.0
	 *
	 * @param bool $is_hidden Hides the table row by adding the class 'hidden'.
	 */
	function display_field_preview_size( $is_hidden = false ) {
	
		$field_key		= 'image_size';
		$label			= __( 'Preview size', CPAC_TEXTDOMAIN );
		
		?>
		<tr class="column_<?php echo $field_key; ?><?php echo $is_hidden ? ' hidden' : ''; ?>">
			
			<?php $this->label_view( $label, '', $field_key ); ?>
			
			<td class="input">			
				<?php foreach ( $sizes = $this->get_all_image_sizes() as $id => $image_label ) : ?>
					<label for="<?php $this->attr_id( $field_key ); ?>-<?php echo $id ?>" class="custom-size">
						<input type="radio" value="<?php echo $id; ?>" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-<?php echo $id ?>"<?php checked( $this->options->image_size, $id ); ?>>
						<?php echo $image_label; ?>
					</label>
				<?php endforeach; ?>
				
				<div class="custom_image_size">
					<label for="<?php $this->attr_id( $field_key ); ?>-custom" class="custom-size image-size-custom" >
						<input type="radio" value="cpac-custom" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-custom"<?php checked( $this->options->image_size, 'cpac-custom' ); ?>><?php _e( 'Custom', CPAC_TEXTDOMAIN ); ?>
					</label>
					<label for="<?php $this->attr_id( $field_key ); ?>-w" class="custom-size-w<?php echo $this->options->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
						<input type="text" name="<?php $this->attr_name( 'image_size_w' ); ?>" id="<?php $this->attr_id( $field_key ); ?>-w" value="<?php echo $this->options->image_size_w; ?>" /><?php _e( 'width', CPAC_TEXTDOMAIN ); ?>
					</label>
					<label for="<?php $this->attr_id( $field_key ); ?>-h" class="custom-size-h<?php echo $this->options->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
						<input type="text" name="<?php $this->attr_name( 'image_size_h' ); ?>" id="<?php $this->attr_id( $field_key ); ?>-h" value="<?php echo $this->options->image_size_h; ?>" /><?php _e( 'height', CPAC_TEXTDOMAIN ); ?>
					</label>
				</div>
			</td>
		</tr>
<?php
	}	
	
	/**
	 * Display
	 *
	 * @since 2.0.0
	 */
	public function display() {
				
		// classes
		$active 	= 'on' == $this->options->state ? 'active' : '';
		$classes 	= implode( ' ', array_filter( array ( "cpac-box-{$this->properties->name}", $this->properties->classes, $active ) ) );

		?>
				
		<div class="cpac-column <?php echo $classes; ?>" data-type="<?php echo $this->properties->type; ?>" data-clone="<?php echo $this->properties->clone; ?>">
			<div class="column-meta">
				<input type="hidden" name="<?php echo $this->attr_name( 'type' ); ?>" value="<?php echo $this->properties->type; ?>" />
				<input type="hidden" class="clone" name="<?php echo $this->attr_name( 'clone' ); ?>" value="<?php echo $this->properties->clone; ?>" />
				<table class="widefat">
					<tbody>
						<tr>
							<td class="column_sort"></td>
							<td class="column_status">
								<input type="hidden" class="cpac-state" name="<?php echo $this->attr_name( 'state' ); ?>" value="<?php echo $this->options->state; ?>" id="<?php echo $this->attr_id( 'state' ); ?>"/>
							</td>
							<td class="column_label">
								<div class="inner">
									<a href="javascript:;">
										<?php echo stripslashes( $this->options->label ); ?>
									</a>
									<div class="column_label_meta">
									
									<?php do_action( 'cpac_column_label_meta', $this ); ?>
									
									</div>
								</div>
							</td>
							<td class="column_type">
								<?php echo stripslashes( $this->properties->label ); ?>
							</td>
							<td class="column_edit"></td>
						</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="column-form">
				<table class="widefat">
					<tbody>
						
						<tr class="column_label<?php echo $this->properties->hide_label ? ' hidden' : ''; ?>">						
							<?php $this->label_view( __( 'Label', CPAC_TEXTDOMAIN ), __( 'This is the name which will appear as the column header.', CPAC_TEXTDOMAIN ), 'label' ); ?>							
							<td class="input">								
								<input class="text" type="text" name="<?php $this->attr_name( 'label' ); ?>" id="<?php $this->attr_id( 'label' ); ?>" value="<?php echo htmlspecialchars( stripslashes( $this->options->label ) ); ?>" />
							</td>
						</tr><!--.column_label-->
						
						<tr class="column_width">						
							<?php $this->label_view( __( 'Width', CPAC_TEXTDOMAIN ), '', 'width' ); ?>
							<td class="input">
								<div class="description width-decription" title="<?php _e( 'default', CPAC_TEXTDOMAIN ); ?>">
									<?php echo $this->options->width > 0 ? $this->options->width . '%' : __( 'default', CPAC_TEXTDOMAIN ); ?>
								</div>
								<div class="input-width-range"></div>
								<input type="hidden" class="input-width" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo $this->options->width; ?>" />
								
							</td>
						</tr><!--.column_width-->
						
						<?php do_action( 'cpac_before_column_settings', $this ); ?>
						
						<?php 
						/**
						 * Load specific column settings.
						 *
						 */
						$this->display_settings(); 
						?>
						
						<?php do_action( 'cpac_after_column_settings', $this ); ?>			
						
					</tbody>
				</table>
			</div><!--.column-form-->
		</div><!--.cpac-column-->
		
		<?php	
	}
	
	/**
	 * Clone method
	 *
	 * An object copy is created by using the clone keyword which calls the object's __clone() method.	 
	 *
	 * @since 2.0.0
	 */
	public function __clone()
    {
        // Force a copy of this->object, otherwise it will point to same object.		
		$this->options 		= clone $this->options;
		$this->properties 	= clone $this->properties;		
    }
}