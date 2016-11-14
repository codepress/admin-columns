<?php

final class AC_Settings_Form_Field extends AC_Settings_Form_Part {

	/**
	 * @var AC_Settings_Form_Group
	 */
	protected $group;

	/**
	 * @var AC_Settings_Form_Field[]
	 */
	protected $subfields = array();

	/**
	 * @var AC_Settings_Form_Label
	 */
	protected $label;

	/**
	 * @var AC_Column
	 */
	private $column;

	/**
	 * @param AC_Column $column
	 * @param string $label
	 */
	public function __construct( AC_Column $column, $label = null ) {
		$this->group = new AC_Settings_Form_Group();
		$this->subfields = array();
		$this->label = new AC_Settings_Form_Label();
		$this->column = $column;

		$this->set_view( new AC_Settings_View_Field() );
		$this->set_label( $label );
	}

	public function render() {
		$view = $this->get_view();
		$view->label = $this->label;

		if ( $this->group ) {
			$view->group = $this->group->render();
		}

		$subfields = array();

		foreach ( $this->subfields as $subfield ) {
			$subfields[] = $subfield->render();
		}

		$view->subfields = implode( "\n", array_filter( $subfields ) );

		return $view->render();
	}

	public function add_subfield( AC_Settings_Form_Field $field ) {
		$this->subfields[] = $field;

		return $this;
	}

	public function get_subfields() {
		return $this->subfields;
	}

	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		$value = $this->get_setting( $element->get_name() );

		if ( $value ) {
			$element->set_value( $value );
		}

		$element->set_column( $this->column );

		$this->get_group()->add_element( $element );

		return $this;
	}

	/**
	 * Return the first element from this field, also looks in subfields
	 *
	 * @return string|false
	 */
	protected function get_first_element() {
		if ( $this->group ) {
			return $this->group->get_first_element();
		}

		if ( $this->subfields ) {
			return $this->subfields[0];
		}

		return false;
	}

	/**
	 * Retrieve setting (value) for an element
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	protected function get_setting( $name ) {
		return $this->column->settings()->get_option( $name );
	}

	/**
	 * Wrapper
	 *
	 * @param string $text
	 *
	 * @see AC_Settings_Form_Label::set_text()
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label->set_label( $label );

		return $this;
	}

	/**
	 * Wrapper
	 *
	 * @param $description
	 *
	 * @see AC_Settings_Form_Label::set_description()
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->label->set_description( $description );

		return $this;
	}

	/**
	 * Wrapper
	 *
	 * @param $url
	 *
	 * @see AC_Settings_Form_Label::set_read_more()
	 * @return $this
	 */
	public function set_read_more( $url ) {
		$this->label->set_read_more( $url );

		return $this;
	}

	/**
	 * Wrapper
	 *
	 * @return AC_Settings_Form_Group
	 */
	public function get_group() {
		return $this->group;
	}

	/**
	 * @param AC_Settings_Form_Group $group
	 *
	 * @return $this
	 */
	public function set_group( AC_Settings_Form_Group $group ) {
		$this->group = $group;

		return $this;
	}

}