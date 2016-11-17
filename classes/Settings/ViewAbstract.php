<?php

abstract class AC_Settings_ViewAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @var AC_Settings_ViewAbstract[]
	 */
	private $views = array();

	public function __construct( array $data = array() ) {
		$this->set_data( $data );
	}

	/**
	 * Add a view and load it as a variable, but store it for later retrieval
	 *
	 * @param AC_Settings_ViewAbstract $view
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_view( AC_Settings_ViewAbstract $view, $name ) {
		$this->views[ $name ] = $view;

		$this->set( $name, $view );

		return $this;
	}

	public function get_view( $name ) {
		return isset( $this->views[ $name ] ) ? $this->views[ $name ] : false;
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
		$this->data[ $key ] = $value;

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