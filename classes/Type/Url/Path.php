<?php

namespace AC\Type\Url;

trait Path
{

    protected $path = '';

    protected function set_path(string $path): void
    {
        $this->path = '/' . trim($path, '/') . '/';
    }

    protected function get_path(): string
    {
        return $this->path;
    }

}