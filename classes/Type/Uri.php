<?php

declare(strict_types=1);

namespace AC\Type;

class Uri implements Url
{

    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    protected function add_arg(string $key, string $value): void
    {
        $this->url = add_query_arg($key, $value, $this->url);
    }

    public function with_arg(string $key, string $value): self
    {
        return new self(add_query_arg($key, $value, $this->url));
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

}