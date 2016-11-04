<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	 * @var AC_Settings_FieldGroup[] $groups
	 */
	private $groups = array();

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
		$field->set_column( $this->column );

		$this->fields[ $field->get_type() ] = $field;

		return $this;
	}

	/**
	 * @param $label
	 *
	 * @return $this
	 */
	public function add_group( $label ) {
		$this->groups[ $label ] = new AC_Settings_FieldGroup( $label, 'description' );

		return $this;
	}

	public function get_group( $group ) {
		return isset( $this->groups[ $group ] ) ? $this->groups[ $group ] : false;
	}

	/**
	 * @param string $type
	 *
	 * @return AC_Settings_FieldAbstract
	 */
	public function get_field( $type ) {
		return $this->fields[ $type ];
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

	/*private function get_groups() {
		return $this->groups;
	}*/

	/**
	 * @param string $label
	 * @param string $description
	 * @param array $field_types
	 *
	 * @return AC_Settings_FieldGroup[]
	 */
	public function define_group( $label, $description, $field_types ) {
		$this->groups[] = new AC_Settings_FieldGroup( $label, $description, $field_types );
	}

}