<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 *
 * @property AC_ColumnFieldSettings field_settings
 * @property AC_ColumnFieldFormat format
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
	 * @var string Post type
	 */
	private $post_type;

	/**
	 * @var string Taxonomy
	 */
	private $taxonomy;


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
	 * @var array $options Contains the user set options for the AC_Column object.
	 */
	// TODO: should be an in it's own object
	private $options;

	// (string) Should the Label be hidden?
	// TODO: should got into field settings
	protected $hide_label;

	// (bool) Should the column use before and after fields
	// TODO: should got into field settings
	protected $use_before_after;

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
	 * Get the type of the column.
	 *
	 * @since 2.4.9
	 * @return string Label of column's type
	 */
	public function get_label() {
		return $this->label;
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
		return $this->post_type;
	}

	/**
	 * @param string $post_type Post type
	 * @return $this
	 */
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;

		return $this;
	}

	/**
	 * @return string Taxonomy
	 */
	public function get_taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @param string $taxonomy Taxonomy
	 * @return $this
	 */
	public function set_taxonomy( $taxonomy ) {
		$this->taxonomy = $taxonomy;

		return $this;
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
	// TODO: used to be is_valid(), replace inside all columns (including add-ons)
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
	 * TODO: refactor. remove.
	 *
	 */

	/**
	 * Hide the label field in the column settings
	 *
	 * @return bool
	 */
	// TODO: should go into field settings
	public function is_hide_label() {
		return $this->hide_label;
	}

	/**
	 * @param $bool
	 *
	 * @return AC_Column
	 */
	// TODO: should go into field settings
	public function set_hide_label( $boolean ) {

		// TODO: this was called $this->properties->hide_label. Make sure to replace all.
		$this->hide_label = $boolean;

		return $this;
	}

	// TODO: should go into field settings
	public function use_before_after() {

		// TODO: this was called $this->properties->use_before_after. Make sure to replace all.
		return apply_filters( 'cac/column/properties/use_before_after', $this->use_before_after, $this );
	}

	// TODO: should go into field settings
	public function set_use_before_after( $boolean ) {
		$this->use_before_after = $boolean;

		return $this;
	}


	// TODO: move all options into it's own class

	/**
	 * Get the stored column options
	 *
	 * @since 2.3.4
	 * @return array Column options set by user
	 */
	// TODO: should be it's own class
	public function get_options() {
		return $this->options;
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
	// TODO: move
	public function sanitize_options( $options ) {
		return $options;
	}

	/**
	 * @param array $options
	 *
	 * @return AC_Column
	 */
	// TODO: $options should be an instance
	public function set_options( $options ) {
		$this->options = $options;

		return $this;
	}

	/**
	 * @param array $options
	 *
	 * @return AC_Column
	 */
	public function set_option( $key, $value ) {
		$this->options[ $key ] = $value;

		return $this;
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
	 * @return int Width
	 */
	// TODO: to options object?
	public function get_width() {
		$width = absint( $this->get_option( 'width' ) );

		if ( ! $width ) {
			$width = $this->get_default_with();
		}

		return $width > 0 ? $width : false;
	}

	/**
	 * @since NEWVERSION
	 * @return string px or %
	 */
	public function get_width_unit() {
		$width_unit = $this->get_option( 'width_unit' );

		if ( ! $width_unit ) {
			$width_unit = $this->get_default_with_unit();
		}

		return 'px' === $width_unit ? 'px' : '%';
	}

	/**
	 * Get default with unit
	 *
	 * @return string
	 */
	public function get_default_with_unit() {
		return '%';
	}

	/**
	 * Get default with unit
	 *
	 * @return string
	 */
	public function get_default_with() {
		return false;
	}

	/**
	 * @since 2.0
	 */
	public function display_settings() {
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