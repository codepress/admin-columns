<?php
/**
 * CPAC_Column class
 *
 * @since 2.0
 *
 * @param object $storage_model CPAC_Storage_Model
 */
class CPAC_Column {

	/**
	 * A Storage Model can be a Posttype, User, Comment, Link or Media storage type.
	 *
	 * @since 2.0
	 * @var CPAC_Storage_Model $storage_model contains a CPAC_Storage_Model object which the column belongs too.
	 */
	public $storage_model;

	/**
	 * @since 2.0
	 * @var array $options contains the user set options for the CPAC_Column object.
	 */
	public $options = array();

	/**
	 * @since 2.0
	 * @var object $options_default contains the options for the CPAC_Column object before they are populated with user input.
	 */
	protected $options_default;

	/**
	 * @since 2.0
	 * @var array $properties describes the fixed properties for the CPAC_Column object.
	 */
	public $properties = array();

	/**
	 * @since 2.0
	 *
	 * @param int $id ID
	 * @return string Value
	 */
	public function get_value( $id ) {}

	/**
	 * Get the raw, underlying value for the column
	 * Not suitable for direct display, use get_value() for that
	 *
	 * @since 2.0.3
	 *
	 * @param int $id ID
	 * @return mixed Value
	 */
	public function get_raw_value( $id ) {}

	/**
	 * @since 2.0
	 */
	protected function display_settings() {}

	/**
	 * Get the sorting value. This value will be used to sort the column.
	 *
	 * @since 2.3.2
	 * @param int $id Object ID
	 * @return string Value for sorting
	 */
	public function get_sorting_value( $id ) {}

	/**
	 * Overwrite this function in child class to sanitize
	 * user submitted values.
	 *
	 * @since 2.0
	 *
	 * @param $options array User submitted column options
	 * @return array Options
	 */
	protected function sanitize_options( $options ) {

		if ( isset( $options['date_format'] ) ) {
			$options['date_format'] = trim( $options['date_format'] );
		}

		return $options;
	}

	/**
	 * Overwrite this function in child class.
	 * Determine whether this column type should be available
	 *
	 * @since 2.2
	 *
	 * @return bool Whether the column type should be available
	 */
	public function apply_conditional() {

		return true;
	}

	/**
	 * Overwrite this function in child class.
	 * Adds (optional) scripts to the listings screen.
	 *
	 * @since 2.3.4
	 */
	public function scripts() {}

	/**
	 * An object copy (clone) is created for creating multiple column instances.
	 *
	 * @since 2.0
	 */
	public function __clone() {

		// Force a copy of this->object, otherwise it will point to same object.
		$this->options 		= clone $this->options;
		$this->properties 	= clone $this->properties;
	}

	/**
	 * @since 2.0
	 *
	 * @param object $storage_model CPAC_Storage_Model
	 */
	public function __construct( CPAC_Storage_Model $storage_model ) {

		$this->storage_model = $storage_model;

		$this->init();
		$this->after_setup();
	}

	/**
	 * @since 2.2
	 */
	public function init() {

		// Default properties
		$default_properties = array(
			'clone'				=> null,	// Unique clone ID
			'type'				=> null,  	// Unique type
			'name'				=> null,  	// Unique name
			'label'				=> null,  	// Label which describes this column.
			'classes'			=> null,	// Custom CSS classes for this column.
			'hide_label'		=> false,	// Should the Label be hidden?
			'is_registered'		=> true,	// Should the column be registered based on conditional logic, example usage see: 'post/page-template.php'
			'is_cloneable'		=> true,	// Should the column be cloneable
			'default'			=> false,	// Is this a WP default column,
			'group'				=> 'custom',
			'hidden'			=> false
		);

		foreach ( $default_properties as $property => $value ) {
			$this->properties[ $property ] = $value;
		}

		// Default options
		$default_options = array(
			'before'	=> '', // Before field
			'after'		=> '', // After field
			'width'		=> null, // Width for this column.
			'state'		=> 'off' // Active state for this column.
		);

		/**
		 * Filter the default options for a column instance, such as label and width
		 *
		 * @since 2.2
		 * @param array $default_options Default column options
		 * @param CPAC_Storage_Model $storage_model Storage Model class instance
		 */
		$default_options = apply_filters( 'cac/column/default_options', $default_options ); // do not pass $this because object is not ready

		foreach ( $default_options as $option => $value ) {
			$this->options[ $option ] = $value;
		}
	}

