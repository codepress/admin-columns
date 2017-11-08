<?php

class AC_Container {

	/**
	 * @var object[]
	 */
	protected $services = array();

	/**
	 * @param string $id
	 *
	 * @return false|object
	 */
	public function get( $id ) {
		return $this->has( $id ) ? $this->services[ $id ] : false;
	}

	/**
	 * @param string $id
	 * @param object $service
	 *
	 * @return $this
	 */
	public function set( $id, $service ) {
		if ( ! is_object( $service ) ) {
			throw new InvalidArgumentException( sprintf( 'The %s service is not an object.', $id ) );
		}

		$this->services[ $id ] = $service;

		return $this;
	}

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has( $id ) {
		return array_key_exists( $id, $this->services );
	}

	/**
	 * @return string[]
	 */
	public function get_ids() {
		return array_keys( $this->services );
	}

}