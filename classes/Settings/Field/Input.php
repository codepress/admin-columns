<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Input extends AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	public function render_field() {
		$input = $this->get_first_element();

		if ( ! $input ) {
			return;
		}

		return $input->render();
	}

	/**
	 * @param AC_Settings_Form_ElementAbstract $element
	 *
	 * @return $this
	 */
	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		// todo: add some form of notification when instance is not input

		return parent::add_element( $element );
	}

}