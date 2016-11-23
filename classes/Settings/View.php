<?php

class AC_Settings_View
	implements AC_Settings_ViewInterface {

	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @var AC_Settings_ViewAbstract[]
	 */
	private $views = array();

	/**
	 * @var string
	 */
	private $template = 'default';

	public function __construct( array $data = array() ) {
		$this->set_data( $data );
	}

	/**
	 * Add a view and load it as a variable, but store it for later retrieval
	 *
	 * @param AC_Settings_View $view
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_view( AC_Settings_View $view, $name ) {
		$this->views[ $name ] = $view;

		$this->set( $name, $view );

		return $this;
	}

	public function get_view( $name ) {
		return isset( $this->views[ $name ] ) ? $this->views[ $name ] : false;
	}

	public function __get( $key ) {
		if ( ! isset( $this->data[ $key ] ) ) {
			return false;
		}

		return $this->data[ $key ];
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
		$paths = apply_filters( 'ac/settings/view/template_path', array( dirname( __FILE__ ) . '/templates' ), $this->template );

		foreach ( $paths as $path ) {
			$file = $path . '/' . $this->template . '.php';

			if ( is_readable( $file ) ) {
				return $file;
			}
		}

		return false;
	}

	public function render() {
		ob_start();

		if ( $template = $this->resolve_template() ) {
			include $template;
		}

		return ob_get_clean();
	}

	public function __toString() {
		return $this->render();
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

}