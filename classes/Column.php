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
	 * @var AC_Settings_Setting[]
	 */
	private $settings;

	/**
	 * @var AC_ListScreen
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
	 * @return AC_ListScreen
	 */
	public function get_list_screen() {
		return $this->list_screen;
	}

	/**
	 * @param AC_ListScreen $list_screen
	 *
	 * @return $this
	 */
	public function set_list_screen( AC_ListScreen $list_screen ) {
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
		return $this->group ? $this->group : __( 'Custom', 'codepress-admin-columns' );
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
	 * @param AC_Settings_Setting $setting
	 *
	 * @return $this
	 */
	public function add_setting( AC_Settings_Setting $setting ) {

		$this->settings[ $setting->get_name() ] = $setting;

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return AC_Settings_Setting|AC_Settings_FormatInterface|null
	 */
	public function get_setting( $id ) {
		return $this->get_settings()->get( $id );
	}

	/**
	 * @return AC_Settings_Collection
	 */
	public function get_settings() {
		if ( null === $this->settings ) {
			$this->add_setting( new AC_Settings_Setting_Type( $this ) )
			     ->add_setting( new AC_Settings_Setting_Label( $this ) )
			     ->add_setting( new AC_Settings_Setting_Width( $this ) );

			$this->register_settings();

			do_action( 'ac/column/settings', $this );

			$this->set_options( $this->get_list_screen()->settings()->get_setting( $this->get_name() ) );
		}

		return new AC_Settings_Collection( $this->settings );
	}

	/**
	 * Register settings
	 */
	protected function register_settings() {
		// Overwrite in child class
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

	/**
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( array $options ) {
		foreach ( $this->get_settings() as $setting ) {
			$setting->set_options( $options );
		}

		return $this;
	}

	/**
	 * Get the current options
	 *
	 * @return array
	 */
	public function get_options() {
		$options = array();

		foreach ( $this->get_settings() as $setting ) {
			$options += $setting->get_values();
		}

		return $options;
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

}