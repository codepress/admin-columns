<?php
/**
 * _codepress_get_column_class
 *
 * @todo: turn into some factory class
 * @since 2.0.0
 *
 * @param string $storage_key
 * @param string $column_name
 * @param string $args
 *
 * @return object Column
 */
/* 
function _codepress_get_column_class( $column_name, $storage_key, $args ) {
	
	$classes = array(		
		'column-featured_image'	=> 'CPAC_Column_Post_Featured_Image',
		'column-excerpt'		=> 'CPAC_Column_Post_Excerpt',		
		
		// @todo: for each column-meta-XXXX we need to add an instance of CPAC_Column_Post_Custom_Field
		'column-meta-1'			=> 'CPAC_Column_Post_Custom_Field'		
	);
	
	// default Class
	$class = 'CPAC_Column';
	
	// defined Class
	if ( isset( $classes[$column_name] ) ) {
		$class = $classes[$column_name];
	}

	return new $class( $column_name, $storage_key, $args );
} 
*/


/**
 * CPAC_Column class
 *
 * @since 2.0.0
 */
class CPAC_Column {
		
	/**
	 * Options set by User
	 *
	 * @since 2.0.0
	 */
	public $options = array();

	/**
	 * Properties
	 *
	 * @since 2.0.0
	 */
	public $properties = array();
	
	/**
	 * Get Value
	 *
	 * @todo make interface
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {}

	/**
	 * Get Sortable vars
	 *
	 * @todo make interface
	 * @since 2.0.0
	 *
	 * @param array $vars Requested WP_Query vars
	 * @param array $posts Posts
	 * @return array WP_Query vars
	 */
	function get_sortable_vars( $vars, $posts = array() ) {}
	
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
	 */
	public function __construct( $storage_key, $column_name = '', $label = '' ) {
		
		// every column contains these default properties
		$default_properties = apply_filters( 'cpac_column_default_properties', array(
			'storage_key'	=> $storage_key,	// Unique key for the Type which this columns belongs too.
			'column_name'	=> $column_name, 	// Unique name for this column.
			'type_label'	=> $label,			// Label which describes this column.
			'classes'		=> '',				// Custom CSS classes for this column.
		));
		
		// merge arguments with defaults. turn into object for easy handling
		$this->properties = (object) array_merge( $default_properties, $this->properties );

		// every column contains these default options
		$default_options = apply_filters( 'cpac_column_default_options', array(
			'label'			=> $label,	// Label for this column.
			'width'			=> null,	// Width for this column.
			'state'			=> false,	// Active state for this column.
		));

		// merge arguments with defaults and stored options. turn into object for easy handling
		$this->options = (object) array_merge( $default_options, $this->options, $this->get_options() );
	}
	
	/**
	 * Set Column Name
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name	 
	 */
	function set_column_name( $column_name ) {
	
		$this->properties->column_name = $column_name;
	}
	
	/**
	 * Get Column options
	 *
	 * @since 2.0.0
	 *
	 * @return array Column options
	 */
	function get_options() {
		
		$options = get_option('cpac_options');
		
		if ( empty( $options['columns'] ) || empty( $options['columns'][$this->properties->storage_key] ) || empty( $options['columns'][$this->properties->storage_key][$this->properties->column_name] ) )
			return array();
		
		return $options['columns'][$this->properties->storage_key][$this->properties->column_name];
	}
	
	/**
	 * Get Attribute Name
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function attr_name( $field_key ) {
		echo "cpac_options[columns][{$this->properties->storage_key}][{$this->properties->column_name}][{$field_key}]";
	}
	
	/**
	 * Get Attribute ID
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function attr_id( $field_key ) {
		echo "cpac-{$this->properties->storage_key}-{$this->properties->column_name}-{$field_key}";
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
	 * Label view
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	function label_view( $label, $description = '', $pointer = '' ) {		
		?>
		<td class="label">
			<label for="cpac-<?php echo "{$this->properties->storage_key}-{$this->properties->column_name}-{$pointer}"; ?>">
				<?php echo $label; ?>
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
	
	public function display() {
		
		// classes
		$active 	= 'on' == $this->options->state ? 'active' : '';
		$classes 	= implode( ' ', array_filter( array ( "cpac-box-{$this->properties->column_name}", $this->properties->classes, $active ) ) );
		
		$licenses = CPAC_Utility::get_licenses();
		
		?>
		<div class="cpac-column <?php echo $classes; ?>">
			<div class="column-meta">
				<table class="widefat">
					<tbody>
						<tr>
							<td class="column_sort"></td>
							<td class="column_status">
								<input type="hidden" class="cpac-state" name="<?php echo $this->attr_name( 'state' ); ?>" value="<?php echo $this->options->state; ?>" id="<?php echo $this->attr_id( 'state' ); ?>"/>
							</td>
							<td class="column_label">
								<a href="javascript:;">
									<?php echo $this->options->label; ?>
								</a>
								<span class="meta-label">
								<?php //if ( $licenses['sortable']->is_unlocked() && ( $this->options->sort || in_array( $this->properties->column_name, array( 'title', 'date' ) ) ) ) : ?>
									<span class="sorting enable"><?php _e( 'sorting',  CPAC_TEXTDOMAIN )?></span>
								<?php //endif; ?>
								</span>
							</td>
							<td class="column_type">
								<?php echo $this->properties->type_label; ?>
							</td>
							<td class="column_edit"></td>
						</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="column-form">
				<table class="widefat">
					<tbody>
						<tr class="column_label">						
							<?php $this->label_view( __( 'Label', CPAC_TEXTDOMAIN ), __( 'This is the name which will appear as the column header.', CPAC_TEXTDOMAIN ), 'label' ); ?>							
							<td class="input">
								<input class="text" type="text" name="<?php $this->attr_name( 'label' ); ?>" id="<?php $this->attr_id( 'label' ); ?>" value="<?php echo esc_attr( $this->options->label ); ?>" />
							</td>
						</tr>
						<tr class="column_width">						
							<?php $this->label_view( __( 'Width', CPAC_TEXTDOMAIN ), '', 'width' ); ?>
							<td class="input">
								<div class="description width-decription" title="<?php _e( 'default', CPAC_TEXTDOMAIN ); ?>">
									<?php echo $this->options->width > 0 ? $this->options->width . '%' : __( 'default', CPAC_TEXTDOMAIN ); ?>
								</div>
								<div class="input-width-range"></div>
								<input type="hidden" class="input-width" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo $this->options->width; ?>" />
								
							</td>
						</tr>
						
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
}