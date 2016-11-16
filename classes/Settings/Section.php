<?php

class AC_Settings_Section {

	/**
	 * @var AC_Settings_Section[]
	 */
	protected $sections;

	/**
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	protected $elements;

	/**
	 * @var AC_Column
	 */
	private $column;

	/**
	 * @var array
	 */
	private $views = array();

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $read_more;

	/**
	 * @var $string
	 */
	private $description;

	/**
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		$this->sections = array();
		$this->column = $column;
		$this->views = array(
			'field'   => new AC_Settings_View_Field(),
			'label'   => new AC_Settings_View_Label(),
			'section' => new AC_Settings_View_Section(),
		);
	}

	public function render() {

		$label = $this->get_view( 'label' );
		$label->set( 'label', $this->label )
		      ->set( 'description', $this->description )
		      ->set( 'read_more', $this->read_more );

		$element = $this->get_first_element();

		if ( $element ) {
			$label->set( 'for', $element->render_id() );
		}

		$field = $this->get_view( 'field' );
		$field->set( 'elements', $this->elements );

		$section = $this->get_view( 'section' );
		$section->set( 'label', $label );
		$section->set( 'field', $field );

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

	public function add_section( AC_Settings_Section $section ) {
		$this->sections[] = $section;

		return $this;
	}

	public function get_sections() {
		return $this->sections;
	}

	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		$element->set_column( $this->column );

		$this->elements[] = $element;

		return $this;
	}

	/**
	 * Return the first element from this field, also looks in subfields
	 *
	 * @return AC_Settings_Form_ElementAbstract|false
	 */
	private function get_first_element() {
		if ( $this->elements ) {
			return $this->elements[0];
		}

		if ( $this->sections ) {
			return $this->sections[0]->get_first_element();
		}

		return false;
	}

	/**
	 * @param string $key
	 *
	 * @return AC_Settings_ViewAbstract|false
	 */
	private function get_view( $key ) {
		if ( ! isset( $this->views[ $key ] ) ) {
			return false;
		}

		return $this->views[ $key ];
	}

	/**
	 * @param string $key
	 * @param AC_Settings_ViewAbstract $view
	 */
	public function set_view( $key, AC_Settings_ViewAbstract $view ) {
		$this->views[ $key ] = $view;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_read_more() {
		return $this->read_more;
	}

	/**
	 * @param string $read_more
	 *
	 * @return $this
	 */
	public function set_read_more( $read_more ) {
		$this->read_more = $read_more;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

}