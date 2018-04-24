<?php

namespace AC;

class View {

	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @var string
	 */
	private $template;

	public function __construct( array $data = array() ) {
		$this->set_data( $data );
	}

	public function get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return null;
		}

		return $this->data[ $key ];
	}

	public function __get( $key ) {
		return $this->get( $key );
	}

	public function __set( $key, $value ) {
		return $this->set( $key, $value );
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 */
	public function set( $key, $value ) {
		$this->data[ $key ] = $value;

		return $this;
	}

	public function get_data() {
		return $this->data;
	}

	public function set_data( array $data ) {
		foreach ( $data as $key => $value ) {
			$this->set( $key, $value );
		}

		return $this;
	}

	/**
	 * Will try to resolve the current template to a file
	 *
	 * @return false|string
	 */
	private function resolve_template() {

		/**
		 * Returns the available template paths for column settings
		 *
		 * @param array $paths Template paths
		 * @param string $template Current template path
		 */
		$paths = apply_filters( 'ac/view/templates', array( AC()->get_dir() . 'templates' ), $this->template );

		foreach ( $paths as $path ) {
			$file = $path . '/' . $this->template . '.php';

			if ( is_readable( $file ) ) {
				include $file;

				return true;
			}
		}

		return false;
	}

	/**
	 * Get a string representation of this object
	 *
	 * @return string
	 */
	public function render() {
		ob_start();

		$this->resolve_template();

		return ob_get_clean();
	}

	/**
	 * @return string
	 */
	public function get_template() {
		return $this->template;
	}

	/**
	 * @param string $template
	 *
	 * @return $this
	 */
	public function set_template( $template ) {
		$this->template = $template;

		return $this;
	}

	/**
	 * Should call self::render when treated as a string
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}

}