	/**
	 * After Setup
	 *
	 */
	public function after_setup() {

		// Column name defaults to column type
		if ( ! isset( $this->properties['name'] ) ) {
			$this->properties['name'] = $this->properties['type'];
		}

		// Check whether the column should be available
		$this->properties['is_registered'] = $this->apply_conditional();

		/**
		 * Filter the properties of a column type, such as type and is_cloneable
		 * Property $column_instance added in Admin Columns 2.2
		 *
		 * @since 2.0
		 * @param array $properties Column properties
		 * @param CPAC_Storage_Model $storage_model Storage Model class instance
		 */
		$this->properties = apply_filters( 'cac/column/properties', $this->properties ); // do not pass $this because object is not ready

		/**
		 * Filter the properties of a column type for a specific storage model
		 * Property $column_instance added in Admin Columns 2.2
		 *
		 * @since 2.0
		 * @see Filter cac/column/properties
		 */
		$this->properties = apply_filters( "cac/column/properties/storage_key={$this->storage_model->key}", $this->properties ); // do not pass $this because object is not ready

		// Column label defaults to column type label
		if ( ! isset( $this->options['label'] ) ) {
			$this->options['label'] = $this->properties['label'];
		}

		// Convert properties and options arrays to object
		$this->options = (object) $this->options;
		$this->properties = (object) $this->properties;

		// Read options from database
		$this->populate_options();

		$this->sanitize_label();
	}

	/**
	 * Populate Options
	 * Added $options parameter in 2.2
	 *
	 * @since 2.0
	 * @param array $options Optional. Options to populate the storage model with. Defaults to options from database.
	 */
	public function populate_options( $options = NULL ) {
		$this->options = (object) array_merge( (array) $this->options, is_array( $options ) ? $options : $this->read() );
	}

	/**
	 * @param string $property
	 * @return mixed $value
	 */
	public function set_properties( $property, $value ) {
		$this->properties->{$property} = $value;

		return $this;
	}

	/**
	 * @param string $option
	 * @return mixed $value
	 */
	public function set_options( $option, $value ) {
		$this->options->{$option} = $value;

		return $this;
	}

	/**
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
	 * @since 1.0
	 */
	public function get_before() {
		return stripslashes( $this->options->before );
	}

