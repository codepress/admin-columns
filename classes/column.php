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
			'is_registered'		=> true,	// Should the column be registered based on conditional logic, example usage see: 'post/page-template.php'
			'is_cloneable'		=> false,	// Should the column be registered based on conditional logic, example usage see: 'post/page-template.php'
		);

		// merge arguments with defaults. turn into object for easy handling
		$properties = array_merge( $default_properties, $this->properties );

		// set column name to column type
		$properties['name'] = $properties['type'];

		// show
		if ( method_exists( $this, 'apply_conditional' ) )
			$properties['is_registered'] = $this->apply_conditional();

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

		// add stored options
		$this->populate_options();
	}

	/**
	 * Is active?
	 *
	 * @since 2.0.0
	 * @return bool true | false
	 */
	function is_active() {

		if ( 'on' !== $this->options->state )
			return false;

		return true;
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
	 * Shorten URL - Value method
	 *
	 * @since 1.3.1
	 */
	protected function get_shorten_url( $url = '' )
	{
		if ( ! $url )
			return false;

		return "<a title='{$url}' href='{$url}'>" . url_shorten( $url ) . "</a>";
	}

	/**
	 * Strip tags and trim - Value method
	 *
	 * @since 1.3
	 */
	protected function strip_trim( $string )
	{
		return trim( strip_tags( $string ) );
	}

	/**
	 * Returns excerpt - Value method
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
	 * Returns shortened string - Value method
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
	 * Get image from assets folder - Value method
	 *
	 * @since 1.3.1
	 *
	 * @param string $name
	 * @param string $title
	 * @return string HTML img element
	 */
	public function get_asset_image( $name = '', $title = '' ) {
		if ( ! $name )
			return false;

		return sprintf( "<img alt='' src='%s' title='%s'/>", CPAC_URL . "assets/images/{$name}", $title );
	}

	/**
	 * Checks an URL for image extension - Value method
	 *
	 * @since 1.2.0
	 *
	 * @param string $url
	 * @return bool
	 */
	protected function is_image( $url ) {

		if ( ! is_string( $url ) )
			return false;

		$validExt  	= array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
		$ext    	= strrchr( $url, '.' );

		return in_array( $ext, $validExt );
	}

	/**
	 * Get all image sizes - Value method
	 *
	 * @since 1.0.0
	 *
	 * @return array Image Sizes.
	 */
	function get_all_image_sizes() {
		$image_sizes = array(
			'thumbnail'	=>	__( "Thumbnail", 'cpac' ),
			'medium'	=>	__( "Medium", 'cpac' ),
			'large'		=>	__( "Large", 'cpac' ),
			'full'		=>	__( "Full", 'cpac' )
		);

		foreach( get_intermediate_image_sizes() as $size ) {
			if ( ! isset( $image_sizes[$size] ) ) {
				$image_sizes[$size] = ucwords( str_replace( '-', ' ', $size) );
			}
		}

		return $image_sizes;
	}

	/**
	 * Get Image Sizes by name - Value method
	 *
	 * @since 2.0.0
	 *
	 * @param string $name
	 * @return array Image Sizes
	 */
	function get_image_size_by_name( $name = '' ) {
		if ( ! $name )
			return false;

		global $_wp_additional_image_sizes;

		if ( ! isset( $_wp_additional_image_sizes[ $name ] ) )
			return false;

		return $_wp_additional_image_sizes[ $name ];
	}

	/**
	 * Image Resize - Value method
	 *
	 * @see image_resize()
	 * @since 2.0.0
	 *
	 * @return string Image URL
	 */
	function image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {

		$resized = false;

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


		return $resized;
	}

	/**
	 * Get thumbnails - Value method
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $meta Image files or Image ID's
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

		// turn string to array
		if ( is_string( $meta ) ) {
			if ( strpos( $meta, ',' ) !== false ) {
				$meta = array_filter( explode( ',', $this->strip_trim( str_replace( ' ', '', $meta ) ) ) );
			}
			else  {
				$meta = array( $meta );
			}
		}

		foreach( $meta as $value ) {

			// Image
			if ( $this->is_image( $value ) ) {

				if ( $sizes = $this->get_image_size_by_name( $args['image_size'] ) ) {
					$args['image_size_w'] = $sizes['image_size_w'];
					$args['image_size_h'] = $sizes['image_size_w'];
				}

				// get correct image path
				$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $value );

				// resize image
				if ( file_exists( $image_path ) ) {

					// try to resize image
					if ( $resized = $this->image_resize( $image_path, $args['image_size_w'], $args['image_size_h'], true ) ) {
						$output = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) .  "' alt='' width='{$args['image_size_w']}' height='{$args['image_size_h']}' />";
					}

					// resizing failed so let's return full image with maxed dimensions
					else {
						$output = "<img src='{$value}' alt='' style='max-width:{$args['image_size_w']}px;max-height:{$args['image_size_h']}px' />";
					}
				}
			}

			// Media Attachment
			else {

				// valid image?
				if ( ! is_numeric( $value ) || ! wp_get_attachment_url( $value ) )
					continue;

				// image attributes
				$attributes = wp_get_attachment_image_src( $value, $args['image_size'] );
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
	 * Implode for multi dimensional array - Value method
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
	 * Get date - Value method
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
	 * Get time - Value method
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
	 * Get Custom FieldType Options - Value method
	 *
	 * @since 2.0.0.0
	 *
	 * @return array Customfield types.
	 */
	public function get_custom_field_types() {

		$custom_field_types = array(
			''				=> __( 'Default'),
			'image'			=> __( 'Image', 'cpac' ),
			'library_id'	=> __( 'Media Library', 'cpac' ),
			'excerpt'		=> __( 'Excerpt'),
			'array'			=> __( 'Multiple Values', 'cpac' ),
			'numeric'		=> __( 'Numeric', 'cpac' ),
			'date'			=> __( 'Date', 'cpac' ),
			'title_by_id'	=> __( 'Post Title (Post ID\'s)', 'cpac' ),
			'user_by_id'	=> __( 'Username (User ID\'s)', 'cpac' ),
			'checkmark'		=> __( 'Checkmark (true/false)', 'cpac' ),
			'color'			=> __( 'Color', 'cpac' ),
		);

		return apply_filters( 'cpac_custom_field_types', $custom_field_types );
	}

	/**
	 * Get Title by ID - Value method
	 *
	 * @since 1.3.0
	 *
	 * @param string $meta
	 * @return string Titles
	 */
	private function get_titles_by_id( $meta ) {
		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace( ' ','', $meta ) );
		// var
		$ids = $titles = array();

		// check for multiple id's
		if ( strpos( $meta, ',' ) !== false )
			$ids = explode( ',', $meta );
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
	 * Get Users by ID - Value method
	 *
	 * @since 1.4.6.3
	 *
	 * @param string $meta
	 * @return string Users
	 */
	protected function get_users_by_id( $meta )
	{
		//remove white spaces and strip tags
		$meta = $this->strip_trim( str_replace( ' ', '', $meta ) );

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
	 * Display Custom Field
	 *
	 * @since 2.0.0
	 */
	protected function display_custom_field() {
		?>

		<tr class="column_field">
			<?php $this->label_view( __( "Custom Field", 'cpac' ), '', 'field' ); ?>
			<td class="input">

				<?php if ( $meta_keys = $this->storage_model->get_meta_keys() ) : ?>
				<select name="<?php $this->attr_name( 'field' ); ?>" id="<?php $this->attr_id( 'field' ); ?>">
				<?php foreach ( $meta_keys as $field ) : ?>
					<option value="<?php echo $field ?>"<?php selected( $field, $this->options->field ) ?>><?php echo substr( $field, 0, 10 ) == "cpachidden" ? str_replace( 'cpachidden','', $field ) : $field; ?></option>
				<?php endforeach; ?>
				</select>
				<?php else : ?>
					<?php _e( 'No custom fields available.', 'cpac' ); ?>
				<?php endif; ?>

			</td>
		</tr>

		<tr class="column_field_type">
			<?php $this->label_view( __( "Field Type", 'cpac' ), __( 'This will determine how the value will be displayed.', 'cpac' ), 'field_type' ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( 'field_type' ); ?>" id="<?php $this->attr_id( 'field_type' ); ?>">
				<?php foreach ( $this->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
					<option value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $this->options->field_type ) ?>><?php echo $fieldtype; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<?php
		/**
		 * Add Preview size
		 *
		 */
		$is_hidden = in_array( $this->options->field_type, array( 'image', 'library_id' ) ) ? false : true;

		$this->display_field_preview_size( $is_hidden );
		?>

		<tr class="column_before">
			<?php $this->label_view( __( "Before", 'cpac' ), __( 'This text will appear before the custom field value.', 'cpac' ), 'before' ); ?>
			<td class="input">
				<input type="text" class="cpac-before" name="<?php $this->attr_name( 'before' ); ?>" id="<?php $this->attr_id( 'before' ); ?>" value="<?php echo $this->options->before; ?>"/>
			</td>
		</tr>
		<tr class="column_after">
			<?php $this->label_view( __( "After", 'cpac' ), __( 'This text will appear after the custom field value.', 'cpac' ), 'after' ); ?>
			<td class="input">
				<input type="text" class="cpac-after" name="<?php $this->attr_name( 'after' ); ?>" id="<?php $this->attr_id( 'after' ); ?>" value="<?php echo $this->options->after; ?>"/>
			</td>
		</tr>
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
		$label			= __( 'Preview size', 'cpac' );

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
						<input type="radio" value="cpac-custom" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>-custom"<?php checked( $this->options->image_size, 'cpac-custom' ); ?>><?php _e( 'Custom', 'cpac' ); ?>
					</label>
					<label for="<?php $this->attr_id( $field_key ); ?>-w" class="custom-size-w<?php echo $this->options->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
						<input type="text" name="<?php $this->attr_name( 'image_size_w' ); ?>" id="<?php $this->attr_id( $field_key ); ?>-w" value="<?php echo $this->options->image_size_w; ?>" /><?php _e( 'width', 'cpac' ); ?>
					</label>
					<label for="<?php $this->attr_id( $field_key ); ?>-h" class="custom-size-h<?php echo $this->options->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
						<input type="text" name="<?php $this->attr_name( 'image_size_h' ); ?>" id="<?php $this->attr_id( $field_key ); ?>-h" value="<?php echo $this->options->image_size_h; ?>" /><?php _e( 'height', 'cpac' ); ?>
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
									<div class="meta">
									<?php do_action( 'cpac_column_label_meta', $this ); ?>
									</div>
									<a href="javascript:;">
										<?php echo stripslashes( $this->options->label ); ?>
									</a>
								</div>
							</td>
							<td class="column_type">
								<div class="inner">
									<?php echo stripslashes( $this->properties->label ); ?>
								</div>
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
							<?php $this->label_view( __( 'Label', 'cpac' ), __( 'This is the name which will appear as the column header.', 'cpac' ), 'label' ); ?>
							<td class="input">
								<input class="text" type="text" name="<?php $this->attr_name( 'label' ); ?>" id="<?php $this->attr_id( 'label' ); ?>" value="<?php echo htmlspecialchars( stripslashes( $this->options->label ) ); ?>" />
							</td>
						</tr><!--.column_label-->

						<tr class="column_width">
							<?php $this->label_view( __( 'Width', 'cpac' ), '', 'width' ); ?>
							<td class="input">
								<div class="description width-decription" title="<?php _e( 'default', 'cpac' ); ?>">
									<?php echo $this->options->width > 0 ? $this->options->width . '%' : __( 'default', 'cpac' ); ?>
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

						<?php if ( $this->properties->is_cloneable || ( isset( $this->options->clone ) && $this->options->clone ) ) :	?>
						<tr class="column_action">
							<td colspan="2">
								<?php if ( null === $this->properties->clone ) : ?>
									<p class="remove-description description"><?php _e( 'This field can not be removed', 'cpac' ); ?></p>
								<?php else : ?>
									<p><a href="javascript:;" class="remove-button"><?php _e( 'Remove');?></a></p>
								<?php endif; ?>
							</td>
						</tr>
						<?php endif; ?>

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
	public function __clone() {

        // Force a copy of this->object, otherwise it will point to same object.
		$this->options 		= clone $this->options;
		$this->properties 	= clone $this->properties;
    }
}