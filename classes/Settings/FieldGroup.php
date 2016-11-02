<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_FieldGroup {

	private $label;
	private $description;

	/**
	 * @var array
	 */
	private $fields;

	public function __construct( $label, $description, $field_types ) {
		$this->label = $label;
		$this->description = $description;
		$this->fields = $field_types;
	}

	public function get_label() {
		return $this->label;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_fields() {
		return $this->fields;
	}

}