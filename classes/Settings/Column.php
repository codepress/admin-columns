<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Column {

	/**
	 * @var AC_Settings_ColumnField[]
	 */
	private $fields;

	/**
	 * @param AC_Settings_ColumnField $field
	 */
	public function add_field( AC_Settings_ColumnField $field ) {
		$this->fields[ $field->get_type() ] = $field;
	}

	/**
	 * @param string $type
	 *
	 * @return AC_Settings_ColumnField
	 */
	public function get_field( $type ) {
		return $this->fields[ $type ];
	}

	/**
	 * @return AC_Settings_ColumnField[]
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Display HTML column settings
	 */
	public function display() {
		foreach ( $this->fields as $field ) {
			$field->display();
		}
	}

}