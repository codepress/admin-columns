<?php

/**
 * @since NEWVERSION
 */
abstract class AC_Column {

	/**
	 * @var string Unique type
	 */
	private $type;

	/**
	 * @var string Label which describes this column
	 */
	private $label;

	/**
	 * @var string Group name
	 */
	private $group;

	/**
	 * @var bool An original column will use the already defined column value and label.
	 */
	private $original;

	/**
	 * @var int Unique clone ID
	 */
	private $clone;

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var AC_ListScreenAbstract
	 */
	protected $list_screen;

	/**
	 * @since 2.0
	 *
	 * @param int $id ID
	 *
	 * @return string Value for displaying inside the column cell.
	 */
	// TODO: protected or public? Is it better to always use get_display_value as public
	abstract protected function get_value( $id );

	/**
	 * @since NEWVERSION
	 * @return mixed
	 */
	public function __get( $name ) {
		return in_array( $name, array( 'format', 'field_settings', 'helper' ) ) ? call_user_func( array( $this, $name ) ) : false;
	}

	/**
	 * Get the name of the column.
	 *
	 * @since 2.3.4
	 * @return string Column name
	 */
	public function get_name() {
		return $this->clone > 0 ? $this->type . '-' . $this->clone : $this->type;
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.3.4
	 * @return string Type
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return AC_ListScreenAbstract
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * @param AC_ListScreenAbstract $list_screen
	 *
	 * @return $this
	 */
	public function set_list_screen( AC_ListScreenAbstract $list_screen ) {
		$this->list_screen = $list_screen;

		return $this;
	}

	/**
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 * @return string Label of column's type
	 */
	public function get_label() {
		$label = $this->label;

		if ( ! $label && $this->is_original() ) {
			$label = $this->get_list_screen()->get_original_label( $this->get_name() );
		}

		return $label;
	}

	/**
	 * @param string $label
	 *
	 * @return AC_Column
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @since NEWVERSION
	 * @return string Group
	 */
	public function get_group() {
		return $this->group ? $this->group : __( 'Default', 'codepress-admin-columns' );
	}

	/**
	 * @param string $group Group label
	 *
	 * @return $this
	 */
	public function set_group( $group ) {
		$this->group = $group;

		return $this;
	}

	/**
	 * @return int Clone ID
	 */
	public function get_clone() {
		return $this->clone;
	}

	/**
	 * @param int $clone
	 */
	public function set_clone( $clone ) {
		$this->clone = absint( $clone );

		return $this;
	}

	/**
	 * @return string Post type
	 */
	public function get_post_type() {
		return method_exists( $this->list_screen, 'get_post_type' ) ? $this->list_screen->get_post_type() : false;
	}

	/**
	 * @return string Taxonomy
	 */
	public function get_taxonomy() {
		return method_exists( $this->list_screen, 'get_taxonomy' ) ? $this->list_screen->get_taxonomy() : false;
	}

	/**
	 * Return true when a default column has been replaced by a custom column.
	 * An original column will then use the original label and value.
	 *
	 * @since NEWVERSION
	 */
	public function is_original() {
		return $this->original;
	}

	/**
	 * @param bool $boolean
	 *
	 * @return $this
	 */
	public function set_original( $boolean ) {
		$this->original = (bool) $boolean;

		return $this;
	}

	/**
	 * Overwrite this function in child class.
	 * Determine whether this column type should be available
	 *
	 * @since 2.2
	 *
	 * @return bool Whether the column type should be available
	 */
	// TODO: used to be apply_conditional(), replace inside all columns (including add-ons)
	public function is_valid() {
		return true;
	}

	/**
	 * Get the raw, underlying value for the column
	 * Not suitable for direct display, use get_value() for that
	 *
	 * @since 2.0.3
	 *
	 * @param int $id ID
	 *
	 * @return array|string|bool Raw column value
	 */
	// TODO: make abstract?
	public function get_raw_value( $id ) {
		return $id;
	}

	/**
	 * @param AC_Settings_SettingAbstract $setting
	 *
	 * @return $this
	 */
	public function add_setting( AC_Settings_SettingAbstract $setting ) {
		$this->settings[ $setting->get_name() ] = $setting;

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return AC_Settings_SettingAbstract|false
	 */
	public function get_setting( $id ) {
		$settings = $this->get_settings();

		return isset( $settings[ $id ] ) ? $settings[ $id ] : false;
	}

	/**
	 * @return AC_Settings_SettingAbstract[]
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->register_settings();

			do_action( 'ac/column/settings', $this );
		}

		return $this->settings;
	}

	/**
	 * Register settings
	 */
	protected function register_settings() {
		$this->add_setting( new AC_Settings_Setting_Type( $this ) )
		     ->add_setting( new AC_Settings_Setting_Label( $this ) )
		     ->add_setting( new AC_Settings_Setting_Width( $this ) );

		// tested
		$this->add_setting( new AC_Settings_Setting_User( $this ) );

		// test
		$this->add_setting( new AC_Settings_Setting_BeforeAfter( $this ) );
		$this->add_setting( new AC_Settings_Setting_Date( $this ) );
		$this->add_setting( new AC_Settings_Setting_LinkLabel( $this ) );
		$this->add_setting( new AC_Settings_Setting_Post( $this ) );
		$this->add_setting( new AC_Settings_Setting_Image( $this ) );
		$this->add_setting( new AC_Settings_Setting_WordsPerMinute( $this ) );
	}

	/**
	 * @param string $key
	 *
	 * @return null|string|bool
	 */
	public function get_option( $key ) {
		$options = $this->get_options();

		return isset( $options[ $key ] ) ? $options[ $key ] : null;
	}

	public function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * @return array|false
	 */
	public function get_options() {

		// Populate options from the list screen
		if ( null === $this->options ) {
			$this->set_options( $this->get_list_screen()->settings()->get_setting( $this->get_name() ) );
		}

		return $this->options;
	}

	/**
	 * Display settings
	 *
	 * @return string
	 */
	public function render() {
		$views = array();

		foreach ( $this->get_settings() as $setting ) {
			$views[] = $setting->render();
		}

		return implode( "\n", array_filter( $views ) );
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param $id
	 *
	 * @return string Value
	 */
	// TODO: looks like a utility method
	public function get_display_value( $id ) {
		$value = '';

		$display_value = $this->get_value( $id );

		if ( $display_value || 0 === $display_value ) {
			$value = $display_value;
		}

		if ( is_scalar( $value ) ) {
			$value = $this->get_option( 'before' ) . $value . $this->get_option( 'after' );
		}

		$value = apply_filters( "cac/column/value", $value, $id, $this );
		$value = apply_filters( "cac/column/value/" . $this->get_type(), $value, $id, $this );

		return $value;
	}




	/**
	 * TODO: refactor. remove.
	 *
	 */

	/**
	 * @var AC_ColumnFieldSettings Instance for adding field settings to the column
	 */
	// TODO: should be an in the settings object
	private $field_settings;

	/**
	 * @var AC_ColumnFieldFormat Instance for formatting column values
	 */
	// TODO: should be an in the settings object
	private $format;

	/**
	 * @return AC_ColumnFieldSettings
	 */
	// TODO: remove in favor of AC_Settings_Column
	protected function field_settings() {
		if ( null === $this->field_settings ) {
			$this->field_settings = new AC_ColumnFieldSettings( $this );
		}

		return $this->field_settings;
	}

	/**
	 * @return AC_ColumnFieldFormat
	 */
	// TODO: remove in favor of AC_Settings_Column
	protected function format() {
		if ( null === $this->format ) {
			$this->format = new AC_ColumnFieldFormat( $this );
		}

		return $this->format;
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
	// TODO: move to field settings
	public function sanitize_options( $options ) {
		return $options;
	}

	/**
	 * @since 2.0
	 */
	public function display_settings() {
	}

	/**
	 * Hide the label field in the column settings
	 *
	 * @return bool
	 */
	// TODO: should go into field settings
	/*public function is_hide_label() {
		return $this->hide_label;
	}*/

	/**
	 * @param $bool
	 *
	 * @return AC_Column
	 */
	// TODO: should go into field settings
	/*public function set_hide_label( $boolean ) {

		// TODO: this was called $this->properties->hide_label. Make sure to replace all.
		$this->hide_label = $boolean;

		return $this;
	}*/

	// TODO: should go into field settings
	/*public function use_before_after() {

		// TODO: this was called $this->properties->use_before_after. Make sure to replace all.
		return apply_filters( 'cac/column/properties/use_before_after', $this->use_before_after, $this );
	}*/

	// TODO: should go into field settings
	/*public function set_use_before_after( $boolean ) {
		$this->use_before_after = $boolean;

		return $this;
	}*/

	// TODO: move all options into it's own class

	/**
	 * Get the stored column options
	 *
	 * @since 2.3.4
	 * @return array Column options set by user
	 */
	// TODO: should be it's own class
	/*public function get_options() {
		return $this->options;
	}*/

	/**
	 * @param array $options
	 *
	 * @return AC_Column
	 */
	// TODO: $options should be an instance
	/*public function set_options( $options ) {
		$this->options = $options;

		return $this;
	}*/

	/**
	 * @param array $options
	 *
	 * @return AC_Column
	 */
	/*public function set_option( $key, $value ) {
		$this->options[ $key ] = $value;

		return $this;
	}*/

	/**
	 * Get a single column option
	 *
	 * @since 2.3.4
	 * @return string|false Single column option
	 */
	// TODO: replace with AC_Settings_Column->get_option()
	/*public function get_option( $name ) {
		$value = $this->settings()->get_value( $name );

		// TODO: remove fallback. use get_value only
		// Fallback
		if ( ! $value ) {
			$value = $this->settings()->get_option( $name );
		}

		return $value;
	}*/

	/**
	 * @since NEWVERSION
	 * @return int Width
	 */
	// TODO: to options object?
	/*public function get_width() {
		$width = absint( $this->get_option( 'width' ) );

		if ( ! $width ) {
			$width = $this->get_default_with();
		}

		return $width > 0 ? $width : false;
	}*/

	/**
	 * @since NEWVERSION
	 * @return string px or %
	 */
	/*public function get_width_unit() {
		$width_unit = $this->get_option( 'width_unit' );

		if ( ! $width_unit ) {
			$width_unit = $this->get_default_with_unit();
		}

		return 'px' === $width_unit ? 'px' : '%';
	}*/

	/**
	 * Get default with unit
	 *
	 * @return string
	 */
	/*public function get_default_with_unit() {
		return '%';
	}*/

	/**
	 * Get default with unit
	 *
	 * @return string
	 */
	/*public function get_default_with() {
		return false;
	}*/

	/**
	 * @since 2.0
	 */
	// TODO: remove
	/*public function get_label() {
		return $this->get_option( 'label' );
	}*/

	/**
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 * @return string Label of column's type
	 */
	//public function get_type_label() {
	//	return $this->get_property( 'label' );
	//}

	/**
	 * @since NEWVERSION
	 * @return string Post type
	 */
	/*public function set_post_type( $post_type ) {
		return $this->set_property( 'post_type', $post_type );
	}*/

	/**
	 * @since NEWVERSION
	 * @return string Taxonomy
	 */
	/*public function get_taxonomy() {
		return $this->get_property( 'taxonomy' );
	}*/

	/**
	 * @since NEWVERSION
	 * @return string Taxonomy
	 */
	/*public function set_taxonomy( $taxonomy ) {
		return $this->set_property( 'taxonomy', $taxonomy );
	}*/

	/**
	 * Get a single column option
	 *
	 * @since 2.4.8
	 * @return false|array Column options set by user
	 */
	// TODO: remove
	/*public function get_property( $name ) {
		return isset( $this->get_properties()->{$name} ) ? $this->get_properties()->{$name} : false;
	}*/

	/**
	 * @since NEWVERSION
	 *
	 * @param string $option
	 * @param string $value
	 *
	 * @return $this AC_Column
	 */
	// TODO: remove
	/*public function set_property( $property, $value ) {
		$this->properties->{$property} = $value;

		return $this;
	}*/

	/**
	 * Checks column type
	 *
	 * @since 2.3.4
	 *
	 * @param string $type Column type. Also work without the 'column-' prefix. Example 'column-meta' or 'meta'.
	 *
	 * @return bool Matches column type
	 */
	// TODO: remove
	/*public function is_type( $type ) {
		return ( $type === $this->get_type() ) || ( 'column-' . $type === $this->get_type() );
	}*/

	/**
	 * @since 2.0
	 * @var array $properties describes the fixed properties for the AC_Column object.
	 */
	// TODO: remove
	//public $properties;

	/**
	 * @since 2.0
	 */
	//public function __construct() {
	//$this->init();
	//$this->after_setup();
	//}

	/**
	 * @since 2.2
	 */
	/*public function init() {
		$this->properties = array(
			'name'             => null,    // (string) Unique name, also it's identifier
			'clone'            => null,    // (int) Unique clone ID
			'type'             => null,    // (string) Unique type
			'label'            => null,    // (string) Label which describes this column.
			'hide_label'       => false,   // (string) Should the Label be hidden?
			'original'         => false,   // (bool) When a default column has been replaced by custom column we mark it as 'original'
			'use_before_after' => false,   // (bool) Should the column use before and after fields
			'group'            => __( 'Custom', 'codepress-admin-columns' ), // (string) Group name
			'post_type'        => false,   // (string) Post type (e.g. post, page etc.)
			'taxonomy'         => false,   // (string) Taxonomy (e.g. category, post_tag etc.)
		);
	}*/

	/**
	 * After Setup
	 *
	 */
	/*public function after_setup() {
		$this->properties = (object) $this->properties;
	}*/

	/**
	 * @since NEWVERSION
	 * @return mixed
	 */
	/*public function __get( $name ) {
		if ( in_array( $name, array( 'format', 'field_settings', 'helper' ) ) ) {
			return call_user_func( array( $this, $name ) );
		}
	}*/

}