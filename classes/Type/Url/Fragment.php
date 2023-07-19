<?php

namespace AC\Type\Url;

trait Fragment
{

    protected $fragment;

    protected function set_fragment(string $fragment): void
    {
        $this->fragment = '#' . $fragment;
    }

    protected function get_fragment(): string
    {
        return $this->fragment;
    }

    protected function has_fragment(): bool
    {
        return null !== $this->fragment;
    }

}