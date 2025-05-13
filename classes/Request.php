<?php

namespace AC;

use AC\Request\Parameters;

class Request
{

    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';

    protected string $method;

    protected Parameters $query;

    protected Parameters $request;

    /**
     * @var Middleware[]
     */
    protected array $middleware;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->query = new Parameters((array)filter_input_array(INPUT_GET));
        $this->request = new Parameters((array)filter_input_array(INPUT_POST));
    }

    public function add_middleware(Middleware $middleware): self
    {
        $this->middleware[] = $middleware;

        $middleware->handle($this);

        return $this;
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

    public function get(string $key, $default = null)
    {
        return $this->get_parameters()->get($key, $default);
    }

    public function has(string $key): bool
    {
        return $this->get_parameters()->has($key);
    }

    public function filter(string $key, $default = null, int $filter = FILTER_DEFAULT, $options = 0)
    {
        return $this->get_parameters()->filter($key, $default, $filter, $options);
    }

}