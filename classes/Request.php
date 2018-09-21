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
		$this->query = filter_input_array( INPUT_GET );
		$this->request = filter_input_array( INPUT_POST );
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
	 * Wrapper around filter_input for INPUT_POST
	 *
	 * @param string|null $key
	 * @param int         $filter
	 * @param array|null  $options
	 *
	 * @return mixed
	 */
	public function get_request( $key = null, $filter = FILTER_DEFAULT, $options = null ) {
		if ( null === $key ) {
			return $this->request;
		}

		return filter_input( INPUT_POST, $key, $filter, $options );
	}

	/**
	 * Wrapper around filter_input for INPUT_GET
	 *
	 * @param string|null $key
	 * @param int         $filter
	 * @param array|null  $options
	 *
	 * @return mixed
	 */
	public function get_query( $key = null, $filter = FILTER_DEFAULT, $options = null ) {
		if ( null === $key ) {
			return $this->request;
		}

		return filter_input( INPUT_GET, $key, $filter, $options );
	}

}