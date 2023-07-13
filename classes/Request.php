<?php

namespace AC;

use AC\Request\Parameters;

class Request
{

    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

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

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = new Parameters((array)filter_input_array(INPUT_GET));
        $this->request = new Parameters((array)filter_input_array(INPUT_POST));
    }

    /**
     * @param Middleware $middleware
     *
     * @return self
     */
    public function add_middleware(Middleware $middleware): self
    {
        $this->middleware[] = $middleware;

        $middleware->handle($this);

        return $this;
    }

    public function is_request(): bool
    {
        return $this->request->count() > 0;
    }

    public function get_query(): Parameters
    {
        return $this->query;
    }

    public function get_request(): Parameters
    {
        return $this->request;
    }

    public function get_method(): string
    {
        return $this->method;
    }

    public function get_parameters(): Parameters
    {
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
    public function get($key, $default = null)
    {
        return $this->get_parameters()->get($key, $default);
    }

    /**
     * @param string    $key
     * @param null      $default
     * @param int       $filter
     * @param array|int $options
     *
     * @return mixed
     */
    public function filter($key, $default = null, $filter = FILTER_DEFAULT, $options = 0)
    {
        return $this->get_parameters()->filter($key, $default, $filter, $options);
    }

}