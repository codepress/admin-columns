<?php

abstract class AC_Settings_ViewAbstract
	implements AC_Settings_ViewInterface {

	private $data;

	public function __construct( array $data = array() ) {
		$this->set_data( $data );
	}

	/**
	 * return string
	 */
	public abstract function template();

	public function __set( $key, $value ) {
		$this->set( $key, $value );
	}

	public function __get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return false;
		}

		return $this->data[ $key ];
	}

	public function set( $key, $value ) {
		$this->vars[ $key ] = $value;

		return $this;
	}

	public function set_data( array $data ) {
		foreach ( $data as $key => $value ) {
			$this->set( $key, $value );
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