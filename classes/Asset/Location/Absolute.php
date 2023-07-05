<?php

namespace AC\Asset\Location;

use AC\Asset\Location;

final class Absolute implements Location
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $path;

    public function __construct(string $url, string $path)
    {
        $this->url = rtrim($url, '/');
        $this->path = rtrim($path, '/');
    }

    public function with_suffix(string $suffix): self
    {
        $url = $this->get_url() . '/' . ltrim($suffix, '/');
        $path = $this->get_path() . '/' . ltrim($suffix, '/');

        return new self($url, $path);
    }

    public function get_url(): string
    {
        return $this->url;
    }

    public function get_path(): string
    {
        return $this->path;
    }

}