	/**
	 * @since 1.0
	 */
	public function get_after() {
		return stripslashes( $this->options->after );
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.3.4
	 */
	public function get_type() {
		return $this->properties->type;
	}

	/**
	 * Get the name of the column.
	 *
	 * @since 2.3.4
	 */
	public function get_name() {
		return $this->properties->name;
	}

	/**
	 * Get the column options set by the user
	 *
	 * @since 2.3.4
	 * @return object Column options set by user
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Get a single column option
	 *
	 * @since 2.3.4
	 * @return array Column options set by user
	 */
	public function get_option( $name ) {
		return isset( $this->options->{$name} ) ? $this->options->{$name} : false;
	}

	/**
	 * Checks column type
	 *
	 * @since 2.3.4
	 * @param string $type Column type. Also work without the 'column-' prefix. Example 'column-meta' or 'meta'.
	 * @return bool Matches column type
	 */
	public function is_type( $type ) {
		return ( $type === $this->get_type() ) || ( 'column-' . $type === $this->get_type() );
	}

	/**
	 * @since 2.1.1
	 */
	public function get_post_type() {
		return $this->storage_model->get_post_type();
	}

	/**
	 * @since 2.3.4
	 */
	public function get_storage_model() {
		return $this->storage_model;
	}

	/**
	 * @since 2.3.4
	 */
	public function get_storage_model_type() {
		return $this->storage_model->get_type();
	}

	/**
	 * @since 2.3.4
	 */
	public function get_storage_model_meta_type() {
		return $this->storage_model->get_meta_type();
	}

	/**
	 * @param string $field_key
	 * @return void
	 */
	public function attr_name( $field_name ) {
		echo "{$this->storage_model->key}[{$this->properties->name}][{$field_name}]";
	}

	/**
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	public function attr_id( $field_name ) {
		echo "cpac-{$this->storage_model->key}-{$this->properties->name}-{$field_name}";
	}

	/**
	 * @since 2.0
	 * @return array Column options
	 */
	public function read() {
		$options = (array) $this->storage_model->get_database_columns();

		if ( empty( $options[ $this->properties->name ] ) ) {
			return array();
		}

		return $options[ $this->properties->name ];
	}

	/**
	 * @since 2.0
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
	 * @since 2.0
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
			// and make an exception for site_url()
			if ( false === strpos( $options['label'], site_url() ) ) {
				$options['label'] = str_replace( ':', '', $options['label'] );
			}
		}

		// used by child classes for additional sanitizing
		$options = $this->sanitize_options( $options );

		return $options;
	}

	/**
	 * @since 2.0
	 */
	public function get_label() {

		/**
		 * Filter the column instance label
		 *
		 * @since 2.0
		 *
		 * @param string $label Column instance label
		 * @param CPAC_Column $column_instance Column class instance
		 */
		return apply_filters( 'cac/column/settings_label', stripslashes( str_replace( '[cpac_site_url]', site_url(), $this->options->label ) ), $this );
	}

	/**
	 * Sanitizes label using intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since 1.0
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
	 * @since 1.3.1
	 */
	protected function get_shorten_url( $url = '' ) {
		if ( ! $url ) {
			return false;
		}

		return "<a title='{$url}' href='{$url}'>" . url_shorten( $url ) . "</a>";
	}

	/**
	 * @since 1.3
	 */
	protected function strip_trim( $string ) {
		return trim( strip_tags( $string ) );
	}

	/**
	 * @since 2.2.1
	 */
	protected function get_term_field( $field, $term_id, $taxonomy ) {
		$term_field = get_term_field( $field, $term_id, $taxonomy, 'display' );
		if ( is_wp_error( $term_field ) ) {
			return false;
		}
		return $term_field;
	}

	/**
	 * @since 1.0
	 * @param int $post_id Post ID
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id, $words )	{
		global $post;

		$save_post 	= $post;
		$post 		= get_post( $post_id );

		setup_postdata( $post );

		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;

		if ( $post ) {
			setup_postdata( $post );
		}

		$output = $this->get_shortened_string( $excerpt, $words );

		return $output;
	}

	/**
	 * @see wp_trim_words();
	 * @since 1.0
	 * @return string Trimmed text.
	 */
	protected function get_shortened_string( $text = '', $num_words = 30, $more = null ) {
		if ( ! $text ) {
			return false;
		}

		return wp_trim_words( $text, $num_words, $more );
	}

	/**
	 * @since 1.3.1
	 * @param string $name
	 * @param string $title
	 * @return string HTML img element
	 */
	public function get_asset_image( $name = '', $title = '' ) {

		if ( ! $name ) {
			return false;
		}

		return sprintf( "<img alt='' src='%s' title='%s'/>", CPAC_URL . "assets/images/{$name}", esc_attr( $title ) );
	}

	/**
	 * @since 1.2.0
	 * @param string $url
	 * @return bool
	 */
	protected function is_image_url( $url ) {

		if ( ! is_string( $url ) ) {
			return false;
		}

		$validExt  	= array('.jpg', '.jpeg', '.gif', '.png', '.bmp');
		$ext    	= strrchr( $url, '.' );

		return in_array( $ext, $validExt );
	}

	/**
	 * @since 1.0
	 * @return array Image Sizes.
	 */
	public function get_all_image_sizes() {
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
	 * @since 2.2.6
	 */
	public function get_terms_for_display( $term_ids, $taxonomy ) {
		if ( empty( $term_ids ) ) {
			return false;
		}

		$values = array();
		$term_ids = (array) $term_ids;
		if ( $term_ids && ! is_wp_error( $term_ids ) ) {
			$post_type = $this->get_post_type();
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id, $taxonomy );
				$title = esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $term->taxonomy, 'edit' ) );

				$filter_key = $term->taxonomy;
				if ( 'category' === $term->taxonomy ) {
					$filter_key = 'category_name';
				}

				$link = "<a href='edit.php?post_type={$post_type}&{$filter_key}={$term->slug}'>{$title}</a>";
				if ( $post_type == 'attachment' ) {
					$link = "<a href='upload.php?taxonomy={$filter_key}&term={$term->slug}'>{$title}</a>";
				}

				$values[] = $link;
			}
		}
		if ( ! $values ) {
			return false;
		}

		return implode( ', ', $values );
	}

	/**
	 * @since 2.0
	 * @param string $name
	 * @return array Image Sizes
	 */
	public function get_image_size_by_name( $name = '' ) {

		if ( ! $name || is_array( $name ) ) {
			return false;
		}

		global $_wp_additional_image_sizes;

		if ( ! isset( $_wp_additional_image_sizes[ $name ] ) ) {
			return false;
		}

		return $_wp_additional_image_sizes[ $name ];
	}

	/**
	 * @see image_resize()
	 * @since 2.0
	 * @return string Image URL
	 */
	public function image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$resized = false;
		$editor  = wp_get_image_editor( $file );

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
	 * @since: 2.2.6
	 *
	 */
	public function get_color_for_display( $color_hex ) {
		if ( ! $color_hex ) {
			return false;
		}
		$text_color = $this->get_text_color( $color_hex );
		return "<div class='cpac-color'><span style='background-color:{$color_hex};color:{$text_color}'>{$color_hex}</span></div>";
	}

	/**
	 * Determines text color absed on bakground coloring.
	 *
	 * @since 1.0
	 */
	public function get_text_color( $bg_color ) {

		$rgb = $this->hex2rgb( $bg_color );

		return $rgb && ( ( $rgb[0]*0.299 + $rgb[1]*0.587 + $rgb[2]*0.114 ) < 186 ) ? '#ffffff' : '#333333';
	}

	/**
	 * Convert hex to rgb
	 *
	 * @since 1.0
	 */
	public function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);

		return $rgb;
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 *
	 * @since 2.3
	 *
	 * @param string $input Input string
	 * @return int Number of words
	 */
	public function str_count_words( $input ) {

		$patterns = array(
			'strip' => '/<[a-zA-Z\/][^<>]*>/',
			'clean' => '/[0-9.(),;:!?%#$¿\'"_+=\\/-]+/',
			'w' => '/\S\s+/',
			'c' => '/\S/'
		);

		$type = 'w';

		$input = preg_replace( $patterns['strip'], ' ', $input );
		$input = preg_replace( '/&nbsp;|&#160;/i', ' ', $input );
		$input = preg_replace( $patterns['clean'], '', $input );

		if ( ! strlen( preg_replace( '/\s/', '', $input ) ) ) {
			return 0;
		}

		return preg_match_all( $patterns[ $type ], $input, $matches ) + 1;
	}

	/**
	 * @since 1.0
	 * @param mixed $meta Image files or Image ID's
	 * @param array $args
	 * @return array HTML img elements
	 */
	public function get_thumbnails( $images, $args = array() ) {

		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
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

		$thumbnails = array();
		foreach( $images as $value ) {

			if ( $this->is_image_url( $value ) ) {

				// get dimensions from image_size
				if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
					$image_size_w = $sizes['width'];
					$image_size_h = $sizes['height'];
				}

				$image_path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $value );

				if ( is_file( $image_path ) ) {

					// try to resize image
					if ( $resized = $this->image_resize( $image_path, $image_size_w, $image_size_h, true ) ) {
						$thumbnails[] = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) .  "' alt='' width='{$image_size_w}' height='{$image_size_h}' />";
					}

					// return full image with maxed dimensions
					else {
						$thumbnails[] = "<img src='{$value}' alt='' style='max-width:{$image_size_w}px;max-height:{$image_size_h}px' />";
					}
				}
			}

			// Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {

				$src = '';
				$width = '';
				$height = '';

				if ( ! $image_size || 'cpac-custom' == $image_size ) {
					$width 		= $image_size_w;
					$height 	= $image_size_h;

					// to make sure wp_get_attachment_image_src() get the image with matching dimensions.
					$image_size = array( $width, $height );
				}

				// Is Image
				if ( $attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
					$src 	= $attributes[0];
					$width	= $attributes[1];
					$height	= $attributes[2];

					// image size by name
					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width 	= $sizes['width'];
						$height	= $sizes['height'];
					}
				}

				// Is File, use icon
				elseif ( $attributes = wp_get_attachment_image_src( $value, $image_size, true ) ) {
					$src = $attributes[0];

					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}
				}

				// maximum dimensions
				$max = max( array( $width, $height ) );

				$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$src}' alt=''/></span>";
			}
		}

		return $thumbnails;
	}

	/**
	 * Implode for multi dimensional array
	 *
	 * @since 1.0
	 * @param string $glue
	 * @param array $pieces
	 * @return string Imploded array
	 */
	public function recursive_implode( $glue, $pieces ) {
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
	 * @since 2.0
	 * @param string $date
	 * @return string Formatted date
	 */
	public function get_timestamp( $date ) {

		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) ) {
			return false;
		}

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
	 * @since 1.3.1
	 * @param string $date
	 * @return string Formatted date
	 */
	protected function get_date( $date, $format = '' ) {

		if ( ! $date = $this->get_timestamp( $date ) ) {
			return false;
		}
		if ( ! $format ) {
			$format = get_option( 'date_format' );
		}

		return date_i18n( $format, $date );
	}

	/**
	 * @since 1.3.1
	 * @param string $date
	 * @return string Formatted time
	 */
	protected function get_time( $date, $format = '' ) {

		if ( ! $date = $this->get_timestamp( $date ) ) {
			return false;
		}
		if ( ! $format ) {
			$format = get_option( 'time_format' );
		}

		return date_i18n( $format, $date );
	}

	/**
	 * Get display name.
	 *
	 * Can also be used by addons.
	 *
	 * @since 2.0
	 */
	public function get_display_name( $user_id ) {

		if ( ! $userdata = get_userdata( $user_id ) ) {
			return false;
		}

		$name = '';

		if ( ! empty( $this->options->display_author_as ) ) {

			$display_as = $this->options->display_author_as;

			if ( 'first_last_name' == $display_as ) {
				$first 	= ! empty( $userdata->first_name ) ? $userdata->first_name : '';
				$last 	= ! empty( $userdata->last_name ) ? " {$userdata->last_name}" : '';
				$name 	= $first.$last;
			}

			elseif ( ! empty( $userdata->{$display_as} ) ) {
				$name = $userdata->{$display_as};
			}
		}

		// default to display_name
		if ( ! $name ) {
			$name = $userdata->display_name;
		}

		return $name;
	}

	/**
	 * @since 2.0
	 * @param string $field_key
	 * @return string Attribute Name
	 */
	public function label_view( $label, $description = '', $pointer = '' ) {
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
	 * @since 2.0
	 */
	public function display_field_date_format() {

		$field_key		= 'date_format';
		$label			= __( 'Date Format', 'cpac' );
		$description	= __( 'This will determine how the date will be displayed.', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>">
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
	 * @since 2.0
	 */
	public function display_field_excerpt_length() {

		$field_key		= 'excerpt_length';
		$label			= __( 'Excerpt length', 'cpac' );
		$description	= __( 'Number of words', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>">
			<?php $this->label_view( $label, $description, $field_key ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $field_key ); ?>" id="<?php $this->attr_id( $field_key ); ?>" value="<?php echo $this->options->excerpt_length; ?>"/>
			</td>
		</tr>
	<?php
	}

	/**
	 * @since 2.0
	 */
	public function display_field_preview_size() {

		$field_key		= 'image_size';
		$label			= __( 'Preview size', 'cpac' );

		?>
		<tr class="column_<?php echo $field_key; ?>">

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
	 * @since 2.1.1
	 */
	public function display_field_before_after() {
		$this->display_field_text( 'before', __( "Before", 'cpac' ), __( 'This text will appear before the custom field value.', 'cpac' ) );
		$this->display_field_text( 'after', __( "After", 'cpac' ), __( 'This text will appear after the custom field value.', 'cpac' ) );
	}

	/**
	 * @since 2.3.2
	 */
	public function display_field_user_format() {

		$nametypes = array(
			'display_name'		=> __( 'Display Name', 'cpac' ),
			'first_name'		=> __( 'First Name', 'cpac' ),
			'last_name'			=> __( 'Last Name', 'cpac' ),
			'nickname'			=> __( 'Nickname', 'cpac' ),
			'user_login'		=> __( 'User Login', 'cpac' ),
			'user_email'		=> __( 'User Email', 'cpac' ),
			'ID'				=> __( 'User ID', 'cpac' ),
			'first_last_name'	=> __( 'First and Last Name', 'cpac' ),
		);

		$this->display_field_select( 'display_author_as', __( 'Display format', 'cpac' ), $nametypes, __( 'This is the format of the author name.', 'cpac' ) );
	}

	/**
	 * @since 2.3.4
	 * @param string $name Name of the column option
	 * @return string $label Label
	 * @return array $options Select options
	 * @return strong $description (optional) Description below the label
	 */
	public function display_field_select( $name, $label, $options = array(), $description = '' ) {
		$current = $this->get_option( $name );
		?>
		<tr class="column-<?php echo $name; ?>">
			<?php $this->label_view( $label, $description, $name ); ?>
			<td class="input">
				<select name="<?php $this->attr_name( $name ); ?>" id="<?php $this->attr_id( $name ); ?>">
				<?php foreach ( $options as $key => $label ) : ?>
					<option value="<?php echo $key; ?>"<?php selected( $key, $current ); ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<?php
	}

	/**
	 * @since 2.3.4
	 * @param string $name Name of the column option
	 * @return string $label Label
	 * @return array $options Select options
	 * @return strong $description (optional) Description below the label
	 */
	public function display_field_text( $name, $label, $description = '' ) {
		?>
		<tr class="column-<?php echo $name; ?>">
			<?php $this->label_view( $label, $description, $name ); ?>
			<td class="input">
				<input type="text" name="<?php $this->attr_name( $name ); ?>" id="<?php $this->attr_id( $name ); ?>" value="<?php echo esc_attr( stripslashes( $this->get_option( $name ) ) ); ?>"/>
			</td>
		</tr>
		<?php
	}

	/**
	 * @since 2.0
	 * @param array Column Objects
	 * @return string HTML List
	 */
	public function get_column_list( $columns = array(), $label = '' ) {

		if ( empty( $columns ) ) {
			return false;
		}

		$list = '';

		// sort by alphabet
		$_columns = array();

		foreach ( $columns as $column ) {
			if ( $column->properties->hidden ) {
				continue;
			}

			$_columns[ $column->properties->type ] = ( 0 === strlen( strip_tags( $column->properties->label ) ) ) ? ucfirst( $column->properties->type ) : $column->properties->label;
		}

		asort( $_columns );

		$list = "<optgroup label='{$label}'>";
		foreach ( $_columns as $type => $label ) {
			$selected = selected( $this->properties->type, $type, false );
			$list .= "<option value='{$type}'{$selected}>{$label}</option>";
		}
		$list .= "</optgroup>";

		return $list;
	}

	/**
	 * @since 2.0
	 */
	public function display() {

		$classes = implode( ' ', array_filter( array ( "cpac-box-{$this->properties->type}", $this->properties->classes ) ) );

		// column list
		$column_list = '';

		$groups = $this->storage_model->get_column_type_groups();
		foreach ( $groups as $group => $label ) {
			$column_list .= $this->get_column_list( $this->storage_model->column_types[ $group ], $label );
		}

		// clone attribute
		$data_clone = $this->properties->is_cloneable ? " data-clone='{$this->properties->clone}'" : '';

		?>
		<div class="cpac-column <?php echo $classes; ?>" data-type="<?php echo $this->properties->type; ?>"<?php echo $data_clone; ?>>
			<input type="hidden" class="column-name" name="<?php echo $this->attr_name( 'column-name' ); ?>" value="<?php echo esc_attr( $this->properties->name ); ?>" />
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

										<span title="<?php echo esc_attr( __( 'width', 'cpac' ) ); ?>" class="width" data-indicator-id="">
											<?php echo ! empty( $this->options->width ) ? $this->options->width . '%' : ''; ?>
										</span>

										<?php
										/**
										 * Fires in the meta-element for column options, which is displayed right after the column label
										 *
										 * @since 2.0
										 *
										 * @param CPAC_Column $column_instance Column class instance
										 */
										do_action( 'cac/column/settings_meta', $this );

										/**
										 * @deprecated 2.2 Use cac/column/settings_meta instead
										 */
										do_action( 'cac/column/label', $this );
										?>

									</div>
									<a class="toggle" href="javascript:;"><?php echo stripslashes( $this->get_label() ); ?></a>
									<a class="edit-button" href="javascript:;"><?php _e( 'Edit', 'cpac' ); ?></a>
									<?php if ( $this->properties->is_cloneable ) : ?>
										<a class="clone-button" href="#"><?php _e( 'Clone', 'cpac' ); ?></a>
									<?php endif; ?>
									<a class="remove-button" href="javascript:;"><?php _e( 'Remove', 'cpac' ); ?></a>
								</div>
							</td>
							<td class="column_type">
								<div class="inner">
									<a href="#"><?php echo stripslashes( $this->properties->label ); ?></a>
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
							<?php $this->label_view( __( 'Type', 'cpac' ), __( 'Choose a column type.', 'cpac' ) . '<em>' . __( 'Type', 'cpac' ) . ': ' . $this->properties->type . '</em><em>' . __( 'Name', 'cpac' ) . ': ' . $this->properties->name . '</em>', 'type' ); ?>
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

						<?php
						/**
						 * Fires directly before the custom options for a column are displayed in the column form
						 *
						 * @since 2.0
						 * @param CPAC_Column $column_instance Column class instance
						 */
						do_action( 'cac/column/settings_before', $this );
						?>

						<?php
						/**
						 * Load specific column settings.
						 *
						 */
						$this->display_settings();

						?>

						<?php
						/**
						 * Fires directly after the custom options for a column are displayed in the column form
						 *
						 * @since 2.0
						 * @param CPAC_Column $column_instance Column class instance
						 */
						do_action( 'cac/column/settings_after', $this );
						?>

						<tr class="column_action">
							<td colspan="2">
								<p>
									<?php if ( $this->properties->is_cloneable ) : ?>
										<a class="clone-button" href="#"><?php _e( 'Clone', 'cpac' ); ?></a>
									<?php endif; ?>
									<a href="javascript:;" class="remove-button"><?php _e( 'Remove' );?></a>
								</p>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
}