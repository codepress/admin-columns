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
	private $storage_model;

	/**
	 * @since 2.0
	 * @var array $options contains the user set options for the CPAC_Column object.
	 */
	public $options = array();

	/**
	 * @since 2.0
	 * @var array $properties describes the fixed properties for the CPAC_Column object.
	 */
	public $properties = array();

	/**
	 * @since 2.4.7
	 */
	protected $filtering_model;

	/**
	 * @since 2.4.8
	 */
	protected $editable_model;

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
	protected function display_settings() {
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
	protected function sanitize_options( $options ) {

		if ( isset( $options['date_format'] ) ) {
			$options['date_format'] = trim( $options['date_format'] );
		}

		if ( isset( $options['width'] ) ) {
			$options['width'] = trim( $options['width'] );
			if ( ! is_numeric( $options['width'] ) ) {
				$options['width'] = '';
			}
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

	public function is_default() {
		return isset( $this->properties->default ) && $this->properties->default;
	}

	public function is_original() {
		return isset( $this->properties->original ) && $this->properties->original;
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
	 * An object copy (clone) is created for creating multiple column instances.
	 *
	 * @since 2.0
	 */
	public function __clone() {

		// Force a copy of this->object, otherwise it will point to same object.
		$this->options = clone $this->options;
		$this->properties = clone $this->properties;
	}

	/**
	 * @since 2.5
	 */
	public function __get( $key ) {
		if ( 'storage_model' == $key ) {
			return $this->{"get_$key"}();
		}
	}

	/**
	 * @since 2.0
	 *
	 * @param object $storage_model CPAC_Storage_Model
	 */
	public function __construct( $storage_model ) {

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
			'clone'            => null,    // Unique clone ID
			'type'             => null,    // Unique type
			'name'             => null,    // Unique name
			'label'            => null,    // Label which describes this column.
			'classes'          => null,    // Custom CSS classes for this column.
			'hide_label'       => false,   // Should the Label be hidden?
			'is_registered'    => true,    // Should the column be registered based on conditional logic, example usage see: 'post/page-template.php'
			'is_cloneable'     => true,    // Should the column be cloneable
			'default'          => false,   // Is this a WP default column, used for displaying values
			'original'         => false,   // When a default column has been replaced by custom column we mark it as 'original'
			'use_before_after' => false,   // Should the column use before and after fields
			'group'            => __( 'Custom', 'codepress-admin-columns' ) // Group name
		);

		// @since 2.4.7
		$default_properties = apply_filters( 'cac/column/default_properties', $default_properties );

		foreach ( $default_properties as $property => $value ) {
			$this->properties[ $property ] = $value;
		}

		// Default options
		$default_options = array(
			'before'     => '',    // Before field
			'after'      => '',    // After field
			'width'      => null,  // Width for this column.
			'width_unit' => '%',   // Unit for width; percentage (%) or pixels (px).
			'state'      => 'off'  // Active state for this column.
		);

		/**
		 * Filter the default options for a column instance, such as label and width
		 *
		 * @since 2.2
		 *
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
		 *
		 * @param array $properties Column properties
		 * @param CPAC_Storage_Model $storage_model Storage Model class instance
		 */
		$this->properties = apply_filters( 'cac/column/properties', $this->properties, $this ); // do not pass $this because object is not ready

		/**
		 * Filter the properties of a column type for a specific storage model
		 * Property $column_instance added in Admin Columns 2.2
		 *
		 * @since 2.0
		 * @see Filter cac/column/properties
		 */
		$this->properties = apply_filters( "cac/column/properties/storage_key={$this->get_storage_model()->key}", $this->properties, $this ); // do not pass $this because object is not ready

		// Column label defaults to column type label
		if ( ! isset( $this->options['label'] ) ) {
			$this->options['label'] = $this->properties['label'];
		}

		// Convert properties and options arrays to object
		$this->options = (object) $this->options;
		$this->properties = (object) $this->properties;

		// Filters
		foreach ( $this->properties as $name => $value ) {
			$this->properties->{$name} = apply_filters( "cac/column/properties/{$name}", $value, $this );
		}
	}

	/**
	 * @param string $property
	 *
	 * @return mixed $value
	 */
	public function set_properties( $property, $value ) {
		$this->properties->{$property} = $value;

		return $this;
	}

	/**
	 * @param string $option
	 *
	 * @return mixed $value
	 */
	public function set_options( $option, $value ) {
		$this->options->{$option} = $value;

		return $this;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_editable_model() {
		return ac_get_editable_model( $this->get_storage_model_key() );
	}
	public function get_editable_settings() {
		return null;
	}
	public function get_editable_ajax_options( $searchterm ) {
		return null;
	}
	/*public function save( $id, $value ) {
		return null;
	}*/

	/**
	 * @since NEWVERSION
	 */
	public function get_sortable_model() {
		return ac_get_sortable_model( $this->get_storage_model_key() );
	}
	public function is_sortable() {
		return null;
	}

	/**
	 * @param $vars array|object Query vars or query object, passed in reference.
	 *
	 * @return boolean|array Post ids or true when $vars has been set.
	 */
	public function get_sortable_results( &$vars ) {
		return null;
	}

	/**
	 * @since NEWVERSION
	 */
	public function is_filterable() {
		return null;
	}
	public function get_filterable_model() {
		return ac_get_filterable_model( $this->get_storage_model_key() );
	}
	// TODO: rename to get_filterable_data
	public function get_filterable_settings() {
		return null;
	}
	public function get_filterable_request_vars( $args, $value ) {
		return null;
	}

	/**
	 * @param int $id
	 *
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
	 * @since 2.4.9
	 */
	public function get_display_value( $id ) {
		$value = $this->get_value( $id );

		// add before and after string
		if ( $value ) {
			$value = $this->get_before() . $value . $this->get_after();
		}

		return $value;
	}

	/**
	 * @since 1.0
	 */
	public function get_before() {
		return isset( $this->options->before ) ? stripslashes( $this->options->before ) : false;
	}

	/**
	 * @since 1.0
	 */
	public function get_after() {
		return isset( $this->options->after ) ? stripslashes( $this->options->after ) : false;
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
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 */
	public function get_type_label() {
		return $this->properties->label;
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.5
	 */
	public function is_registered() {
		return $this->properties->is_registered;
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
	 * Get a single column option
	 *
	 * @since 2.4.8
	 * @return array Column options set by user
	 */
	public function get_property( $name ) {
		return isset( $this->properties->{$name} ) ? $this->properties->{$name} : false;
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
	 * @since 2.3.4
	 */
	public function get_storage_model() {
		return cpac()->get_storage_model( $this->storage_model );
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
	 * @param string $field_key
	 *
	 * @return void
	 */
	public function attr_name( $field_name ) {
		echo $this->get_attr_name( $field_name );
	}

	public function get_attr_name( $field_name ) {
		return "{$this->get_storage_model()->key}[{$this->properties->name}][{$field_name}]";
	}

	/**
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function get_attr_id( $field_name ) {
		return "cpac-{$this->get_storage_model()->key}-{$this->properties->name}-{$field_name}";
	}

	public function attr_id( $field_name ) {
		echo $this->get_attr_id( $field_name );
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
	 *
	 * @param $options array User submitted column options
	 *
	 * @return array Options
	 */
	public function sanitize_storage( $options ) {

		// excerpt length must be numeric, else we will return it's default
		if ( isset( $options['excerpt_length'] ) ) {
			$options['excerpt_length'] = trim( $options['excerpt_length'] );
			if ( empty( $options['excerpt_length'] ) || ! is_numeric( $options['excerpt_length'] ) ) {
				$options['excerpt_length'] = 30;
			}
		}

		if ( ! empty( $options['label'] ) ) {

			// Label can not contains the character ":"" and "'", because
			// CPAC_Column::get_sanitized_label() will return an empty string
			// and make an exception for site_url()
			// Enable data:image url's
			if ( false === strpos( $options['label'], site_url() ) && false === strpos( $options['label'], 'data:' ) ) {
				$options['label'] = str_replace( ':', '', $options['label'] );
				$options['label'] = str_replace( "'", '', $options['label'] );
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
		return apply_filters( 'cac/column/settings_label', stripslashes( str_replace( '[cpac_site_url]', site_url(), $this->get_option( 'label' ) ) ), $this );
	}

	/**
	 * Sanitizes label using intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since 1.0
	 *
	 * @param string $string
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
	public function strip_trim( $string ) {
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

	// since 2.4.8
	public function get_raw_post_field( $field, $id ) {
		global $wpdb;

		return $id && is_numeric( $id ) ? $wpdb->get_var( $wpdb->prepare( "SELECT " . $wpdb->_real_escape( $field ) . " FROM {$wpdb->posts} WHERE ID = %d LIMIT 1", $id ) ) : false;
	}

	// since 2.4.8
	public function get_post_title( $id ) {
		return esc_html( $this->get_raw_post_field( 'post_title', $id ) );
	}

	/**
	 * @since 1.0
	 *
	 * @param int $post_id Post ID
	 *
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id, $words ) {
		global $post;

		$save_post = $post;
		$post = get_post( $post_id );

		setup_postdata( $post );

		$excerpt = get_the_excerpt();
		$post = $save_post;

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
	public function get_shortened_string( $text = '', $num_words = 30, $more = null ) {
		if ( ! $text ) {
			return false;
		}

		return wp_trim_words( $text, $num_words, $more );
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
		return $name ? sprintf( "<img alt='' src='%s' title='%s'/>", CPAC_URL . "assets/images/{$name}", esc_attr( $title ) ) : false;
	}

	/**
	 * @since 3.4.4
	 */
	public function get_user_postcount( $user_id, $post_type ) {
		global $wpdb;
		$sql = "
			SELECT COUNT(ID)
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_author = %d
			AND post_type = %s
		";

		return $wpdb->get_var( $wpdb->prepare( $sql, $user_id, $post_type ) );
	}

	/**
	 * @since 1.2.0
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	protected function is_image_url( $url ) {

		if ( ! is_string( $url ) ) {
			return false;
		}

		$validExt = array( '.jpg', '.jpeg', '.gif', '.png', '.bmp' );
		$ext = strrchr( $url, '.' );

		return in_array( $ext, $validExt );
	}

	/**
	 * @since 1.0
	 * @return array Image Sizes.
	 */
	private function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array(
			'default' => array(
				'title'   => __( 'Default', 'codepress-admin-columns' ),
				'options' => array(
					'thumbnail' => __( "Thumbnail", 'codepress-admin-columns' ),
					'medium'    => __( "Medium", 'codepress-admin-columns' ),
					'large'     => __( "Large", 'codepress-admin-columns' )
				)
			)
		);

		$all_sizes = get_intermediate_image_sizes();

		if ( ! empty( $all_sizes ) ) {
			foreach ( $all_sizes as $size ) {
				if ( 'medium_large' == $size || isset( $sizes['default']['options'][ $size ] ) ) {
					continue;
				}

				if ( ! isset( $sizes['defined'] ) ) {
					$sizes['defined']['title'] = __( "Others", 'codepress-admin-columns' );
				}

				$sizes['defined']['options'][ $size ] = ucwords( str_replace( '-', ' ', $size ) );
			}
		}

		// add sizes
		foreach ( $sizes as $key => $group ) {
			foreach ( array_keys( $group['options'] ) as $_size ) {

				$w = isset( $_wp_additional_image_sizes[ $_size ]['width'] ) ? $_wp_additional_image_sizes[ $_size ]['width'] : get_option( "{$_size}_size_w" );
				$h = isset( $_wp_additional_image_sizes[ $_size ]['height'] ) ? $_wp_additional_image_sizes[ $_size ]['height'] : get_option( "{$_size}_size_h" );
				if ( $w && $h ) {
					$sizes[ $key ]['options'][ $_size ] .= " ({$w} x {$h})";
				}
			}
		}

		// last
		$sizes['default']['options']['full'] = __( "Full Size", 'codepress-admin-columns' );

		$sizes['custom'] = array(
			'title'   => __( 'Custom', 'codepress-admin-columns' ),
			'options' => array( 'cpac-custom' => __( 'Custom Size', 'codepress-admin-columns' ) . '..' )
		);

		return $sizes;
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
	 *
	 * @param string $name
	 *
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
		$editor = wp_get_image_editor( $file );
		if ( is_wp_error( $editor ) ) {
			return false;
		}

		$editor->set_quality( $jpeg_quality );

		$resized = $editor->resize( $max_w, $max_h, $crop );
		if ( is_wp_error( $resized ) ) {
			return false;
		}

		$dest_file = $editor->generate_filename( $suffix, $dest_path );

		$saved = $editor->save( $dest_file );

		if ( is_wp_error( $saved ) ) {
			return false;
		}

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

		return $rgb && ( ( $rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114 ) < 186 ) ? '#ffffff' : '#333333';
	}

	/**
	 * Convert hex to rgb
	 *
	 * @since 1.0
	 */
	public function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		}
		else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb;
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 *
	 * @since 2.3
	 *
	 * @param string $input Input string
	 *
	 * @return int Number of words
	 */
	public function str_count_words( $input ) {

		$patterns = array(
			'strip' => '/<[a-zA-Z\/][^<>]*>/',
			'clean' => '/[0-9.(),;:!?%#$¿\'"_+=\\/-]+/',
			'w'     => '/\S\s+/',
			'c'     => '/\S/'
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
	 * @since 2.5
	 */
	public function get_empty_char() {
		return '&ndash;'; // dash
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

		if ( empty( $images ) || 'false' == $images ) {
			return array();
		}

		// turn string to array
		if ( is_string( $images ) || is_numeric( $images ) ) {
			if ( strpos( $images, ',' ) !== false ) {
				$images = array_filter( explode( ',', $this->strip_trim( str_replace( ' ', '', $images ) ) ) );
			}
			else {
				$images = array( $images );
			}
		}

		// Image size
		$defaults = array(
			'image_size'   => 'cpac-custom',
			'image_size_w' => 80,
			'image_size_h' => 80,
		);
		$args = wp_parse_args( $args, $defaults );

		$image_size = $args['image_size'];
		$image_size_w = $args['image_size_w'];
		$image_size_h = $args['image_size_h'];

		$thumbnails = array();
		foreach ( $images as $value ) {

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
						$thumbnails[] = "<img src='" . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $resized ) . "' alt='' width='{$image_size_w}' height='{$image_size_h}' />";
					} // return full image with maxed dimensions
					else {
						$thumbnails[] = "<img src='{$value}' alt='' style='max-width:{$image_size_w}px;max-height:{$image_size_h}px' />";
					}
				}
			} // Media Attachment
			elseif ( is_numeric( $value ) && wp_get_attachment_url( $value ) ) {

				$src = '';
				$width = '';
				$height = '';

				if ( ! $image_size || 'cpac-custom' == $image_size ) {
					$width = $image_size_w;
					$height = $image_size_h;

					// to make sure wp_get_attachment_image_src() get the image with matching dimensions.
					$image_size = array( $width, $height );
				}

				// Is Image
				if ( $attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

					$src = $attributes[0];
					$width = $attributes[1];
					$height = $attributes[2];

					// image size by name
					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}
				} // Is File, use icon
				elseif ( $attributes = wp_get_attachment_image_src( $value, $image_size, true ) ) {
					$src = $attributes[0];

					if ( $sizes = $this->get_image_size_by_name( $image_size ) ) {
						$width = $sizes['width'];
						$height = $sizes['height'];
					}
				}
				if ( is_array( $image_size ) ) {
					$width = $image_size_w;
					$height = $image_size_h;

					$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px; background-size: cover; background-image: url({$src}); background-position: center;'></span>";

				}
				else {
					$max = max( array( $width, $height ) );
					$thumbnails[] = "<span class='cpac-column-value-image' style='width:{$width}px;height:{$height}px;'><img style='max-width:{$max}px;max-height:{$max}px;' src='{$src}' alt=''/></span>";
				}

			}
		}

		return $thumbnails;
	}

	/**
	 * Implode for multi dimensional array
	 *
	 * @since 1.0
	 *
	 * @param string $glue
	 * @param array $pieces
	 *
	 * @return string Imploded array
	 */
	public function recursive_implode( $glue, $pieces ) {
		if ( is_array( $pieces ) ) {
			foreach ( $pieces as $r_pieces ) {
				if ( is_array( $r_pieces ) ) {
					$retVal[] = $this->recursive_implode( $glue, $r_pieces );
				}
				else {
					$retVal[] = $r_pieces;
				}
			}
			if ( isset( $retVal ) && is_array( $retVal ) ) {
				return implode( $glue, $retVal );
			}
		}

		if ( is_scalar( $pieces ) ) {
			return $pieces;
		}

		return false;
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

		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) ) {
			return false;
		}

		// some plugins store dates in a jquery timestamp format, format is in ms since The Epoch.
		// See http://api.jqueryui.com/datepicker/#utility-formatDate
		// credits: nmarks
		if ( is_numeric( $date ) ) {
			$length = strlen( trim( $date ) );

			// Dates before / around September 8th, 2001 are saved as 9 numbers * 1000 resulting in 12 numbers to store the time.
			// Dates after September 8th are saved as 10 numbers * 1000, resulting in 13 numbers.
			// For example the ACF Date and Time Picker uses this format.
			// credits: Ben C
			if ( 12 === $length || 13 === $length ) {
				$date = round( $date / 1000 ); // remove the ms
			}

			// Date format: yyyymmdd ( often used by ACF ) must start with 19xx or 20xx and is 8 long
			// @todo: in theory a numeric string of 8 can also be a unixtimestamp; no conversion would be needed
			if ( 8 === $length && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0 ) ) {
				$date = strtotime( $date );
			}
		} // Parse with strtotime if it's not numeric
		else {
			$date = strtotime( $date );
		}

		return $date;
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
	 * @return string Formatted date
	 */
	public function get_date( $date, $format = '' ) {
		if ( ! $timestamp = $this->get_timestamp( $date ) ) {
			return false;
		}

		// get general date format
		if ( ! $format ) {
			$format = get_option( 'date_format' );
		}

		return date_i18n( $format, $timestamp );
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
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

		if ( $display_as = $this->get_option( 'display_author_as' ) ) {

			if ( 'first_last_name' == $display_as ) {
				$first = ! empty( $userdata->first_name ) ? $userdata->first_name : '';
				$last = ! empty( $userdata->last_name ) ? " {$userdata->last_name}" : '';
				$name = $first . $last;
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
	 *
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function label_view( $label, $description = '', $for = '' ) {
		if ( $label ) : ?>
			<td class="label<?php echo $description ? ' description' : ''; ?>">
				<label for="<?php $this->attr_id( $for ); ?>">
					<span><?php echo stripslashes( $label ); ?></span>
					<?php if ( $description ) : ?><p class="description"><?php echo $description; ?></p><?php endif; ?>
				</label>
			</td>
			<?php
		endif;
	}

	/**
	 * @since NEWVERSION
	 */
	public function form_field( $args = array() ) {
		$defaults = array(
			'type'           => 'text',
			'name'           => '',
			'label'          => '', // empty label will apply colspan 2
			'description'    => '',
			'toggle_trigger' => '', // triggers a toggle event on toggle_handle
			'toggle_handle'  => '', // can be used to toggle this element
			'refresh_column' => false, // when value is selected the column element will be refreshed with ajax
			'hidden'         => false,
			'for'            => false,
			'section'        => false,
			'help'           => '' // help message below input field
		);
		$args = wp_parse_args( $args, $defaults );
		$field = (object) $args;
		?>
		<tr class="<?php echo $field->type; ?> column-<?php echo $field->name; ?><?php echo $field->hidden ? ' hide' : ''; ?><?php echo $field->section ? ' section' : ''; ?>"<?php echo $field->toggle_handle ? ' data-handle="' . $this->get_attr_id( $field->toggle_handle ) . '"' : ''; ?><?php echo $field->refresh_column ? ' data-refresh="1"' : ''; ?>>
			<?php $this->label_view( $field->label, $field->description, ( $field->for ? $field->for : $field->name ) ); ?>
			<td class="input"<?php echo( $field->toggle_trigger ? ' data-trigger="' . $this->get_attr_id( $field->toggle_trigger ) . '"' : '' ); ?><?php echo empty( $field->label ) ? ' colspan="2"' : ''; ?>>
				<?php
				switch ( $field->type ) {
					case 'select' :
						$this->select_field( $args );
						break;
					case 'radio' :
						$this->radio_field( $args );
						break;
					case 'text' :
						$this->text_field( $args );
						break;
					case 'width' :
						$this->width_field();
						break;
				}

				if ( $field->help ) : ?>
					<p class="help-msg">
						<?php echo $field->help; ?>
					</p>
				<?php endif; ?>

			</td>
		</tr>
		<?php
	}

	/**
	 * @since NEWVERSION
	 */
	public function select_field( $args ) {
		$defaults = array(
			'name'            => '',
			'options'         => array(),
			'grouped_options' => array(),
			'no_result'       => '',
			'default'         => '',
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $this->get_option( $args->name );
		if ( ! $current ) {
			$current = $args->default;
		}

		if ( $args->options || $args->grouped_options ) : ?>
			<select name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name ); ?>">
				<?php if ( $args->options ) : ?>
					<?php foreach ( $args->options as $key => $label ) : ?>
						<option value="<?php echo $key; ?>"<?php selected( $key, $current ); ?>><?php echo $label; ?></option>
					<?php endforeach; ?>
				<?php elseif ( $args->grouped_options ) : ?>
					<?php foreach ( $args->grouped_options as $group ) : ?>
						<optgroup label="<?php echo esc_attr( $group['title'] ); ?>">
							<?php foreach ( $group['options'] as $key => $label ) : ?>
								<option value="<?php echo $key ?>"<?php selected( $key, $current ) ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</optgroup>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>

			<?php //ajax message ?>
			<div class="msg"></div>
		<?php elseif ( $args->no_result ) :
			echo $args->no_result;
		endif;
	}

	/**
	 * @since NEWVERSION
	 */
	public function radio_field( $args ) {
		$defaults = array(
			'name'     => '',
			'options'  => array(),
			'default'  => '',
			'vertical' => false // display radio buttons vertical
		);

		$args = (object) wp_parse_args( (array) $args, $defaults );

		$current = $this->get_option( $args->name );
		if ( ! $current ) {
			$current = $args->default;
		}

		foreach ( $args->options as $key => $label ) : ?>
			<label>
				<input type="radio" name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name . '-' . $key ); ?>" value="<?php echo $key; ?>"<?php checked( $key, $current ); ?>>
				<?php echo $label; ?>
			</label>
			<?php echo $args->vertical ? '<br/>' : ''; ?>
		<?php endforeach;
	}

	/**
	 * @since NEWVERSION
	 */
	public function text_field( $args ) {
		$args = (object) wp_parse_args( $args, array(
			'name'        => '',
			'placeholder' => '',
		) ); ?>
		<input type="text" name="<?php $this->attr_name( $args->name ); ?>" id="<?php $this->attr_id( $args->name ); ?>" value="<?php echo esc_attr( stripslashes( $this->get_option( $args->name ) ) ); ?>"<?php echo $args->placeholder ? ' placeholder="' . esc_attr( $args->placeholder ) . '"' : ''; ?>/>
		<?php
	}

	/**
	 * @since NEWVERSION
	 */
	public function width_field() {
		?>
		<div class="description" title="<?php _e( 'default', 'codepress-admin-columns' ); ?>">
			<input class="width" type="text" placeholder="<?php _e( 'auto', 'codepress-admin-columns' ); ?>" name="<?php $this->attr_name( 'width' ); ?>" id="<?php $this->attr_id( 'width' ); ?>" value="<?php echo $this->get_option( 'width' ); ?>"/>
			<span class="unit"><?php echo $this->get_option( 'width_unit' ); ?></span>
		</div>
		<div class="width-slider"></div>

		<div class="unit-select">
			<label for="<?php $this->attr_id( 'width_unit_px' ); ?>">
				<input type="radio" class="unit" name="<?php $this->attr_name( 'width_unit' ); ?>" id="<?php $this->attr_id( 'width_unit_px' ); ?>" value="px"<?php checked( $this->get_option( 'width_unit' ), 'px' ); ?>/>px
			</label>
			<label for="<?php $this->attr_id( 'width_unit_perc' ); ?>">
				<input type="radio" class="unit" name="<?php $this->attr_name( 'width_unit' ); ?>" id="<?php $this->attr_id( 'width_unit_perc' ); ?>" value="%"<?php checked( $this->get_option( 'width_unit' ), '%' ); ?>/>%
			</label>
		</div>
		<?php
	}

	/**
	 * @since 2.3.2
	 */
	public function display_field_user_format() {

		$nametypes = array(
			'display_name'    => __( 'Display Name', 'codepress-admin-columns' ),
			'first_name'      => __( 'First Name', 'codepress-admin-columns' ),
			'last_name'       => __( 'Last Name', 'codepress-admin-columns' ),
			'nickname'        => __( 'Nickname', 'codepress-admin-columns' ),
			'user_login'      => __( 'User Login', 'codepress-admin-columns' ),
			'user_email'      => __( 'User Email', 'codepress-admin-columns' ),
			'ID'              => __( 'User ID', 'codepress-admin-columns' ),
			'first_last_name' => __( 'First and Last Name', 'codepress-admin-columns' ),
		);

		asort( $nametypes ); // sorts also when translated

		$this->form_field( array(
			'type'        => 'select',
			'name'        => 'display_author_as',
			'label'       => __( 'Display format', 'codepress-admin-columns' ),
			'options'     => $nametypes,
			'description' => __( 'This is the format of the author name.', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @since 2.0
	 */
	public function display_field_date_format() {
		$this->form_field( array(
			'type'        => 'text',
			'name'        => 'date_format',
			'label'       => __( 'Date Format', 'codepress-admin-columns' ),
			'placeholder' => __( 'Example:', 'codepress-admin-columns' ) . ' d M Y H:i',
			'description' => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
			'help'        => sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>"
		) );
	}

	/**
	 * @since 2.0
	 */
	public function display_field_excerpt_length() {
		$this->form_field( array(
			'type'        => 'text',
			'name'        => 'excerpt_length',
			'label'       => __( 'Excerpt length', 'codepress-admin-columns' ),
			'description' => __( 'Number of words', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @since 2.4.9
	 */
	public function display_field_link_label() {
		$this->form_field( array(
			'type'        => 'text',
			'name'        => 'link_label',
			'label'       => __( 'Link label', 'codepress-admin-columns' ),
			'description' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @since 2.0
	 */
	public function display_field_preview_size() { ?>
		<tr>
			<?php $this->label_view( __( 'Preview size', 'codepress-admin-columns' ) ); ?>
			<td class="input nopadding">
				<table class="widefat">
					<?php
					$this->form_field( array(
						'type'            => 'select',
						'name'            => 'image_size',
						'grouped_options' => $this->get_all_image_sizes()
					) );
					$this->form_field( array(
						'type'          => 'text',
						'name'          => 'image_size_w',
						'label'         => __( "Width", 'codepress-admin-columns' ),
						'description'   => __( "Width in pixels", 'codepress-admin-columns' ),
						'toggle_handle' => 'image_size_w',
						'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' )
					) );
					$this->form_field( array(
						'type'          => 'text',
						'name'          => 'image_size_h',
						'label'         => __( "Height", 'codepress-admin-columns' ),
						'description'   => __( "Height in pixels", 'codepress-admin-columns' ),
						'toggle_handle' => 'image_size_h',
						'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' )
					) );
					?>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * @since 2.1.1
	 */
	public function display_field_before_after() { ?>
		<tr class="section">
			<?php $this->label_view( __( 'Display Options', 'codepress-admin-columns' ) ); ?>
			<td class="input nopadding">
				<table class="widefat">
					<?php
					$this->form_field( array(
						'type'        => 'text',
						'name'        => 'before',
						'label'       => __( "Before", 'codepress-admin-columns' ),
						'description' => __( 'This text will appear before the column value.', 'codepress-admin-columns' )
					) );
					$this->form_field( array(
						'type'        => 'text',
						'name'        => 'after',
						'label'       => __( "After", 'codepress-admin-columns' ),
						'description' => __( 'This text will appear after the column value.', 'codepress-admin-columns' )
					) );
					?>
				</table>
			</td>
		</tr>
		<?php
	}

	/**
	 * @since 2.0
	 */
	public function display() {
		$classes = implode( ' ', array_filter( array( "cpac-box-{$this->properties->type}", $this->properties->classes ) ) );
		?>
		<div class="cpac-column <?php echo $classes; ?>" data-type="<?php echo $this->get_type(); ?>"<?php echo $this->properties->is_cloneable ? " data-clone='{$this->properties->clone}'" : ''; ?> data-default="<?php echo $this->is_default(); ?>">
			<input type="hidden" class="column-name" name="<?php echo $this->attr_name( 'column-name' ); ?>" value="<?php echo esc_attr( $this->get_name() ); ?>"/>
			<input type="hidden" class="type" name="<?php echo $this->attr_name( 'type' ); ?>" value="<?php echo $this->get_type(); ?>"/>
			<input type="hidden" class="clone" name="<?php echo $this->attr_name( 'clone' ); ?>" value="<?php echo $this->properties->clone; ?>"/>

			<div class="column-meta">
				<table class="widefat">
					<tbody>
					<tr>
						<td class="column_sort">
							<span class="cpacicon-move"></span>
						</td>
						<td class="column_label">
							<div class="inner">
								<div class="meta">

									<span title="<?php echo esc_attr( __( 'width', 'codepress-admin-columns' ) ); ?>" class="width" data-indicator-id="">
										<?php echo $this->get_option( 'width' ) ? $this->get_option( 'width' ) . $this->get_option( 'width_unit' ) : ''; ?>
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
								<a class="edit-button" href="javascript:;"><?php _e( 'Edit', 'codepress-admin-columns' ); ?></a>
								<a class="close-button" href="javascript:;"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $this->properties->is_cloneable ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a class="remove-button" href="javascript:;"><?php _e( 'Remove', 'codepress-admin-columns' ); ?></a>
							</div>
						</td>
						<td class="column_type">
							<div class="inner">
								<a href="#"><?php echo stripslashes( $this->get_type_label() ); ?></a>
							</div>
						</td>
						<td class="column_edit">
						</td>
					</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="column-form">
				<table class="widefat">
					<tbody>

					<?php
					$this->form_field( array(
						'type'            => 'select',
						'name'            => 'type',
						'label'           => __( 'Type', 'codepress-admin-columns' ),
						'description'     => __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $this->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $this->get_name() . '</em>',
						'grouped_options' => $this->get_storage_model()->get_grouped_columns(),
						'default'         => $this->get_type()
					) );

					$this->form_field( array(
						'type'        => 'text',
						'name'        => 'label',
						'label'       => __( 'Label', 'codepress-admin-columns' ),
						'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
						'hidden'      => $this->properties->hide_label
					) );

					$this->form_field( array(
						'type'  => 'width',
						'name'  => 'width',
						'label' => __( 'Width', 'codepress-admin-columns' )
					) );

					/**
					 * Fires directly before the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
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
					 * Load before and after fields for custom columns.
					 *
					 */
					if ( $this->get_property( 'use_before_after' ) && ! $this->is_default() ) {
						$this->display_field_before_after();
					}
					?>

					<?php
					/**
					 * Fires directly after the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
					 * @param CPAC_Column $column_instance Column class instance
					 */
					do_action( 'cac/column/settings_after', $this );
					?>

					<tr class="column_action section">
						<td class="label"></td>
						<td class="input">
							<p>
								<a href="#" class="close-button button"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $this->properties->is_cloneable ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a href="#" class="remove-button"><?php _e( 'Remove' ); ?></a>
							</p>
						</td>
					</tr>

					</tbody>
				</table>
			</div><!--.column-form-->
		</div><!--.cpac-column-->
		<?php
	}

	/**
	 * Display settings field for post property to display
	 *
	 * @since 2.4.7
	 */
	public function display_field_post_property_display() {
		$this->form_field( array(
			'type'        => 'select',
			'name'        => 'post_property_display',
			'label'       => __( 'Property To Display', 'codepress-admin-columns' ),
			'options'     => array(
				'title'  => __( 'Title' ), // default
				'id'     => __( 'ID' ),
				'author' => __( 'Author' )
			),
			'description' => __( 'Post property to display for related post(s).', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * Display settings field for the page the posts should link to
	 *
	 * @since 2.4.7
	 */
	public function display_field_post_link_to() {
		$this->form_field( array(
			'type'        => 'select',
			'name'        => 'post_link_to',
			'label'       => __( 'Link To', 'codepress-admin-columns' ),
			'options'     => array(
				''            => __( 'None' ),
				'edit_post'   => __( 'Edit Post' ),
				'view_post'   => __( 'View Post' ),
				'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
				'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' )
			),
			'description' => __( 'Page the posts should link to.', 'codepress-admin-columns' )
		) );
	}

	/**
	 * @since 2.4.7
	 */
	function display_settings_placeholder( $url ) { ?>
		<div class="is-disabled">
			<p>
				<strong><?php printf( __( "The %s column is only available in Admin Columns Pro - Business or Developer.", 'codepress-admin-columns' ), $this->get_label() ); ?></strong>
			</p>

			<p>
				<?php printf( __( "If you have a business or developer licence please download & install your %s add-on from the <a href='%s'>add-ons tab</a>.", 'codepress-admin-columns' ), $this->get_label(), admin_url( 'options-general.php?page=codepress-admin-columns&tab=addons' ) ); ?>
			</p>

			<p>
				<?php printf( __( "Admin Columns Pro offers full %s integration, allowing you to easily display and edit %s fields from within your overview.", 'codepress-admin-columns' ), $this->get_label(), $this->get_label() ); ?>
			</p>
			<a href="<?php echo add_query_arg( array(
				'utm_source'   => 'plugin-installation',
				'utm_medium'   => $this->get_type(),
				'utm_campaign' => 'plugin-installation'
			), $url ); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>
		</div>
		<?php
	}





	// Deprecated methods

	/**
	 * @since 2.3.4
	 * @deprecated NEWVERSION
	 *
	 * @param string $name Name of the column option
	 * @param string $label Label
	 * @param array $options Select options
	 * @param strong $description (optional) Description below the label
	 */
	public function display_field_text( $name, $label, $description = '', $placeholder = '', $optional_toggle_id = '' ) {
		_deprecated_function( 'CPAC_Column::display_field_text', CPAC_VERSION, 'CPAC_Column::form_field' );

		$this->form_field( array(
			'type'          => 'text',
			'name'          => $name,
			'label'         => $label,
			'description'   => $description,
			'toggle_handle' => $optional_toggle_id,
		) );
	}

	/**
	 * @since 2.3.4
	 * @deprecated NEWVERSION
	 *
	 * @param string $name Name of the column option
	 * @param string $label Label
	 * @param array $options Select options
	 * @param strong $description (optional) Description below the label
	 * @param string $optional_toggle_id (optional) Toggle ID will hide the row untill the toggle is triggered
	 * @param boolean $refresh This will JS refresh the column on change.
	 */
	public function display_field_select( $name, $label, $options = array(), $description = '', $optional_toggle_id = '', $js_refresh = false ) {
		_deprecated_function( 'CPAC_Column::display_field_select', CPAC_VERSION, 'CPAC_Column::form_field' );

		$this->form_field( array(
			'type'           => 'select',
			'name'           => $name,
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
	 *
	 * @param string $name Name of the column option
	 * @param string $label Label
	 * @param array $options Select options
	 * @param strong $description (optional) Description below the label
	 * @param string $optional_toggle_id (optional) Toggle ID will hide the row untill the toggle is triggered
	 */
	public function display_field_radio( $name, $label, $options = array(), $description = '', $toggle_handle = false, $toggle_trigger = false, $colspan = false ) {
		_deprecated_function( 'CPAC_Column::display_field_radio', CPAC_VERSION, 'CPAC_Column::form_field' );

		$this->form_field( array(
			'type'           => 'radio',
			'name'           => $name,
			'label'          => $label,
			'options'        => $options,
			'description'    => $description,
			'toggle_trigger' => $toggle_trigger,
			'toggle_handle'  => $toggle_handle,
			'colspan'        => $colspan,
		) );
	}
}