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
	public function add_field( AC_Settings_FieldAbstract $field, $group = false ) {

		// TODO add by array (field args)
		// TODO add by field_type (before_after) convert to classname

		$this->fields[ $field->get_type() ] = $field->set_column( $this->column );

		if ( $group ) {
			$field->add_group( $group );
		}

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

		/*foreach( $this->fields as $field ) {

		}

		foreach ( $this->groups as $group ) {

			$html = '';
			foreach ( $group->get_fields() as $field_type ) {
				$field = $this->get_field( $field_type );

				ob_start();
				$field->display();

				$html .= ob_get_clean();
			}

			$this->display_group( $html );

			$custom = new AC_Settings_Field_Custom();
			$custom->set_column( $this->column );

			$custom->field( array(
				'label'       => $group->get_label(),
				'description' => $group->get_description(),
				'type'        => 'html',
				'html'        => $html,
			) );
		}*/

		foreach ( (array) $this->fields as $field ) {
			$field->display();
		}

		/*foreach ( $this->groups as $group ) {
			$group->display();
		}*/

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