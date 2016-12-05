<?php

class AC_Collection {

	/**
	 * @var array
	 */
	private $items;

	public function __construct( array $items = array() ) {
		$this->items = $items;
	}

	public function all() {
		return $this->items;
	}

	public function has( $key ) {
		return isset( $this->items[ $key ] );
	}

	public function put( $key, $value ) {
		$this->items[ $key ] = $value;

		return $this;
	}

	public function get( $key, $default = null ) {
		if ( $this->has( $key ) ) {
			return $this->items[ $key ];
		}

		return $default;
	}

	public function __get( $key ) {
		return $this->get( $key );
	}

}