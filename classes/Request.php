<?php

namespace AC;

use AC\Request\Parameters;

class Request {

	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var Parameters
	 */
	protected $query;

	/**
	 * @var Parameters
	 */
	protected $request;

	/**
	 * @var Middleware[]
	 */
	protected $middleware;

	public function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->query = new Parameters( (array) filter_input_array( INPUT_GET ) );
		$this->request = new Parameters( (array) filter_input_array( INPUT_POST ) );
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
		return $this->request->count() > 0;
	}

	/**
	 * @return Parameters
	 */
	public function get_query() {
		return $this->query;
	}

	/**
	 * @return bool
	 */
	public function is_query() {
		return $this->request->count() > 0;
	}

	/**
	 * @return Parameters
	 */
	public function get_request() {
		return $this->request;
	}

	/**
	 * @return string
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Return the parameters based on the current method
	 *
	 * @return Parameters
	 */
	public function get_parameters() {
		return $this->get_method() === self::METHOD_POST
			? $this->get_request()
			: $this->get_query();
	}

	/**
	 * @param string $key
	 * @param null   $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		return $this->get_parameters()->get( $key, $default );
	}

	/**
	 * @param string $key
	 * @param null   $default
	 * @param int    $filter
	 * @param null   $options
	 *
	 * @return mixed
	 */
	public function filter( $key, $default = null, $filter = FILTER_DEFAULT, $options = null ) {
		return $this->get_parameters()->filter( $key, $default, $filter, $options );
	}

}