<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Settings_Form_View_Select {

	/**
	 * @var AC_Settings_Form_Element_Select
	 */
	protected $select;

	/**
	 * @var string
	 */
	protected $no_result;

	public function __construct( AC_Settings_Form_Element_Select $select ) {
		$this->select = $select;
	}

	public function render() {
		$options = $this->select->get_options();
		$output = $this->get_no_result();

		if ( ! empty( $options ) ) {
			$output = $this->select->render();

			// AJAX message
			$output .= '<div class="msg"></div>';
		}

		return $output;
	}

	/**
	 * @return string
	 */
	public function get_no_result() {
		return $this->no_result;
	}

	/**
	 * @param string $no_result
	 *
	 * @return AC_Settings_Form_View_Select
	 */
	public function set_no_result( $no_result ) {
		$this->no_result = $no_result;

		return $this;
	}

}