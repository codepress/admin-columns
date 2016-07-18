<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column class
 *
 * @since 2.0
 *
 * @param object $storage_model CPAC_Storage_Model
 */
class CPAC_Column {

	/**
	 * A Storage Model can be a Post Type, User, Comment, Link or Media storage type.
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
	 * @var AC_ColumnFieldSettings
	 */
	private $settings;

	/**
	 * @since 2.5
	 * @return false|CPAC_Storage_Model
	 */
	public function __get( $key ) {
		$call = false;
		if ( 'storage_model' == $key ) {
			$call = 'get_' . $key;
		}

		return $call ? call_user_func( array( $this, $call ) ) : false;
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

	public function settings() {
		return $this->settings;
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

		// Default options
		$this->options = array(
			'label'      => null,  // Human readable label
			'width'      => null,  // Width for this column.
			'width_unit' => '%',   // Unit for width; percentage (%) or pixels (px).
		);

		do_action( 'ac/column/defaults', $this );
	}

	/**
	 * After Setup
	 *
	 */
	public function after_setup() {

		// Convert properties and options arrays to object
		$this->options = (object) $this->options;
		$this->properties = (object) $this->properties;

		// Column name defaults to column type
		if ( null === $this->properties->name ) {
			$this->properties->name = $this->properties->type;
		}

		// Column label defaults to column type label
		if ( null === $this->options->label ) {
			$this->options->label = $this->properties->label;
		}

		$this->settings = new AC_ColumnFieldSettings( $this->get_name(), $this->get_storage_model_key() );

		$this->populate_options();

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
	 * @since NEWVERSION
	 * @param array $options
	 */
	public function populate_options( $options = array() ) {
		if ( ! $options ) {
			$stored = $this->get_storage_model()->get_stored_columns();
			if ( isset( $stored[ $this->get_name() ] ) ) {
				$options = $stored[ $this->get_name() ];
			}
		}

		if ( ! $options ) {
			return;
		}

		if ( isset( $options['clone'] ) ) {
			$this->set_clone( $options['clone'] );
		}
		// replace urls, so export will not have to deal with them
		if ( isset( $options['label'] ) ) {
			$options['label'] = stripslashes( str_replace( '[cpac_site_url]', site_url(), $options['label'] ) );
		}

		$options = array_merge( (array) $this->options, $options );

		$this->settings()->set_options( $options );

		$this->options = (object) $options;
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
			->set_option( 'label', $label );
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
		return 'px' === $this->get_option( 'width_unit' ) ? 'px' : '%';
	}

	/**
	 * Get the column options set by the user
	 *
	 * @since 2.3.4
	 * @return stdClass Column options set by user
	 */
	public function get_options() {
		return $this->options;
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
	 * @return string Single column option
	 */
	public function get_option( $name ) {
		return isset( $this->get_options()->{$name} ) ? $this->get_options()->{$name} : false;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $option
	 * @param string $value
	 *
	 * @return $this CPAC_Column
	 */
	public function set_option( $option, $value ) {
		$this->options->{$option} = $value;

		return $this;
	}

	/**
	 * Get a single column option
	 *
	 * @since 2.4.8
	 * @return array Column options set by user
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
	 * @since 2.3.4
	 * @return CPAC_Storage_Model
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
	 * @since: 2.2.6
	 *
	 */
	public function get_color_for_display( $color_hex ) {
		if ( ! $color_hex ) {
			return false;
		}
		$text_color = ac_helper()->string->hex_get_contrast( $color_hex );

		return '<div class="cpac-color"><span style="background-color:' . esc_attr( $color_hex ) . ';color:' . esc_attr( $text_color ) . '">' . esc_html( $color_hex ) . '</span></div>';
	}

	/**
	 * @since 2.0
	 *
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function label_view( $label, $description = '', $for = '', $more_link = false ) {
		if ( $label ) : ?>
			<td class="label<?php echo esc_attr( $description ? ' description' : '' ); ?>">
				<label for="<?php $this->settings()->attr_id( $for ); ?>">
					<span class="label"><?php echo stripslashes( $label ); ?></span>
					<?php if ( $more_link ) : ?>
						<a target="_blank" class="more-link" title="<?php echo esc_attr( __( 'View more' ) ); ?>" href="<?php echo esc_url( $more_link ); ?>"><span class="dashicons dashicons-external"></span></a>
					<?php endif; ?>
					<?php if ( $description ) : ?><p class="description"><?php echo $description; ?></p><?php endif; ?>
				</label>
			</td>
			<?php
		endif;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param $user
	 * @param bool $format
	 *
	 * @return false|string
	 */
	public function get_user_formatted( $user ) {
		return ac_helper()->user->get_display_name( $user, $this->get_option( 'display_author_as' ) );
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

		natcasesort( $nametypes ); // sorts also when translated

		$this->settings->fields( array(
			'type'        => 'select',
			'name'        => 'display_author_as',
			'label'       => __( 'Display format', 'codepress-admin-columns' ),
			'options'     => $nametypes,
			'description' => __( 'This is the format of the author name.', 'codepress-admin-columns' ),
		) );
	}

	public function form_field_date() {
		return array(
			'type'        => 'text',
			'name'        => 'date_format',
			'label'       => __( 'Date Format', 'codepress-admin-columns' ),
			'placeholder' => __( 'Example:', 'codepress-admin-columns' ) . ' d M Y H:i',
			'description' => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
			'help'        => sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>",
		);
	}

	/**
	 * @since 2.0
	 */
	public function display_field_date_format() {
		$this->settings->fields( array(
			'type'        => 'text',
			'name'        => 'date_format',
			'label'       => __( 'Date Format', 'codepress-admin-columns' ),
			'placeholder' => __( 'Example:', 'codepress-admin-columns' ) . ' d M Y H:i',
			'description' => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
			'help'        => sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>",
		) );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_date_formatted( $date ) {
		return ac_helper()->date->date( $date, $this->get_option( 'date_format' ) );
	}

	public function form_field_word_limit() {
		return array(
			'type'        => 'number',
			'name'        => 'excerpt_length',
			'label'       => __( 'Word Limit', 'codepress-admin-columns' ),
			'description' => __( 'Maximum number of words', 'codepress-admin-columns' ),
		);
	}

	/**
	 * @since NEWVERSION
	 */
	public function display_field_word_limit() {
		$this->settings->fields( array(
			'type'        => 'number',
			'name'        => 'excerpt_length',
			'label'       => __( 'Word Limit', 'codepress-admin-columns' ),
			'description' => __( 'Maximum number of words', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @since NEWVERSION
	 */
	public function format_word_limit( $string ) {
		$limit = $this->get_option( 'excerpt_length' );

		return $limit ? wp_trim_words( $string, $limit ) : $string;
	}

	/**
	 * @since NEWVERSION
	 */
	public function display_field_character_limit() {
		$this->settings->fields( array(
			'type'        => 'number',
			'name'        => 'character_limit',
			'label'       => __( 'Character Limit', 'codepress-admin-columns' ),
			'description' => __( 'Maximum number of characters', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @since NEWVERSION
	 */
	public function format_character_limit( $string ) {
		$limit = $this->get_option( 'character_limit' );

		return is_numeric( $limit ) && 0 < $limit && strlen( $string ) > $limit ? substr( $string, 0, $limit ) . __( '&hellip;' ) : $string;
	}

	/**
	 * @since 2.0
	 */
	public function display_field_excerpt_length() {
		$this->display_field_word_limit();
	}

	public function form_field_link_label() {
		return array(
			'type'        => 'text',
			'name'        => 'link_label',
			'label'       => __( 'Link label', 'codepress-admin-columns' ),
			'description' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
		);
	}

	/**
	 * @since 2.4.9
	 */
	public function display_field_link_label() {
		$this->settings->fields( array(
			'type'        => 'text',
			'name'        => 'link_label',
			'label'       => __( 'Link label', 'codepress-admin-columns' ),
			'description' => __( 'Leave blank to display the url', 'codepress-admin-columns' ),
		) );
	}

	/**
	 * @return array|string
	 */
	public function get_image_size_formatted() {
		$size = $this->get_option( 'image_size' );

		if ( 'cpac-custom' == $size ) {
			$size = array(
				$this->get_option( 'image_size_w' ),
				$this->get_option( 'image_size_h' ),
			);
		}

		return $size;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param int[] | int $attachment_ids
	 *
	 * @return string HTML Image
	 */
	public function get_image_formatted( $attachment_ids ) {
		return ac_helper()->image->get_images_by_ids( $attachment_ids, $this->get_image_size_formatted() );
	}

	/**
	 * @since 1.0
	 * @return array Image Sizes.
	 */
	private function get_grouped_image_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array(
			'default' => array(
				'title'   => __( 'Default', 'codepress-admin-columns' ),
				'options' => array(
					'thumbnail' => __( "Thumbnail", 'codepress-admin-columns' ),
					'medium'    => __( "Medium", 'codepress-admin-columns' ),
					'large'     => __( "Large", 'codepress-admin-columns' ),
				),
			),
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
			'options' => array( 'cpac-custom' => __( 'Custom Size', 'codepress-admin-columns' ) . '..' ),
		);

		return $sizes;
	}

	public function form_fields_image() {
		return array(
			array(
				'label'           => __( 'Image Size', 'codepress-admin-columns' ),
				'type'            => 'select',
				'name'            => 'image_size',
				'grouped_options' => $this->get_grouped_image_sizes(),
			),
			array(
				'type'          => 'text',
				'name'          => 'image_size_w',
				'label'         => __( "Width", 'codepress-admin-columns' ),
				'description'   => __( "Width in pixels", 'codepress-admin-columns' ),
				'toggle_handle' => 'image_size_w',
				'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' ),
			),
			array(
				'type'          => 'text',
				'name'          => 'image_size_h',
				'label'         => __( "Height", 'codepress-admin-columns' ),
				'description'   => __( "Height in pixels", 'codepress-admin-columns' ),
				'toggle_handle' => 'image_size_h',
				'hidden'        => 'cpac-custom' !== $this->get_option( 'image_size' ),
			),
		);
	}

	public function get_form_args_before() {
		return array(
			'type'        => 'text',
			'name'        => 'before',
			'label'       => __( "Before", 'codepress-admin-columns' ),
			'description' => __( 'This text will appear before the column value.', 'codepress-admin-columns' ),
		);
	}

	public function get_form_args_after() {
		return array(
			'type'        => 'text',
			'name'        => 'after',
			'label'       => __( "After", 'codepress-admin-columns' ),
			'description' => __( 'This text will appear after the column value.', 'codepress-admin-columns' ),
		);
	}

	/**
	 * @since 2.1.1
	 */
	public function display_field_before_after() {
		$this->settings->fields( array(
			'label'  => __( 'Display Options', 'codepress-admin-columns' ),
			'fields' => array(
				$this->get_form_args_before(),
				$this->get_form_args_after(),
			),
		) );

	}

	public function display_indicator( $name, $label ) { ?>
		<span class="indicator-<?php echo esc_attr( $name ); ?> <?php echo esc_attr( $this->get_option( $name ) ); ?>" data-indicator-id="<?php $this->settings()->attr_id( $name ); ?>" title="<?php echo esc_attr( $label ); ?>"></span>
		<?php
	}

	/**
	 * Display settings field for post property to display
	 *
	 * @since 2.4.7
	 */
	public function display_field_post_property_display() {
		$this->settings->fields( array(
			'type'        => 'select',
			'name'        => 'post_property_display',
			'label'       => __( 'Property To Display', 'codepress-admin-columns' ),
			'options'     => array(
				'title'  => __( 'Title' ), // default
				'id'     => __( 'ID' ),
				'author' => __( 'Author' ),
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
		$this->settings->fields( array(
			'type'        => 'select',
			'name'        => 'post_link_to',
			'label'       => __( 'Link To', 'codepress-admin-columns' ),
			'options'     => array(
				''            => __( 'None' ),
				'edit_post'   => __( 'Edit Post' ),
				'view_post'   => __( 'View Post' ),
				'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
				'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
			),
			'description' => __( 'Page the posts should link to.', 'codepress-admin-columns' ),
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
				'utm_campaign' => 'plugin-installation',
			), $url ); ?>" class="button button-primary"><?php _e( 'Find out more', 'codepress-admin-columns' ); ?></a>
		</div>
		<?php
	}





	// Deprecated methods

	/**
	 * @param string $field_name
	 */
	public function attr_name( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->settings()->attr_name()' );

		echo $this->settings()->attr_name( $field_name );
	}

	/**
	 * @param string $field_name
	 *
	 * @return string Attribute name
	 */
	public function get_attr_name( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->settings()->get_attr_name()' );

		return $this->settings()->get_attr_name( $field_name );
	}

	/**
	 * @param string $field_key
	 *
	 * @return string Attribute Name
	 */
	public function get_attr_id( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->settings()->get_attr_id()' );

		return $this->settings()->get_attr_id( $field_name );
	}

	public function attr_id( $field_name ) {
		_deprecated_function( __METHOD__, 'AC NEWVERSION', '$this->settings()->attr_id()' );

		echo $this->settings()->attr_id( $field_name );
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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::set_option()' );

		return $this->set_option( $option, $value );
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

		ac_helper()->user->get_display_name( $user, $format );
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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'ac_helper()->image->get_thumbnails()' );

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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::form_field()' );

		$this->settings->fields( array(
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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::form_field()' );

		$this->settings->fields( array(
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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::form_field()' );

		$this->settings->fields( array(
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
		_deprecated_function( __METHOD__, 'AC NEWVERSION', 'CPAC_Column::settings()->image_field()' );

		$this->settings()->image_field();
	}

}