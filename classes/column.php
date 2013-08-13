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
	 * A Storage Model can be a Posttype, User, Comment, Link or Media storage type.
	 *
	 * @since 2.0.0
	 * @var object $storage_model contains a CPAC_Storage_Model object which the column belongs too.
	 */
	public $storage_model;

	/**
	 * Options
	 *
	 * @since 2.0.0
	 * @var array $options contains the user set options for the CPAC_Column object.
	 */
	public $options = array();

	/**
	 * Options - Default
	 *
	 * @since 2.0.0
	 * @var object $options_default contains the options for the CPAC_Column object before they are populated with user input.
	 */
	protected $options_default;

	/**
	 * Properties
	 *
	 * @since 2.0.0
	 * @var array $properties describes the fixed properties for the CPAC_Column object.
	 */
	public $properties = array();

	/**
	 * Get value
	 *
	 * Returns the value for the column.
	 *
	 * @since 2.0.0
	 * @param int $id ID
	 * @return string Value
	 */
	public function get_value( $id ) {}

	/**
	 * Display_settings
	 *
	 * @since 2.0.0
	 */
	protected function display_settings() {}

	/**
	 * Sanitize_options
	 *
	 * Overwrite this function in child class to sanitize
	 * user submitted values.
	 *
	 * @since 2.0.0
	 * @param $options array User submitted column options
	 * @return array Options
	 */
	protected function sanitize_options( $options ) {

		if ( isset( $options['date_format'] ) )
			$options['date_format'] = trim( $options['date_format'] );

		return $options;
	}

	/**
	 * Clone method
	 *
	 * An object copy (clone) is created for creating multiple column instances.
	 *
	 * @since 2.0.0
	 */
	public function __clone() {

        // Force a copy of this->object, otherwise it will point to same object.
		$this->options 		= clone $this->options;
		$this->properties 	= clone $this->properties;
    }

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 *
	 * @param object $storage_model CPAC_Storage_Model
	 */
	function __construct( CPAC_Storage_Model $storage_model ) {

		$this->storage_model = $storage_model;

		// every column contains these default properties
		$default_properties = array(
			'clone'				=> null,	// Unique clone ID
			'type'				=> null,  	// Unique type
			'name'				=> null,  	// Unique name
			'label'				=> null,  	// Label which describes this column.
			'classes'			=> null,	// Custom CSS classes for this column.
			'hide_label'		=> false,	// Should the Label be hidden?
			'is_registered'		=> true,	// Should the column be registered based on conditional logic, example usage see: 'post/page-template.php'
			'is_cloneable'		=> true,	// Should the column be cloneable
		);

		// merge arguments with defaults. turn into object for easy handling
		$properties = array_merge( $default_properties, $this->properties );

		// set column name to column type
		$properties['name'] = $properties['type'];

		// apply conditional statements wheter this column should be available or not.
		if ( method_exists( $this, 'apply_conditional' ) )
			$properties['is_registered'] = $this->apply_conditional();

		// add filters
		$properties = apply_filters( 'cac/column/properties', $properties );
		$properties = apply_filters( "cac/column/properties/storage_key={$this->storage_model->key}", $properties );

		// convert to object for easy handling
		$this->properties = (object) $properties;

		// every column contains these default options
		$default_options = array(
			'label'			=> $this->properties->label,	// Label for this column.
			'width'			=> null,						// Width for this column.
			'state'			=> 'off',						// Active state for this column.
		);

		// add filters
		$default_options = apply_filters( 'cac/column/options', $default_options, $this );
		$default_options = apply_filters( "cac/column/options/storage_key={$this->storage_model->key}", $default_options, $this );

		// merge arguments with defaults and stored options. turn into object for easy handling
		$this->options = (object) array_merge( $default_options, $this->options );

		// set default options before populating
		$this->options_default = $this->options;

		// add stored options
		$this->populate_options();

		// sanitize label
		$this->sanitize_label();
	}

	/**
	 * Populate Options
	 *
	 * @since 2.0.0
	 * @return object
	 */
	public function populate_options() {

		$this->options = (object) array_merge( (array) $this->options, $this->read() );
	}

	/**
	 * Set Properties
	 *
	 * @param string $property
	 * @return mixed $value
	 */
	public function set_properties( $property, $value ) {

		$this->properties->{$property} = $value;

		return $this;
	}

	/**
	 * Set Options
	 *
	 * @param string $option
	 * @return mixed $value
	 */
	public function set_options( $option, $value ) {

		$this->options->{$option} = $value;

		return $this;
	}

	/**
	 * Set Clone
	 *
	 * @param int $id
	 * @return object
	 */
	public function set_clone( $id = null ) {

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
	public function attr_name( $field_name ) {

		echo "{$this->storage_model->key}[{$this->properties->name}][{$field_name}]";
	}

	/**
	 * Get Attribute ID
	 *
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	public function attr_id( $field_name ) {
		echo "cpac-{$this->storage_model->key}-{$this->properties->name}-{$field_name}";
	}

	/**
	 * Read options
	 *
	 * @since 2.0.0
	 *
	 * @return array Column options
	 */
	public function read() {
		$options = (array) get_option( "cpac_options_{$this->storage_model->key}" );

		if ( empty( $options[ $this->properties->name ] ) )
			return array();

		return $options[ $this->properties->name ];
	}

	/**
	 * Santize Label
	 *
	 * @since 2.0.0
	 */
	public function sanitize_label() {

		// check if original label has changed. Example WPML adds a language column, the column heading will have to display the added flag.
		if ( $this->properties->hide_label && $this->properties->label !== $this->options->label ) {
			$this->options->label = $this->properties->label;
		}

		// replace urls, so export will not have to deal with them
		$this->options->label = stripslashes( str_replace( '[cpac_site_url]', site_url(), $this->options->label ) );
	}

	/**
	 * Sanitize storage
	 *
	 * Sanitizes options.
	 *
	 * @since 2.0.0
	 * @param $options array User submitted column options
	 * @return array Options
	 */
	public function sanitize_storage( $options ) {

		// excerpt length must be numeric, else we will return it's default
		if ( isset( $options['excerpt_length'] ) ) {
			$options['excerpt_length'] = trim( $options['excerpt_length'] );
			if ( empty( $options['excerpt_length'] ) || ! is_numeric( $options['excerpt_length'] ) ) {
				$options['excerpt_length'] = $this->options_default->excerpt_length;
			}
		}

		if ( ! empty( $options['label'] ) ) {

			// Label can not contains the character ':', because
			// CPAC_Column::get_sanitized_label() will return an empty string
			$options['label'] = str_replace( ':', '', $options['label'] );
		}

		// used by child classes for additional sanitizing
		$options = $this->sanitize_options( $options );

		return $options;
	}

	/**
	 * Get Label
	 *
	 * @since 2.0.0
	 */
	function get_label() {

		return apply_filters( 'cac/column/get_label', stripslashes( str_replace( '[cpac_site_url]', site_url(), $this->options->label ) ), $this );
	}

	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string
	 * @return string Sanitized string
	 */
	public function get_sanitized_label() {
		$string = esc_url( $this->options->label );
		$string = str_replace( 'http://', '', $string );
		$string = str_replace( 'https://', '', $string );

		return $string;
	}

	/**
	 * Set cache objects
	 *
	 * @since 2.0.0
	 *
	 * @param $id Cache ID
	 * @param $cache_object Cache Object
	 */
	function set_cache( $id, $cache_object ) {

		if ( empty( $cache_object ) )
			return false;

		$cache_name = $this->storage_model->key . $this->properties->name . $id;

		if ( strlen( $cache_name ) > 64 ) {
			trigger_error( 'Cache name too long.' );
			return false;
		}

		set_transient( $cache_name, $cache_object );
	}

	/**
	 * Get cache objects
	 *
	 * @since 2.0.0
	 *
	 * @param $id Cache ID ( could be a name of an addon for example )
	 * @return false | mixed Returns either false or the cached objects
	 */
	function get_cache( $id ) {
		$cache = get_transient( $this->storage_model->key . $this->properties->name . $id );
		if( empty( $cache ) )
			return false;

		return $cache;
	}

	/**
	 * Delete cache objects
	 *
	 * @since 2.0.0
	 *
	 * @param $id Cache ID
	 */
	function delete_cache( $id ) {

		delete_transient( $this->storage_model->key . $this->properties->name . $id );
	}

	/**
	 * Shorten URL - Value method
	 *
	 * @since 1.3.1
	 */
	protected function get_shorten_url( $url = '' ) {
		if ( ! $url )
			return false;

		return "<a title='{$url}' href='{$url}'>" . url_shorten( $url ) . "</a>";
	}

	/**
	 * Strip tags and trim - Value method
	 *
	 * @since 1.3
	 */
	protected function strip_trim( $string ) {

		return trim( strip_tags( $string ) );
	}

	/**
	 * Returns excerpt - Value method
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id, $words )	{
		global $post;

		$save_post 	= $post;
		$post 		= get_post( $post_id );
		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;

		$output = $this->get_shortened_string( $excerpt, $words );

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
	protected function get_shortened_string( $text = '', $num_words = 30, $more = null ) {
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

		return sprintf( "<img alt='' src='%s' title='%s'/>", CPAC_URL . "assets/images/{$name}", esc_attr( $title ) );
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
		if ( ! $name || is_array( $name ) )
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
	 * @return array HTML img elements
	 */
	public function get_thumbnails( $images, $args = array() ) {
		if ( empty( $images ) || 'false' == $images )
			return array();

		$thumbnails = array();

		// turn string to array
		if ( is_string( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', $this->strip_trim( str_replace( ' ', '', $images ) ) ) );
			}
			else  {
				$images = array( $images );
			}
		}

		// Image size
		$defaults = array(
			'image_size'	=> 'cpac-custom',
			'image_size_w'	=> 80,
			'image_size_h'	=> 80,
		);
		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		// foreach image
		foreach( $images as $value ) {

			// Image
			if ( $this->is_image( $value ) ) {

				// get dimensions from image_size
				if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
					$image_size_w = $sizes['width'];
					$image_size_h = $sizes['height'];
				}

				// get correct image path
				$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $value );

				// resize image
				if ( is_file( $image_path ) ) {

					// try to resize image
					if ( $resized = $this->image_resize( $image_path, $image_size_w, $image_size_h, true ) ) {
						$thumbnails[] = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) .  "' alt='' width='{$image_size_w}' height='{$image_size_h}' />";
					}

					// resizing failed so let's return full image with maxed dimensions
					else {
						$thumbnails[] = "<img src='{$value}' alt='' style='max-width:{$image_size_w}px;max-height:{$image_size_h}px' />";
					}
				}
			}

			// Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {

				// custom image size
				if ( ! $image_size || 'cpac-custom' == $image_size ) {
					$width 		= $image_size_w;
					$height 	= $image_size_h;

					// to make sure wp_get_attachment_image_src() get the image with matching dimensions.
					$image_size = array( $width, $height );
				}

				// image attributes
				$attributes = wp_get_attachment_image_src( $value, $image_size );
				$src 		= $attributes[0];
				$width		= $attributes[1];
				$height		= $attributes[2];

				// image size by name
				if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
					$width 	= $sizes['image_size_w'];
					$height	= $sizes['image_size_h'];
				}

				// maximum dimensions
				$max = max( array( $width, $height ) );

				$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$attributes[0]}' alt=''/></span>";
			}
		}

		return $thumbnails;
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
	protected function recursive_implode( $glue, $pieces ) {
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
	 * Get timestamp
	 *
	 * @since  2.0.0
	 *
	 * @param string $date
	 * @return string Formatted date
	 */
	private function get_timestamp( $date ) {

		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) )
			return false;

		// some plugins store dates in a jquery timestamp format, format is in ms since The Epoch
		// See http://api.jqueryui.com/datepicker/#utility-formatDate
		// credits: nmarks
		if ( is_numeric( $date ) && 13 === strlen( trim( $date ) ) ) {
			$date = substr( $date, 0, -3 );
		}

		// Parse with strtotime if it's:
		// - not numeric ( like a unixtimestamp )
		// - date format: yyyymmdd ( format used by ACF ) must start with 19xx or 20xx and is 8 long

		// @todo: in theory a numeric string of 8 can also be a unixtimestamp.
		// we need to replace this with an option to mark a date as unixtimestamp.
		if ( ! is_numeric( $date ) || ( is_numeric( $date ) && strlen( trim( $date ) ) == 8 && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0  ) ) ) {
			$date = strtotime( $date );
		}

		return $date;
	}

	/**
	 * Get date - Value method
	 *
	 * @since 1.3.1
	 *
	 * @param string $date
	 * @return string Formatted date
	 */
	protected function get_date( $date, $format = '' ) {

		if ( ! $date = $this->get_timestamp( $date ) )
			return false;

		if ( ! $format )
			$format = get_option( 'date_format' );

		return date_i18n( $format, $date );
	}

	/**
	 * Get time - Value method
	 *
	 * @since 1.3.1
	 *
	 * @param string $date
	 * @return string Formatted time
	 */
	protected function get_time( $date, $format = '' ) {

		if( ! $date = $this->get_timestamp( $date ) )
			return false;

		if ( ! $format )
			$format = get_option( 'time_format' );

		return date_i18n( $format, $date );
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

				<?php if( $description ) : ?><p class="description"><?php echo $description; ?></p><?php endif; ?>
			</label>
		</td>
		<?php
	}

	/**
	 * Display field Date Format
	 *
	 * @since 2.0.0
	 *
	 * @param bool $is_hidden Hides the table row by adding the class 'hidden'.
	 */
	function display_field_date_format( $is_hidden = false ) {

		$field_key		= 'date_format';
		$label			= __( 'Date Format', 'cpac' );
		$description	= __( 'This will determine how the date will be displayed.', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>"<?php echo $is_hidden ? " style='display:none'" : ''; ?>>
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->options->date_format; ?>" placeholder="<?php _e( 'Example:', 'cpac' ); ?> d M Y H:i"/>
				<p class="description">
					<?php printf( __( 'Leave empty for WordPress date format, change your <a href="%s">default date format here</a>.' , 'cpac' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ); ?>
					<a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'><?php _e( 'Documentation on date and time formatting.', 'cpac' ); ?></a>
				</p>
			</td>
		</tr>
<?php
	}

	/**
	 * Display field Excerpt
	 *
	 * @since 2.0.0
	 *
	 * @param bool $is_hidden Hides the table row by adding the class 'hidden'.
	 */
	function display_field_excerpt_length( $is_hidden = false ) {

		$field_key		= 'excerpt_length';
		$label			= __( 'Excerpt length', 'cpac' );
		$description	= __( 'Number of words', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>"<?php echo $is_hidden ? " style='display:none'" : ''; ?>>
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->options->excerpt_length; ?>"/>
			</td>
		</tr>
<?php
	}

	/**
	 * Display field Preview Size
	 *
	 * @since 2.0.0
	 *
	 * @param bool $is_hidden Hides the table row by adding the class 'hidden'.
	 */
	function display_field_preview_size( $is_hidden = false ) {

		$field_key		= 'image_size';
		$label			= __( 'Preview size', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>"<?php echo $is_hidden ? " style='display:none'" : ''; ?>>

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
	 * Get column list
	 *
	 * @since 2.0.0
	 *
	 * @param array Column Objects
	 * @return string HTML List
	 */
	public function get_column_list( $columns = array(), $label = '' ) {

		if ( empty( $columns ) )
			return array();

		$list = '';

		$_columns = array();
		foreach ( $columns as $column ) {
			$_columns[ $column->properties->type ] = 0 === strlen( strip_tags( $column->properties->label ) ) ? ucfirst( $column->properties->type ) : $column->properties->label;
		}
		asort( $_columns );

		$list = "<optgroup label='{$label}'>";
		foreach ( $_columns as $type => $label ){
			$selected = selected( $this->properties->type, $type, false );
			$list .= "<option value='{$type}'{$selected}>{$label}</option>";
		}
		$list .= "</optgroup>";

		return $list;
	}

	/**
	 * Display
	 *
	 * @since 2.0.0
	 */
	public function display() {

		// classes
		$classes = implode( ' ', array_filter( array ( "cpac-box-{$this->properties->type}", $this->properties->classes ) ) );

		// column list
		$column_list = $this->get_column_list( $this->storage_model->get_custom_registered_columns(), __( 'Custom', 'cpac' ) );
		$column_list .= $this->get_column_list( $this->storage_model->get_default_registered_columns(), __( 'Default', 'cpac' ) );

		// clone attribute
		$data_clone =  $this->properties->is_cloneable ? " data-clone='{$this->properties->clone}'" : '';

		?>
		<div class="cpac-column <?php echo $classes; ?>" data-type="<?php echo $this->properties->type; ?>"<?php echo $data_clone; ?>>
			<input type="hidden" class="type"  name="<?php echo $this->attr_name( 'type' ); ?>" value="<?php echo $this->properties->type; ?>" />
			<input type="hidden" class="clone" name="<?php echo $this->attr_name( 'clone' ); ?>" value="<?php echo $this->properties->clone; ?>" />
			<div class="column-meta">
				<table class="widefat">
					<tbody>
						<tr>
							<td class="column_sort"></td>
							<td class="column_label">
								<div class="inner">
									<div class="meta">

									<?php do_action( 'cac/column/label', $this ); ?>

									</div>
									<a class="toggle" href="javascript:;">
										<?php echo stripslashes( $this->get_label() ); ?>
									</a>
									<a class="remove-button" href="javacript:;">
										<?php _e( 'Remove', 'cpac' ); ?>
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
						<tr class="column_type">
							<?php $this->label_view( __( 'Type', 'cpac' ), __( 'Choose a column type.', 'cpac' ), 'type' ); ?>
							<td class="input">
								<select name="<?php $this->attr_name( 'type' ); ?>" id="<?php $this->attr_id( 'type' ); ?>">
									<?php echo $column_list; ?>
								</select>
								<div class="msg"></div>
							</td>
						</tr><!--.column_label-->

						<tr class="column_label<?php echo $this->properties->hide_label ? ' hidden' : ''; ?>">
							<?php $this->label_view( __( 'Label', 'cpac' ), __( 'This is the name which will appear as the column header.', 'cpac' ), 'label' ); ?>
							<td class="input">
								<input class="text" type="text" name="<?php $this->attr_name( 'label' ); ?>" id="<?php $this->attr_id( 'label' ); ?>" value="<?php echo esc_attr( $this->options->label ); //echo sanitize_text_field( $this->options->label ); ?>" />
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

						<?php do_action( 'cac/column/settings_before', $this ); ?>

						<?php
						/**
						 * Load specific column settings.
						 *
						 */
						$this->display_settings();
						?>

						<?php do_action( 'cac/column/settings_after', $this ); ?>

						<tr class="column_action">
							<td colspan="2">
								<p><a href="javascript:;" class="remove-button"><?php _e( 'Remove' );?></a></p>
							</td>
						</tr>

					</tbody>
				</table>
			</div><!--.column-form-->
		</div><!--.cpac-column-->
		<?php

	}
}