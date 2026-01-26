<?php

declare(strict_types=1);

namespace AC\Type;

class Uri implements Url
{

    protected string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function with_arg(string $key, string $value): self
    {
        return new self(
            add_query_arg($key, $value, $this->url)
        );
    }

    protected function add(string $key, string $value): void
    {
        $this->url = add_query_arg($key, $value, $this->url);
    }

    protected function add_path(string $path): void
    {
        $this->url = sprintf('%s/%s/', rtrim($this->url, '/'), trim($path, '/'));
    }

    protected function add_fragment(string $fragment): void
    {
        $this->url = sprintf('%s#%s', $this->url, $fragment);
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