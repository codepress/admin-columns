<?php

namespace AC\Storage;

class Option
	implements KeyValuePair {

	/**
	 * @var string
	 */
	protected $key;

	public function __construct( $key ) {
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