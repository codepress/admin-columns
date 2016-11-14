<?php

abstract class AC_Settings_ViewAbstract
	implements AC_Settings_ViewInterface {

	private $data;

	public function __construct( array $data = array() ) {
		$this->data = $data;
	}

	/**
	 * return string
	 */
	public abstract function template();

	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	public function __get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return false;
		}

		return $this->data[ $key ];
	}

	public function set_var( $key, $value ) {
		$this->vars[ $key ] = $value;

		return $this;
	}

	public function set_vars( array $vars ) {
		foreach ( $vars as $key => $value ) {
			$this->set_var( $key, $value );
		}

		return $this;
	}

	public function render() {
		ob_start();

		$this->template();

		return ob_get_clean();
	}

	public function __toString() {
		return $this->render();
	}

}