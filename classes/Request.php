<?php

namespace AC;

class Request {

	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var array
	 */
	protected $query;

	/**
	 * @var array
	 */
	protected $request;

	/**
	 * @var Middleware[]
	 */
	protected $middleware;

	public function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->query = filter_input_array( INPUT_GET );
		$this->request = filter_input_array( INPUT_POST );
	}

	/**
	 * @param Middleware $middleware
	 */
	public function add_middleware( Middleware $middleware ) {
		$this->middleware[] = $middleware;

		$middleware->handle( $this );
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
	 * @return string
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Wrapper that defaults to request method accessor
	 *
	 * @param string|null $key
	 * @param int         $filter
	 * @param array|null  $options
	 *
	 * @return mixed
	 */
	public function get( $key = null, $filter = FILTER_DEFAULT, $options = null ) {
		if ( $this->method === self::METHOD_POST ) {
			return $this->get_request( $key, $filter, $options );
		}

		return $this->get_query( $key, $filter, $options );
	}

	/**
	 * Merge input with current request method
	 *
	 * @param array $input
	 */
	public function merge( array $input ) {
		$target = 'query';

		if ( $this->method === self::METHOD_POST ) {
			$target = 'request';
		}

		$this->$target = array_merge( $this->$target, $input );
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

		if ( ! isset( $this->request[ $key ] ) ) {
			return false;
		}

		return filter_var( $this->request[ $key ], $filter, $options );
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
			return $this->query;
		}

		if ( ! isset( $this->query[ $key ] ) ) {
			return false;
		}

		return filter_var( $this->query[ $key ], $filter, $options );
	}

}