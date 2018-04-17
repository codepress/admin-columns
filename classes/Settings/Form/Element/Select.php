<?php

use AC\Form\Element\Select;

class AC_Settings_Form_Element_Select extends Select {

	protected function render_ajax_message() {
		return '<div class="msg"></div>';
	}

	/**
	 * @return string|false
	 */
	protected function render_description() {
		return parent::render_description() . $this->render_ajax_message();
	}

}