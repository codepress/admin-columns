<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Input extends AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	public function render_field() {
		if ( ! $this->get_first_element() ) {
			return;
		}

		return $this->get_first_element()->render();
	}

}