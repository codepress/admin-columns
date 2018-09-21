<?php

namespace AC;

class Request {

	/**
	 * @var array
	 */
	protected $query;

	/**
	 * @var array
	 */
	protected $request;

	public function __construct() {
		$this->query = $_GET;
		$this->request = $_POST;
	}

	/**
	 * @return bool
	 */
	public function is_request() {
		return ! empty( $this->request );
	}

	/**
	 * @return bool
	 */
	public function is_query() {
		return ! empty( $this->query );
	}

	/**
	 * @param string|null $key
	 * @param mixed       $default
	 *
	 * @return mixed
	 */
	public function get_request( $key = null, $default = null ) {
		if ( null === $key ) {
			return $this->request;
		}

		if ( ! isset( $this->request[ $key ] ) ) {
			return $default;
		}

		return $this->request[ $key ];
	}

	/**
	 * @param string|null $key
	 * @param mixed       $default
	 *
	 * @return mixed
	 */
	public function get_query( $key = null, $default = null ) {
		if ( null === $key ) {
			return $this->query;
		}

		if ( ! isset( $this->query[ $key ] ) ) {
			return $default;
		}

		return $this->query[ $key ];
	}

}