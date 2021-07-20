<?php

namespace AC;

use AC\Storage;
use LogicException;

class Transient implements Expirable {

	/**
	 * @var Storage\Option
	 */
	protected $option;

	/**
	 * @var Storage\Timestamp
	 */
	protected $timestamp;

	public function __construct( $key, $network_only = false ) {
		$this->option = new Storage\Option( $key );
		$this->timestamp = new Storage\Timestamp(
			( new Storage\OptionFactory() )->create( $key . '_timestamp', $network_only )
		);
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
	 * @return bool
	 */
	public function has_expiration_time() {
		return false !== $this->timestamp->get();
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
	 * @param int   $expiration Time until expiration in seconds.
	 *
	 * @return bool
	 * @throws LogicException
	 */
	public function save( $data, $expiration ) {
		// Always store timestamp before option data.
		$this->timestamp->save( time() + (int) $expiration );

		return $this->option->save( $data );
	}

}