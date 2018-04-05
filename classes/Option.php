<?php

class AC_Option {

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @param string $key
	 */
	public function __construct( $key ) {
		$this->set_key( $key );
	}

	/**
	 * @param string $key
	 */
	protected function set_key( $key ) {
		$this->key = $key;
	}

	/**
	 * @return mixed
	 */
	public function get() {
		return get_option( $this->key );
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function save( $value ) {
		return update_option( $this->key, $value, false );
	}

	/**
	 * @return bool
	 */
	public function delete() {
		return delete_option( $this->key );
	}

}