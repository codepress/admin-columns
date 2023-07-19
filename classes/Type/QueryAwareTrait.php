<?php

namespace AC\Type;

trait QueryAwareTrait
{

    protected $url;

    public function set_url(string $url): void
    {
        $this->url = $url;
    }

    public function add_one(string $key, string $value): void
    {
        $this->url = add_query_arg($key, $value, $this->url);
    }

    public function add(array $params = []): void
    {
        foreach ($params as $key => $value) {
            $this->add_one($key, $value);
        }
    }

    public function remove(string $key): void
    {
        $this->url = remove_query_arg($key, $this->url);
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->get_url();
    }

}