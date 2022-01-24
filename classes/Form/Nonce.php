<?php

namespace AC\Form;

use AC\Request;

class Nonce {

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @var string
	 */
	private $name;

	public function __construct( $action, $name ) {
		$this->action = (string) $action;
		$this->name = (string) $name;
	}

	/**
	 * @return string
	 */
	public function get_action() {
		return $this->action;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string|null
	 */
	public function create() {
		return wp_create_nonce( $this->action ) ?: null;
	}

	/**
	 * @return string
	 */
	public function create_field() {
		return wp_nonce_field( $this->action, $this->name, true, false );
	}

	/**
	 * @param string $nonce
	 *
	 * @return bool
	 */
	public function verify_nonce( $nonce ) {
		return (bool) wp_verify_nonce( (string) $nonce, $this->action );
	}

	/**
	 * @param Request $request
	 *
	 * @return bool
	 */
	public function verify( Request $request ) {
		return $this->verify_nonce( $request->get( $this->name ) );
	}

}