<?php

class AC_Settings_Form_Part
	implements AC_Settings_ViewInterface {

	/**
	 * @var AC_Settings_ViewAbstract
	 */
	private $view;

	/**
	 * Access other instances of AC_Settings_Form_Part
	 *
	 * @param $key
	 *
	 * @return false|AC_Settings_Form_Part
	 */
	public function __get( $key ) {
		if ( ! isset( $this->$key ) || ! ( $this->$key instanceof AC_Settings_Form_Part ) ) {
			return false;
		}

		return $this->$key;
	}

	/**
	 * @param AC_Settings_ViewAbstract $view
	 *
	 * @return $this
	 */
	public function set_view( $view ) {
		$this->view = $view;

		return $this;
	}

	/**
	 * @return AC_Settings_ViewAbstract
	 */
	public function get_view() {
		return $this->view;
	}

	/**
	 * Render this form part
	 *
	 * @return false|string
	 */
	public function render() {
		if ( ! ( $this->view instanceof AC_Settings_ViewAbstract ) ) {
			return false;
		}

		return $this->view->render();
	}

	public function __toString() {
		return $this->render();
	}

}