<?php

final class AC_Settings_Form_Group extends AC_Settings_Form_Part {

	/**
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	private $elements;

	public function __construct() {
		$this->elements = array();
		$this->set_view( new AC_Settings_View_Group() );
	}

	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		$this->elements[] = $element;

		return $this;
	}

	public function get_elements() {
		return $this->elements;
	}

	public function get_first_element() {
		if ( ! $this->elements() ) {
			return false;
		}

		return $this->elements[0];
	}

	public function render() {
		$view = $this->get_view();
		$view->elements = $this->elements;

		// assign element names as variables
		foreach ( $this->elements as $element ) {
			$name = $element->get_name();

			// PHP official variable regex
			if ( preg_match( '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $name ) ) {
				$view->$name = $element;
			}
		}

		return $view->render();
	}

}