<?php

class AC_Settings_Column {

	/**
	 * @var AC_Settings_FieldAbstract[]
	 */
	private $fields;

	/**
	 * @var AC_Column
	 */
	public $column;

	/**
	 * @var array
	 */
	private $options;

	/**
	 * AC_Settings_Column constructor.
	 *
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		$this->column = $column;
	}

	/**
	 * @param AC_Settings_FieldAbstract $field
	 */
	public function add_field( AC_Settings_FieldAbstract $field ) {

		// TODO: remove column reference from field
		//$field->set_column( $this->column );

		// TODO: maybe add settings reference to field. Use settings to display field value in column.

		$field->set_settings( $this );

		$this->fields[ $field->get_type() ] = $field;

		return $this;
	}

	/**
	 * @param string $type
	 *
	 * @return AC_Settings_FieldAbstract|false
	 */
	public function get_field( $type ) {
		return isset( $this->fields[ $type ] ) ? $this->fields[ $type ] : false;
	}

	/**
	 * @return AC_Settings_FieldAbstract[]
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Display HTML column settings
	 */
	public function display() {
		foreach ( (array) $this->fields as $field ) {
			$field->display();
		}
	}

	/**
	 * @param array $options
	 */
	public function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @param array $options
	 *
	 * @return $this
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
		return isset( $this->options[ $name ] ) ? $this->options[ $name ] : null;
	}

	/**
	 * @param string $field_type (e.g. label, width, type, before_after )
	 *
	 * @return bool
	 */
	public function get_value( $field_type ) {
		$field = $this->get_field( $field_type );

		return $field ? $field->get_value() : false;
	}

}