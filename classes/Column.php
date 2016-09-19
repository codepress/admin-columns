<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column class
 *
 * @since 2.0
 *
 * @param string $storage_model Storage Model Key
 *
 * @property AC_ColumnFieldFormat format
 * @property AC_ColumnFieldSettings field_settings
 */
abstract class CPAC_Column {

	/**
	 * A Storage Model can be a Post Type, User, Comment, Link or Media storage type.
	 *
	 * @since 2.0
	 * @var string $storage_model contains a AC_StorageModel object which the column belongs too.
	 */
	private $storage_model;

	/**
	 * Default options
	 *
	 * @since 2.0
	 * @deprecated NEWVERSION
	 * @var stdClass $options Contains the user set options for the CPAC_Column object.
	 */
	public $options;

	/**
	 * Default options
	 *
	 * @since 2.0
	 * @var array $default_options Default column options.
	 */
	public $default_options;

	/**
	 * @since NEWVERSION
	 * @var array|null
	 */
	private $stored_options = array();

	/**
	 * @since 2.0
	 * @var array $properties describes the fixed properties for the CPAC_Column object.
	 */
	public $properties = array();

	/**
	 * Instance for adding field settings to the column
	 *
	 * @var AC_ColumnFieldSettings
	 */
	private $field_settings;

	/**
	 * Instance for formatting column values
	 *
	 * @var AC_ColumnFieldFormat
	 */
	private $format;

	/**
	 * @since 2.0
	 *
	 * @param object $storage_model AC_StorageModel
	 */
	public function __construct( $storage_model ) {

		$this->storage_model = $storage_model;

		$this->field_settings = new AC_ColumnFieldSettings( $this );
		$this->format = new AC_ColumnFieldFormat( $this );

		$this->init();
		$this->after_setup();
	}

	/**
	 * @since 2.5
	 * @return false|AC_StorageModel
	 */
	public function __get( $key ) {
		$call = false;
		if ( 'storage_model' == $key ) {
			$call = 'get_' . $key;
		}
		if ( in_array( $key, array( 'format', 'field_settings' ) ) ) {
			$call = $key;
		}

		return $call ? call_user_func( array( $this, $call ) ) : false;
	}

	public function field_settings() {
		return $this->field_settings;
	}

	public function format() {
		return $this->format;
	}

	/**
	 * @since 2.2
	 */
	public function init() {

		// Default properties
		$this->properties = array(
			'clone'            => null,    // Unique clone ID
			'type'             => null,    // Unique type
			'name'             => null,    // Unique name
			'label'            => null,    // Label which describes this column.
			'classes'          => null,    // Custom CSS classes for this column.
			'hide_label'       => false,   // Should the Label be hidden?
			'is_cloneable'     => true,    // Should the column be cloneable
			'default'          => false,   // Is this a WP default column, used for displaying values
			'original'         => false,   // When a default column has been replaced by custom column we mark it as 'original'
			'use_before_after' => false,   // Should the column use before and after fields
			'group'            => __( 'Custom', 'codepress-admin-columns' ), // Group name
		);

		$default_options = array(
			'label'      => null,  // Human readable label
			'width'      => null,  // Width for this column.
			'width_unit' => '%',   // Unit for width; percentage (%) or pixels (px).
		);

		// Options (deprecated)
		$this->options = $default_options;

		// Default options
		$this->default_options = $default_options;

		do_action( 'ac/column/defaults', $this );
	}

	/**
	 * After Setup
	 *
	 */
	public function after_setup() {
		$this->properties = (object) $this->properties;

		// Column name defaults to column type
		if ( null === $this->properties->name ) {
			$this->properties->name = $this->properties->type;
		}

		/**
		 * Add before and after fields to specific columns
		 *
		 * @since 2.0
		 * @deprecated NEWVERSION
		 */
		$this->set_property( 'use_before_after', apply_filters( 'cac/column/properties/use_before_after', $this->get_property( 'use_before_after' ), $this ) );
	}

	/**
	 * @param int $id
	 *
	 * @return object
	 */
	public function set_clone( $id = null ) {

		if ( $id !== null && $id > 0 ) {
			$this->set_property( 'name', $this->get_type() . '-' . $id );
			$this->set_property( 'clone', $id );
		}

		return $this;
	}

