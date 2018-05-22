<?php

namespace AC;

class Transient
	implements Expirable {

	/**
	 * @var Option
	 */
	protected $option;

	/**
	 * @var Option\Timestamp
	 */
	protected $timestamp;

	public function __construct( $key ) {
		$this->option = new Option( $key );
		$this->timestamp = new Option\Timestamp( $key . '_timestamp' );
	}

	/**
	 * @param int|null $value
	 *
	 * @return bool
	 */
	public function is_expired( $value = null ) {
		return $this->timestamp->is_expired( $value );
	}

	/**
	 * @return mixed
	 */
	public function get() {
		return $this->option->get();
	}

	public function delete() {
		$this->option->delete();
		$this->timestamp->delete();
	}

	/**
	 * @param mixed $data
	 * @param int   $expiration Time until expiration in seconds. Default 0 (no expiration).
	 */
	public function save( $data, $expiration ) {
		return $this->option->save( $data ) && $this->timestamp->save( time() + intval( $expiration ) );
	}

}