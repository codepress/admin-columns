<?php

namespace AC\Ajax;

use AC\Registrable;
use LogicException;

class Handler implements Registrable {

	const NONCE_ACTION = 'ac-ajax';

	/**
	 * @var array
	 */
	protected $params;

	/**
	 * @var string|array
	 */
	protected $callback;

	/**
	 * @var bool
	 */
	protected $wp_ajax;

	/**
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * @param bool|null $wp_ajax Using the WP Ajax endpoint or custom
	 */
	public function __construct( $wp_ajax = null ) {
		$this->wp_ajax = $wp_ajax === null;

		$this->set_nonce();
	}

	public function register() {
		if ( ! $this->get_action() ) {
			throw new LogicException( 'Action parameter is missing.' );
		}

		if ( ! $this->get_callback() ) {
			throw new LogicException( 'Callback is missing.' );
		}

		add_action( $this->get_action(), $this->get_callback(), $this->priority );
	}

	public function deregister() {
		remove_action( $this->get_action(), $this->get_callback(), $this->priority );
	}

	/**
	 * @return string|null
	 */
	public function get_action() {
		$action = $this->get_param( 'action' );

		if ( $this->wp_ajax ) {
			$action = 'wp_ajax_' . $action;
		}

		return $action;
	}

	/**
	 * @param string $action
	 *
	 * @return $this
	 */
	public function set_action( $action ) {
		$this->params['action'] = $action;

		return $this;
	}

	/**
	 * @param int $priority
	 *
	 * @return Handler
	 */
	public function set_priority( $priority ) {
		if ( ! is_int( $priority ) ) {
			throw new LogicException( 'Priority can only be of type integer.' );
		}

		$this->priority = $priority;

		return $this;
	}

	/**
	 * @return int
	 */
	public function get_priority() {
		return $this->priority;
	}

	/**
	 * @param string|array $callback
	 *
	 * @return $this
	 */
	public function set_callback( $callback ) {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * @return array|string
	 */
	public function get_callback() {
		return $this->callback;
	}

	/**
	 * @param null|string $nonce
	 *
	 * @return $this
	 */
	public function set_nonce( $nonce = null ) {
		if ( null === $nonce ) {
			$nonce = wp_create_nonce( self::NONCE_ACTION );
		}

		$this->params['_ajax_nonce'] = $nonce;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function unset_nonce() {
		unset( $this->params['_ajax_nonce'] );

		return $this;
	}

	/**
	 * @param string $action
	 */
	public function verify_request( $action = null ) {
		if ( null === $action ) {
			$action = self::NONCE_ACTION;
		}

		check_ajax_referer( $action );
	}

	/**
	 * @return array
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
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
		foreach ( $params as $key => $value ) {
			$this->set_param( $key, $value );
		}

		return $this;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function set_param( $key, $value ) {
		switch ( $key ) {
			case 'action':
				$this->set_action( $value );

				break;
			case 'nonce':
				$this->set_nonce( $value );

				break;
			default:
				$this->params[ $key ] = $value;
		}

		return $this;
	}

}