	/**
	 * @param string $name Column name
	 * @param string $label Column label
	 */
	public function set_defaults( $name, $label ) {
		if ( ! $label ) {
			$label = ucfirst( $name );
		}

		// Hide Label when it contains HTML elements
		if ( strlen( $label ) != strlen( strip_tags( $label ) ) ) {
			$this->set_property( 'hide_label', true );
		}

		if ( ! $this->get_group() ) {
			$this->set_property( 'group', __( 'Default', 'codepress-admin-columns' ) );
		}

		$this
			->set_property( 'type', $name )
			->set_property( 'name', $name )
			->set_property( 'label', $label )
			->set_default_option( 'label', $label );
	}

	/**
	 * @since 2.0
	 *
	 * @param int $id ID
	 *
	 * @return string Value
	 */
	public function get_value( $id ) {
	}

	/**
	 * Get the raw, underlying value for the column
	 * Not suitable for direct display, use get_value() for that
	 *
	 * @since 2.0.3
	 *
	 * @param int $id ID
	 *
	 * @return mixed Value
	 */
	public function get_raw_value( $id ) {
	}

	/**
	 * @since 2.0
	 */
	public function display_settings() {
	}

	/**
	 * Overwrite this function in child class to sanitize
	 * user submitted values.
	 *
	 * @since 2.0
	 *
	 * @param $options array User submitted column options
	 *
	 * @return array Options
	 */
	public function sanitize_options( $options ) {
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
	 * @since NEWVERSION
	 */
	public function is_default() {
		return $this->get_property( 'default' );
	}

	/**
	 * @since NEWVERSION
	 */
	public function is_original() {
		return $this->get_property( 'original' );
	}

	/**
	 * Overwrite this function in child class.
	 * Adds (optional) scripts to the listings screen.
	 *
	 * @since 2.3.4
	 */
	public function scripts() {
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.3.4
	 * @return string Type
	 */
	public function get_type() {
		return $this->get_property( 'type' );
	}

	/**
	 * Get the name of the column.
	 *
	 * @since 2.3.4
	 * @return string Column name
	 */
	public function get_name() {
		return $this->get_property( 'name' );
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 * @return string Label of column's type
	 */
	public function get_type_label() {
		return $this->get_property( 'label' );
	}

	/**
	 * @since NEWVERSION
	 * @return string Group
	 */
	public function get_group() {
		return $this->get_property( 'group' );
	}

	/**
	 * @since NEWVERSION
	 * @return int Width
	 */
	public function get_width() {
		$width = absint( $this->get_option( 'width' ) );

		return $width > 0 ? $width : false;
	}

	/**
	 * @since NEWVERSION
	 * @return string px or %
	 */
	public function get_width_unit() {
		$width_unit = $this->get_option( 'width_unit' );

		if ( ! $width_unit ) {
			$width_unit = $this->default_options['width_unit'];
		}

		return 'px' === $width_unit ? 'px' : '%';
	}

	/**
	 * Get the stored column options
	 *
	 * @since 2.3.4
	 * @return array Column options set by user
	 */
	public function get_options() {
		$options = $this->stored_options;

		if ( ! $options ) {
			$stored = $this->get_storage_model()->get_stored_columns();
			if ( isset( $stored[ $this->get_name() ] ) ) {
				$options = $stored[ $this->get_name() ];
			}
		}

		// replace urls, so export will not have to deal with them
		if ( isset( $options['label'] ) ) {
			$options['label'] = stripslashes( str_replace( '[cpac_site_url]', site_url(), $options['label'] ) );
		}

		return $options ? array_merge( $this->default_options, $options ) : $this->default_options;
	}

	public function set_stored_options( $options ) {
		$this->stored_options = $options;
	}

	/**
	 * Get the column properties
	 *
	 * @since NEWVERSION
	 * @return stdClass Column properties
	 */
	public function get_properties() {
		return $this->properties;
	}

	/**
	 * Get a single column option
	 *
	 * @since 2.3.4
	 * @return string|false Single column option
	 */
	public function get_option( $name ) {
		$options = $this->get_options();

		return isset( $options[ $name ] ) ? $options[ $name ] : false;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $option
	 * @param string $value
	 *
	 * @return $this CPAC_Column
	 */
	public function set_default_option( $option, $value ) {
		$this->default_options[ $option ] = $value;

		return $this;
	}

	/**
	 * Get a single column option
	 *
	 * @since 2.4.8
	 * @return false|array Column options set by user
	 */
	public function get_property( $name ) {
		return isset( $this->get_properties()->{$name} ) ? $this->get_properties()->{$name} : false;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $option
	 * @param string $value
	 *
	 * @return $this CPAC_Column
	 */
	public function set_property( $property, $value ) {
		$this->properties->{$property} = $value;

		return $this;
	}

	/**
	 * Checks column type
	 *
	 * @since 2.3.4
	 *
	 * @param string $type Column type. Also work without the 'column-' prefix. Example 'column-meta' or 'meta'.
	 *
	 * @return bool Matches column type
	 */
	public function is_type( $type ) {
		return ( $type === $this->get_type() ) || ( 'column-' . $type === $this->get_type() );
	}

	/**
	 * @since 2.1.1
	 */
	public function get_post_type() {
		return $this->get_storage_model()->get_post_type();
	}

	/**
	 * @since 2.5.4
	 */
	public function get_storage_model_key() {
		return $this->storage_model;
	}

	/**
	 * @since 2.5.4
	 */
	public function get_layout() {
		return $this->get_storage_model()->layouts()->get_layout();
	}

	/**
	 * @since 2.3.4
	 * @return AC_StorageModel
	 */
	public function get_storage_model() {
		return cpac()->get_storage_model( $this->get_storage_model_key() );
	}

	/**
	 * @since 2.3.4
	 */
	public function get_storage_model_type() {
		return $this->get_storage_model()->get_type();
	}

	/**
	 * @since 2.3.4
	 */
	public function get_meta_type() {
		return $this->get_storage_model()->get_meta_type();
	}

	/**
	 * @since 2.5
	 */
	public function get_empty_char() {
		return '&ndash;';
	}

	/**
	 * @since NEWVERSION
	 * @return wpdb
	 */
	public function wpdb() {
		global $wpdb;

		return $wpdb;
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
		return apply_filters( 'cac/column/settings_label', stripslashes( str_replace( '[cpac_site_url]', site_url(), $this->get_option( 'label' ) ) ), $this );
	}

	/**
	 * Sanitizes label using intern WordPress function esc_url so it matches the label sorting url.
	 *
	 * @since 1.0
	 *
	 * @param CPAC_Column $column
	 *
	 * @return string Sanitized string
	 */
	public function get_sanitized_label() {
		if ( $this->is_default() ) {
			$string = $this->get_name();
		}
		else {
			$string = $this->get_option( 'label' );
			$string = strip_tags( $string );
			$string = preg_replace( "/[^a-zA-Z0-9]+/", "", $string );
			$string = str_replace( 'http://', '', $string );
			$string = str_replace( 'https://', '', $string );
		}

		return $string;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param $id
	 *
	 * @return string Value
	 */
	public function get_display_value( $id ) {
		$value = '';

		$display_value = $this->get_value( $id );

		if ( $display_value || 0 === $display_value ) {
			$value = $display_value;
		}

		if ( is_scalar( $value ) ) {
			$value = $this->get_option( 'before' ) . $value . $this->get_option( 'after' );
		}

		$value = apply_filters( "cac/column/value", $value, $id, $this, $this->get_storage_model_key() );
		$value = apply_filters( "cac/column/value/" . $this->get_type(), $value, $id, $this, $this->get_storage_model_key() );

		return $value;
	}

	/**
	 * @param string $name
	 * @param string $label
	 */
	public function display_indicator( $name, $label ) { ?>
		<span class="indicator-<?php echo esc_attr( $name ); ?> <?php echo esc_attr( $this->get_option( $name ) ); ?>" data-indicator-id="<?php $this->field_settings->attr_id( $name ); ?>" title="<?php echo esc_attr( $label ); ?>"></span>
		<?php
	}


	// Deprecated methods

	/**
	 * @param string $field_name
	 */
	public function attr_name( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->field_settings->attr_name()' );

		$this->field_settings->attr_name( $field_name );
	}

	/**
	 * @param string $field_name
	 *
	 * @return string Attribute name
	 */
	public function get_attr_name( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->field_settings->get_attr_name()' );

		return $this->field_settings->get_attr_name( $field_name );
	}

	/**
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function get_attr_id( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->field_settings->get_attr_id()' );

		return $this->field_settings->get_attr_id( $field_name );
	}

	public function attr_id( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->field_settings->attr_id()' );

		$this->field_settings->attr_id( $field_name );
	}

	/**
	 * @param string $property
	 *
	 * @return mixed $value
	 */
	public function set_properties( $property, $value ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::set_property()' );

		return $this->set_property( $property, $value );
	}

	/**
	 * @param string $option
	 *
	 * @return mixed $value
	 */
	public function set_options( $option, $value ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::set_default_option()' );

		return $this->set_default_option( $option, $value );
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public function get_post_title( $id ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->post->get_post_title()' );

		return ac_helper()->post->get_post_title( $id );
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
	 * @return string Formatted date
	 */
	public function get_date( $date, $format = '' ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->date->date()' );

		return ac_helper()->date->date( $date, $format );
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
	 * @return string Formatted time
	 */
	protected function get_time( $date, $format = '' ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->date->time()' );

		return ac_helper()->date->time( $date, $format );
	}

	/**
	 * Get timestamp
	 *
	 * @since 2.0
	 *
	 * @param string $date
	 *
	 * @return string Formatted date
	 */
	public function get_timestamp( $date ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->date->strtotime()' );

		return ac_helper()->date->strtotime( $date );
	}

	/**
	 * @since 3.4.4
	 */
	public function get_user_postcount( $user_id, $post_type ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->user->get_postcount()' );

		return ac_helper()->user->get_postcount( $user_id, $post_type );
	}

	/**
	 * @since 1.3.1
	 */
	protected function get_shorten_url( $url ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->shorten_url()' );

		return ac_helper()->string->shorten_url( $url );
	}

	/**
	 * @since 2.4.8
	 */
	public function get_raw_post_field( $field, $id ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION' );

		return ac_helper()->post->get_raw_field( $field, $id );
	}

	/**
	 * @since 1.0
	 */
	public function get_before() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION' );

		return $this->get_option( 'before' );
	}

	/**
	 * @since 1.0
	 */
	public function get_after() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION' );

		return $this->get_option( 'after' );
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $name
	 * @param string $title
	 *
	 * @return string HTML img element
	 */
	public function get_asset_image( $name = '', $title = '' ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION' );

		return $name ? sprintf( "<img alt='' src='%s' title='%s'/>", cpac()->get_plugin_url() . "assets/images/" . $name, esc_attr( $title ) ) : false;
	}

	/**
	 * @since 1.0
	 *
	 * @param int $post_id Post ID
	 *
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id, $words ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->post->excerpt()' );

		return ac_helper()->post->excerpt( $post_id, $words );
	}

	/**
	 * @since 1.2.0
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	protected function is_image_url( $url ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->is_image()' );

		return ac_helper()->string->is_image( $url );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $name
	 *
	 * @return array Image Sizes
	 */
	public function get_image_size_by_name( $name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->image->get_image_sizes_by_name()' );

		return ac_helper()->image->get_image_sizes_by_name( $name );
	}

	/**
	 * @see image_resize()
	 * @since 2.0
	 * @return string Image URL
	 */
	public function image_resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->image->get_image_size_by_name()' );

		return ac_helper()->image->resize( $file, $max_w, $max_h, $crop, $suffix, $dest_path, $jpeg_quality );
	}

	/**
	 * @param $user
	 * @param bool $format
	 *
	 * @return false|string
	 */
	public function get_display_name( $user, $format = false ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ac_helper()->user->get_display_name()' );

		return ac_helper()->user->get_display_name( $user, $format );
	}

	/**
	 * Convert hex to rgb
	 *
	 * @since 1.0
	 * @deprecated NEWVERSION
	 */
	public function hex2rgb( $hex ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ac_helper()->string->hex_to_rgb()' );

		return ac_helper()->string->hex_to_rgb( $hex );
	}

	/**
	 * Determines text color based on background coloring.
	 *
	 * @since 1.0
	 * @deprecated NEWVERSION
	 */
	public function get_text_color( $bg_color ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ac_helper()->string->hex_get_contrast()' );

		return ac_helper()->string->hex_get_contrast( $bg_color );
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 *
	 * @since 2.3
	 * @deprecated NEWVERSION
	 *
	 * @param string $input Input string
	 *
	 * @return int Number of words
	 */
	public function str_count_words( $input ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ac_helper()->string->word_count()' );

		return ac_helper()->string->word_count( $input );
	}

	/**
	 * @see wp_trim_words();
	 *
	 * @since 1.0
	 * @deprecated NEWVERSION
	 *
	 * @return string Trimmed text.
	 */
	public function get_shortened_string( $text = '', $num_words = 30, $more = null ) {
		_deprecated_function( __METHOD__, 'NEWVERSION', 'ac_helper()->string->trim_words()' );

		return ac_helper()->string->trim_words( $text, $num_words, $more );
	}

	/**
	 * @since 1.3
	 */
	public function strip_trim( $string ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->strip_trim()' );

		return ac_helper()->string->strip_trim( $string );
	}

	/**
	 * @since 1.0
	 *
	 * @param mixed $meta Image files or Image ID's
	 * @param array $args
	 *
	 * @return array HTML img elements
	 */
	public function get_thumbnails( $images, $args = array() ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->image->get_images()' );

		$args = wp_parse_args( $args, array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		) );

		$size = $args['image_size'];
		if ( ! $args['image_size'] || 'cpac-custom' == $args['image_size'] ) {
			$size = array( $args['image_size_w'], $args['image_size_h'] );
		}

		return ac_helper()->image->get_images( ac_helper()->string->comma_separated_to_array( $images ), $size );
	}

	/**
	 * @since 2.3.4
	 * @deprecated NEWVERSION
	 */
	public function display_field_text( $name, $label, $description = '', $placeholder = '', $optional_toggle_id = '' ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->fields()' );

		$this->field_settings->fields( array(
			'type'          => 'text',
			'option'        => $name,
			'label'         => $label,
			'description'   => $description,
			'toggle_handle' => $optional_toggle_id,
			'placeholder'   => $placeholder,
		) );
	}

	/**
	 * @since 2.3.4
	 * @deprecated NEWVERSION
	 */
	public function display_field_select( $name, $label, $options = array(), $description = '', $optional_toggle_id = '', $js_refresh = false ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->fields()' );

		$this->field_settings->fields( array(
			'type'           => 'select',
			'option'         => $name,
			'label'          => $label,
			'description'    => $description,
			'toggle_handle'  => $optional_toggle_id,
			'refresh_column' => $js_refresh,
			'options'        => $options,
		) );
	}

	/**
	 * @since 2.4.7
	 * @deprecated NEWVERSION
	 */
	public function display_field_radio( $name, $label, $options = array(), $description = '', $toggle_handle = false, $toggle_trigger = false, $colspan = false ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->fields()' );

		$this->field_settings->fields( array(
			'type'           => 'radio',
			'option'         => $name,
			'label'          => $label,
			'options'        => $options,
			'description'    => $description,
			'toggle_trigger' => $toggle_trigger,
			'toggle_handle'  => $toggle_handle,
			'colspan'        => $colspan,
		) );
	}

	/**
	 * @since 2.0
	 */
	public function display_field_preview_size() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->image()' );

		$this->field_settings->image();
	}

	/**
	 * @since 2.1.1
	 */
	public function display_field_before_after() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->before_after()' );

		$this->field_settings->before_after();
	}

	/**
	 * @since 2.0
	 */
	public function display_field_date_format() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->date()' );

		$this->field_settings->date();
	}

	/**
	 * @since 2.3.2
	 */
	public function display_field_user_format() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->user()' );

		$this->field_settings->user();
	}

	/**
	 * @since 2.0
	 */
	public function display_field_excerpt_length() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->word_limit()' );

		$this->field_settings->word_limit();
	}

	/**
	 * @since 2.4.9
	 */
	public function display_field_link_label() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->url()' );

		$this->field_settings->url();
	}

	/**
	 * Display settings field for post property to display
	 *
	 * @since 2.4.7
	 */
	public function display_field_post_property_display() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->post()' );

		$this->field_settings->post();
	}

	/**
	 * Display settings field for the page the posts should link to
	 *
	 * @since 2.4.7
	 */
	public function display_field_post_link_to() {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->post_link_to()' );

		$this->field_settings->post_link_to();
	}

	public function label_view( $label, $description = '', $for = '', $more_link = false ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->label()' );

		$this->field_settings->label( array(
			'label'       => $label,
			'description' => $description,
			'for'         => $for,
			'more_link'   => $more_link,
		) );
	}

	/**
	 * @since 2.4.7
	 */
	function display_settings_placeholder( $url ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::field_settings->placeholder()' );

		$this->field_settings->placeholder( array( 'label' => $this->get_label, 'type' => $this->get_type(), 'url' => $url ) );
	}

	/**
	 * @since: 2.2.6
	 *
	 */
	public function get_color_for_display( $color_hex ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->string->get_color_block()' );

		return ac_helper()->string->get_color_block( $color_hex );
	}

}