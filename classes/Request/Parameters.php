<?php

namespace AC\Request;

final class Parameters {

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @param array $parameters
	 */
	public function __construct( array $parameters ) {
		$this->parameters = $parameters;
	}

	/**
	 * @return array
	 */
	public function all() {
		return $this->parameters;
	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return array_key_exists( $key, $this->parameters )
			? $this->parameters[ $key ]
			: $default;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set( $key, $value ) {
		$this->parameters[ $key ] = $value;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return array_key_exists( $key, $this->parameters );
	}

	/**
	 * @param string $key
	 */
	public function remove( $key ) {
		unset( $this->parameters[ $key ] );
	}

	/**
	 * @param array $input
	 */
	public function merge( array $input ) {
		$this->parameters = array_merge( $this->parameters, $input );
	}

	/**
	 * Wrapper account filter_var
	 *
	 * @param string    $key
	 * @param null      $default
	 * @param int       $filter
	 * @param array|int $options
	 *
	 * @return mixed
	 */
	public function filter( $key, $default = null, $filter = FILTER_DEFAULT, $options = 0 ) {
		$value = $this->get( $key, $default );

		return filter_var( $value, $filter, $options );
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->parameters );
	}

}