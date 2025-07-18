<?php

namespace AC\Asset\Location;

use AC\Asset\Location;

final class Absolute implements Location
{

    private string $url;

    private string $path;

    public function __construct(string $url, string $path)
    {
        $this->url = rtrim($url, '/');
        $this->path = rtrim($path, '/');
    }

    public static function create_by_plugin_file(string $file): self
    {
        return new self(
            plugin_dir_url($file),
            plugin_dir_path($file)
        );
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