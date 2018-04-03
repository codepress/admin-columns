<?php

class AC_Ajax_Handler {

	/**
	 * @var array
	 */
	protected $params;

	/**
	 * @param string $action
	 */
	public function __construct( $action ) {
		$this->set_action( $action );
	}

	public function register( callable $callback, $priority = 10 ) {
		if ( ! is_int( $priority ) ) {
			throw new InvalidArgumentException( 'Argument priority needs to be an integer.' );
		}

		add_action( $this->get_action(), $callback, $priority );
	}

	public function get_action() {
		return $this->get_param( 'action' );
	}

	/**
	 * @param string $action
	 */
	protected function set_action( $action ) {
		$prefix = 'wp_ajax_';

		if ( strpos( $action, $prefix ) !== 0 ) {
			$action = $prefix . $action;
		}

		$this->set_param( 'action', $action );
	}

	/**
	 * @return $this
	 */
	public function set_nonce() {
		$this->set_param( 'nonce', wp_create_nonce( 'ac-ajax' ) );

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_params() {
		return $this->params;
	}

	public function get_param( $key ) {
		if ( ! array_key_exists( $key, $this->params ) ) {
			return null;
		}

		return $this->params[ $key ];
	}

	/**
	 * @param array $params
	 *
	 * @return $this
	 */
	public function set_params( array $params ) {
		$this->params = $params;

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function set_param( $key, $value ) {
		$this->params[ $key ] = $value;

		return $this;
	}

}