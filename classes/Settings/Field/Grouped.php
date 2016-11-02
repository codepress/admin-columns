<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Grouped extends AC_Settings_FieldAbstract {

	private $fields;

	// Only for grouped
	private $label;
	private $description;

	public function __construct( $type ) {
		$this->set_type( $type);
	}

	public function display() {

		/*if ( $this->label ) {
			$this->fields( array(
				'label'       => $this->label,
				'description' => $this->description,
				'fields'      => $this->fields,
			) );
		}*/

	}

	/**
	 * @param array $fields
	 */
	public function add_field( AC_Settings_FieldAbstract $field ) {
		$this->fields[] = $field;
	}

	public function use_grouped_fields( $label, $description ) {
		$this->label = $label;
		$this->description = $description;
	}

}