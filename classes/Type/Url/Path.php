<?php

namespace AC\Type\Url;

trait Path
{

    protected function normalize_path(string $path): string
    {
        return '/' . trim($path, '/') . '/';
    }

}