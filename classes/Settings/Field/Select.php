<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Field_Select extends AC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var string
	 */
	protected $no_result;

	public function render_field() {
		$select = $this->get_first_element();

		if ( ! $select ) {
			return;
		}

		$options = $select->get_options();
		$output = $this->get_no_result();

		if ( ! empty( $options ) ) {
			$output = $select->render();

			// AJAX message
			$output .= '<div class="msg"></div>';
		}

		return $output;
	}

	/**
	 * @param AC_Settings_Form_ElementAbstract $element
	 *
	 * @return $this
	 */
	public function add_element( AC_Settings_Form_ElementAbstract $element ) {
		// todo: add some form of notification when instance is not select

		return parent::add_element( $element );
	}

	/**
	 * @return string
	 */
	public function get_no_result() {
		if ( empty( $this->no_result ) ) {
			return false;
		}

		return $this->no_result;
	}

	/**
	 * @param string $no_result
	 *
	 * @return AC_Settings_View_Select
	 */
	public function set_no_result( $no_result ) {
		$this->no_result = $no_result;

		return $this;
	}

}